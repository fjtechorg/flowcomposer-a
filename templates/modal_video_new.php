<div id="operator_video_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Video Message</h4>
      </div>
      <div class="modal-body">
	  
	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_video-1"><i class="icon-file-video"></i>Video File</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_video-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_video-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_video-4"><i class="fa icon-tags"></i>Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_video-5"><i class="icon-code"></i>JSON Code</a></li>
                                    
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_video-1" class="tab-pane active">
                                    <table class="table-noborder grid_table">
                                    	   <tbody> 
                                    	   		   <tr>
                                    			   	   <td><input placeholder="Message name" class="form-control" id="video_operator_title" type="text"  onchange="ChangeThisItem('#video_operator_title','', '_msg_name', '');">
                                    				   </td>
                                    			   </tr> 
                                    			   <tr>
                                    			       <td><input placeholder="Video url" class="form-control" type="text" value="" name="video_url" id="video_url" class="video_url" onchange="ChangeThisItem('#video_url','_msg_content', '_video_url','');">
                                    									<span href="#myModalUpload" data-itemid=""  data-msgid="" data-msgtype="video" class="OpenUploadModal">
                                    									<br>
                                                                            <input type="button" class="btn btn-default form-control" value="Upload Video File"></span>
                                    									<br>optional, use the button to upload a video file or enter the url
                                    					</td>
                                    				</tr>
                                    </tbody></table> <br /><br /> 										
  								</div>
								<div id="tab_video-2" class="tab-pane">
									<div id="triggers_keywords_video"></div>
								</div>
								<div id="tab_video-3" class="tab-pane">
									<div id="triggers_url_video"></div>
								</div>
								<div id="tab_video-4" class="tab-pane">
									<div id="triggers_tags_video"></div>
								</div>
								<div id="tab_video-5" class="tab-pane">
									<div id="triggers_json_video"></div>
								</div>
							</div>	
						</div>									

	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_video_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>