<div class="modal fade" id="myModalImgNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal_image_upload_height">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                	<h4 class="modal-title">Upload Image</h4>
            </div>
            <div class="modal-body">
				                    <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-title m-b-md">Select an image</div>
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab-4"><i class="icon-upload2"></i> Upload image</a></li>
									<?php 
									$youzign_public_key=smartbot_get_options($user_id,'','','youzign_public_key');
									$youzign_token=smartbot_get_options($user_id,'','','youzign_token');
									if($youzign_public_key!="" && $youzign_token!=""){
									?>
                                    <li class=""><a data-toggle="tab" href="#tab-5"><i class="icon-magnifier"></i> YouZign library</a></li>
									<?php
									}
									?>
									<li class=""><a data-toggle="tab" href="#tab-6"><i class="icon-file-empty"></i> Giphy</a></li>
									<li class=""><a data-toggle="tab" href="#tab-7"><i class="icon-file-empty"></i> Upload library</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
			
			<div class="tab-content">
                                <div id="tab-4" class="tab-pane active">
                        
								<div id="image_preview"><img id="previewingnew" src="./images/preview.png" class="img-responsive"/></div><br />
								<div id="message"></div>
								<div id="new_image_detail"></div>
                                   <div class="btn-group NewImgModal">
                                    
									<form id="uploadNewImage" action="" method="post" enctype="multipart/form-data">
									<input type="hidden" name="item_id" id="item_id">
									<input type="hidden" name="msg_id" id="msg_id">
									<input type="hidden" name="msg_type" id="msg_type">
									<input type="hidden" name="action" value="upload_file">
									<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                  <input type="file" accept="image/*" name="file" id="inputNewImage" class="fileContainer" style="display: none;">
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
                                    <div id="youzign_results"></div>
									</div>
                               <?php } ?>
							   
						    <div id="tab-6" class="tab-pane">
									<input type="text" name="giphy_keyword" id="giphy_keyword" class="form-control">
									<span class="btn btn-primary search_giphy">Search Giphy</span>
									<div id="giphy_results"></div>
								</div>
																
								<div id="tab-7" class="tab-pane">
									  <strong>Your uploads</strong><br>
									  <div id="library_results_new"></div>
								</div>
								
                            </div>

                        </div>

                    </div>
	  				<script type="text/javascript">		
$(document).on('click', '.search_giphy', function (e){
		e.preventDefault();
		jQuery("#giphy_results").html('Searching...');
		var ajax_url='includes/admin-ajax.php';
		var giphy_keyword =jQuery('#giphy_keyword').val();
		
		var data = {'action': 'search_giphy',
					 'keyword':giphy_keyword,
                     'offset':offset
				    };
		 		
    		jQuery.post(ajax_url, data, function(response) {
    		jQuery("#giphy_results").html(response);		
		});	
		
	});

// Function to preview image
	jQuery(function($) {
        $("#inputNewImage").change(function() {
			$("#image_detail").html(' ');         // To remove the previous error message
			var file = this.files[0];
			var imagefile = file.type;
			var match= ["image/jpeg","image/png","image/jpg","image/gif"];	
			if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))
			{
			$('#previewingnew').attr('src','noimage.png');
			$("#image_detail").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only gif, jpeg, jpg and png Images type allowed</span>");
			return false;
			}
            else
			{
                var reader = new FileReader();	
                reader.onload = NewimageIsLoaded;
                reader.readAsDataURL(this.files[0]);
				jQuery.ajax({url: "includes/admin-ajax.php",type: "POST",data: new FormData($('#uploadNewImage')[0]), contentType: false,cache: false,processData:false,success: function(response)
              {
              var ThisItemId =jQuery(".NewImgModal #item_id").val();
              var MsgId = jQuery('.NewImgModal #msg_id').val();
              var ThisType =jQuery(".NewImgModal #msg_type").val();
              var response_arr = response.split("|", 2);
              jQuery("#new_image_detail").html(response_arr['0']);
              var imgurl=response_arr['1'];
                if(imgurl!==""){
              	jQuery('#'+ThisItemId+'_img_url').val(imgurl);
              	if(ThisType==="simple_image"){
              	jQuery('#'+ThisItemId+'_image_url').val(imgurl);
              	jQuery('#simple_image_url').val(imgurl);
              	jQuery('#preview_'+ThisItemId+' .broadcast_preview_img').html('<img id="'+ThisItemId+'preview_image" class="broadcast_preview_image" src="'+imgurl+'">');
              	}
                  
              	if(ThisType==="carousel"){
              	jQuery('#'+ThisItemId+'_preview_image').html('<img id="'+ThisItemId+'preview_img" class="preview_carousel_image" src="'+imgurl+'">');
              	jQuery('#'+MsgId+'_'+ThisItemId+'_item_image_url').val(imgurl);
              	}	
              		
              	if(ThisType==="list"){
              	jQuery('#'+ThisItemId+'_preview_list_image').html('<img id="'+ThisItemId+'preview_image" class="preview_list_image" src="'+imgurl+'">');
              	jQuery('#'+MsgId+'_'+ThisItemId+'_item_image_url').val(imgurl);
              	}
              	
              	if(ThisType==="quick"){
              	jQuery('#'+ThisItemId+'_preview_quick_image').html('<img id="'+ThisItemId+'preview_image" class="preview_quick_image" src="'+imgurl+'">');
              	jQuery('#'+ThisItemId+'_preview_quick_image').addClass('preview_quick_image_div');
              	jQuery('#'+MsgId+'_'+ThisItemId+'_img_url').val(imgurl);
              	}
              	
                }
              }});
            }		
        });
    });
	function NewimageIsLoaded(e) { 
		$("#file").css("color","green");
        $('#image_preview').css('display', 'block');
        $('#previewingnew').attr('src', e.target.result);
	};


$(document).on('click', '.youzign_tab', function (e){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'youzign_lib',
        'user':'<?php echo $_SESSION['user_id'];?>'
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery("#youzign_results").html(response);
    });
});

					</script>
					<div style="clear:both;"></div>		
			</div>
            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn btn-primary">Save changes</a>

            </div>
        </div>
    </div>
</div>