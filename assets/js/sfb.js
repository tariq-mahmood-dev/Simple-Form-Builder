jQuery(function($){

    check_and_set_email_to_visibility($);
    check_and_set_recaptcha_key_visibility($);
    check_and_set_redirect_after_submit_visibility($);
    sort_fields($);
    adjust_pagination_classes($);

    $("#send_email_alerts").on("change",function(){
        check_and_set_email_to_visibility($);
    });

    $("#use_recaptcha").on("change",function(){
        check_and_set_recaptcha_key_visibility($);
    });

    $("#redirect_after_submit").on("change",function(){
        check_and_set_redirect_after_submit_visibility($);
    });

});

function check_and_set_email_to_visibility($)
{
    if($("#send_email_alerts").val() === "1")
    {
        $("#email_to_container").show();
        $("#email_to").attr("required","");
        $("#email_from").attr("required","");
    }
    else 
    {
        $("#email_to_container").hide();
        $("#email_to").removeAttr("required");
        $("#email_from").removeAttr("required");
    }
}

function check_and_set_recaptcha_key_visibility($)
{
    if($("#use_recaptcha").val() === "1")
    {
        $("#google_recaptcha_key_container").show();
        $("#google_recaptcha_key").attr("required","");
        $("#google_recaptcha_secret").attr("required","");
    }
    else 
    {
        $("#google_recaptcha_key_container").hide();
        $("#google_recaptcha_key").removeAttr("required");
        $("#google_recaptcha_secret").removeAttr("required");
    }
}

function check_and_set_redirect_after_submit_visibility($)
{
    if($("#redirect_after_submit").val() === "1")
    {
        $("#redirect_to_url_container").show();
        $("#redirect_to_url").attr("required","");

        $("#message_after_submit_container").hide();
        $("#message_after_submit").removeAttr("required");
    }
    else 
    {
        $("#message_after_submit_container").show();
        $("#message_after_submit").attr("required","");

        $("#redirect_to_url_container").hide();
        $("#redirect_to_url").removeAttr("required");
    }
}

function sort_fields($)
{
    $(".sortable").sortable({  
        helper: function(e, tr)
        {
              var $originals = tr.children();
              var $helper = tr.clone();
              $helper.children().each(function(index)
              {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
              });
              return $helper;
        },	
        update : function () {
                var order = $('.sortable').sortable('toArray');		 
                $.ajax({
                      data: {'items' : order, 'action':'sfb_sort_fields','_wpnonce':$("#sort_fields_nonce").val()},
                      type: 'POST',
                      url: $("#ajax_url").val(),
                      success:function(result){}
                  });
              }
        });
}

function adjust_pagination_classes($)
{
    $("ul.page-numbers").removeClass("page-numbers").addClass("pagination justify-content-center");
    $("a.page-numbers").removeClass("page-numbers").addClass("page-link");
    $("span.page-numbers").removeClass("page-numbers").addClass("page-link active");
    $("span.dots").removeClass("active").addClass("page-link disabled");
    $("a.page-link").closest('li').addClass("page-item");
}