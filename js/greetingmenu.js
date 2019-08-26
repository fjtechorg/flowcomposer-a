$(document).on('click', '.save_greeting', function (e) {
jQuery('#greeting_result').html('');
jQuery('#greeting_result').html('Submitting....');
var ajax_url='includes/admin-ajax.php';
var greeting_text = jQuery('#greeting_text').val();	
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();	
                	 var data = {
                		'action': 'greeting_text',
						'user_id': user_id,
						'page_id': page_id,
						'greeting_text': greeting_text};	
                          jQuery.post(ajax_url, data, function(response) {

//do something with response here
jQuery('#greeting_result').html(response);
});
});


var MenuItems = jQuery('#num_menu_items').val(); if (MenuItems===''){MenuItems=Number("1");}

$(document).on('click', '.add_menu_item', function (e) {
e.preventDefault();
MenuItems = Number(jQuery('#num_menu_items').val()); if (MenuItems===''){MenuItems=Number("1");}
if(MenuItems<3){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	  
     $('#sticky_menu table').append('<tr id="'+uniqID+'"><td><input type="hidden" name="menu_items[]" value="'+uniqID+'" />	 <input type="text" name="'+uniqID+'_menu_title" id="'+uniqID+'_menu_title" class="form-control input-lg m-b" placeholder="Enter Menu Text...max 30 Characters" size="30"  maxlength="30"></input>	 <input type="hidden" name="'+uniqID+'_menu_msg" id="'+uniqID+'_menu_msg" /></input>	 <input type="hidden" name="'+uniqID+'_menu_url" id="'+uniqID+'_menu_url" /><input type="hidden" name="'+uniqID+'_menu_type" id="'+uniqID+'_menu_type"/><input type="hidden" name="'+uniqID+'_num_submenu_items" id="'+uniqID+'_num_submenu_items"/></td><td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_link_select"><i class="fa icon-link2 fa-lg menu_link_item" aria-hidden="true"></i></div></td>	 <td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_msg_select"><i class="fa icon-bubble fa-lg menu_msg_item" aria-hidden="true"></i></div></td><td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_submenu_select"><i class="fa icon-indent-increase fa-lg menu_submenu_item" aria-hidden="true"></i></div></td> <td valign="middle"><div class="input-lg m-b"><i class="fa icon-cross fa-lg delete_menu_item" aria-hidden="true"></i></div></td></tr>');
	 var NewNum = Number("1");
	 NewMenuNum = MenuItems+NewNum;
	 jQuery('#num_menu_items').val(NewMenuNum);
	 if(NewMenuNum==3){jQuery('.add_menu_item').hide();}
	 }
});

