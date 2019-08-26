<div id="operator_file_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
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
                                    <li class="active"><a data-toggle="tab" href="#tab_file-1"><i class="icon-upload2"></i>Upload File</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_file-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_file-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_file-4"><i class="fa icon-tags"></i>Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_file-5"><i class="icon-code"></i>JSON Code</a></li>
                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_file-1" class="tab-pane active">
                                    <table class="table-noborder grid_table">
                                    	   <tbody> 
                                    	   		   <tr>
                                    			   	   <td><input placeholder="Message name" class="form-control" id="file_operator_title" type="text"  onchange="ChangeThisItem('#file_operator_title','', '_msg_name', '');">
                                    				   </td>
                                    			   </tr> 
                                    			   <tr>
                                    			       <td><input placeholder="File url" class="form-control" type="text" value="" name="file_url" id="file_url" class="file_url" onchange="ChangeThisItem('#file_url','_msg_content', '_file_url','');">
                                    					<span href="#myModalUpload" data-itemid=""  data-msgid="" data-msgtype="file" class="OpenUploadModal"><input type="button" class="btn btn-default form-control" value="Upload File"></span>
                                    										<br>optional, use the button to upload a file or enter the url</td>
                                    				</tr>
                                    </tbody></table> <br /><br /> 	
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
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_file_msg" data-dismiss="modal">Save Settings</button>
      </div>
    </div>

  </div>
</div>