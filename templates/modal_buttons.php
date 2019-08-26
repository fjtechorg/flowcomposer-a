<div id="operator_buttons" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Buttons</h4>
      </div>
      <div class="modal-body">	
	  
	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_buttons-1">Button message</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>
                                    <li class=""><a data-toggle="tab" href="#tab_buttons-2">Triggers</a></li>
                               		<?php endif;?>
                                    <li class=""><a data-toggle="tab" href="#tab_buttons-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_buttons-4">Tags</a></li>
                                <li class=""><a data-toggle="tab" href="#tab_buttons-5">JSON Code</a></li>
                                
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_buttons-1" class="tab-pane active">
                                	<div class="row">
	                            		<div class="col-lg-7 styling_modalbuttons_prnlicon">
									 		<input class="form-control input-lg" id="buttons_operator_title" type="text"  onchange="ChangeThisItem('#buttons_operator_title','', '_msg_name', '');" placeholder="Message name">
                                            <div class="styling_modal_fieldbackground" style="text-align: center;">
                                            <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">
                                             <span style="float:left; margin-bottom:10px;" class="badge badge-primary">Button message</span>
                                            <textarea class="msg_text form-control input-lg modal_button_txt" name="button_text" id="button_text" placeholder="Message text" oninput="ChangePreviewText('','buttons');"  maxlength="640" ></textarea>
											<?php echo PersonalizationHTML() ?>
                                            </p>
                                            <div id="button_settings">
												<table class="table-noborder" id="button_settings_table"></table>
											</div>
											<span data-msgtype="buttons" class="btn btn-primary AddButton m-t" style="margin-bottom: 10px;" value="+Add Button">+ Add button</span>
											<input type="hidden" name="num_buttons" id="num_buttons" value="0" />
                                            </div>
	                            		</div>
	                            		<div class="col-lg-5 styling_withrightpadding">
		                            	<?php 
    									echo phone_preview_top();
    									?>
					                            		
														<div class="boxlayout_big" id="button_msg_preview">

                                                            <div style="clear:both;"></div>
                                                            <div class="button_wrapper">
                                                                 <div class="button_preview_text message-left broadcast_preview_text button_header"></div>
                                                                 <div style="clear:both;"></div>
                                                                 <div id="buttons_preview"></div>
                                                            </div>
                                                        </div>
														
    									<?php 
    									echo phone_preview_bottom();
    									?>
	                            	</div>
									</div>
                            	</div>
								<div id="tab_buttons-2" class="tab-pane">
									<div id="triggers_keywords_buttons"></div>
								</div>
								<div id="tab_buttons-3" class="tab-pane">
									<div id="triggers_url_buttons"></div>
								</div>
								<div id="tab_buttons-4" class="tab-pane">
									<div id="triggers_tags_buttons"></div>
								</div>
								<div id="tab_buttons-5" class="tab-pane">
									<div id="triggers_json_buttons"></div>
								</div>									
							</div>	
						</div>										
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_thebutton_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>	
<script type="text/javascript">
$(document).on('click', '.AddButton', function () {

	 var ButtonTxt = jQuery('#button_text').val();
	 if(ButtonTxt!==""){
     var ThisItemId = jQuery('#edit_msgid').val();
	 var NumButtons = jQuery('#'+ThisItemId+'_num_buttons').val();
	 if (NumButtons===''){NumButtons=Number("0");}else{NumButtons=Number(NumButtons);}

	 if(NumButtons<3){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	 $('#button_settings_table').append('<tr id="'+uniqID+'"><td ><input type="text" name="'+uniqID+'_button_title" id="'+uniqID+'_button_title" onkeyup="ChangePreviewButtonTitle(\''+uniqID+'\');" class="form-control " placeholder="Enter button text" size="30" maxlength="20"></input></td><td valign="middle"><div class="input-lg m-b button_link_item" id="'+uniqID+'_button_link_select" data-item_id="'+uniqID+'"><i class="icon-link2" aria-hidden="true"></i></div></td>	 <td valign="middle"><div class="input-lg m-b button_msg_item" id="'+uniqID+'_button_msg_select"  data-item_id="'+uniqID+'"><i class="icon-bubbles" aria-hidden="true" data-item_id="'+uniqID+'"></i></div></td><td valign="middle"><div class="input-lg m-b button_phone_item" id="'+uniqID+'_button_phone_item" data-item_id="'+uniqID+'"><i class="icon-telephone" aria-hidden="true"></i></div></td><td valign="middle"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
	 $('#buttons_preview').append('<div id="'+uniqID+'_preview"></div>');
	 var $buttonfields = $('<input type="hidden"  id="'+ThisItemId+'_button_items" class="'+ThisItemId+'_'+uniqID+'_button_items" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> <input type="hidden" name="'+uniqID+'_title" id="'+uniqID+'_title"><input type="hidden" name="'+uniqID+'_type" id="'+uniqID+'_type"><input type="hidden" name="'+uniqID+'_webview_height_ratio" id="'+uniqID+'_webview_height_ratio"><input type="hidden" name="'+uniqID+'_url" id="'+uniqID+'_url"><input type="hidden" name="'+uniqID+'_msg" id="'+uniqID+'_msg"><input type="hidden" name="'+uniqID+'_phone" id="'+uniqID+'_phone">');
	 jQuery($buttonfields).appendTo('#form');
	 var NewNum = Number("1");
	 var NewButtonNum = NumButtons+NewNum;
	 jQuery('#'+ThisItemId+'_num_buttons').val(NewButtonNum);
	 ButtonPreviewStyling(ThisItemId,NewButtonNum,uniqID);
	 if(NewButtonNum===3){jQuery('.AddButton').hide();}
	 var $flowchart = $('#visualbuilder');
	 var data = JSON.parse($('#flowchart_data3').val());
 	 var NewItem = { label: "item",itemID:uniqID};
	 var ThisDataItem = data.operators[ThisItemId];
     ThisDataItem.properties.outputs["output_"+uniqID]=NewItem;
	 jQuery('#flowchart_data3').val(JSON.stringify(data, null, 2));
	 $flowchart.flowchart('setOperatorData',ThisItemId,ThisDataItem);
	 ResetButtonValuesFlowChart(ThisItemId);
     ChangePreviewText('','buttons');
	 }
	 }else{
	 MissingTitle('button_text','Message');
	 }
});


function ButtonPreviewStyling(ThisItemId,NewButtonNum,uniqID){
var Items = Number("1");
$('#button_settings_table > tbody > tr').each(function(i, tr) {
//looping through the table and getting the item id's
var ThisId = $(tr).attr('id');
if(Items===1 && NewButtonNum===1){
jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_top');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_middle');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_bottom');
}

if(Items===1 && NewButtonNum >1){
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons');
jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_top');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_middle');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_bottom');
}

if(Items===2 && NewButtonNum===2){
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_top');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_middle');
jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_bottom');
}
if(Items===2 && NewButtonNum===3){
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_top');
jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_middle');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_bottom');
}
if(Items===3){
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_top');
jQuery('#'+ThisId+'_preview').removeClass('broadcast_preview_buttons_middle');
jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_bottom');
}
Items++;
});
}

