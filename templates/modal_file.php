<div id="operator_file" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">File Message</h4>
      </div>
      <div class="modal-body">
	   <div class="panel blank-panel">
                       
					   
					    <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_file-1">Upload file</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>
                                    <li class=""><a data-toggle="tab" href="#tab_file-2">Triggers</a></li>
                                    <?php endif; ?>
                                	<li class=""><a data-toggle="tab" href="#tab_file-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_file-4">Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_file-5">JSON code</a></li>
                                
								</ul>
                            </div>
                        </div>
						
						
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_file-1" class="tab-pane active">
								<div class="col-lg-7 styling_noleftpadding">
                                    <table class="table-noborder grid_table">
                                    	   <tbody> 
                                    	   		   <tr>
                                    			   	   <td><input placeholder="Message name" class="form-control input-lg" id="file_operator_title" type="text"  onchange="ChangeThisItem('#file_operator_title','', '_msg_name', '');">
                                    				   </td>
                                    			   </tr> 
                                    			   <tr>
                                    			       <td style="padding-top: 10px;"><input placeholder="File url" class="form-control input-lg m-b" type="text" value="" name="file_url" id="file_url" class="file_url" onchange="ChangeThisItem('#file_url','_msg_content', '_file_url','');">
                                    					<span href="#myModalUpload" data-itemid=""  data-msgid="" data-msgtype="file" class="OpenUploadModal upload_btn styling_modal_fieldbackground" style="text-align: center;margin: 0;margin-top: 10px;">
                                                            <i class="fa icon-file-empty fa-2x" style="padding-top: 10px;"></i>
                                                            <input type="button" class="btn btn-primary form-control" value="Upload File"></span>
                                    									</td>
                                    				</tr>
                                    </tbody></table>
									</div>

<div class="col-lg-5 styling_norightpadding">
									
									<?php 
									echo phone_preview_top();
									?>
												
												<div class="boxlayout_big" id="broadcast_preview_file">

												</div>

									<?php 
									echo phone_preview_bottom();
									?>
									
									</div>										
									
                                    </div>
									
    								<div id="tab_file-2" class="tab-pane">
    									<div id="triggers_keywords_file"></div>
    								</div>
    								<div id="tab_file-3" class="tab-pane">
    									<div id="triggers_url_file"></div>
    								</div>
    								<div id="tab_file-4" class="tab-pane">
    									<div id="triggers_tags_file"></div>
    								</div>
      								<div id="tab_file-5" class="tab-pane">
      									<div id="triggers_json_file"></div>
      								</div>
									
							</div>	
						</div>									

	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_file_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>