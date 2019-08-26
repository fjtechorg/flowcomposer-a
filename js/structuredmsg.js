jQuery(document).ready(function($){	
			$('#modal_structured_content table').sortable({
									  containment: '#smartmessenger_filter_modules',		
									  cursor: 'crosshair',
									  forcePlaceHolderSize: true,
									  handle: '.smartmessenger-move',
									  items: 'tr',		axis: 'y'	});	
			
			$('#modal_structured_content').on('click','.smartmessenger-delete',function(){
			
			var trid = $(this).closest('tr').attr('id');
			var prevli = trid+'preview_li';
			var itemID = $('#edit_msgid').val();
			
			$(this).closest("tr").remove();	
			$('#'+prevli).remove();
			
			$('input[name^="'+trid+'"]').each(function() {
					$(this).remove();
			});
			
			$('input[name^="'+itemID+'_item_id"]').each(function() {
				var ThisVal = $(this).val();
				if (ThisVal === trid) {
					$(this).remove();
				}
			});
			
			$('#modal_structured_content table').sortable('refresh');		
			});	
			
			//var empty = $('#smartmessenger_filter_modules tr:last').clone();
			var empty2 = $('#banner-fade li:last').clone();
			var empty3 = $('#banner-fade2 li:last').clone();
			
			$('#modal_structured_content').on('click', '.smartmessenger-add',function(){	
			var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
			var uniqid = randLetter + Date.now();
			var itemID = jQuery('#edit_msgid').val();
			
			$('.preview_slider ul').append('<li id="'+uniqid+'preview_li" style="display:none;"><div id="'+uniqid+'preview_item"><div id="'+uniqid+'preview_image" style="background-image: url(\'\');background-position:50% 50%;padding-bottom:52%;background-size: cover;"></div><div id="'+uniqid+'preview_title" style="font-weight: bold;font-size: 12px;padding:0 5px"></div><div id="'+uniqid+'preview_subtitle" style="color:#90949c;font-size: 12px;padding:0 5px"></div><div id="'+uniqid+'preview_url" style="color:#90949c;font-size: 12px;margin-top:10px;padding:0 5px"></div><div id="'+uniqid+'preview_buttons"><span id="'+uniqid+'preview_button1" class="preview_buttons"></span><span id="'+uniqid+'preview_button2" class="preview_buttons"></span><span id="'+uniqid+'preview_button3" class="preview_buttons"></span></div></div></li>');	
			
			$('#smartmessenger_filter_modules table').append('<tr id="'+uniqid+'"><td class="smartmessenger_label"><div class="image_box" id="'+uniqid+'item_edit_image"><span  data-toggle="modal" href="#myModalImg"  data-itemid="'+uniqid+'" data-msgid="'+itemID+'" class="OpenImgModal"><img src="./images/noimage.png" id="'+uniqid+'item_image" class="item_image" style="top:0px;right:0px;height:85px;" border="0"/></span><input type="hidden" id="'+uniqid+'_item_image_url" value=""  name="'+uniqid+'item_image_url"/></div><div class="item_content"><span style="float:left;width:65px">Name:</span><input type="text" name="'+uniqid+'item_name" id="'+uniqid+'item_name"  size="25" placeholder="Item Name, Your refference only"/><br><span style="float:left;width:65px">Title:</span><input type="text" name="'+uniqid+'item_title" id="'+uniqid+'_item_title"  size="25" placeholder="Title, max 20 characters"  onchange="PrevThis(\'title\',this.id);"/><br><span style="float:left;width:65px">Subtitle:</span><input type="text" name="'+uniqid+'item_subtitle" id="'+uniqid+'_item_subtitle"   size="25"  placeholder="Text below Title"  onchange="PrevThis(\'subtitle\',this.id);"/><br><span style="float:left;width:65px">Item url:</span><input type="text" name="'+uniqid+'item_url"  id="'+uniqid+'_item_url" size="25" value="" placeholder="Link when clicked the image"  onchange="PrevThis(\'url\',this.id);"/><br></div></td><td class="smartmessenger_buttons"><div id="'+uniqid+'_item_button1"><a class="_5ssp"><div id="'+uniqid+'_item_button1_title_txt">Button 1</div></a></div><br><div id="'+uniqid+'_item_button2"><a class="_5ssp"><div id="'+uniqid+'_item_button2_title_txt">Button 2</div></a></div><br><div id="'+uniqid+'_item_button3"><a class="_5ssp"><div id="'+uniqid+'_item_button3_title_txt">Button 3</div></a></div><br><span class="button_settings_click" id="'+uniqid+'_item_edit_buttons"><a data-toggle="modal" href="#myModalBtn" data-itemid="'+uniqid+'" data-msgid="'+itemID+'"  class="OpenBtnModal"><img src="./images/button_settings.png" border="0"/></a>Click to edit buttons</span></td><td><div class="smartmessenger-move">Move</div><br/><div class="smartmessenger-delete">Delete</div><br/><div class="smartmessenger-add">Add</div></td></tr>');		
						
			
			jQuery('<input type="hidden" name="'+itemID+'_item_id[]" value="'+uniqid+'"/>').prependTo('#form');

			CarouselAddItem(itemID,uniqid,'item_buttons_num');
			CarouselAddItem(itemID,uniqid,'item_image');
			CarouselAddItem(itemID,uniqid,'item_image_url');
			CarouselAddItem(itemID,uniqid,'item_title');
			CarouselAddItem(itemID,uniqid,'item_subtitle');
			CarouselAddItem(itemID,uniqid,'item_name');
			CarouselAddItem(itemID,uniqid,'item_url');
							
			CarouselAddItemButtons(itemID,uniqid);
						
			$('#modal_structured_content table').sortable({
									  containment: '#smartmessenger_filter_modules',		
									  cursor: 'crosshair',
									  forcePlaceHolderSize: true,
									  handle: '.smartmessenger-move',
									  items: 'tr',		axis: 'y'	});		

			$("#item_settings_preview").stick_in_parent();
			
 $('#modal_structured_content .preview_slider').bjqs({
            height      : 320,
            width       : 170,
            responsive  : true
          });
		  			
			});

			$("#item_settings_preview").stick_in_parent();
			
			});
			

