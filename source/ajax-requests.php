<?php 

add_action('wp_ajax_sfb_sort_fields', 'sfb_sort_fields' );

function sfb_sort_fields()
{
    global $wpdb;
    $data = sanitize_post($_POST);
    $items = $data['items'];
    $counter = 1;

    foreach($items as $key=>$item)
    {
        $id = str_replace("item_","",$item);
        $wpdb->query(
            $wpdb->prepare( 
                "UPDATE {$wpdb->prefix}sfb_fields  SET sort_order=%s where id=%s",
                array($counter++, $id)
            )
        );
    }
}