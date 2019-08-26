<div id="operator_video" class="modal fade" role="dialog" style="overflow-y: scroll;">
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
                                    <li class="active"><a data-toggle="tab" href="#tab_video-1">Video file</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_video-2">Triggers</a></li>
                               	<?php endif; ?>
                               		<li class=""><a data-toggle="tab" href="#tab_video-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_video-4">Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_video-5">JSON code</a></li>
                                    
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_video-1" class="tab-pane active">
								<div class="col-lg-7 styling_noleftpadding">
                                    <table class="table-noborder grid_table">
                                    	   <tbody> 
                                    	   		   <tr>
                                    			   	   <td><input placeholder="Message name" class="form-control input-lg" id="video_operator_title" type="text"  onchange="ChangeThisItem('#video_operator_title','', '_msg_name', '');">
                                    				   </td>
                                    			   </tr> 
                                    			   <tr>
                                    			       <td style="padding-top: 10px;"><input placeholder="Video url" class="form-control input-lg m-b" type="text" value="" name="video_url" id="video_url" class="video_url" onchange="ChangeThisItem('#video_url','_msg_content', '_video_url','');">

                                                           <span href="#myModalUpload" data-itemid=""  data-msgid="" data-msgtype="video" class="OpenUploadModal upload_btn styling_modal_fieldbackground" style="text-align: center;margin: 0;margin-top: 10px;">
                                    							<i class="fa icon-file-video fa-2x" style="padding-top: 10px;"></i>
                                                               <input type="button" class="btn btn-primary form-control" value="Upload Video File"></span>
                                    					</td>
                                    				</tr>
                                    </tbody></table>
									</div>

<div class="col-lg-5 styling_norightpadding">
									
									<?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="broadcast_msg_preview">
												<div class="broadcast_preview_video message-left" id="broadcast_preview_video"></div>
												</div>

									<?php 
									echo phone_preview_bottom();
									?>
									
									</div>		
														
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