$(document).on('click', '.add_submenu_item', function (e) {
e.preventDefault();
var MenuID = jQuery('#current_menu_item').val();
var MenuItems = Number(jQuery('#'+MenuID+'_num_submenu_items').val()); if (MenuItems===''){MenuItems=Number("1");}
if(MenuItems===0){
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').addClass('sticky_menu_item_selected');	
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');}
if(MenuItems<5){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	  
     $('#sticky_submenu_table').append('<tr id="'+uniqID+'"><td><input type="hidden" id="'+MenuID+'_submenu_items[]" name="'+MenuID+'_submenu_items[]" value="'+uniqID+'" />	 <input type="text" name="'+uniqID+'_menu_title" id="'+uniqID+'_menu_title" class="form-control input-lg m-b" placeholder="Enter Menu Text...max 30 Characters" size="30"  maxlength="30"></input>	 <input type="hidden" name="'+uniqID+'_menu_msg" id="'+uniqID+'_menu_msg" /></input>	 <input type="hidden" name="'+uniqID+'_menu_url" id="'+uniqID+'_menu_url" /><input type="hidden" name="'+uniqID+'_menu_type" id="'+uniqID+'_menu_type"/></td><td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_link_select"><i class="fa icon-link2 fa-lg submenu_link_item" aria-hidden="true"></i></div></td>	 <td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_msg_select"><i class="fa fa-comment-o fa-lg submenu_msg_item" aria-hidden="true"></i></div></td><td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_submenu_select"><i class="fa icon-indent-increase fa-lg submenu_submenu_item" aria-hidden="true"></i></div></td><td valign="middle"><div class="input-lg m-b"><i class="fa icon-cross fa-lg delete_submenu_item" aria-hidden="true"></i></div></td></tr>');
	 var NewNum = Number("1");
	 NewMenuNum = MenuItems+NewNum;
	 jQuery('#'+MenuID+'_num_submenu_items').val(NewMenuNum);
	 if(NewMenuNum==5){jQuery('.add_submenu_item').hide();}
	 }
});

$(document).on('click', '.add_subsubmenu_item', function (e) {
e.preventDefault();
var MenuID = jQuery('#current_subsubmenu_item').val();
var MenuItems = Number(jQuery('#'+MenuID+'_num_subsubmenu_items').val()); if (MenuItems===''){MenuItems=Number("1");}
if(MenuItems===0){
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').addClass('sticky_menu_item_selected');	
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');}
if(MenuItems<5){
	  var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
	  var uniqID = randLetter + Date.now();
	  
     $('#sticky_subsubmenu_table').append('<tr id="'+uniqID+'"><td><input type="hidden" id="'+MenuID+'_submenu_items[]" name="'+MenuID+'_submenu_items[]" value="'+uniqID+'" />	 <input type="text" name="'+uniqID+'_menu_title" id="'+uniqID+'_menu_title" class="form-control input-lg m-b" placeholder="Enter Menu Text...max 30 Characters" size="30"  maxlength="30"></input>	 <input type="hidden" name="'+uniqID+'_menu_msg" id="'+uniqID+'_menu_msg" /></input>	 <input type="hidden" name="'+uniqID+'_menu_url" id="'+uniqID+'_menu_url" /><input type="hidden" name="'+uniqID+'_menu_type" id="'+uniqID+'_menu_type"/></td><td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_link_select"><i class="fa icon-link2 fa-lg submenu_link_item" aria-hidden="true"></i></div></td>	 <td valign="middle"><div class="input-lg m-b sticky_menu_item" id="'+uniqID+'_menu_msg_select"><i class="fa fa-comment-o fa-lg submenu_msg_item" aria-hidden="true"></i></div></td><td valign="middle"><div class="input-lg m-b"><i class="fa icon-cross fa-lg delete_subsubmenu_item" aria-hidden="true"></i></div></td></tr>');
	 var NewNum = Number("1");
	 NewMenuNum = MenuItems+NewNum;
	 jQuery('#'+MenuID+'_num_subsubmenu_items').val(NewMenuNum);
	 if(NewMenuNum==5){jQuery('.add_subsubmenu_item').hide();}
	 }
});

$(document).on('click', '.delete_menu_item', function (e) {
			var trid = $(this).closest('tr').attr('id');	
			RemoveStickyMenuItem(trid);
			$(this).closest("tr").remove();	
			var MenuItems = Number(jQuery('#num_menu_items').val());
			if(MenuItems==3){jQuery('.add_menu_item').show();}
			var NewNum = Number("1");
	 		NewMenuNum = MenuItems-NewNum;
			jQuery('#num_menu_items').val(NewMenuNum);
});			

$(document).on('click', '.delete_submenu_item', function (e) {
			var trid = $(this).closest('tr').attr('id');	
			$(this).closest("tr").remove();	
			var MenuID = jQuery('#current_menu_item').val();

			RemoveStickySubMenuItem(trid,MenuID);
			var MenuItems = Number(jQuery('#'+MenuID+'_num_submenu_items').val()); if (MenuItems===''){MenuItems=Number("1");}
			if(MenuItems<6){jQuery('.add_submenu_item').show();}
			var NewNum = Number("1");
	 		NewMenuNum = MenuItems-NewNum;
			jQuery('#'+MenuID+'_num_submenu_items').val(NewMenuNum);
});

$(document).on('click', '.delete_subsubmenu_item', function (e) {
			var trid = $(this).closest('tr').attr('id');	
			$(this).closest("tr").remove();	
			var MenuID = jQuery('#current_submenu_item').val();

			RemoveStickySubSubMenuItem(trid,MenuID);
			var MenuItems = Number(jQuery('#'+MenuID+'_num_subsubmenu_items').val()); if (MenuItems===''){MenuItems=Number("1");}
			if(MenuItems<6){jQuery('.add_subsubmenu_item').show();}
			var NewNum = Number("1");
	 		NewMenuNum = MenuItems-NewNum;
			jQuery('#'+MenuID+'_num_subsubmenu_items').val(NewMenuNum);
});


$(document).on('click', '.menu_msg_item', function (e) {
			var MenuID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			if(MenuTitle!=""){
			jQuery('#current_menu_item').val(MenuID);
			jQuery('#modal_menu_msg').modal();
            var ajax_url='includes/admin-ajax.php';
            var page_id = jQuery('#greeting_page_id').val();	
            var user_id = jQuery('#greeting_user_id').val();	
			var MsgID =jQuery('#'+MenuID+'_menu_msg').val();
			
			var data = {'action': 'menu_item_msg',
			'user_id': user_id,
			'page_id': page_id,
			'button_msg':MsgID,
			'item_id': MenuID
			}
jQuery.post(ajax_url, data, function(response) {
					  var response_arr = response.split("|", 3);	
    				  jQuery("#menu_item_msg_result").html(response_arr[0]);		
						  });	
			}else{
			MenuMissingTitle(MenuID);
			}			  
			
			
});		

$(document).on('click', '.submenu_msg_item', function (e) {
			var MenuID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			if(MenuTitle!=""){
			jQuery('#current_submenu_item').val(MenuID);
			var MsgID =jQuery('#'+MenuID+'_menu_msg').val();
			jQuery('#modal_submenu_msg').modal();
            var ajax_url='includes/admin-ajax.php';
            var page_id = jQuery('#greeting_page_id').val();	
            var user_id = jQuery('#greeting_user_id').val();	
			
			var data = {'action': 'menu_item_msg',
			'user_id': user_id,
			'page_id': page_id,
			'item_id': MenuID,
			'button_msg':MsgID
			}
jQuery.post(ajax_url, data, function(response) {
	
					  var response_arr = response.split("|", 3);	
    				  jQuery("#submenu_item_msg_result").html(response_arr[0]);		
						  });	
			}else{
			MenuMissingTitle(MenuID);
			}			  
			
			
});


$(document).on('click', '.menu_submenu_item', function (e) {
			var MenuID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			var page_id = jQuery('#greeting_page_id').val();	
			if(MenuTitle!=""){
			jQuery('#current_menu_item').val(MenuID);
			var ajax_url='includes/admin-ajax.php';
			var data = {'action': 'submenu_item',
			'page_id': page_id,
			'menu_id': MenuID
			}
			jQuery.post(ajax_url, data, function(response) {
			 jQuery("#sticky_submenu_result").html(response);		 
			});
			
			jQuery('#modal_sticky_submenu').modal();
			}else{
			MenuMissingTitle(MenuID);
			}
});

$(document).on('click', '.submenu_submenu_item', function (e) {
			var MenuID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			var page_id = jQuery('#greeting_page_id').val();	
			if(MenuTitle!=""){
			jQuery('#current_subsubmenu_item').val(MenuID);
			var ajax_url='includes/admin-ajax.php';
			var data = {'action': 'subsubmenu_item',
			'page_id': page_id,
			'menu_id': MenuID
			}
			jQuery.post(ajax_url, data, function(response) {
			 jQuery("#sticky_subsubmenu_result").html(response);		 
			});
			
			jQuery('#modal_sticky_subsubmenu').modal();
			}else{
			MenuMissingTitle(MenuID);
			}
});

$(document).on('click', '.menu_link_item', function (e) {
			var MenuID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			if(MenuTitle!=""){
			var menu_link = $('#'+MenuID+'_menu_url').val();	
			jQuery('#menu_link').val(menu_link);
			jQuery('#current_menu_item').val(MenuID);
			jQuery('#modal_menu_link').modal();
			}else{
			MenuMissingTitle(MenuID);
			}
});

$(document).on('click', '.submenu_link_item', function (e) {
			var ItemID = $(this).closest('tr').attr('id');
			var MenuTitle = jQuery('#'+ItemID+'_menu_title').val();
			if(MenuTitle!=""){
			var submenu_link = $('#'+ItemID+'_menu_url').val();	
			jQuery('#submenu_link').val(submenu_link);
			jQuery('#current_submenu_item').val(ItemID);
			jQuery('#modal_submenu_link').modal();
			}else{
			MenuMissingTitle(ItemID);
			}
});


function MenuMissingTitle(MenuID){
jQuery("#"+MenuID+"_menu_title").addClass("missing_title");
jQuery("#"+MenuID+"_menu_title").css({"background-color": "#f2dede", "color": "#a94442", "border-color": "#a94442"});
jQuery('#'+MenuID+'_menu_title').attr('placeholder', 'Please Enter Your Menu Text First');
}

$(document).on('click', '.save_menu_link', function (e) {
			var MenuID = jQuery('#current_menu_item').val();
			var MenuLink = jQuery('#menu_link').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			jQuery('#'+MenuID+'_menu_url').val(MenuLink);
			jQuery('#'+MenuID+'_menu_type').val('web_url');
			jQuery('#'+MenuID+'_menu_msg').val('');
			
			if(MenuTitle!==''){
			jQuery('#menu_link').val('');
			//we have a full set so we are able to create a Menu with this
			CreateStickyMenu(MenuID);
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').removeClass('sticky_menu_item_selected');	
			$('#'+MenuID+'_menu_link_select').addClass('sticky_menu_item_selected');
			}
});	

$(document).on('click', '.save_submenu_link', function (e) {
			var MenuID = jQuery('#current_submenu_item').val();
			var MenuLink = jQuery('#submenu_link').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			jQuery('#'+MenuID+'_menu_url').val(MenuLink);
			jQuery('#'+MenuID+'_menu_type').val('web_url');
			jQuery('#submenu_link').val('');

			if(MenuTitle!==''){
			jQuery('#'+MenuID+'_menu_msg').val('');
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_link_select').addClass('sticky_menu_item_selected');
			}
});	


$(document).on('click', '.save_menu_msg', function (e) {
			var MenuID = jQuery('#current_menu_item').val();
			var MenuMsg = jQuery('#button1_msg_select').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			jQuery('#'+MenuID+'_menu_msg').val(MenuMsg);
			jQuery('#'+MenuID+'_menu_type').val('postback');
			jQuery('#menu_item_msg_result').html('');
			if(MenuTitle!==''){
			jQuery('#'+MenuID+'_menu_url').val('');
			//we have a full set so we are able to create a Menu with this
			CreateStickyMenu(MenuID);	
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').removeClass('sticky_menu_item_selected');		
			$('#'+MenuID+'_menu_msg_select').addClass('sticky_menu_item_selected');
			}
});	


$(document).on('click', '.save_submenu_msg', function (e) {
			var MenuID = jQuery('#current_submenu_item').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			var MenuMsg = jQuery('#submenu_item_msg_result #button1_msg_select').val();
			jQuery('#'+MenuID+'_menu_type').val('postback');
			jQuery('#'+MenuID+'_menu_msg').val(MenuMsg);	
			if(MenuTitle!==''){
			jQuery('#'+MenuID+'_menu_url').val('');		
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_msg_select').addClass('sticky_menu_item_selected');
			}
});	


$(document).on('click', '.save_submenu', function (e) {
			var MenuID = jQuery('#current_menu_item').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			jQuery('#'+MenuID+'_menu_type').val('submenu');
			jQuery('#menu_item_msg_result').html('');
			if(MenuTitle!==''){
			//we have a full set so we are able to create a Menu with this
			CreateStickySubMenu(MenuID,MenuTitle);
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').addClass('sticky_menu_item_selected');		
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			}
});	

$(document).on('click', '.save_subsubmenu', function (e) {
			var MenuID = jQuery('#current_subsubmenu_item').val();
			var SubMenuID = jQuery('#current_submenu_item').val();
			var MainMenuID =jQuery('#current_menu_item').val();
			var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
			jQuery('#'+MenuID+'_menu_type').val('subsubmenu');
			jQuery('#menu_item_msg_result').html('');
			if(MenuTitle!==''){
			//we have a full set so we are able to create a Menu with this
			CreateStickySubSubMenu(MainMenuID,SubMenuID,MenuID,MenuTitle);
			$('#'+MenuID+'_menu_link_select').removeClass('sticky_menu_item_selected');
			$('#'+MenuID+'_menu_submenu_select').addClass('sticky_menu_item_selected');		
			$('#'+MenuID+'_menu_msg_select').removeClass('sticky_menu_item_selected');
			}
});

	
function RemoveStickyMenuItem(MenuID){
//we have a removal...lets delete it from the db
var ajax_url='includes/admin-ajax.php';
var BotID = jQuery('#bot_id').val();
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();	
var data = {'action': 'delete_sticky_menu_item',
			'user_id': user_id,
			'page_id': page_id,
			'bot_id': BotID,
			'item_id': MenuID
			}
jQuery.post(ajax_url, data, function(response) {
						  		jQuery('#menu_result').html(response);
						  });			

}	

function RemoveStickySubMenuItem(ItemID,MenuID){
//we have a removal of a submenu item...lets delete it from the db
var ajax_url='includes/admin-ajax.php';
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();	
var data = {'action': 'delete_sticky_submenu_item',
			'user_id': user_id,
			'page_id': page_id,
			'menu_id': MenuID,
			'item_id': ItemID
			}
jQuery.post(ajax_url, data, function(response) {
						  		jQuery('#submenu_result').html(response);
						  });	

}

function RemoveStickySubSubMenuItem(ItemID,MenuID){
//we have a removal of a subsubmenu item...lets delete it from the db
var ajax_url='includes/admin-ajax.php';
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();	
var data = {'action': 'delete_sticky_subsubmenu_item',
			'user_id': user_id,
			'page_id': page_id,
			'menu_id': MenuID,
			'item_id': ItemID
			}
jQuery.post(ajax_url, data, function(response) {
						  		jQuery('#submenu_result').html(response);
						  });	

}
	
function CreateStickyMenu(MenuID){
//we have a complete MenuItem...lets add it to the database
var ajax_url='includes/admin-ajax.php';
var BotID = jQuery('#bot_id').val();
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();	
var MenuTitle = jQuery('#'+MenuID+'_menu_title').val();
var MenuType = jQuery('#'+MenuID+'_menu_type').val();
var MenuUrl = jQuery('#'+MenuID+'_menu_url').val();
var MenuMsg = jQuery('#'+MenuID+'_menu_msg').val();

var data = {'action': 'create_sticky_menu_item',
			'user_id': user_id,
			'page_id': page_id,
			'bot_id': BotID,
			'item_id': MenuID,
			'menu_title': MenuTitle,
			'menu_type': MenuType,
			'menu_url': MenuUrl,
			'menu_msg': MenuMsg
			};	
                          jQuery.post(ajax_url, data, function(response) {
						  		jQuery('#menu_result').html(response);
						  });
}

function CreateStickySubMenu(MenuID,MenuTitle){
//we have a complete MenuItem...lets add it to the database
var ajax_url='includes/admin-ajax.php';
var BotID = jQuery('#bot_id').val();
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();
  var ItemId = [];	
  var SubMenuTitle= [];
  var SubMenuType= [];
  var SubMenuUrl= [];
  var SubMenuMsg= [];
  var NumItem = Number("0");
  $('#sticky_submenu_result [id^="'+MenuID+'_submenu_item"]').each(function(i) {
  ThisItem = $(this).val();
  ItemId[NumItem] = ThisItem;
  SubMenuTitle[NumItem] = jQuery('#'+ThisItem+'_menu_title').val();
  SubMenuType[NumItem] = jQuery('#'+ThisItem+'_menu_type').val();
  SubMenuUrl[NumItem] = jQuery('#'+ThisItem+'_menu_url').val();
  SubMenuMsg[NumItem] = jQuery('#'+ThisItem+'_menu_msg').val();
  NumItem++;
});

var data = {'action': 'create_sticky_submenu',
			'user_id': user_id,
			'page_id': page_id,
			'bot_id': BotID,
			'menu_id': MenuID,
			'menu_title': MenuTitle,
			'item_ids': ItemId,
			'submenu_title': SubMenuTitle,
			'submenu_type': SubMenuType,
			'submenu_url': SubMenuUrl,
			'submenu_msg': SubMenuMsg
			};	
                          jQuery.post(ajax_url, data, function(response) {
						  });
}

function CreateStickySubSubMenu(MainMenuID,SubMenuID,MenuID,MenuTitle){
//we have a complete MenuItem...lets add it to the database
var ajax_url='includes/admin-ajax.php';
var BotID = jQuery('#bot_id').val();
var page_id = jQuery('#greeting_page_id').val();	
var user_id = jQuery('#greeting_user_id').val();
  var ItemId = [];	
  var SubMenuTitle= [];
  var SubMenuType= [];
  var SubMenuUrl= [];
  var SubMenuMsg= [];
  var NumItem = Number("0");
  $('#sticky_subsubmenu_result [id^="'+MenuID+'_submenu_items"]').each(function(i) {
  ThisItem = $(this).val();
  ItemId[NumItem] = ThisItem;
  SubMenuTitle[NumItem] = jQuery('#'+ThisItem+'_menu_title').val();
  SubMenuType[NumItem] = jQuery('#'+ThisItem+'_menu_type').val();
  SubMenuUrl[NumItem] = jQuery('#'+ThisItem+'_menu_url').val();
  SubMenuMsg[NumItem] = jQuery('#'+ThisItem+'_menu_msg').val();
  NumItem++;
});

var data = {'action': 'create_sticky_subsubmenu',
			'user_id': user_id,
			'page_id': page_id,
			'bot_id': BotID,
			'menu_id': MenuID,
			'main_menu_id': MainMenuID,
			'submenu_id': SubMenuID,
			'menu_title': MenuTitle,
			'item_ids': ItemId,
			'submenu_title': SubMenuTitle,
			'submenu_type': SubMenuType,
			'submenu_url': SubMenuUrl,
			'submenu_msg': SubMenuMsg
			};	
                          jQuery.post(ajax_url, data, function(response) {
						  });
}

$(document).on('click', '.menu_share_item', function (e) {
			var trid = $(this).closest('tr').attr('id');	
			jQuery('#current_menu_item').val(trid);
			jQuery('#modal_menu_share').modal();
});

$(document).on('click', '.menu_phone_item', function (e) {
			var trid = $(this).closest('tr').attr('id');
			jQuery('#current_menu_item').val(trid);	
			jQuery('#modal_menu_phone').modal();
});	

$(document).on('keyup', '.missing_title',function(){
$(this).removeAttr("style");
$(this).removeClass('missing_title');
$(this).attr('placeholder', 'Enter Menu Text...max 30 Characters');
});