jQuery(document).ready(function($){	
			$('#product_message_result table').sortable({
									  containment: '#smartmessenger_filter_modules',		
									  cursor: 'crosshair',
									  forcePlaceHolderSize: true,
									  handle: '.smartmessenger-move',
									  items: 'tr',		axis: 'y'	});	
			
			$('#product_message_result').on('click','.smartmessenger-delete',function(){
			
			$('#product_message_result  table').sortable({
									  containment: '#smartmessenger_filter_modules',		
									  cursor: 'crosshair',
									  forcePlaceHolderSize: true,
									  handle: '.smartmessenger-move',
									  items: 'tr',		axis: 'y'	});	
			
			var trid = $(this).closest('tr').attr('id');
			var prevli = trid+'preview_li';
			var itemID = jQuery('#edit_msgid').val();
			var ThisShopifyItem = $('#'+trid).data('id');
			if(ThisShopifyItem !==''){
				 $('#'+ThisShopifyItem).data("added","");
				 $('#'+ThisShopifyItem).addClass('btn-default');
	 			 $('#'+ThisShopifyItem).removeClass('btn-primary');
	 			 $('#'+ThisShopifyItem).html('Add To Message');
			}
			$(this).closest("tr").remove();	
			$('#'+prevli).remove();
			
			$('input[name^="'+trid+'"]').each(function() {
					$(this).remove();
			});
			
			$('input[name^="'+itemID+'_'+trid+'"]').each(function() {
					$(this).remove();
			});
			
			$('input[name^="'+itemID+'_item_id"]').each(function() {
				var ThisVal = $(this).val();
				if (ThisVal === trid) {
					$(this).remove();
				}
			});
			
			$('#product_message_result table').sortable('refresh');		
			});	
			
			//var empty = $('#product_message_result tr:last').clone();
			var empty2 = $('#banner-fade li:last').clone();
			var empty3 = $('#banner-fade2 li:last').clone();
			
			$('#product_message_result').on('click', '.add_shopify_item',function(){	
			var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
			var uniqid = randLetter + Date.now();
			var itemID = jQuery('#edit_msgid').val();
			
			$('.preview_slider ul').append('<li id="'+uniqid+'preview_li" style="display:none;"><div id="'+uniqid+'preview_item"><div id="'+uniqid+'preview_image" style="background-image: url(\'\');background-position:50% 50%;padding-bottom:52%;background-size: cover;"></div><div id="'+uniqid+'preview_title" style="font-weight: bold;font-size: 12px;padding:0 5px"></div><div id="'+uniqid+'preview_subtitle" style="color:#90949c;font-size: 12px;padding:0 5px"></div><div id="'+uniqid+'preview_url" style="color:#90949c;font-size: 12px;margin-top:10px;padding:0 5px"></div><div id="'+uniqid+'preview_buttons"><span id="'+uniqid+'preview_button1" class="preview_buttons"></span><span id="'+uniqid+'preview_button2" class="preview_buttons"></span><span id="'+uniqid+'preview_button3" class="preview_buttons"></span></div></div></li>');	
			
			$('#product_message_result table').append('<tr id="'+uniqid+'"><td class="smartmessenger_label"><div class="image_box" id="'+uniqid+'item_edit_image"><span  data-toggle="modal" href="#myModalImg"  data-itemid="'+uniqid+'" data-msgid="'+itemID+'" class="OpenImgModal"><img src="./images/noimage.png" id="'+uniqid+'item_image" class="item_image" style="top:0px;right:0px;height:85px;" border="0"/></span><input type="hidden" id="'+uniqid+'item_image_url" value=""  name="'+uniqid+'item_image_url"/></div><div class="item_content"><span style="float:left;width:65px">Title:</span><input type="text" name="'+uniqid+'item_title" id="'+uniqid+'_item_title"  size="25" placeholder="Title, max 20 characters"  onchange="PrevThis(\'title\',this.id);"/><br><span style="float:left;width:65px">Subtitle:</span><input type="text" name="'+uniqid+'item_subtitle" id="'+uniqid+'_item_subtitle"   size="25"  placeholder="Text below Title"  onchange="PrevThis(\'subtitle\',this.id);"/><br><span style="float:left;width:65px">Item url:</span><input type="text" name="'+uniqid+'item_url"  id="'+uniqid+'_item_url" size="25" value="" placeholder="Link when clicked the image"  onchange="PrevThis(\'url\',this.id);"/><br></div></td><td class="smartmessenger_buttons"><div id="'+uniqid+'_item_button1"><a class="_5ssp"><div id="'+uniqid+'_item_button1_title_txt">Button 1</div></a></div><br><div id="'+uniqid+'_item_button2"><a class="_5ssp"><div id="'+uniqid+'_item_button2_title_txt">Button 2</div></a></div><br><div id="'+uniqid+'_item_button3"><a class="_5ssp"><div id="'+uniqid+'_item_button3_title_txt">Button 3</div></a></div><br><span class="button_settings_click" id="'+uniqid+'_item_edit_buttons"><a data-toggle="modal" href="#myModalBtn" data-itemid="'+uniqid+'" data-msgid="'+itemID+'"  class="OpenBtnModal"><img src="./images/button_settings.png" border="0"/></a>Click to edit buttons</span></td><td><div class="smartmessenger-move">Move</div><br/><div class="smartmessenger-delete">Delete</div><br/><div class="add_shopify_item smartmessenger-add">Add</div></td></tr>');		
						
			
			jQuery('<input type="hidden" name="'+itemID+'_item_id[]" value="'+uniqid+'"/>').prependTo('#form');

			CarouselAddItem(itemID,uniqid,'item_buttons_num');
			CarouselAddItem(itemID,uniqid,'item_image');
			CarouselAddItem(itemID,uniqid,'item_image_url');
			CarouselAddItem(itemID,uniqid,'item_title');
			CarouselAddItem(itemID,uniqid,'item_subtitle');
			CarouselAddItem(itemID,uniqid,'item_name');
			CarouselAddItem(itemID,uniqid,'item_url');
							
			CarouselAddItemButtons(itemID,uniqid);
						
			$('#product_message_result table').sortable('refresh');		

			$("#item_settings_preview").stick_in_parent();
			
 			$('#product_message_result .preview_slider').bjqs({
            height      : 320,
            width       : 170,
            responsive  : false
          });
		  			
			});

			$("#item_settings_preview").stick_in_parent();
			
			});
			


function CarouselAddItem(itemID,uniqid,itemName){
//document.getElementById(itemName).id = uniqid+itemName;
//jQuery('#'+uniqid+itemName).attr('name', uniqid+itemName);	
//jQuery('#'+itemName).attr('placeholder', '');
jQuery('<input type="hidden" name="'+itemID+'_items['+uniqid+']['+itemName+']" id="'+itemID+'_'+uniqid+'_'+itemName+'" value=""/>').prependTo('#form');		
}	

function CarouselAddItemButtons(itemID,uniqid){
  for(var i = 1; i < 4; i++){
  CarouselAddItem(itemID,uniqid,'item_button'+i+'_title');
  CarouselAddItem(itemID,uniqid,'item_button'+i+'_type');
  CarouselAddItem(itemID,uniqid,'item_button'+i+'_url');
  CarouselAddItem(itemID,uniqid,'item_button'+i+'_phone');
  CarouselAddItem(itemID,uniqid,'item_button'+i+'_msg');
  }
}		
			
function clearSelectedButtons(){
    var elements = document.getElementById("select_trigger_buttonsm").options;
 
    for(var i = 0; i < elements.length; i++){
      if(elements[i].selected){
        elements[i].selected = false;
	  }
    }
  }			