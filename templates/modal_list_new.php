<div id="operator_list_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">List Message</h4>
      </div>
      <div class="modal-body">	  	  
	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_list-1"><i class="icon-menu"></i>Message</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_list-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_list-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_list-4"><i class="fa icon-tags"></i>Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_list-5"><i class="icon-code"></i>JSON Code</a></li>
                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_list-1" class="tab-pane active">

				<input placeholder="Message name" class="form-control" id="list_operator_title" type="text"  onchange="ChangeThisItem('#list_operator_title','', '_msg_name', '');">
				<br />
				<!--Need the class modal_list_content to display the ajax result. Please do not remove or change this name -->
	  			<div id="modal_list_content"></div>		
	 <br /><br /> 	
	 <div style="clear:both;"></div>
	 			</div>
								<div id="tab_list-2" class="tab-pane">
									<div id="triggers_keywords_list"></div>
								</div>
								<div id="tab_list-3" class="tab-pane">
									<div id="triggers_url_list"></div>
								</div>
								<div id="tab_list-4" class="tab-pane">
									<div id="triggers_tags_list"></div>
								</div>
								<div id="tab_list-5" class="tab-pane">
									<div id="triggers_json_list"></div>
								</div>
							</div>	
						</div>						
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_list_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>