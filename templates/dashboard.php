
<div class="sfb">
    <div class="container-fluid mt-5">
        <h3>SFB Dashboard&nbsp;<a href="/wp-admin/admin.php?page=sfb-create-form" class="btn btn-success btn-sm"><span class="dashicons dashicons-plus-alt2"></span></a></h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Form Name</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Short Code</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            global $wpdb;
                            $forms = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_forms");
                            foreach ($forms as $form):
                        ?>
                            <tr>
                                <td><?php echo $form->form_name; ?></td>
                                <td><?php echo $form->created_at; ?></td>
                                <td>
                                    <span class="user-select-all">[sfb id="<?php echo $form->id; ?>"]</span>
                                </td>
                                <td>
                                    <a href="/wp-admin/admin.php?page=sfb-create-form&form_id=<?php echo $form->id; ?>" class="btn btn-sm btn-primary"><span class="dashicons dashicons-edit"></span></a>
                                    <form method="post" action="" style="display:inline;" onSubmit="return confirm('Delete `<?php echo $form->form_name; ?>`, Are you sure?');">
                                        <button type="submit" name="delete-form" class="btn btn-sm btn-danger"><span class="dashicons dashicons-trash"></span></button>
                                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>"/>
                                    </form>
                                    <a href="/wp-admin/admin.php?page=sfb-form-fields&form_id=<?php echo $form->id; ?>" class="btn btn-sm btn-secondary">Fields</a>
                                    <a href="/wp-admin/admin.php?page=sfb-list-submissions&form_id=<?php echo $form->id; ?>" class="btn btn-sm btn-info">Submissions</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>