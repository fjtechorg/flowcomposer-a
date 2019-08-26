

$(document).on('click', '.delete_image', function (e) {

    e.preventDefault();

    var ThisItemId = $(this).data('item_id');

    var ThisType = $(this).data('msg_type');

    jQuery('#'+ThisItemId+'_img_url').val('');

    jQuery('#myModalImg #message').html('image deleted');

    jQuery('#previewing').attr('src', './images/preview.png');

    if(ThisType==="simple_image"){

        jQuery('#simple_image_url').val('');

        jQuery('#preview_'+ThisItemId+' .broadcast_preview_img').html('');

    }



    if(ThisType==="carousel"){

        jQuery('#'+ThisItemId+'_preview_image').html('');

    }



    if(ThisType==="list"){

        jQuery('#'+ThisItemId+'_preview_list_image').html('');

    }



    if(ThisType==="quick"){

        jQuery('#'+ThisItemId+'_preview_quick_image').html('');

        jQuery('#'+ThisItemId+'_preview_quick_image').removeClass('preview_quick_image_div');

    }

});

$(document).on('click', '.OpenImgModal', function (e) {

    e.preventDefault();

    jQuery('#previewing').attr('src', '');

    jQuery('#myModalImg #message').html('');

    var ThisItemId = $(this).data('itemid');

    var ThisMsgId =jQuery('#edit_msgid').val();

    var ThisType = $(this).data('msgtype');

    jQuery(".ImgModal #item_id").val( ThisItemId );

    jQuery(".ImgModal #msg_id").val( ThisMsgId );

    jQuery(".ImgModal #msg_type").val(ThisType );

    var img_url = jQuery('#'+ThisItemId+'_img_url').val();

    if(img_url!=""){

        jQuery('#previewing').attr('src', img_url);

        jQuery('#myModalImg #message').html('<button class="delete_image btn btn-primary" data-item_id="'+ThisItemId+'" data-msg_type="'+ThisType+'">Delete Image</button><br/><hr />Or Upload New image');

    }else{

        jQuery('#previewing').attr('src', '../images/preview.png');

    }


    var ajax_url='../includes/admin-ajax.php';
    var user_id = jQuery('#user_id').val();
    var data={
        'action':'show_image_library',
        'user_id': user_id
    }

    jQuery.post(ajax_url, data, function(response) {

        jQuery('#image_library_results').html(response);
    });


    jQuery("#image_detail").html("");

    jQuery("#inputImage").val("");

    jQuery('#myModalImg').modal();

});



$(document).on('click', '.OpenUploadModal', function () {

    jQuery(".UploadModal #inputFile").val( '');

    var ThisItemId = $(this).data('itemid');

    var ThisMsgId =$(this).data('itemid');

    var ThisType = $(this).data('msgtype');

    jQuery(".UploadModal #item_id").val( ThisItemId );



    jQuery("#upload_message").html('');

    jQuery(".UploadModal #msg_id").val( ThisMsgId );

    jQuery(".UploadModal #msg_type").val(ThisType );



    var ajax_url='../includes/admin-ajax.php';
    var user_id = jQuery('#user_id').val();
    var data={

        'action':'show_file_library',

        'user_id': user_id,

        'msg_type': ThisType

    }

    jQuery.post(ajax_url, data, function(response) {

        jQuery('#file_library_results').html(response);

    });



    jQuery('#myModalUpload').modal();

});





// Function to preview image

jQuery(function($) {

    $("#inputImage").change(function() {

        $("#image_detail").html(' ');         // To remove the previous error message

        $("#image_detail").html('uploading....');

        var file = this.files[0];

        var imagefile = file.type;

        var match= ["image/jpeg","image/png","image/jpg","image/gif"];

        if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))

        {

            $('#previewing').attr('src','noimage.png');

            $("#image_detail").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only gif, jpeg, jpg and png Images type allowed</span>");

            return false;

        }

        else

        {

            var reader = new FileReader();

            reader.onload = imageIsLoaded;

            reader.readAsDataURL(this.files[0]);



            jQuery.ajax({url: "../includes/admin-ajax.php",type: "POST",data: new FormData($('#uploadimage')[0]), contentType: false,cache: false,processData:false,success: function(response)

            {



                var ThisItemId =jQuery(".ImgModal #item_id").val();

                var ThisType =jQuery(".ImgModal #msg_type").val();

                var ThisMsgId =jQuery('#edit_msgid').val();

                var response_arr = response.split("|", 2);

                jQuery("#image_detail").html(response_arr['0']);

                var imgurl=response_arr['1'];

                if(imgurl!==""){

                    jQuery('#'+ThisItemId+'_img_url').val(imgurl);

                    jQuery('#image_library_results').append('<div class="image_library_box uploaded_image giphy_selected" data-img_url="'+imgurl+'"><img class="img-responsive" src="'+imgurl+'"></div>');

                    AddPreviewImage(ThisItemId,ThisType,imgurl);

                }



            }});

        }

    });

});

function imageIsLoaded(e) {

    $("#file").css("color","green");

    $('#image_preview').css("display", "block");

    $('#previewing').attr('src', e.target.result);

};


function AddPreviewImage(ThisItemId,ThisType,imgurl){

    if(ThisType==="simple_image" || ThisType==="image"){
        jQuery('#simple_image_url').val(imgurl);
        jQuery('#preview_'+ThisItemId+' .broadcast_preview_img').html('<img id="'+ThisItemId+'preview_image" class="broadcast_preview_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="carousel"){
        jQuery('#'+ThisItemId+'_preview_image').css("background-image", "url("+imgurl+")");
        //jQuery('#'+ThisItemId+'_preview_image').html('<img id="'+ThisItemId+'preview_img" class="preview_carousel_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="list"){
        jQuery('#'+ThisItemId+'_preview_list_image').html('<img id="'+ThisItemId+'preview_image" class="preview_list_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="quick"){
        jQuery('#'+ThisItemId+'_preview_quick_image').html('<img id="'+ThisItemId+'preview_image" class="preview_quick_image img-responsive" src="'+imgurl+'">');
        jQuery('#'+ThisItemId+'_preview_quick_image').addClass('preview_quick_image_div');
        jQuery('#'+ThisItemId+'_img_url').val(imgurl);
    }
}