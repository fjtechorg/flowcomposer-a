<div id="operator_quick" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Quick Replies</h4>
      </div>
      <div class="modal-body">
	  	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_quick-1">Quick Replies</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_quick-2">Triggers</a></li>
                               		<?php endif;?>
                                    <li class=""><a data-toggle="tab" href="#tab_quick-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_quick-4">Tags</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_quick-5">JSON Code</a></li>
                                
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_quick-1" class="tab-pane active">
									 <div class="col-lg-7 styling_noleftpadding">
        								<input type="hidden" name="tr_order" id="tr_order"/>
        								<input type="hidden" value="" id="current_item">
            								<div id="broadcast_msgs_canvas">
											<input placeholder="Message name" type="text" name="quick_msg_name" id="quick_msg_name" class="form-control input-lg m-b" oninput="ChangeQuickMsgName();"  >


                                                <div class="styling_modal_fieldbackground" style="text-align: center;">
                                                <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">
                                                    <span style="float:left; margin-bottom:10px;" class="badge badge-primary">Quick Reply message</span>
                                                    <textarea class="msg_text form-control input-lg modal_quick_txt" name="button_text" id="quick_text" placeholder="Enter The Text Above Buttons Here"  oninput="ChangePreviewText('','quick');"  maxlength="640" ></textarea>
                                                    <?php echo PersonalizationHTML() ?>
                                                </p>
                                                    <div id="broadcast_msgs_table" style="height: auto !important;">
                                                        <table id="broadcast_msgs" style="width:100%;border:none;" class="borderless grid_table">
            									        </table>
                                                    </div>
            									 </div>
    									 
    									 </div>
                        	   		 </div>
							   		 <div class="col-lg-5 styling_norightpadding">
									 <?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="broadcast_msg_preview">
												<div id="broadcast_preview_quick_text" class="broadcast_preview_text message-left"></div>
												<div style="clear:both;"></div>
                                                    <div  class="qslides">
												        <span id="quick_previous" class="controls quick_previous" data-slide_id="">&lt;</span>
                                                        <span class="controls quick_next" data-slide_id="">&gt;</span>
                                                        <input type="hidden" id="quick_currentSlide" value="1"/>
												<div id="broadcast_preview_quick" class="broadcast_preview_quick"></div>
												</div>

                                         </div>

									<?php 
									echo phone_preview_bottom();

									?>
									
									</div>	
							   
							    
                        	   </div>
								<div id="tab_quick-2" class="tab-pane">
									<div id="triggers_keywords_quick"></div>
								</div>
								<div id="tab_quick-3" class="tab-pane">
									<div id="triggers_url_quick"></div>
								</div>
								<div id="tab_quick-4" class="tab-pane">
									<div id="triggers_tags_quick"></div>
								</div>
								<div id="tab_quick-5" class="tab-pane">
									<div id="triggers_json_quick"></div>
								</div>								
							</div>	
						</div>				
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_quick_msg" data-dismiss="modal">Save Settings</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
function ChangeQuickMsgName(){
var ThisItemId = jQuery('#edit_msgid').val();
var OrgText = jQuery('#quick_msg_name').val();
jQuery('#'+ThisItemId+'_msg_name').val(OrgText);
jQuery ('#'+ThisItemId+' .flowchart-operator-title').html(OrgText)
}
</script>