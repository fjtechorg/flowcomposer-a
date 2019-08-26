<div id="operator_structured_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Carousel Message</h4>
      </div>
      <div class="modal-body">
	  	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_structured-1"><i class="icon-picture"></i>Message</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_structured-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_structured-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_structured-4"><i class="fa icon-tags"></i>Tags</a></li>
									<li class=""><a data-toggle="tab" href="#tab_structured-5"><i class="icon-code"></i>JSON Code</a></li>
                                                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_structured-1" class="tab-pane active">
	  
	  	   		<table class="table-noborder">
    				<tr><td width="120px"><label for="operator_title">Message Name: </label></td>
    				<td><input class="form-control" id="structured_operator_title" type="text"  onchange="ChangeThisItem('#structured_operator_title','', '_msg_name', '');"></td>
    				</tr>
				</table>	
				<!--Need the class modal_structured_content to display the ajax result. Please do not remove or change this name -->
	  			<div id="modal_structured_content"></div>		
	 <br /><br /> 	
	 <div style="clear:both;"></div>
	 			 </div>
								<div id="tab_structured-2" class="tab-pane">
									<div id="triggers_keywords_structured"></div>
								</div>
								<div id="tab_structured-3" class="tab-pane">
									<div id="triggers_url_structured"></div>
								</div>
								<div id="tab_structured-4" class="tab-pane">
									<div id="triggers_tags_structured"></div>
								</div>
								<div id="tab_structured-5" class="tab-pane">
									<div id="triggers_json_structured"></div>
								</div>
							</div>	
						</div>				
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_structured_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>