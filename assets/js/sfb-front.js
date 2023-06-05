
jQuery(function($){

    $(".sfb-forms").each(function(){
        $('#'+$(this).attr("id")).submit(function(event) {

            //validate recaptcha
            if($(this).find('#sfb_validate_captcha').length)
            {
                var recaptcha = $("#g-recaptcha-response").val();
                if (recaptcha === "") {
                    event.preventDefault();
                    alert("Please check the I'm not a robot check-box");
                }
            }
        });
    });
});
