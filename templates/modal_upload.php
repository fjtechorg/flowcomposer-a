<div class="modal fade" id="myModalUpload">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                	<h4 class="modal-title">Upload File</h4>

            </div>

            <div class="modal-body">

				  <div class="panel blank-panel">

                        <div class="panel-heading">

                            <div class="panel-title m-b-md"></div>

                            <div class="panel-options">

                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab_upload-1">Upload file</a></li>

									<li class=""><a data-toggle="tab" href="#tab_upload-2">Upload library</a></li>

								</ul>

                            </div>

                        </div>



                        <div class="panel-body">

			

            			<div class="tab-content">

                                            <div id="tab_upload-1" class="tab-pane active">

                                            <div id="upload_message"></div>

                                                   <div class="btn-group UploadModal">

                                                    <form id="uploadfile" action="" method="post" enctype="multipart/form-data">

                									<input type="hidden" name="item_id" id="item_id">

                									<input type="hidden" name="msg_id" id="msg_id">

                									<input type="hidden" name="msg_type" id="msg_type">

                									<input type="hidden" name="action" value="upload_file">

                									<input type="hidden" name="user_id" value="<?php echo $user_id;?>">

                                                        <input type="file" name="file" id="inputFile" class="inputFileSelect fileContainer" style="display: none;">
					                                    <label for="inputFile" style="color: white; background: #0084FF; width: 100%;" class="btn">
					                                    	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
					                                    	<span>Choose a file...</span>
					                                    </label>
					                                    <strong>Max file size: 25mb</strong>

                									</form>

                                               	   </div>

            								   

            								</div>

											

											<div id="tab_upload-2" class="tab-pane">

									  		<strong>Your uploads:</strong><br>

									  		<div id="file_library_results"></div>

											</div>

                         </div>



                        </div>



                    </div>

	  

					<div style="clear:both;"></div>		

			</div>

            <div class="modal-footer">	<a href="#" data-dismiss="modal" class="btn btn-primary">Save changes</a>



            </div>

        </div>

    </div>

<script type="text/javascript">



		$(document).on('click', '.uploaded_file', function (){

		$(".uploaded_file").removeClass("giphy_selected");

		$(this).addClass('giphy_selected');

		var UploadUrl = $(this).data('file_url');

		var UploadName = $(this).data('file_name');

		var log = $(this).data('file_name');

		var ThisItemId =jQuery(".UploadModal #item_id").val();

		var ThisType =jQuery(".UploadModal #msg_type").val();

		var ThisMsgId =jQuery('.UploadModal #msg_id').val();

		

		if(ThisType==='audio'){

	jQuery('#audio_url').val(UploadUrl);

    jQuery('#'+ThisMsgId+'_audio_url').val(UploadUrl);

	jQuery('#'+ThisMsgId+'_msg_content').html('<audio controls class="audio_'+ThisItemId+'"><source id="'+ThisItemId+'_audio_src" src="'+UploadUrl+'" type="audio/mp3"></audio>');

	jQuery('#preview_'+ThisItemId+' .broadcast_preview_audio' ).html('<span class="chat_view_audio"><audio controls class="audio_'+ThisItemId+'"><source id="'+ThisItemId+'_audio_src" src="'+UploadUrl+'" type="audio/mp3"></audio></span>');

	jQuery('#broadcast_preview_audio').html('<span class="chat_view_audio"><audio controls class="preview_audio_'+ThisItemId+'"><source id="'+ThisItemId+'_audio_src" src="'+UploadUrl+'" type="audio/mp3"></audio></span>');
	
	Plyr.setup('.audio_'+ThisItemId, {
		controls: ['play-large', 'play', 'progress', 'current-time']
	});
	
		Plyr.setup('.preview_audio_'+ThisItemId, {
		controls: ['play-large', 'play', 'progress', 'current-time']
	});

	}

	

	if(ThisType==='video'){

	jQuery('#video_url').val(UploadUrl);

    jQuery('#'+ThisMsgId+'_video_url').val(UploadUrl);

	jQuery('#'+ThisMsgId+'_msg_content').html('<video class="video_'+ThisItemId+'" poster="" controls><source id="'+ThisItemId+'_video_src" src="'+UploadUrl+'" type="video/mp4"></video>');

	jQuery('#'+ThisItemId+'_video_src').attr('src', UploadUrl);

	jQuery('#preview_'+ThisItemId+' .broadcast_preview_video').html('<video class="video_'+ThisItemId+'" poster="" controls><source id="'+ThisItemId+'_video_src" src="'+UploadUrl+'" type="video/mp4"></video>');

	jQuery('#broadcast_preview_video').html('<video class="preview_video_'+ThisItemId+'" poster="" controls><source id="'+ThisItemId+'_video_src" src="'+UploadUrl+'" type="video/mp4"></video>');

	Plyr.setup('.preview_video_'+ThisItemId);

	Plyr.setup('.video_'+ThisItemId);

	}

	

	if(ThisType==='file'){

	jQuery('#file_url').val(UploadUrl);

    jQuery('#'+ThisMsgId+'_file_url').val(UploadUrl);
    jQuery('#'+ThisMsgId+'_file_name').val(UploadName);

	jQuery('#'+ThisMsgId+'_msg_content').html('<a href="'+UploadUrl+'" target="_blank"><i class="fa  icon-file-empty"></i> '+UploadName+'</a>');

	jQuery('#broadcast_preview_file').html('<a href="'+UploadUrl+'" target="_blank"><i class="fa  icon-file-empty"></i> '+UploadName+'</a>');
    jQuery('#preview_'+ThisMsgId+' .broadcast_preview_file').html('<a href="'+UploadUrl+'" target="_blank"><i class="fa  icon-file-empty"></i> '+UploadName+'</a>');
	

	

	}

	});

	

</script>	

</div>