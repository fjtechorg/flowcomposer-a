jQuery(document).ready(function(){
    jQuery("#phone_preview_image").hide();

    jQuery("#select_reference_type,#select_reference_flow,#select_reference_flow_card").on("change", function(e) {
        $(".action_download_code_container").hide();
        $("#phone_preview_image").hide();
    });

    jQuery('#download_code').click(function() {
        $('a.download_img_href > img').trigger( "click" );
        return false;
    });

    jQuery(document).on('click', '.action_create_code', function () {
        let imgSize = jQuery('#select_size').val();
        if(imgSize < 100 || imgSize > 2000){
            modalAlert('Messenger code size should be between 100px and 2000px');
            return false;
        }
        let refType = jQuery('#select_reference_type').val();
        let flowID = jQuery('#select_reference_flow').val();
        let msgID = jQuery('#select_reference_flow_card').val();
        let widgetID = jQuery('#widget_id').val();
        requestMessengerCode(imgSize,refType,flowID,msgID,widgetID,true,"builder");
    });

});

function SetMessengerCode(MsgSize,ImgUrl,source){
    if (source==="builder")
        size = 260;
    else if (source==="library")
        size = 375;
    jQuery('#msg_code_size').html(MsgSize+'px');
    jQuery('#download_img_src').attr('src',ImgUrl);
    jQuery('#phone_preview_image').css('background','url('+ImgUrl+')').css("background-repeat", "no-repeat").css("background-size", size).show();
    jQuery('.action_download_code').attr('href',"download_proxy.php?f="+encodeURIComponent(ImgUrl));

}

function requestMessengerCode(imgSize,refType,flowID,msgID,widgetID,showToaster,source){
    jQuery('#phone_preview_image').hide();
    var selector = "body";

    if (source==="builder") {
        selector = ".messenger-code-copy-div2";
    }
    else if (source==="library"){
        selector = "body";
    }

    jQuery(selector).block({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Fetching data...</span>',
        overlayCSS: {opacity: .5}
    });

    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'get_messenger_code','size':imgSize,'refType':refType,'flowID':flowID,'msgID':msgID,'widgetID':widgetID,'update':showToaster};
    jQuery.post(ajax_url, data, function(response) {
        if(response){
            //we have the page scan code here
            SetMessengerCode(imgSize, response,source);
            jQuery(".action_download_code_container").show();
            jQuery(".messenger-code-copy-div2,body").unblock();

            if (showToaster) {
                toastr.success("Your Messenger Code is saved.", "Success!");
            }
        }
        else{
            if (showToaster)
                toastr.error("Could not save your Messenger Code.", "Error!");
        }
    });
}