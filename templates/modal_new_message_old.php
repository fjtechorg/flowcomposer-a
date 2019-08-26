<div id="operator_new_message" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Message</h4>
      </div>
      <div class="modal-body">
	  	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_quick-1"><i class="icon-ellipsis"></i>New Message</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_quick-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_quick-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_quick-4"><i class="fa icon-tags"></i>Tags</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_quick-5"><i class="icon-code"></i>JSON code</a></li>
                                
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_quick-1" class="tab-pane active">
									 <div class="col-lg-6">
        								<input type="hidden" name="tr_order" id="tr_order"/>
            								<div id="new_msgs_canvas">
											<label>Message Name:</label><input type="text" name="new_msg_name" id="new_msg_name" class="form-control input-lg m-b"><br>
 
									 <div id="new_msgs_table">
									 <table id="new_msgs" style="width:100%">
									 
									 </table>
									 </div>
									 
								</div>
								
								
									<div class="draggable_operators_divs row" id="boundbox">
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="simple"><i class="icon-text-format"></i><br/>Text</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="simple_image"><i class="icon-picture"></i><br/>Image</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="simple_audio"><i class="icon-file-audio"></i><br/>Audio</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="simple_video"><i class="icon-file-video"></i><br/>Video</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="simple_file"><i class="icon-file-empty"></i><br/>File</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="buttons"><i class="icon-pointer-up"></i><br/>Buttons</div><br>
                                                    </div>
                                                   <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="products"><i class="icon-cart"></i><br/>Products</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="structured"><i class="icon-map2"></i><br/>Carousel</div><br>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="list"><i class="icon-menu"></i><br/>List</div><br>
                                                    </div>
													<div class="col-lg-3">
                                                        <div class="new_operator ui-draggable ui-draggable-handle" data-msgtype="quick"><i class="icon-ellipsis"></i><br/>Quick reply</div><br>
                                                    </div> 

                                                </div>									
												<br />		
																	

    									 </div>
                        	   		 </div>
							   		 <div class="col-lg-6">
    								   <strong>Preview</strong>
										<div class="boxlayout_big" id="new_msg_preview">
    										
										</div>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_new_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
  
 
function NewsendOrderToInput(){
var tr_id_order = '';
$('#new_msgs tr').each(function (i, row) {
        var $row = $(row);
		var $tr_id = $row.attr('id');
        tr_id_order +=','+$tr_id;
    });
jQuery('#tr_order').val(tr_id_order);
}

function NewaddMsgTypeForm(MsgType,unqid){

}

