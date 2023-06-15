<?php 

global $wpdb;
$sfb_forms_table_name = $wpdb->prefix . 'sfb_forms';
$sbf_fields_table_name = $wpdb->prefix . 'sfb_fields';
$sfb_submissions_table_name = $wpdb->prefix .'sfb_submissions';
$sfb_values_table_name = $wpdb->prefix .'sfb_values';

if(isset($_POST['new-form']))
{
    $data = sanitize_post($_POST);

    $wpdb->query(
        $wpdb->prepare( 
            "INSERT INTO $sfb_forms_table_name(form_name, email_from, email_to, send_email_alerts,submit_button_text,submit_button_class, use_recaptcha, google_recaptcha_key, google_recaptcha_secret, message_after_submit, redirect_after_submit, redirect_to_url )
            VALUES ( %s, %s, %s, %s, %s, %s,  %s, %s, %s, %s, %s, %s)",
            array( $data['form_name'], $data['email_from'], $data['email_to'], $data['send_email_alerts'], $data['submit_button_text'], $data['submit_button_class'], $data['use_recaptcha'], $data['google_recaptcha_key'],$data['google_recaptcha_secret'] ,$data['message_after_submit'], $data['redirect_after_submit'], $data['redirect_to_url'] )
        )
    );
    header('Location:'. admin_url( '/admin.php?page=sfb' ) );
}

if(isset($_POST['update-form']))
{
    $data = sanitize_post($_POST);

    $wpdb->query(
        $wpdb->prepare( 
            "UPDATE {$sfb_forms_table_name}  SET form_name = %s, email_from= %s, email_to = %s, send_email_alerts = %s,submit_button_text=%s,submit_button_class=%s, use_recaptcha=%s, google_recaptcha_key=%s, google_recaptcha_secret=%s ,message_after_submit=%s, redirect_after_submit=%s, redirect_to_url=%s where id=%s",
            array( $data['form_name'], $data['email_from'], $data['email_to'], $data['send_email_alerts'], $data['submit_button_text'], $data['submit_button_class'], $data['use_recaptcha'], $data['google_recaptcha_key'], $data['google_recaptcha_secret'],$data['message_after_submit'], $data['redirect_after_submit'], $data['redirect_to_url'], $data['form_id']  )
        )
    );
    header('Location:'. admin_url( '/admin.php?page=sfb' ) );
}

if(isset($_POST['delete-form']))
{
    $data = sanitize_post($_POST);
    $wpdb->delete( $sfb_forms_table_name , array( 'id' => $data['form_id'] ) );
    header('Location:'. admin_url( '/admin.php?page=sfb' ) );
}

if(isset($_POST['new-field']))
{
    $data = sanitize_post($_POST);
    $field_name = sfb_create_field_name($data['label']);

    $wpdb->query(
        $wpdb->prepare( 
            "INSERT INTO $sbf_fields_table_name(field_type, label, field_name, is_required, default_value, column_width,options, form_id)
            VALUES ( %s, %s, %s, %s, %s, %s, %s, %s)",
            array( $data['field_type'], $data['label'], $field_name, $data['is_required'], $data['default_value'], $data['column_width'], $data['options'], $data['form_id'])
        )
    );
    header('Location:'. admin_url( '/admin.php?page=sfb-form-fields&form_id='.$data['form_id'] ) );
}

if(isset($_POST['update-field']))
{
    $data = sanitize_post($_POST);
    $field_name = sfb_create_field_name($data['label']);

    $wpdb->query(
        $wpdb->prepare( 
            "UPDATE {$sbf_fields_table_name}  SET field_type=%s, label=%s, field_name=%s, is_required=%s, default_value=%s, column_width=%d, options=%s where id=%s",
            array( $data['field_type'], $data['label'], $field_name, $data['is_required'], $data['default_value'], $data['column_width'], $data['options'], $data['field_id']  )
        )
    );
    header('Location:'. admin_url( '/admin.php?page=sfb-form-fields&form_id='.$data['form_id'] ) );
}

if(isset($_POST['delete-field']))
{
    $data = sanitize_post($_POST);
    $wpdb->delete( $sbf_fields_table_name , array( 'id' => $data['field_id'] ) );
    header('Location:'. admin_url( '/admin.php?page=sfb-form-fields&form_id='.$data['form_id'] ) );
}

if(isset($_POST['sbf-submit']))
{
    sfb_register_session();

    $data = sanitize_post($_POST);
    $form_id = $data['form_id'];

   
    $form = $wpdb->get_row("SELECT * FROM {$sfb_forms_table_name} WHERE id={$form_id}");

    if($form->use_recaptcha*1 === 1)
    {
        if(sfb_validate_recaptcha($data) === false)
        {
            $_SESSION['sfb_error_message'] = "You didn't pass spam bot verification, please try again!";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    sfb_save_data($data);

    if($form->redirect_after_submit*1 === 0)
    {
        $_SESSION['sfb_success_message'] = $form->message_after_submit;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    else 
    {
        header('Location: ' . $form->redirect_to_url);
        exit;
    }
    
}

if(isset($_POST['delete-submission']))
{
    $data = sanitize_post($_POST);
    $form_id = $data['form_id'];
    $wpdb->delete( $sfb_submissions_table_name , array( 'id' => $data['submission_id'] ) );

    if(isset($_GET['sfbspage']))
    {
        $current_page = $_GET['sfbspage'];
        $total_records = $wpdb->get_var("SELECT COUNT(*) FROM {$sfb_submissions_table_name} WHERE form_id={$form_id}");

        $items_per_page = SFB_ITEMS_PER_PAGE;
        $totalPage = ceil($total_records / $items_per_page);

        if($totalPage < $current_page) {
            // the page the user was on doesn't exist anymore
            $redirect_url =  '/wp-admin/admin.php?page=sfb-list-submissions&form_id='.$form_id.'&sfbspage='.$current_page-1;
        } else {
            // the page still exists
            $redirect_url =  '/wp-admin/admin.php?page=sfb-list-submissions&form_id='.$form_id.'&sfbspage='.$current_page;
        }
    }
    else 
    {
        $redirect_url =  '/wp-admin/admin.php?page=sfb-list-submissions&form_id='.$form_id;
    }
    header('Location:'.$redirect_url);
}