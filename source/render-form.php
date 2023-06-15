<?php 
function sfb_do_short_code($atts,$content, $shortcode_tag) {

    $data = sfb_get_form_and_fields($atts['id']);
    $form = $data['form'];

    ob_start();?>
    <div class="sfb">
        <?php if(isset($_SESSION['sfb_success_message'])) : ?>
        <div class="container" id="sfb_success_message">
            <div class="row" >
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <span class="dashicons dashicons-saved top-margined"></span>&nbsp;<?php echo $_SESSION['sfb_success_message'];     unset($_SESSION['sfb_success_message']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else : ?>
        <div class="container">
        
            <?php if(isset($_SESSION['sfb_error_message'])) : ?>
                <div class="row" >
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                        <span class="dashicons dashicons-no-alt top-margined"></span>&nbsp;<?php echo $_SESSION['sfb_error_message'];     unset($_SESSION['sfb_error_message']); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data" id="sfb_dynamic_form_<?= $form->id; ?>" class="sfb-forms">
                <div class="row">
                    <?php foreach($data['fields'] as $field):  ?>
                        <div class="col-md-<?= $field->column_width; ?>">
                            <div class="mb-3">
                                    <?php if($field->field_type != "hidden"): ?>
                                        <label for="<?= $field->field_name; ?>" class="form-label"><?= $field->label; ?></label>
                                    <?php endif; ?>
                                <?php switch($field->field_type): 
                                         case "text": ?>
                                        <input type="text" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "readonly": ?>
                                        <input type="text" readonly="" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "hidden": ?>
                                        <input type="hidden" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "email": ?>
                                        <input type="email" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "url": ?>
                                        <input type="url" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "date": ?>
                                        <input type="date" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "phone": ?>
                                        <input type="tel" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> value="<?= $field->default_value; ?>" />
                                    <?php break; ?>
                                    <?php case "file": ?>
                                        <input type="file" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> />
                                    <?php break; ?>
                                    <?php case "image": ?>
                                        <input type="file" class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> accept="image/*" />
                                    <?php break; ?>
                                    <?php case "textarea": ?>
                                        <textarea class="form-control" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" ><?= $field->default_value; ?></textarea>
                                    <?php break; ?>
                                    <?php case "select": ?>
                                        <select class="form-select" name="<?= $field->field_name; ?>" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> >
                                            <?php foreach(explode(",",$field->options) as $option): ?>
                                                <option value="<?= $option; ?>"><?= $option; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php break; ?>
                                    <?php case "select-multiple": ?>
                                        <select class="form-select" multiple="multiple" name="<?= $field->field_name; ?>[]" id="<?= $field->field_name."_".$field->id; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?> >
                                            <?php foreach(explode(",",$field->options) as $option): ?>
                                                <option value="<?= $option; ?>"><?= $option; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php break; ?>

                                    <?php case "checkbox": ?>
                                        <?php foreach(explode(",",$field->options) as $option): 
                                            $option_slug = sfb_create_field_name($option)    
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="<?= $field->field_name; ?>[]" value="<?= $option; ?>" id="<?= $option_slug; ?>">
                                                <label class="form-check-label" for="<?= $option_slug; ?>"><?= $option; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php break; ?>

                                    <?php case "radio": ?>
                                        <?php foreach(explode(",",$field->options) as $option): 
                                            $option_slug = sfb_create_field_name($option)    
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="<?= $field->field_name; ?>" value="<?= $option; ?>" id="<?= $option_slug; ?>" <?php if($field->is_required == "1") : ?> required="" <?php endif; ?>>
                                                <label class="form-check-label" for="<?= $option_slug; ?>"><?= $option; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php break; ?>
                                <?php endswitch; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if($form->use_recaptcha*1 === 1): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="<?php echo $form->google_recaptcha_key; ?>"></div>
                                <script src='https://www.google.com/recaptcha/api.js' async defer></script>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="sfb_validate_captcha" value="yes" />
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>" />
                        
                        <input name="sbf-submit" type="submit" value="<?php echo $form->submit_button_text; ?>" class="<?php echo $form->submit_button_class; ?> sbf-submit"/>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('sfb', 'sfb_do_short_code');