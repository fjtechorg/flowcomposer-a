<div class="modal fade" id="myModalUploadNew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                	<h4 class="modal-title">Upload File</h4>
            </div>
            <div class="modal-body">
				  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-title m-b-md"><h4>Select a File and Add it</h4></div>
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_upload-1"><i class="icon-upload2"></i>Upload File</a></li>
									<li class=""><a data-toggle="tab" href="#tab_upload-2"><i class="icon-file-empty"></i>Your Uploads</a></li>
								</ul>
                            </div>
                        </div>

                        <div class="panel-body">
			
			<div class="tab-content">
                                <div id="tab_upload-" class="tab-pane active">
								<strong>Max file size : 25mb</strong><br />
                                <div id="new_upload_message"></div>
                                   <div class="btn-group UploadModalNew">
                                    <form id="newuploadfile" action="" method="post" enctype="multipart/form-data">
									<input type="hidden" name="item_id" id="item_id">
									<input type="hidden" name="msg_id" id="msg_id">
									<input type="hidden" name="msg_type" id="msg_type">
									<input type="hidden" name="action" value="upload_file">
									<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                                        <input type="file" name="file" id="inputFileNew" class="inputFileSelect">
									</form>
                               	   </div>
								</div>
										<div id="tab_upload2" class="tab-pane">
									  		<strong>Your Uploads</strong><br>
									  		<div id="library_results"></div>
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
</div>
<script type="text/javascript">
$("#inputFileNew").change(function() {
jQuery("#new_upload_message").html(''); 	 
jQuery.ajax({url: "includes/admin-ajax.php",type: "POST",data: new FormData($('#newuploadfile')[0]), contentType: false,cache: false,processData:false,success: function(response)
{
var ThisItemId =jQuery(".UploadModalNew #item_id").val();
var response_arr = response.split("|", 4);
jQuery("#new_upload_message").html(response_arr['0']);
var UploadUrl = response_arr['1'];
  if(UploadUrl!==""){
  var ThisType = response_arr['2'];
    
	if(ThisType==='audio'){
	 jQuery('#audio_url').val(UploadUrl);
    jQuery('#'+ThisItemId+'_audio_url').val(UploadUrl);
	jQuery('#preview_'+ThisItemId+' .broadcast_preview_audio' ).html('<span class="chat_view_audio"><audio controls class="audio_'+ThisItemId+'"><source id="'+ThisItemId+'_audio_src" src="'+UploadUrl+'" type="audio/mp3"></audio></span>');
	Plyr.setup('.audio_'+ThisItemId, {
		controls: ['play-large', 'play', 'progress', 'current-time']
	});
	}
	
	if(ThisType==='video'){
	jQuery('#video_url').val(UploadUrl);
    jQuery('#'+ThisItemId+'_video_url').val(UploadUrl);
	jQuery('#'+ThisItemId+'_video_src').attr('src', UploadUrl);
	jQuery('#preview_'+ThisItemId+' .broadcast_preview_video').html('<video class="video_'+ThisItemId+'" poster="" controls><source id="'+ThisItemId+'_video_src" src="'+UploadUrl+'" type="video/mp4"></video>');
	Plyr.setup('.video_'+ThisItemId);
	}
	
	if(ThisType==='file'){
	var UploadName = response_arr['3'];
	jQuery('#file_url').val(UploadUrl);
    jQuery('#'+ThisItemId+'_file_url').val(UploadUrl);
	jQuery('#preview_'+ThisItemId+' .broadcast_preview_file').html('<a href="'+UploadUrl+'" target="_blank"><i class="fa  icon-file-empty"></i> '+UploadName+'</a>');
	
	}
  }

}});
});

</script>