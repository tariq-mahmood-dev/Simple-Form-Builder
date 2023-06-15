<?php 


add_action( 'admin_menu', 'sfb_menu' );


function sfb_menu() {
    global $submenu;

	add_menu_page( 'Simple Form Builder', 'Simple Form Builder', 'manage_options', 'sfb', 'sfb_dashboard' , 'dashicons-forms', 90);
	add_menu_page( 'Create Form', 'Create Form', 'manage_options', 'sfb-create-form', 'sfb_show_create' , 'dashicons-admin-page', 91);
	add_menu_page( 'Form Fields', 'Form Fields', 'manage_options', 'sfb-form-fields', 'sfb_show_form_fields' , 'dashicons-admin-page', 92);
	add_menu_page( 'Create Form Fields', 'Create Form Fields', 'manage_options', 'sfb-create-fields', 'sfb_show_create_fields' , 'dashicons-admin-page', 93);
	add_menu_page( 'List Submissions', 'List Submissions', 'manage_options', 'sfb-list-submissions', 'sfb_list_submissions' , 'dashicons-admin-page', 94);
	add_menu_page( 'View Submission', 'View Submission', 'manage_options', 'sfb-view-submission', 'sfb_view_submission' , 'dashicons-admin-page', 95);
	remove_menu_page('sfb-create-form');
	remove_menu_page('sfb-form-fields');
	remove_menu_page('sfb-create-fields');
	remove_menu_page('sfb-list-submissions');
	remove_menu_page('sfb-view-submission');
}


function sfb_dashboard() {
	sfb_check_permissions();

    require_once(SFB_PATH.'/templates/dashboard.php');
}

function sfb_show_create()
{
	sfb_check_permissions();

	if(isset($_GET['form_id']))
	{
		require_once(SFB_PATH.'/templates/edit-form.php');
	}
	else 
	{
		require_once(SFB_PATH.'/templates/create-form.php');
	}
    
}

function sfb_show_form_fields()
{
	sfb_check_permissions();
	require_once(SFB_PATH.'/templates/form-fields.php');
}

function sfb_show_create_fields()
{
	sfb_check_permissions();

	if(isset($_GET['field_id']))
	{
		require_once(SFB_PATH.'/templates/edit-form-field.php');
	}
	else 
	{
		require_once(SFB_PATH.'/templates/create-form-field.php');
	}
}

function sfb_list_submissions()
{
	require_once(SFB_PATH.'/templates/submissions.php');
}
function sfb_view_submission()
{
	require_once(SFB_PATH.'/templates/view-submission.php');
}