function ChangePreviewButtonTitle(uniqid){
var OrgText = jQuery('#'+uniqid+'_button_title').val();
var ThisItemId = jQuery('#edit_msgid').val();
jQuery('#'+uniqid+'_preview').html(OrgText);
jQuery('#'+uniqid+'_title').val(OrgText);

    var ButtonLabel = OrgText.substr(0, 8);
    if (OrgText.length>8) ButtonLabel += '...';
    ButtonLabel = ButtonLabel.replace(/\\/g, '');

jQuery('#'+ThisItemId+'_con_label_output_'+uniqid).html(ButtonLabel);
var data = JSON.parse($('#flowchart_data3').val());
var ThisOp = data.operators[ThisItemId];
    if(typeof ThisOp.properties.outputs["output_"+uniqid] !== "undefined"){
        data.operators[ThisItemId].properties.outputs["output_"+uniqid].label=OrgText;
        jQuery('#flowchart_data3').val(JSON.stringify(data, null, 2));
        ThisOp.properties.outputs["output_"+uniqid].label=OrgText;
        var $flowchart = $('#visualbuilder');
        $flowchart.flowchart('setOperatorData',ThisItemId,ThisOp);
        ChangePreviewText('','buttons');
    }
}

function ResetButtonValuesFlowChart(ThisItemId){
//after adding a new button or deletion the title on the flowchart is overwritten with item in the case the button is not saved yet. This function will correct it again
  $('#button_settings_table > tbody > tr').each(function(i, tr) {
  var ThisId = $(tr).attr('id');
  var OrgText = jQuery('#'+ThisId+'_button_title').val();
      OrgText = OrgText.replace(/\\/g, '');
      jQuery('#'+ThisItemId+'_con_label_output_'+ThisId).html(OrgText);
  });
}

</script>