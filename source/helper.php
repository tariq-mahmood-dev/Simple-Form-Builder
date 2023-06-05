<?php 

function sfb_create_field_name($string)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string)));;
}

function sfb_check_permissions()
{
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
}

function sfb_get_form_and_fields($form_id)
{
	global $wpdb;

	$form = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_forms where id={$form_id}");
	$fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_fields where form_id={$form_id} order by sort_order");
	return ['form'=>$form,'fields'=>$fields];
}

function sfb_get_field_value($submission_id,$field_id)
{
	global $wpdb;
	$field = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_fields where id={$field_id}");
	$value = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_values where submission_id={$submission_id} AND field_id={$field_id}");

	$return_value = "";

	if($field->field_type === 'checkbox' || $field->field_type === "select-multiple")
	{
		$return_value = implode (",",unserialize($value->value));
	}
	else if($field->field_type === 'file' || $field->field_type === "image")
	{
		$download_link = plugin_dir_url(__DIR__).'uploads/'.$value->value;
		

		$return_value = "<a href='".$download_link."' target='_blank' download='".$value->value."' class='btn btn-outline-secondary btn-sm'><span class='dashicons dashicons-download'></span></a>";
	}
	else 
	{
		$return_value = $value->value;
	}

	return $return_value;
}

function sfb_get_path($url)
{
	$parts = parse_url($url);

	$url =  str_replace($parts['scheme']."://", "", $url );
	$url = str_replace($parts['host'], "", $url );

	return $url;
}

function sfb_save_data($data)
{
	global $wpdb;

	$sfb_forms_table_name = $wpdb->prefix . 'sfb_forms';
	$sbf_fields_table_name = $wpdb->prefix . 'sfb_fields';
	$sfb_submissions_table_name = $wpdb->prefix .'sfb_submissions';
	$sfb_values_table_name = $wpdb->prefix .'sfb_values';

	$form_id = $data['form_id'];
    $wpdb->query(
        $wpdb->prepare( 
            "INSERT INTO $sfb_submissions_table_name(form_id)
            VALUES (%s)",
            array($form_id)
        )
    );

    $submission_id =  $wpdb->insert_id;
    $form = $wpdb->get_row("SELECT * FROM {$sfb_forms_table_name} WHERE id='$form_id'");
    $fields = $wpdb->get_results("SELECT * FROM {$sbf_fields_table_name} where form_id='$form_id' order by sort_order");


    foreach($fields as $field)
    {
        if($field->field_type !="file" && $field->field_type !="image")
        {
            if(isset($data[$field->field_name]))
            {
                $value = $data[$field->field_name];

                if($field->field_type == "checkbox" || $field->field_type =="select-multiple")
                {
                    $value = serialize($data[$field->field_name]);
                }

                $wpdb->query(
                    $wpdb->prepare( 
                        "INSERT INTO $sfb_values_table_name(field_id,submission_id,value)
                        VALUES (%s,%s,%s)",
                        array($field->id,$submission_id,$value)
                    )
                );
            }
        }
        else 
        {
        
            $info = pathinfo($_FILES[$field->field_name]['name']);
            $ext = $info['extension']; // get the extension of the file
            $newname = time().".".$ext; 

            $target = SFB_PATH.'/uploads/'.$newname;
            move_uploaded_file( $_FILES[$field->field_name]['tmp_name'], $target);

            $wpdb->query(
                $wpdb->prepare( 
                    "INSERT INTO $sfb_values_table_name(field_id,submission_id,value)
                    VALUES (%s,%s,%s)",
                    array($field->id,$submission_id,$newname)
                )
            );
        }
    }

    if($form->send_email_alerts*1 === 1)
    {
        sfb_send_email($submission_id,$form);
    }
}

function sfb_register_session(){
    if(!session_id())
    {
        session_start();
    }
}

function sfb_validate_recaptcha($data)
{
	global $wpdb;

	$sfb_forms_table_name = $wpdb->prefix . 'sfb_forms';
	$form_id = $data['form_id'];
	$form = $wpdb->get_row("SELECT * FROM {$sfb_forms_table_name} WHERE id='$form_id'");
	$captcha = $data['g-recaptcha-response'];

	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$form->google_recaptcha_secret}&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

	return $response['success'];
}

function sfb_send_email($submission_id,$form)
{
    global $wpdb;
    $fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_fields WHERE form_id={$form->id} order by sort_order");

    $to = $form->email_to;
    $subject = "New Submission for ".$form->form_name;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


    $form_data = "";

    foreach($fields as $field)
    {
        $form_data.= "<tr><td><span style='font-weight:bold;'>".$field->label."</span></td><td>" . sfb_get_field_value($submission_id,$field->id). "</td></tr>";
    }

    $data = "
    <html>
    <head>
    <title>New Submission for {$form->form_name}</title>
    <style type='text/css'>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      td{
        padding:10px;
      }
      table{
        width:100%;
      }
    </style>
    </head>
    <body>
    <p>Hi!</p>
    <p>You got a new submission for {$form->form_name}</p>
    <table>
    <tbody>
    ".$form_data."
    </tbody>
    </table>
    <p>Email generated by Simple Form Builder</p>
    </body>
    </html>
    ";

    mail($to,$subject,$data,$headers);
}