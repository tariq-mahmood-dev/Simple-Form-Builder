<?php 

$form_id = $_GET['form_id'];
$submission_id = $_GET['submission_id'];

global $wpdb;

$fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_fields WHERE form_id={$form_id} order by sort_order");
$form = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_forms WHERE id={$form_id}");
$submission = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_submissions WHERE id={$submission_id}");

?>

<div class="sfb">
    <div class="container-fluid mt-5">
        <h3 class="mb-3"><a href="/wp-admin/admin.php?page=sfb-list-submissions&form_id=<?= $form_id; ?>" class="btn btn-dark btn-sm"><span class="dashicons dashicons-arrow-left-alt"></span></a>&nbsp;Submission For  - <?php echo $form->form_name; ?></h3>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Label</th>
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fields as $field): ?>
                            <tr>
                                <td><?= $field->label; ?></td>
                                <td><?= sfb_get_field_value($submission_id,$field->id); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Submitted At</td>
                            <td><?= $submission->created_at ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>