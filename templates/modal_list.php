<div id="operator_list" class="modal fade" role="dialog" style="overflow-y: scroll;">
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
                                    <li class="active"><a data-toggle="tab" href="#tab_list-1">List Message</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_list-2">Triggers</a></li>
                                	<?php endif;?>
                                    <li class=""><a data-toggle="tab" href="#tab_list-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_list-4">Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_list-5">JSON Code</a></li>
                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_list-1" class="tab-pane active">


				<div class="col-lg-7 styling_noleftpadding">
                <input placeholder="Message name" class="form-control input-lg" id="list_operator_title" type="text"  onchange="ChangeThisItem('#list_operator_title','', '_msg_name', '');">

				<!--Need the class modal_list_content to display the ajax result. Please do not remove or change this name -->
	  			<div id="modal_list_content" class="styling_modal_fieldbackground" style="text-align:center;"></div>
				</div>
				
				<div class="col-lg-5 styling_norightpadding">
										
										<?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="list_preview"></div>
										<?php 
										echo phone_preview_bottom();
										?>
									
									</div>	
															
    	
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
<script type="text/javascript">

function ChangePreviewListButtonTitle(){
var MsgID =jQuery('#edit_msgid').val();
var ItemID = jQuery('#current_item').val();
var OrgText = jQuery('#list_button_title').val();
jQuery('#'+ItemID+'_preview_button_title').html(OrgText);
jQuery('#'+MsgID+'_'+ItemID+'_item_button1_title').val(OrgText);
}

</script>