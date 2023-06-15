<div class="sfb">
    <div class="container-fluid mt-5">
        <h3 class="mb-3"><a href="/wp-admin/admin.php?page=sfb" class="btn btn-dark btn-sm"><span class="dashicons dashicons-arrow-left-alt"></span></a>&nbsp;Create Form </h3>
        
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="form_name">Name</label>
                        <input type="text" name="form_name" id="form_name" class="form-control" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="send_email_alerts">Send Email Alerts</label>
                        <select name="send_email_alerts" id="send_email_alerts" class="form-select" required>
                            <option value="1">Yes</option>
                            <option value="0" selected="">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row"  id="email_to_container" style="display:none">
                 <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email_from">Email From</label>
                        <input type="email" name="email_from" id="email_from" class="form-control" value="no-reply@<?= sfb_get_domain_name(); ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email_to">Email To</label>
                        <input type="text" name="email_to" id="email_to" class="form-control" />
                        <p class="help-block">Seperate multiple emails with comma</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="submit_button_text">Submit Button Text</label>
                        <input type="text" name="submit_button_text" id="submit_button_text" class="form-control" value="Submit" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="submit_button_class">Submit Button Class <a target="_blank" href="https://getbootstrap.com/docs/5.3/components/buttons/">See Button Classes</a></label>
                        <input type="text" name="submit_button_class" id="submit_button_class" class="form-control" value="btn btn-primary w-100" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="use_recaptcha">Use RECAPTCHA</label>
                        <select name="use_recaptcha" id="use_recaptcha" class="form-select" required>
                            <option value="1">Yes</option>
                            <option value="0" selected="">No</option>
                        </select>
                    </div>
                </div>
            </div>    
            <div class="row" id="google_recaptcha_key_container" style="display:none">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <a href="https://www.google.com/recaptcha/admin/create" target="_blank">Use V2 captcha</a> with "I'm not a robot" tickbox
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="mb-3">
                        <label class="form-label" for="google_recaptcha_key">RECAPTCHA Site Key</label>
                        <input type="text" name="google_recaptcha_key" id="google_recaptcha_key" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="mb-3">
                        <label class="form-label" for="google_recaptcha_secret">RECAPTCHA Site Secret</label>
                        <input type="text" name="google_recaptcha_secret" id="google_recaptcha_secret" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                    <label class="form-label" for="redirect_after_submit">Redirect After Submit</label>
                        <select name="redirect_after_submit" id="redirect_after_submit" class="form-select" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6" id="redirect_to_url_container" style="display:none">
                    <div class="mb-3">
                        <label class="form-label" for="redirect_to_url">Redirect To URL</label>
                        <input type="url" name="redirect_to_url" id="redirect_to_url" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6" id="message_after_submit_container">
                    <div class="mb-3">
                        <label class="form-label" for="message_after_submit">Message After Submit</label>
                        <input type="text" name="message_after_submit" id="message_after_submit" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="submit" name="new-form" class="btn btn-dark w-100" value="Create" />
                </div>
            </div>
        </form>
    </div>
</div>