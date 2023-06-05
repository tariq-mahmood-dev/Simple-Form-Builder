<?php 

$form_id = $_GET['form_id'];
global $wpdb;

$fields = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sfb_fields WHERE form_id={$form_id} order by sort_order");
$form = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_forms WHERE id={$form_id}");


$customPagHTML     = "";
$items_per_page = SFB_ITEMS_PER_PAGE;


$total = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sfb_submissions WHERE form_id={$form_id}");
$page = isset( $_GET['sfbspage'] ) ? abs( (int) $_GET['sfbspage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;


$submissions = $wpdb->get_results( " SELECT * FROM {$wpdb->prefix}sfb_submissions WHERE form_id={$form_id} order by id desc LIMIT ${offset}, ${items_per_page}" );
$totalPage = ceil($total / $items_per_page);


if($totalPage > 1){
    $customPagHTML     =  '<div class="mt-2 mb-2"><span>Page '.$page.' of '.$totalPage.'</span></div><div>'.paginate_links( array(
    'base' => add_query_arg( 'sfbspage', '%#%' ),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => $totalPage,
    'current' => $page,
    'type' => 'list'
    )).'</div>';
}

?>

<div class="sfb">
    <div class="container-fluid mt-5">
    <h3 class="mb-3"><a href="/wp-admin/admin.php?page=sfb" class="btn btn-dark btn-sm"><span class="dashicons dashicons-arrow-left-alt"></span></a>&nbsp;Submissions For  - <?php echo $form->form_name; ?></h3>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <?php 
                                $counter = 1; 
                                foreach($fields as $field) : 
                                if($field->field_type !="file" && $field->field_type !="image") :
                            ?>
                                <th scope="col"><?= $field->label; ?></th>
                                <?php if($counter++ === 2) { break; } ?>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <th scope="col">Submitted At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($submissions as $submission) : ?>
                            <tr>
                                <?php 
                                    $counter = 1; 
                                    foreach($fields as $field) : 
                                    if($field->field_type !="file" && $field->field_type !="image") :
                                ?>
                                    <td><?= sfb_get_field_value($submission->id,$field->id); ?></td>
                                <?php if($counter++ === 2) { break; } ?>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <td><?= $submission->created_at ?></td>
                                <td>
                                    <a href="/wp-admin/admin.php?page=sfb-view-submission&form_id=<?= $submission->form_id; ?>&submission_id=<?= $submission->id; ?>" class="btn btn-primary btn-sm"><span class="dashicons dashicons-visibility"></span></a>
                                    <form method="post" action="" style="display:inline;" onSubmit="return confirm('Delete Submission, Are you sure?');">
                                        <button type="submit" name="delete-submission" class="btn btn-sm btn-danger"><span class="dashicons dashicons-trash"></span></button>
                                        <input type="hidden" name="submission_id" value="<?php echo $submission->id; ?>"/>
                                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>" />
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo $customPagHTML; ?>
            </div>
        </div>
    </div>
</div>