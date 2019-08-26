<div class="modal fade" id="myModalImg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal_image_upload_height">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                	<h4 class="modal-title">Upload Image</h4>
            </div>
            <div class="modal-body">
				                    <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-title m-b-md"></div>
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab-4"> Upload image</a></li>
									<?php
                                    $youzign_public_key=smartbot_get_options($user_id,'','','youzign_public_key');
                                    $youzign_token=smartbot_get_options($user_id,'','','youzign_token');
									if($youzign_public_key!="" && $youzign_token!=""){
									?>
                                    <li class="youzign_tab"><a data-toggle="tab" href="#tab-5"> YouZign library</a></li>
									<?php
									}
									?>
									<li class=""><a data-toggle="tab" href="#tab-6"> Giphy</a></li>
									<li class=""><a data-toggle="tab" href="#tab-7"> Upload library</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
			
			<div class="tab-content">
                                <div id="tab-4" class="tab-pane active">
								<div id="image_preview"><img id="previewing" src="./images/preview.png" class="img-responsive"/></div><br />
								<div id="message"></div>
								<div id="image_detail"></div>
                                   <div class="btn-group ImgModal">
                                    
									<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
									<input type="hidden" name="item_id" id="item_id">
									<input type="hidden" name="msg_id" id="msg_id">
									<input type="hidden" name="msg_type" id="msg_type">
									<input type="hidden" name="action" value="upload_file">
									<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                    <input type="file" accept="image/*" name="file" id="inputImage" class="fileContainer" style="display: none;">
                                    <label for="inputImage" style="color: white; background: #0084FF; width: 100%;" class="btn">
                                    	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    	<span>Choose a file...</span>
                                    </label>
									</form>
                               	   </div>
								</div>						
									<?php
                                if($youzign_public_key!="" && $youzign_token!=""){?>
                                <div id="tab-5" class="tab-pane">
                                    <input type="hidden" id="youzign_fetched">
                                    <div id="youzign_results"><i class="fa icon-loading fa-spin fa-3x fa-fw"></i>
                                        <span style="font-size: 16px">Loading...</span></div>

                                    <p></p>
									</div>
                               <?php } ?>
							   
							    <div id="tab-6" class="tab-pane">
									  <div class="input-group">
										  <input type="text" name="giphy_keyword" id="giphy_keyword" class="form-control" placeholder="Enter keyword">
										  <span class="input-group-btn search_giphy" data-offset="0"><button type="button" class="btn btn-primary">Search Giphy</button></span>
									  </div>
									  <div id="giphy_results"></div>
                                      <p></p>
								</div>							
								<div id="tab-7" class="tab-pane">
									  <strong>Your uploads</strong><br>
									  <div id="image_library_results"></div>
								</div>
	                          </div>
                        </div>

                    </div>
                    <style>
                    /*-----temp styling for drag and drop for images-----*/
                    .dragging{
                    border: 2px dashed #0084FF;
                		}
                	/*-----/temp styling for drag and drop for images-----*/
                    </style>
	  				<script type="text/javascript">
	  	/*-------------- drag and drop for images-----------------------*/
	  	$("#image_preview").on("dragover", function(e) {
	    e.preventDefault();  
	    e.stopPropagation();
	    $(this).addClass('dragging');
		});

		$("#image_preview").on("dragleave", function(e) {
		    e.preventDefault();  
		    e.stopPropagation();
		    $(this).removeClass('dragging');
		});

		$(document).on('drop', '#image_preview', function(e) {

                    e.preventDefault();  
                    e.stopPropagation();

					jQuery('#myModalImg #message').html('');
                    $("#image_detail").html(' ');         // To remove the previous error message

                    $("#image_detail").html('uploading....');

                    var file = e.originalEvent.dataTransfer.files[0];

                    var imagefile = file.type;

                    var match= ["image/jpeg","image/png","image/jpg","image/gif"];

                    if(!((imagefile===match[0]) || (imagefile===match[1]) || (imagefile===match[2]) || (imagefile===match[3])))

                    {

                        $('#previewing').attr('src','noimage.png');

                        $("#image_detail").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only gif, jpeg, jpg and png Images type allowed</span>");

                        return false;

                    }

                    else

                    {

                        var reader = new FileReader();

                        reader.onload = imageIsLoaded;

                        reader.readAsDataURL(e.originalEvent.dataTransfer.files[0]);
                        var ImageUploadFormData =  new FormData($('#uploadimage')[0]);
                        ImageUploadFormData.set('file', e.originalEvent.dataTransfer.files[0]);

                        jQuery.ajax({url: "includes/admin-ajax.php",type: "POST",data: ImageUploadFormData, contentType: false,cache: false,processData:false,success: function(response)

                        {

                            var ThisItemId =jQuery(".ImgModal #item_id").val();

                            var ThisType =jQuery(".ImgModal #msg_type").val();

                            var ThisMsgId =jQuery('#edit_msgid').val();

                            var response_arr = response.split("|", 2);

                            jQuery("#image_detail").html(response_arr['0']);


                            var imgurl=response_arr['1'];

                            if(imgurl!==""){
							jQuery('#myModalImg #message').html('');
                                jQuery('#image_library_results').append('<div class="image_library_box uploaded_image giphy_selected" data-img_url="'+imgurl+'"><img class="img-responsive" src="'+imgurl+'"></div>');

                                if(ThisType==='welcome'){

                                    jQuery('#'+ThisMsgId+'_welcome_image_url').val(imgurl);

                                    jQuery('#welcome_image_url').val(imgurl);

                                    var trimmedString = '<div style="background-image: url(\''+imgurl+'\');background-position: 50% 50%;background-size: cover;padding-bottom: 52%;width:138px;"></div>';

                                    jQuery('#'+ThisMsgId+'_msg_content_img').html(trimmedString);

                                    jQuery('#'+ThisMsgId+'_welcome_img_url').val(imgurl);

                                }



                                if(ThisType==="simple_image"){

                                    jQuery('#simple_image_url').val(imgurl);

                                    jQuery('#'+ThisMsgId+'_image_url').val(imgurl);



                                    var ImgPreview = '<div style="background-image: url(\''+imgurl+'\');background-position: 50% 50%;background-size: cover;padding-bottom: 52%;width:120px;margin: 15px 0 -20px;"></div>';

                                    jQuery('#'+ThisMsgId+'_msg_content').html(ImgPreview);

                                    jQuery('#phone_preview_image').attr('src', imgurl);


                                    AddImage(ThisType,ThisItemId,ThisMsgId,imgurl);



                                }



                                else{

                                    jQuery('#'+ThisItemId+'item_image').attr('src', imgurl);

                                    jQuery('#'+ThisItemId+'preview_image').css('background-image', 'url('+imgurl+')');

                                    jQuery('#'+ThisMsgId+'_'+ThisItemId+'_item_image_url').val(imgurl);

                                }


                                if(ThisType==="widget"){
                                    AddImage(ThisType,ThisItemId,ThisMsgId,imgurl);
                                }



                                if(ThisType==="list"){

                                    jQuery('#'+ThisItemId+'_preview_list_image').html('<img id="'+ThisItemId+'preview_image" class="preview_list_image" src="'+imgurl+'">');

                                }

                                if(ThisType==="quick"){

                                    jQuery('#'+ThisMsgId+'_'+ThisItemId+'_img_url').val(imgurl);


                                }



                            }



                        }});



                    }


                });
		function imageIsLoaded(e) { 
		jQuery('#myModalImg #message').html('');
		$("#file").css("color","green");

        $('#image_preview').css("display", "block");

        $('#previewing').attr('src', e.target.result);

		}
		/*-------------- /drag and drop for images-----------------------*/
		
		$(document).on('click', '.search_giphy', function (e){
		e.preventDefault();
		jQuery("#giphy_results").html('Searching...');
		var ajax_url='includes/admin-ajax.php';
		var giphy_keyword =jQuery('#giphy_keyword').val();
		var offset =  $(this).data('offset');
		var data = {'action': 'search_giphy',
					 'keyword':giphy_keyword,
                    'offset':offset
				    };
    		jQuery.post(ajax_url, data, function(response) {
    		jQuery("#giphy_results").html(response);		
			});	
		});

        $(document).on('click', '.youzign_tab', function (e){
            var youzign_fetched = jQuery('#youzign_fetched').val();
            if(youzign_fetched!=="yeah"){
            var ajax_url='includes/admin-ajax.php';
            var data = {'action': 'youzign_lib',
                'user':'<?php echo $_SESSION['user_id'];?>'
            };
            jQuery.post(ajax_url, data, function(response) {
                jQuery("#youzign_results").html(response);
                jQuery('#youzign_fetched').val('yeah');
            });
            }
        });
		
		$(document).on('click', '.uploaded_image', function (){
		jQuery('#myModalImg #message').html('');
		$(".giphy_selected").removeClass("giphy_selected");
		var ThisItemId =jQuery(".ImgModal #item_id").val();
		var ThisType =jQuery(".ImgModal #msg_type").val();
		var ThisMsgId =jQuery('#edit_msgid').val();
		var imgurl = $(this).data('img_url');
		$(this).addClass('giphy_selected');
		AddImage(ThisType,ThisItemId,ThisMsgId,imgurl)
		});	
				
		$(document).on('click', '.youzign_image', function (){
		jQuery('#myModalImg #message').html('');
		$(".giphy_selected").removeClass("giphy_selected");
		var ThisItemId =jQuery(".ImgModal #item_id").val();
		var ThisType =jQuery(".ImgModal #msg_type").val();
		var ThisMsgId =jQuery('#edit_msgid').val();
		var imgurl = $(this).data('img_url');
		$(this).addClass('giphy_selected');
		AddImage(ThisType,ThisItemId,ThisMsgId,imgurl)
		});	
		
		$(document).on('click', '.giphy_image', function (){
		jQuery('#myModalImg #message').html('');
		$(".giphy_selected").removeClass("giphy_selected");
		var imgurl = $(this).attr('src');
		$(this).addClass('giphy_selected');
		var ThisItemId =jQuery(".ImgModal #item_id").val();
		var ThisType =jQuery(".ImgModal #msg_type").val();
		var ThisMsgId =jQuery('#edit_msgid').val();
		AddImage(ThisType,ThisItemId,ThisMsgId,imgurl);
		
		});
		

		function AddImage(ThisType,ThisItemId,ThisMsgId,imgurl){
		jQuery('#'+ThisItemId+'_img_url').val(imgurl);
		if(ThisType==='welcome'){
    	jQuery('#'+ThisMsgId+'_welcome_image_url').val(imgurl);
    	jQuery('#welcome_image_url').val(imgurl);
   		 var trimmedString = '<div style="background-image: url(\''+imgurl+'\');background-position: 50% 50%;background-size: cover;padding-bottom: 52%;width:138px;"></div>';
   		  jQuery('#'+ThisMsgId+'_msg_content_img').html(trimmedString);
    	  jQuery('#'+ThisMsgId+'_welcome_img_url').val(imgurl);
    	  }
	
		if(ThisType==="simple_image"){
		jQuery('#simple_image_url').val(imgurl);
		jQuery('#'+ThisMsgId+'_image_url').val(imgurl);
		jQuery('#preview_'+ThisItemId+' .broadcast_preview_img').html('<img id="'+ThisItemId+'preview_image" class="broadcast_preview_image img-responsive" src="'+imgurl+'">');
		jQuery('#'+ThisItemId+'_img_url').val(imgurl);
		jQuery('#phone_preview_image').attr('src', imgurl);
		}else{
    	jQuery('#'+ThisItemId+'item_image').attr('src', imgurl);
    	jQuery('#'+ThisItemId+'preview_image').css('background-image', 'url('+imgurl+')');
   		 jQuery('#'+ThisMsgId+'_'+ThisItemId+'_item_image_url').val(imgurl);
		 jQuery('#phone_preview_image').attr('src', imgurl);
    	 }


         if(ThisType==="template"){
                jQuery('#template_icon').val(imgurl);
         }
        if(ThisType==="widget"){
          jQuery('#'+ThisItemId).val(imgurl).trigger("change");
         }



		if(ThisType==="carousel"){
		jQuery('#'+ThisItemId+'_preview_image').css("background-image", "url("+imgurl+")");
		//jQuery('#'+ThisItemId+'_preview_image').html('<img id="'+ThisItemId+'preview_img" class="preview_carousel_image img-responsive" src="'+imgurl+'">');
		jQuery('#'+ThisItemId+'_img_url').val(imgurl);

		//we should check if this item is the first and if so we will add this image to the canvas. Else not
		jQuery('#'+ThisMsgId+'_msg_content_img').html('<div class="visual_img_preview" style="background-image: url(\''+imgurl+'\');"></div>');

		}	
		 
		 if(ThisType==="list"){
		 jQuery('#'+ThisItemId+'_preview_list_image').html('<img id="'+ThisItemId+'preview_image" class="preview_list_image img-responsive" src="'+imgurl+'">');
		 jQuery('#'+ThisItemId+'_img_url').val(imgurl);
		 }
		 if(ThisType==="quick"){
		 jQuery('#'+ThisMsgId+'_'+ThisItemId+'_img_url').val(imgurl);
		 jQuery('#'+ThisItemId+'_preview_quick_image').html('<img id="'+ThisItemId+'preview_image" class="preview_quick_image img-responsive" src="'+imgurl+'">');
		 jQuery('#'+ThisItemId+'_preview_quick_image').addClass('preview_quick_image_div');
		 }
		}
		</script>

                <div style="clear:both;"></div>
			</div>
            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn btn-primary">Save changes</a>

            </div>
        </div>
    </div>
</div>