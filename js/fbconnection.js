$(document).on('click', '.fb_connection', function (e){
		e.preventDefault();
		jQuery.blockUI({
			message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Please hold on, connecting to your facebook page...</span>',
			overlayCSS: {opacity: .5}
		});
		jQuery("#fb_connection_result").html('Submitting....');
		var ajax_url='includes/admin-ajax.php';
		var page_id=jQuery(this).data('page_id');
		var bot_id=jQuery('#bot_id').val();		
		var bot_page=jQuery('#bot_page').val();
		var data = {'action': 'smartbot_connect_bot_page',
					 'page_id': page_id,
		   		   	 'bot_id': bot_id,
					 'bot_page': bot_page
					};
    		jQuery.post(ajax_url, data, function(response) {
    		jQuery("#fb_connection_result").html(response);
    			if (response.match("^Success") || response.match("^DisconnectBotPage")) {
    			//add the btn-success class and change the text
				
				   jQuery('#bot_page').val(page_id);
                   jQuery('#page_id').val(page_id);
				   jQuery('#visual_page_id').val(page_id);
				   jQuery('#greeting_page_id').val(page_id);
				   jQuery('#greeting_bot_id').val(bot_id);


				$("a[href='#next']").show();
				
    			jQuery('#bot_button_'+page_id).html('Connected');
    			jQuery('#bot_button_'+page_id).removeClass("btn-primary");
    			jQuery('#bot_button_'+page_id).removeClass("btn-white");
    			jQuery('#bot_button_'+page_id).removeClass("btn-w-m");
    			jQuery('#bot_button_'+page_id).addClass("fb_connected");
				jQuery('#newbot_form_submit_'+page_id).trigger("click");
    			}
				
				if(response.match("^DisconnectThis")) {

                $("a[href='#next']").hide();
				
				jQuery('#page_id').val('');
				jQuery('#bot_page').val('');
				jQuery('#visual_page_id').val('');
				jQuery('#greeting_page_id').val('');
				
				jQuery('#bot_button_'+page_id).html('Connect to Page');			
    			jQuery('#bot_button_'+page_id).addClass("btn-w-m");
    			jQuery('#bot_button_'+page_id).addClass("btn-primary");
    			jQuery('#bot_button_'+page_id).removeClass("fb_connected");
				}	
				
				if(response.match("^DisconnectBotPage")) {

                    $("a[href='#next']").hide();
				
				jQuery('#bot_button_'+bot_page).html('Connect to Page');				
    			jQuery('#bot_button_'+bot_page).addClass("btn-w-m");
    			jQuery('#bot_button_'+bot_page).addClass("btn-primary");
    			jQuery('#bot_button_'+bot_page).removeClass("fb_connected");
				}
				
			});
});