function NewShowMsgInput(ThisType,uniqid){
if(ThisType==='simple_audio'){var ThisInput ='<i class="icon-file-audio fa-3x"></i><br/><span href="#myModalUploadNew" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="audio" class="OpenUploadModalNew"><input type="button" class="btn btn-primary form-control" value="Upload Audio"></span><input type="hidden" id="'+uniqid+'_audio_url" name="'+uniqid+'_audio_url"/><input type="hidden" name="'+uniqid+'_msg_type" value="audio" />';}
if(ThisType==='buttons'){var ThisInput ='<i class="icon-pointer-up fa-3x"></i><br/><input type="text" class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Enter The Text Above Buttons Here" onkeyup="NewChangePreviewText(\'\',\''+uniqid+'\',\'buttons\');"  maxlength="640"></input><br><div id="'+uniqid+'_button_settings"><table class="table-noborder" id="'+uniqid+'_button_settings_table"></table></div><span data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="buttons" class="btn btn-primary NewAddButton" value="+Add Button">+Add Button</span><input type="hidden" name="'+uniqid+'_msg_type" value="buttons" /><input type="hidden" name="'+uniqid+'_num_buttons" id="'+uniqid+'_num_items" value="0" />';}
if(ThisType==='structured'){var ThisInput ='<i class="icon-map2 fa-3x"></i><br/><input type="hidden" name="'+uniqid+'_msg_type" value="carousel" /><div id="'+uniqid+'_carousel_settings"><table class="table-noborder" id="'+uniqid+'_carousel_settings_table"></table></div><span data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="carousel" class="btn btn-primary NewAddCarouselItem" value="+Add Item">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}
if(ThisType==='simple_file'){var ThisInput ='<i class="icon-file-empty fa-3x"></i><br/><span href="#myModalUploadNew" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="file" class="OpenUploadModalNew"><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" id="'+uniqid+'_file_url" name="'+uniqid+'_file_url"/><input type="hidden" name="'+uniqid+'_msg_type" value="file" />';}
if(ThisType==='simple_image'){var ThisInput ='<i class="icon-picture fa-3x"></i><br/><span href="#myModalImgNew" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="simple_image" class="OpenImgModalNew"><input type="button" class="btn btn-primary form-control" value="Upload Image"></span><input type="hidden" id="'+uniqid+'_img_url" name="'+uniqid+'_img_url"/><input type="hidden" name="'+uniqid+'_msg_type" value="img" />';}
if(ThisType==='list'){var ThisInput ='<i class="icon-menu fa-3x"></i><br/><input type="hidden" name="'+uniqid+'_msg_type" value="list" /><div id="'+uniqid+'_list_settings"><table class="table-noborder" id="'+uniqid+'_list_settings_table"></table></div><span data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="list" class="btn btn-primary NewAddListItem" value="+Add Item">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}
if(ThisType==='products'){var ThisInput ='<i class="icon-cart fa-3x"></i><br/><span href="#myModalUploadNew" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="products" class="OpenUploadModalNew"><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" name="'+uniqid+'_msg_type" value="products" />';}
if(ThisType==='quick'){var ThisInput ='<i class="icon-ellipsis fa-3x"></i><br/><input type="text" class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Enter The Text Above Buttons Here" onkeyup="NewChangePreviewText(\'\',\''+uniqid+'\',\'quick\');"  maxlength="640"></input><br><input type="hidden" name="'+uniqid+'_msg_type" value="quick" /><div id="'+uniqid+'_quick_settings"><table class="table-noborder" id="'+uniqid+'_quick_settings_table"></table></div><span data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="quick" class="btn btn-primary NewAddQuickItem" value="+Add Item">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}
if(ThisType==='simple'){var ThisInput ='<i class="icon-text-format fa-3x"></i><input type="text" class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Enter Your Text Here" onkeyup="NewChangePreviewText(\'\',\''+uniqid+'\',\'simple\');"  maxlength="640" ></input><input type="hidden" name="'+uniqid+'_msg_type" value="text" />';}
if(ThisType==='typing'){var ThisInput ='<i class="icon-bubble-dots fa-3x"></i><br/>Show Typing Action<input type="hidden" name="'+uniqid+'_msg_type" value="typing" />';}
if(ThisType==='simple_video'){var ThisInput ='<i class="icon-file-video fa-3x"></i><br/><span href="#myModalUploadNew" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="video" class="OpenUploadModalNew"><input type="button" class="btn btn-primary form-control" value="Upload Video"></span><input type="hidden" id="'+uniqid+'_video_url" name="'+uniqid+'_video_url"/><input type="hidden" name="'+uniqid+'_msg_type" value="video" />';}
return ThisInput;
}

function NewShowMsgPreview(ThisType,uniqid){
if(ThisType==='simple_audio'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_audio message-right"></div></div><div style="clear:both;"></div>';}
if(ThisType==='buttons'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text message-right"></div><div style="clear:both;"></div><div id="buttons_'+uniqid+'"></div></div><div style="clear:both;"></div>';}
if(ThisType==='structured'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_carousel"><div id="carousel_items_'+uniqid+'" class="preview_slider"><input type="hidden" id="'+uniqid+'_currentSlide" value="0"/><ul id="slides_'+uniqid+'" class="carouselslides"></ul><span class="controls carousel_previous" data-slide_id="'+uniqid+'"><</span><span class="controls carousel_next" data-slide_id="'+uniqid+'">></span></div></div></div><div style="clear:both;"></div>';}
if(ThisType==='simple_image'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_img"></div></div><div style="clear:both;"></div>';}
if(ThisType==='simple_file'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_file"></div></div><div style="clear:both;"></div>';}
if(ThisType==='list'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_list"></div></div><div style="clear:both;"></div>';}
if(ThisType==='products'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_products message-right"></div></div><div style="clear:both;"></div>';}
if(ThisType==='quick'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text message-right"></div><div style="clear:both;"></div><div class="broadcast_preview_quick"></div></div><div style="clear:both;"></div>';}
if(ThisType==='simple'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text message-right"></div></div><div style="clear:both;"></div>';}
if(ThisType==='typing'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_typing message-right">. . .</div></div><div style="clear:both;"></div>';}
if(ThisType==='simple_video'){var ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_video message-right"></div></div><div style="clear:both;"></div>';}
return ThisInput;
}

function NewChangePreviewText(itemID,uniqid,msgType){

  if(msgType==='carousel_title'){
  var OrgText = jQuery('#'+uniqid+'_carousel_title').val();
  jQuery('#'+uniqid+'_preview_title').html(OrgText);
  jQuery('#'+itemID+'_'+uniqid+'_item_title').val(OrgText);
  
  }
  if(msgType==='carousel_subtitle'){
  var OrgText = jQuery('#'+uniqid+'_carousel_subtitle').val();
  jQuery('#'+uniqid+'_preview_subtitle').html(OrgText);
  jQuery('#'+itemID+'_'+uniqid+'_item_subtitle').val(OrgText);
  }
  if(msgType==='quick_title'){
  var OrgText = jQuery('#'+uniqid+'_quick_title').val();
  jQuery('#'+uniqid+'_preview_title').html(OrgText);
  }
  if(msgType==='list_title'){
  var OrgText = jQuery('#'+uniqid+'_list_title').val();
  jQuery('#'+uniqid+'_preview_title').html(OrgText);
  jQuery('#'+itemID+'_'+uniqid+'_item_title').val(OrgText);
  }
  if(msgType==='list_subtitle'){
  var OrgText = jQuery('#'+uniqid+'_list_subtitle').val();
  jQuery('#'+uniqid+'_preview_subtitle').html(OrgText);  
  jQuery('#'+itemID+'_'+uniqid+'_item_subtitle').val(OrgText);
  }
  if(msgType==='buttons' || msgType==='simple' || msgType==='quick'){
  var OrgText = jQuery('#text_'+uniqid).val();
  jQuery('#preview_'+uniqid+' .broadcast_preview_text').html(OrgText);
  }
  
  if(msgType==='buttons'){
    jQuery('#'+uniqid+'_msg_buttontext').val(OrgText);
  }
  
  if(msgType==="simple"){
  jQuery('#'+uniqid+'_msg_text').val(OrgText);
  var ShortTxt =  OrgText.substr(0, 60); 
  jQuery('#'+uniqid+'_msg_content').html(ShortTxt);
  }
}

function NewChangePreviewButtonTitle(uniqid,ItemID){
var OrgText = jQuery('#'+uniqid+'_button_title').val();
jQuery('#'+uniqid+'_preview').html(OrgText);
var listItem = $( "#"+uniqid );
var IndexRow = $("#"+ItemID+"_button_settings_table tr").index(listItem) + 1;
var IndexRowTitle = IndexRow - 1;
jQuery('#'+ItemID+'_button'+IndexRow+'_title').val(OrgText);
//adding a new connector to the flow builder item
	 var data = JSON.parse($('#flowchart_data3').val());
	 var $flowchart = $('#visualbuilder');
	 $flowchart.flowchart({
      data: data
	  });
 	 var NewItem = { label: OrgText,itemID:uniqid};
	 var ThisDataItem = data.operators[ItemID];
     ThisDataItem.properties.outputs["output_"+IndexRowTitle]=NewItem;
	 jQuery('#flowchart_data3').val(JSON.stringify(data, null, 2));
	 $flowchart.flowchart('setOperatorData',ItemID,ThisDataItem);
}

function NewChangePreviewListButtonTitle(){
var ItemID = jQuery('#current_item').val();
var OrgText = jQuery('#list_button_title').val();
jQuery('#'+ItemID+'_preview_button_title').html(OrgText);
}

function NewChangePreviewCarouselButtonTitle(ThisButton){
var ItemID = jQuery('#current_item').val();
var OrgText = jQuery('#carousel_button_title'+ThisButton).val();
jQuery('#'+ItemID+'_preview_button_title'+ThisButton).html(OrgText);
}

$(document).on('click', '.OpenImgModalNew', function (e) {
e.preventDefault();
     var ThisItemId = $(this).data('itemid');
	 var ThisMsgId =$(this).data('msgid');
	 var ThisType = $(this).data('msgtype');
     jQuery(".NewImgModal #item_id").val( ThisItemId );
	 jQuery(".NewImgModal #msg_id").val( ThisMsgId );
	 jQuery(".NewImgModal #msg_type").val(ThisType );
	 
	 jQuery('#previewingnew').attr('src', './images/preview.png');
	 jQuery(".NewImgModal #message").html("");
	 jQuery("#new_image_detail").html("");
	 jQuery("#inputNewImage").val("");
	 jQuery('#myModalImgNew').modal();
});

$(document).on('click', '.OpenUploadModalNew', function () {
     jQuery(".UploadModalNew #inputFileNew").val('');
     var ThisItemId = $(this).data('itemid');
	 var ThisMsgId =jQuery('#edit_msgid').val();
	 var ThisType = $(this).data('msgtype');
     jQuery(".UploadModalNew #item_id").val( ThisItemId );

	 jQuery("#upload_message").html(''); 
	 jQuery(".UploadModalNew #msg_id").val( ThisMsgId );
	 jQuery(".UploadModalNew #msg_type").val(ThisType );
	 jQuery('#myModalUploadNew').modal();
});

$(document).on('click', '.NewAddListItem', function () {

     var ThisItemId = $(this).data('itemid'); 
	 var NumItems = jQuery('#'+ThisItemId+'_num_items').val();
	 if (NumItems===''){NumItems=Number("1");}else{NumItems=Number(NumItems);}
	 
	 if(NumItems<4){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	 $('#'+ThisItemId+'_list_settings_table').append('<tr id="'+uniqID+'"><td width="70%"><input type="text" name="'+uniqID+'_list_title" id="'+uniqID+'_list_title" onkeyup="NewChangePreviewText(\''+ThisItemId+'\',\''+uniqID+'\',\'list_title\');" class="form-control input-lg m-b" placeholder="Enter Title Text...max 80 Characters" size="50" maxlength="80"></input><br /><input type="text" name="'+uniqID+'_list_subtitle" id="'+uniqID+'_list_subtitle" onkeyup="NewChangePreviewText(\''+ThisItemId+'\',\''+uniqID+'\',\'list_subtitle\');" class="form-control input-lg m-b" placeholder="Enter SubText...max 80 Characters" size="50" maxlength="80"></input></td><td valign="middle" width="10%"><div class="input-lg m-b OpenImgModalNew" data-itemid="'+uniqID+'" data-msgid="'+ThisItemId+'" data-msgtype="list" id="'+uniqID+'_list_img_select"><i class="icon-picture fa-lg list_img_item" aria-hidden="true"></i></div><input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url"/></td>	 <td valign="middle" width="10%"><div class="input-lg m-b" id="'+uniqID+'_button_list_msg"><i class="icon-pointer-up fa-lg button_list_msg" aria-hidden="true" data-item_id="'+uniqID+'"></i></div></td> <td valign="middle" width="10%"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
	 $('#preview_'+ThisItemId+' .broadcast_preview_list').append('<div id="'+uniqID+'_preview" class="preview_list"><div id="preview_list_content"><div id="'+uniqID+'_preview_title" style="font-weight: bold;font-size: 12px;padding:0 5px"></div><div id="'+uniqID+'_preview_subtitle" style="color:#90949c;font-size: 12px;padding:0 5px"></div><div id="'+uniqID+'_preview_url" style="color:#90949c;font-size: 12px;margin-top:10px;padding:0 5px;height:15px;overflow:hidden"></div><span id="'+uniqID+'_preview_button" class="preview_list_button"></span></div><div id="'+uniqID+'_preview_list_image"><img id="'+uniqID+'_preview_image" class="preview_list_image" src="./images/preview.png"></div><div class="preview_list_footer"></div>	</div>');
	 $('#form').append('<input name="'+ThisItemId+'_items['+uniqID+'][item_url]" id="'+ThisItemId+'_'+uniqID+'_item_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_name]" id="'+ThisItemId+'_'+uniqID+'_item_name" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_subtitle]" id="'+ThisItemId+'_'+uniqID+'_item_subtitle" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_title]" id="'+ThisItemId+'_'+uniqID+'_item_title" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_image_url]" id="'+ThisItemId+'_'+uniqID+'_item_image_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_image]" id="'+ThisItemId+'_'+uniqID+'_item_image" value="" type="hidden"><input name="'+ThisItemId+'_item_id[]" value="'+uniqID+'" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_button1_phone]" id="'+ThisItemId+'_qe1496516223274_item_button1_phone" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_button1_msg]" id="'+ThisItemId+'_qe1496516223274_item_button1_msg" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_button1_url]" id="'+ThisItemId+'_qe1496516223274_item_button1_url" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_button1_type]" id="'+ThisItemId+'_qe1496516223274_item_button1_type" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_button1_title]" id="'+ThisItemId+'_qe1496516223274_item_button1_title" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_url]" id="'+ThisItemId+'_qe1496516223274_item_url" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_name]" id="'+ThisItemId+'_qe1496516223274_item_name" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_subtitle]" id="'+ThisItemId+'_qe1496516223274_item_subtitle" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_title]" id="'+ThisItemId+'_qe1496516223274_item_title" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_image_url]" id="'+ThisItemId+'_qe1496516223274_item_image_url" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_image]" id="'+ThisItemId+'_qe1496516223274_item_image" value="" type="hidden"><input name="'+ThisItemId+'_items[qe1496516223274][item_buttons_num]" id="'+ThisItemId+'_qe1496516223274_item_buttons_num" value="" type="hidden">');
	 
	 var NewNum = Number("1");
	 NewItemsNum = NumItems+NewNum;
	 jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);
	 
	 }
});

$(document).on('click', '.NewAddQuickItem', function () {

     var ThisItemId = $(this).data('itemid'); 
	 var NumItems = jQuery('#'+ThisItemId+'_num_items').val();
	 if (NumItems===''){NumItems=Number("1");}else{NumItems=Number(NumItems);}
	 
	 if(NumItems<11){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	 $('#'+ThisItemId+'_quick_settings_table').append('<tr id="'+uniqID+'"><td width="60%"><input type="hidden" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> <input type="text" name="'+uniqID+'_quick_title" id="'+uniqID+'_quick_title" onkeyup="NewChangePreviewText(\''+ThisItemId+'\',\''+uniqID+'\',\'quick_title\');" class="form-control input-lg m-b" placeholder="Enter Button Text...max 20 Characters" size="30"  maxlength="20"></input><input type="hidden" name="'+uniqID+'_quick_msg" id="'+uniqID+'_button_msg" /></input><input type="hidden" name="'+uniqID+'_button_type" id="'+uniqID+'_button_type"/></td><td valign="middle" width="10%"><div class="input-lg m-b OpenImgModal" data-itemid="'+uniqID+'"  data-msgid="'+ThisItemId+'" data-msgtype="quick" id="'+uniqID+'_quick_img_select"><i class="icon-picture fa-lg quick_img_item" aria-hidden="true"></i></div><input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url"/></td>	 <td valign="middle" width="10%"><div class="input-lg m-b" id="'+uniqID+'_quick_msg_select"><i class="icon-pointer-up fa-lg new_button_msg_item" aria-hidden="true" data-item_id="'+uniqID+'"></i></div></td> <td valign="middle" width="10%"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
	 $('#preview_'+ThisItemId+' .broadcast_preview_quick').append('<div id="'+uniqID+'_preview" class="preview_quick_item"><div id="preview_quick_content"><div id="'+uniqID+'_preview_quick_image" ></div><div id="'+uniqID+'_preview_title" class="preview_quick_title"></div></div>');
	 var NewNum = Number("1");
	 NewItemsNum = NumItems+NewNum;
	 jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);
	 
	 }
});

$(document).on('click', '.NewAddCarouselItem', function () {

     var ThisItemId = $(this).data('itemid'); 
	 var NumItems = jQuery('#'+ThisItemId+'_num_items').val();
	 if (NumItems===''){NumItems=Number("1");}else{NumItems=Number(NumItems);}
	 
	 if(NumItems<10){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	 $('#'+ThisItemId+'_carousel_settings_table').append('<tr id="'+uniqID+'"><td width="70%"> <input type="text" name="'+uniqID+'_carousel_title" id="'+uniqID+'_carousel_title" onkeyup="NewChangePreviewText(\''+ThisItemId+'\',\''+uniqID+'\',\'carousel_title\');" class="form-control input-lg m-b" placeholder="Enter Title Text...max 80 Characters" size="50" maxlength="80"></input><br /><input type="text" name="'+uniqID+'_carousel_subtitle" id="'+uniqID+'_carousel_subtitle" onkeyup="NewChangePreviewText(\''+ThisItemId+'\',\''+uniqID+'\',\'carousel_subtitle\');" class="form-control input-lg m-b" placeholder="Enter SubText...max 80 Characters" size="50"  maxlength="80"></input>	</td><td valign="middle" width="10%"><div class="input-lg m-b OpenImgModalNew" data-itemid="'+uniqID+'" data-msgid="'+ThisItemId+'" data-msgtype="carousel" id="'+uniqID+'_carousel_img_select"><i class="icon-picture" aria-hidden="true" ></i></div><input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url"/></td>	 <td valign="middle" width="10%"><div class="input-lg m-b" id="'+uniqID+'_button_msg_select"><i class="icon-pointer-up button_carousel_msg" aria-hidden="true" data-item_id="'+uniqID+'"></i></div></td> <td valign="middle" width="10%"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
	 $('#carousel_items_'+ThisItemId+' ul').append('<li id="slide_'+uniqID+'" class="carouselslide"><span id="'+uniqID+'_preview" class="broadcast_preview_carousel_item"><span id="'+uniqID+'_preview_item"><span id="'+uniqID+'_preview_image" style="height:150px;text-align:center;overflow:hidden;"></span><span id="'+uniqID+'_preview_title" style="font-weight: bold;font-size: 12px;padding:0 5px"></span><br /><span id="'+uniqID+'_preview_subtitle" style="color:#90949c;font-size: 12px;padding:0 5px"></span><br /><span id="'+uniqID+'_preview_url" style="color:#90949c;font-size: 12px;margin-top:10px;padding:0 5px"></span><br /><span id="'+uniqID+'_preview_buttons"><span id="'+uniqID+'_preview_button1" class="preview_buttons"></span><span id="'+uniqID+'_preview_button2" class="preview_buttons"></span><span id="'+uniqID+'_preview_button3" class="preview_buttons"></span></span></span></div></li>');
	 $('#form').append('<input name="'+ThisItemId+'_item_id[]" id="'+ThisItemId+'_'+uniqID+'_item_id" value="'+uniqID+'" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button3_msg]" id="'+ThisItemId+'_'+uniqID+'_item_button3_msg" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button3_url]" id="'+ThisItemId+'_'+uniqID+'_item_button3_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button3_type]" id="'+ThisItemId+'_'+uniqID+'_item_button3_type" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button3_title]" id="'+ThisItemId+'_'+uniqID+'_item_button3_title" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button2_msg]" id="'+ThisItemId+'_'+uniqID+'_item_button2_msg" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button2_url]" id="'+ThisItemId+'_'+uniqID+'_item_button2_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button2_type]" id="'+ThisItemId+'_'+uniqID+'_item_button2_type" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button2_title]" id="'+ThisItemId+'_'+uniqID+'_item_button2_title" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button1_msg]" id="'+ThisItemId+'_'+uniqID+'_item_button1_msg" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button1_url]" id="'+ThisItemId+'_'+uniqID+'_item_button1_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button1_type]" id="'+ThisItemId+'_'+uniqID+'_item_button1_type" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_button1_title]" id="'+ThisItemId+'_'+uniqID+'_item_button1_title" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_url]" id="'+ThisItemId+'_'+uniqID+'_item_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_name]" id="'+ThisItemId+'_'+uniqID+'_item_name" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_subtitle]" id="'+ThisItemId+'_'+uniqID+'_item_subtitle" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_title]" id="'+ThisItemId+'_'+uniqID+'_item_title" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_image_url]" id="'+ThisItemId+'_'+uniqID+'_item_image_url" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_image]" id="'+ThisItemId+'_'+uniqID+'_item_image" value="" type="hidden"><input name="'+ThisItemId+'_items['+uniqID+'][item_buttons_num]" id="'+ThisItemId+'_'+uniqID+'_item_buttons_num" value="" type="hidden">');
	
	 //is there any li item that is showing already? 
	 if(NumItems===0){
	 	jQuery('#slide_'+uniqID).addClass('carouselshowing');
	 }
	 var NewNum = Number("1");
	 NewItemsNum = NumItems+NewNum;
	 jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);
	 }
});

$(document).on('click', '.NewAddButton', function () {

     var ThisItemId = $(this).data('itemid'); 
	 var NumButtons = jQuery('#'+ThisItemId+'_num_items').val();
	 if (NumButtons===''){NumButtons=Number("1");}else{NumButtons=Number(NumButtons);}
	 
	 if(NumButtons<3){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	 $('#'+ThisItemId+'_button_settings_table').append('<tr id="'+uniqID+'"><td ><input type="hidden" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> <input type="text" name="'+uniqID+'_button_title" id="'+uniqID+'_button_title" onkeyup="NewChangePreviewButtonTitle(\''+uniqID+'\',\''+ThisItemId+'\');" class="form-control input-lg m-b" placeholder="Enter Button Text...max 20 Characters" size="30" maxlength="20"></input>	 <input type="hidden" name="'+uniqID+'_button_msg" id="'+uniqID+'_button_msg" /></input><input type="hidden" name="'+uniqID+'_button_phone" id="'+uniqID+'_button_phone" /></input> <input type="hidden" name="'+uniqID+'_button_url" id="'+uniqID+'_button_url" /><input type="hidden" name="'+uniqID+'_button_type" id="'+uniqID+'_button_type"/></td><td valign="middle"><div class="input-lg m-b" id="'+uniqID+'_button_link_select"><i class="icon-link2 new_button_link_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td>	 <td valign="middle"><div class="input-lg m-b" id="'+uniqID+'_button_msg_select"><i class="icon-bubbles new_button_msg_item" aria-hidden="true" data-item_id="'+uniqID+'"></i></div></td><td valign="middle"><div class="input-lg m-b" id="'+uniqID+'_new_button_phone_item"><i class="icon-telephone new_button_phone_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td><td valign="middle"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
	 $('#buttons_'+ThisItemId).append('<div id="'+uniqID+'_preview" class="broadcast_preview_buttons"></div>');
	 var NewNum = Number("1");
	 NewButtonNum = NumButtons+NewNum;
	 jQuery('#'+ThisItemId+'_num_items').val(NewButtonNum);
	 jQuery('#'+ThisItemId+'_num_buttons').val(NewButtonNum);	 
	 }
});

$(document).on('click', '.new_button_share_item', function (e) {
			jQuery('#current_button').val('');
			var ItemId = $(this).data('itemid');
			jQuery('#current_msg_id').val(ItemId);
			var trid = $(this).closest('tr').attr('id');if(trid==null){trid = jQuery('#current_item').val();}
			var CurrentButton = $(this).data('button'); if(CurrentButton >0){jQuery('#current_button').val(CurrentButton);}
			jQuery('#current_item').val(trid);
			jQuery('#modal_button_share_new').modal();
});

$(document).on('click', '.new_button_phone_item', function (e) {
			jQuery('#current_button').val('');
			var ItemId = $(this).data('itemid');
			jQuery('#current_msg_id').val(ItemId);
			
			var trid = $(this).closest('tr').attr('id');if(trid==null){trid = jQuery('#current_item').val();}
			var CurrentButton = $(this).data('button'); if(CurrentButton >0){jQuery('#current_button').val(CurrentButton);}else{
			var $tr = $(this).closest('tr');
    		var myRow = $("#"+ItemId+"_button_settings_table tr").index($tr) + 1;
			jQuery('#current_button').val(myRow);
			}
			var button_phone = $('#'+trid+'_button_phone').val();	
			jQuery('#button_phone').val(button_phone);
			jQuery('#current_item').val(trid);
			jQuery('#modal_button_phone_new').modal();
});

$(document).on('click', '.new_button_link_item', function (e) {
			jQuery('#current_button').val('');
			jQuery('#new_button_link').val('');			
			
			var ItemId = $(this).data('itemid');
			jQuery('#current_msg_id').val(ItemId);
			
			var trid = $(this).data('trid');if(trid==null){trid = jQuery('#current_item').val();}
			var button_link = $('#'+trid+'_button_url').val();	
			if(button_link!==''){jQuery('#button_link').val(button_link);}else{jQuery('#button_link').val('');}
			jQuery('#current_item').val(trid);
			jQuery('#modal_button_link_new').modal();
			var $tr = $(this).closest('tr');
    		var myRow = $("#"+ItemId+"_button_settings_table tr").index($tr) + 1;
			jQuery('#current_button').val(myRow);
});

$(document).on('click', '.new_button_msg_item', function (e) {
			jQuery('#current_button').val('');
			var ItemId = $(this).data('itemid');
			jQuery('#current_msg_id').val(ItemId);
			
			var MenuID = $(this).closest('tr').attr('id');if(MenuID==null){MenuID = jQuery('#current_item').val();}
			var CurrentButton = $(this).data('button'); if(CurrentButton >0){jQuery('#current_button').val(CurrentButton);}else{
			var $tr = $(this).closest('tr');
    		var myRow = $("#"+ItemId+"_button_settings_table tr").index($tr) + 1;
			jQuery('#current_button').val(myRow);
			}
			jQuery('#current_item').val(MenuID);
			jQuery('#modal_button_msg_new').modal();
			
            var ajax_url='includes/admin-ajax.php';		
			var data = {'action': 'menu_item_msg',
			'user_id': '<?php echo $_SESSION['user_id'];?>',
			'page_id': '<?php if(isset($_SESSION['page_id'])){echo $_SESSION['page_id'];}?>',
			'bot_id': '',
			'item_id': MenuID
			}
jQuery.post(ajax_url, data, function(response) {
					  var response_arr = response.split("|", 3);	
    				  jQuery("#menu_item_msg_result").html(response_arr[0]);		
						  });	
			
			
});		



$(document).on('click', '.new_save_button_msg', function (e) {
			var ItemID = jQuery('#current_msg_id').val();
			var ButtonMsg = jQuery('#button_msg_select').val();
			var CurrentButton = jQuery('#current_button').val();
			if(CurrentButton>0){
			jQuery('#'+ItemID+'_button_msg_'+CurrentButton).val(ButtonMsg);
			jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('postback');
			}else{
			jQuery('#'+ItemID+'_button_msg').val(ButtonMsg);
			jQuery('#'+ItemID+'_button_type').val('postback');
			}
			jQuery('#menu_item_msg_result').html('');
});	

$(document).on('click', '.save_new_button_link', function (e) {
			var ItemID = jQuery('#current_msg_id').val();
			var ButtonLink = jQuery('#new_button_link').val();
			var CurrentButton = jQuery('#current_button').val();
			
			if(CurrentButton>0){
			jQuery('#'+ItemID+'_button_url_'+CurrentButton).val(ButtonLink);
			jQuery('#'+ItemID+'_button'+CurrentButton+'_url').val(ButtonLink);
			jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('web_url');
			jQuery('#'+ItemID+'_button'+CurrentButton+'_type').val(CurrentButton+'buttons'+CurrentButton+'url');
			
			}else{
			jQuery('#'+ItemID+'_button_url').val(ButtonLink);
			jQuery('#'+ItemID+'_button_type').val('web_url');
			}
			jQuery('#button_link').val('');
});		

$(document).on('click', '.save_new_button_phone', function (e) {
			var ItemID = jQuery('#current_msg_id').val();
			var CurrentButton = jQuery('#current_button').val();
			var ButtonPhone = jQuery('#button_phone').val();
			if(CurrentButton>0){
			jQuery('#'+ItemID+'_button_phone_'+CurrentButton).val(ButtonPhone);
			jQuery('#'+ItemID+'_button'+CurrentButton+'_phone').val(ButtonPhone);
			jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('phone');			
			jQuery('#'+ItemID+'_button'+CurrentButton+'_type').val('phone');
			}else{
			jQuery('#'+ItemID+'_button_phone').val(ButtonPhone);
			jQuery('#'+ItemID+'_button_type').val('phone');
			}
			jQuery('#button_phone').html('');
});
   
$(document).on('click', '.save_new_button_share', function (e) {
			var ItemID = jQuery('#current_msg_id').val();
			var CurrentButton = jQuery('#current_button').val();
			if(CurrentButton>0){
			jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('share');
			jQuery('#'+ItemID+'_button'+CurrentButton+'_type').val('share');
			}else{
			jQuery('#'+ItemID+'_button_type').val('share');
			}
});   
	  
$(document).on('click', '.save_new_list_button', function (e) {
			var ItemId = jQuery('#current_item').val();
			var buttonTitle = jQuery('#list_button_title').val();
			jQuery('#'+ItemId+'_button_title').val(buttonTitle);		
		
			jQuery('#list_button_title').val('');			
			jQuery('#list_button_url').val('');		
			jQuery('#list_button_msg').val('');		
			jQuery('#list_button_type').val('');
			
			 
});

$(document).on('click', '.save_new_carousel_button', function (e) {
			var ItemId = jQuery('#current_item').val();
			var buttonTitle1 = jQuery('#carousel_button_title1').val();
			var buttonTitle2 = jQuery('#carousel_button_title2').val();
			var buttonTitle3 = jQuery('#carousel_button_title3').val();						
			jQuery('#'+ItemId+'_button_title_1').val(buttonTitle1);		
			jQuery('#'+ItemId+'_button_title_2').val(buttonTitle2);	
			jQuery('#'+ItemId+'_button_title_3').val(buttonTitle3);	

			jQuery('#carousel_button_title1').val('');
			jQuery('#carousel_button_title2').val('');								
			jQuery('#carousel_button_title3').val('');			
			 
});

</script>