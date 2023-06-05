<?php
    $form_id = $_GET['form_id'];
    $field_id = $_GET['field_id'];

    global $wpdb;
    $form = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_forms WHERE id='$form_id'");
    $field = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}sfb_fields WHERE id='$field_id'");

    $types = [
        'text' => "Text",
        'email' => "Email",
        'url' => "URL",
        'date' => "Date",
        'phone' => "Phone",
        'file' => "File Upload",
        'image' => "Image Upload",
        'textarea' => "Textarea",
        'select' => "Select",
        'checkbox' => 'Checkbox',
        'radio' => 'Radio',
        'select-multiple' => "Select Multiple",
        'hidden' => 'Hidden',
        'readonly' => 'Readonly'
    ];
?>

<div class="sfb">
    <div class="container-fluid mt-5">
        <h3 class="mb-3"><a href="/wp-admin/admin.php?page=sfb-form-fields&form_id=<?php echo $form_id; ?>" class="btn btn-dark btn-sm"><span class="dashicons dashicons-arrow-left-alt"></span></a>&nbsp;Edit Field  - <?php echo $form->form_name; ?></h3>
        <form method="post" action="">
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="field_type">Type</label>
                        <select name="field_type" id="field_type" class="form-select" required>
                            <?php foreach($types as $key=>$val) : ?>
                                <option <?php if($field->field_type === $key): ?> selected="" <?php endif; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="label">Label</label>
                        <input type="text" name="label" id="label" class="form-control" required value="<?php echo $field->label; ?>" />
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="is_required">Required</label>
                        <select name="is_required" id="is_required" class="form-select" required>
                            <option value="1" <?php if($field->is_required === "1"): ?> selected="" <?php endif; ?>>Yes</option>
                            <option value="0" <?php if($field->is_required === "0"): ?> selected="" <?php endif; ?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="default_value">Default Value</label>
                        <input type="text" name="default_value" id="default_value" class="form-control"  value="<?php echo $field->default_value; ?>"/>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="column_width">Column Width</label>
                        <select name="column_width" id="column_width" class="form-select" required>
                            <?php for($i=1; $i<13; $i++) : ?>
                                <option value="<?php echo $i; ?>" <?php if($field->column_width*1 === $i): ?> selected="" <?php endif; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label" for="options">Options</label>
                        <textarea class="form-control" name="options" id="options"><?php echo $field->options; ?></textarea>
                        <p class="help-block text-dark">Comma seperated options for radio,checkbox, select</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="field_id" value="<?php echo $field->id; ?>"/>
                    <input type="hidden" name="form_id" value="<?php echo $form->id; ?>"/>
                    <input type="submit" name="update-field" class="btn btn-dark w-100" value="Update" />
                </div>
            </div>
        </form>
    </div>
</div>