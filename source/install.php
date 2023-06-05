<?php 

class SimpleFormBuilder{

    static function install() {
        SimpleFormBuilder::sfb_create_tables(); 
    }

    static function sfb_create_tables() {    
        SimpleFormBuilder::sbf_create_forms_table();
        SimpleFormBuilder::sfb_create_fields_table();
        SimpleFormBuilder::sfb_create_submissions_table();
        SimpleFormBuilder::sfb_create_values_table();
        SimpleFormBuilder::sfb_create_first_form();
    } 
    
    static function sfb_create_first_form()
    {
        global $wpdb;

        $sfb_forms_table_name = $wpdb->prefix . 'sfb_forms';
        $sbf_fields_table_name = $wpdb->prefix . 'sfb_fields';

        $wpdb->query(
            $wpdb->prepare( 
                "INSERT INTO $sfb_forms_table_name(form_name, email_to, send_email_alerts,submit_button_text,submit_button_class, use_recaptcha, google_recaptcha_key, google_recaptcha_secret, message_after_submit, redirect_after_submit, redirect_to_url )
                VALUES ( %s, %s, %s, %s, %s, %s,  %s, %s, %s, %s, %s)",
                array( "Test Form", "", "0", "Submit", "btn btn-primary w-100", "0", "","" ,"Thank you for you submission, our representative will contact you soon!", "0", "" )
            )
        );
        $form_id =  $wpdb->insert_id;

        $wpdb->query(
            $wpdb->prepare( 
                "INSERT INTO $sbf_fields_table_name(field_type, label, field_name, is_required, default_value, column_width,options, form_id)
                VALUES ( %s, %s, %s, %s, %s, %s, %s, %s)",
                array( 'text', "Name", 'name', "1", "", "6", "", $form_id)
            )
        );
        $wpdb->query(
            $wpdb->prepare( 
                "INSERT INTO $sbf_fields_table_name(field_type, label, field_name, is_required, default_value, column_width,options, form_id)
                VALUES ( %s, %s, %s, %s, %s, %s, %s, %s)",
                array( 'email', "Email", 'email', "1", "", "6", "", $form_id)
            )
        );
        $wpdb->query(
            $wpdb->prepare( 
                "INSERT INTO $sbf_fields_table_name(field_type, label, field_name, is_required, default_value, column_width,options, form_id)
                VALUES ( %s, %s, %s, %s, %s, %s, %s, %s)",
                array( 'textarea', "Message", 'message', "1", "", "12", "", $form_id)
            )
        );
    }
    static function sbf_create_forms_table()
    {   
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'sfb_forms';
    
        $sql = "CREATE TABLE " . $table_name . "(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        form_name VARCHAR(250) NOT NULL, 
        email_to VARCHAR(1100) NOT NULL, 
        send_email_alerts ENUM('0','1') DEFAULT '1', 
        submit_button_text VARCHAR(250) NOT NULL, 
        submit_button_class VARCHAR(250) NULL, 
        use_recaptcha ENUM('0','1') DEFAULT '0', 
        google_recaptcha_key VARCHAR(250) NULL,
        google_recaptcha_secret VARCHAR(250) NULL,
        message_after_submit VARCHAR(1100) NOT NULL, 
        redirect_after_submit ENUM('0','1') DEFAULT '0', 
        redirect_to_url VARCHAR(1100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
     
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    static function sfb_create_fields_table()
    {
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'sfb_fields';
        $forms_table_name =  $wpdb->prefix . 'sfb_forms';
    
        $sql = "CREATE TABLE " . $table_name . "(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        label VARCHAR(255) NOT NULL, 
        field_type VARCHAR(255) NOT NULL, 
        field_name VARCHAR(255) NOT NULL, 
        is_required ENUM('0','1') DEFAULT '0', 
        default_value VARCHAR(255) NULL,
        sort_order VARCHAR(255) NOT NULL DEFAULT 1,
        column_width VARCHAR(255) NOT NULL DEFAULT 12,
        options text NULL,
        form_id int(11) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_sfb_form_field FOREIGN KEY (form_id) REFERENCES {$forms_table_name}(id) ON DELETE CASCADE
        ) $charset_collate;";
     
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    
    static function sfb_create_submissions_table()
    {
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'sfb_submissions';
        $forms_table_name =  $wpdb->prefix . 'sfb_forms';
    
        $sql = "CREATE TABLE " . $table_name . "(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        form_id int(11) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_sfb_form_submission FOREIGN KEY (form_id) REFERENCES {$forms_table_name}(id) ON DELETE CASCADE
        ) $charset_collate;";
     
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    static function sfb_create_values_table()
    {
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . 'sfb_values';
        $fields_table_name =  $wpdb->prefix . 'sfb_forms';
        $submissions_table_name =  $wpdb->prefix . 'sfb_submissions';
    
        $sql = "CREATE TABLE " . $table_name . "(id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
        value text Null,
        field_id int(11) NOT NULL,
        submission_id int(11) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_sfb_field_value FOREIGN KEY (field_id) REFERENCES {$fields_table_name}(id) ON DELETE CASCADE,
        CONSTRAINT fk_sfb_submission_value FOREIGN KEY (submission_id) REFERENCES {$submissions_table_name}(id) ON DELETE CASCADE
        ) $charset_collate;";
     
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
