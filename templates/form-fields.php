<?php
    $form_id = $_GET['form_id'];
    global $wpdb;

    $form = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_forms WHERE id='$form_id'");
    $fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_fields WHERE form_id='$form_id' order by sort_order");
    
?>

<div class="sfb">
    <div class="container-fluid mt-5">
        <h3 class="mb-3">
            <a href="/wp-admin/admin.php?page=sfb" class="btn btn-dark btn-sm"><span class="dashicons dashicons-arrow-left-alt"></span></a>&nbsp;
            <a href="/wp-admin/admin.php?page=sfb-create-fields&form_id=<?php echo $form->id; ?>" class="btn btn-success btn-sm"><span class="dashicons dashicons-plus-alt2"></span></a>&nbsp;
            Fields - <?php echo $form->form_name; ?>
        </h3>
        <div class="row">
            <div class="col-md-12">
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Sort</th>
                            <th scope="col">Label</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="sortable">
                        <?php foreach($fields as $field): ?>
                            <tr id="item_<?php echo $field->id; ?>">
                                <td class="cursor-move"><span class="dashicons dashicons-move"></span></td>
                                <td><?php echo $field->label; ?></td>
                                <td>
                                    <a href="/wp-admin/admin.php?page=sfb-create-fields&form_id=<?php echo $form->id; ?>&field_id=<?php echo $field->id; ?>" class="btn btn-sm btn-primary"><span class="dashicons dashicons-edit"></span></a>
                                    <form method="post" action="" style="display:inline;" onSubmit="return confirm('Delete `<?php echo $field->label; ?>`, Are you sure?');">
                                        <button type="submit" name="delete-field" class="btn btn-sm btn-danger"><span class="dashicons dashicons-trash"></span></button>
                                        <input type="hidden" name="field_id" value="<?php echo $field->id; ?>"/>
                                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>"/>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
<input type="hidden" id="sort_fields_nonce" value = "<?php echo wp_create_nonce("sfb_sort_fields_nonce"); ?>" />