var globalFlowMsgData;
$(document).ready(function (){
    var globalFieldSelector ='';
    //var globalMsgData = {};
    preloadMenuData();
    //Add parent menu item
    $(document).on('click','.add_parent_menu',function() {
        var count = $('.nested_with_switch').children().length;
        if(count <= 2) {
            //var parentItemHtml = '<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="font-size: 14px;width: 20px;height: 60px;"><i class="fa icon-menu" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" placeholder="Enter Menu Text...max 30 Characters" size="30" maxlength="30"  style="height: 60px;"><span class="input-group-addon input-lg add_menu_list_item"><i class="fa icon-plus"></i></span><span class="input-group-addon input-lg menu_func"><i class="fa icon-pencil"></i></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol></ol></li>';
            var parentItemHtml = '<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="width: 20px;height: 60px;"><i class="fa icon-menu menu-icon" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" data-emojiable="true" data-charcounter="true" placeholder="Enter text here (max. 30 characters)" size="30" maxlength="30"  style="height: 60px;z-index:0;"><span class="input-group-addon input-lg settings-handle" style="padding: 0;">' +
                '<a class="hoveraddon" style="display: flex;padding: 20px 16px;"><i class="fa icon-cog"></i></a>' +
                '<a class="hoveraddon add_menu_list_item" style="margin-top: -31px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-plus"></i></a>' +
                '<a class="hoveraddon add_menu_weblink" style="margin-top: -117px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-link"></i></a>' +
                '<a class="hoveraddon add_menu_message" style="margin-top: -119px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-bubble"></i></a></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol></ol></li>';
            $('.nested_with_switch').append(parentItemHtml);
        }
        if(count ==2){
            $(this).css('display','none');
        }
        resetMenuPreview();
        $('.char-counter-styling').remove();
        charCountSet("[data-charcounter='true']");
    });
    //delete menu item
    $(document).on('click','.delete_menu_list_item',function() {
        var thisItem = $(this);
        modalConfirm("Are you sure you want to delete this item?<br>Note: if this item is a menu it will also delete the items inside this menu.",
            function(){
                //user confirmed to delete
                var level = thisItem.parentsUntil('.nested_with_switch', 'li');
                level = level.length;
                if(level == 1){
                    var count = $('.nested_with_switch').children().length;
                    if(count<3){
                        $('.add_parent_menu').css('display', '');
                    }
                }
                if(level != 1) {
                    thisItem.closest('ol').first().closest('li').first().find('.icon-plus').first().css('color', '');
                }
                thisItem.closest('li').remove();
                resetMenuPreview();
            },
            function(){
                //user clicked cancel
            });

    });
    //add menu item
    $(document).on('click','.add_menu_list_item',function(e) {
        e.stopPropagation();
        var level = $(this).parentsUntil('.nested_with_switch', 'li');
        level = level.length;
        var olList = $(this).closest('li').find('ol').first();
        var olListLength = olList.children().length;
        if(level <=2 && olListLength<5) {
            if(level ==2){
                var whitePlus = 'style="color:white;"';
            }
            var ItemHtml = '<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="width: 20px;height: 60px;"><i class="fa icon-menu menu-icon" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" data-emojiable="true" data-charcounter="true" placeholder="Enter text here (max. 30 characters)" size="30" maxlength="30"  style="height: 60px;z-index:0;"><span class="input-group-addon input-lg settings-handle" style="padding: 0;">' +
                '<a class="hoveraddon" style="display: flex;padding: 20px 16px;"><i class="fa icon-cog"></i></a>' +
                '<a class="hoveraddon add_menu_list_item" style="margin-top: -31px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-plus"'+whitePlus+'></i></a>' +
                '<a class="hoveraddon add_menu_weblink" style="margin-top: -117px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-link"></i></a>' +
                '<a class="hoveraddon add_menu_message" style="margin-top: -119px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-bubble"></i></a></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol></ol></li>';
            olList.append(ItemHtml);
            resetMenuPreview();
            $(this).parents('div.input-group').find('input.menu_txt_input').first().attr('data-itemtype','');
            $(this).parents('li').first().find('span.draghandle').first().children('i').removeClass().addClass('fa icon-menu').addClass("menu-icon");
            if(olListLength==4){
                $(this).parents('div.input-group').find('.icon-plus').first().css('color','white');
            }
        }
        else{
            if(level>=3){
                //alert('Only upto two(2) levels are allowed');
                return false;
            }
            if(olListLength>=5){
                //alert('Maximum five(5) items per menu are allowed');
                return false;
            }
        }
        $('.char-counter-styling').remove();
        charCountSet("[data-charcounter='true']");
    });
    $(document).on('input','.menu_txt_input',function(){
        resetMenuPreview();
    });
    $(document).on('click', '.preview_menu_sub_back', function () {
        resetMenuPreview();
    });
    $(document).on('click', '.preview_menu_subsub_back', function () {
        jQuery('#sub-menu-page').show();
        jQuery('#sub-sub-menu-page').hide();
    });
    $(document).on('click', '.preview-has-children', function () {
        if($(this).hasClass("preview_menu_item")){
            /*this is a parent class get its index value and find child elements then add them to child
            *
             */
            var titleName = $(this).html();
            var temp = $(this).attr('id');
            var i = temp.replace('main_menu', '');
            var finalHtml = '<div class="message-handle"></div><div id="sub_back" class="menu-text preview_menu_sub_back" data-menu_id="subback">'+titleName+'</div>';
            var countChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children().length;
            for(var j=1;j<=countChild;j++){
                var childItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('input.menu_txt_input').first().val();
                var temp = '';
                var countSubChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children().length;
                if(countSubChild>0){
                    temp = 'preview-has-children';
                }
                finalHtml = finalHtml+'<div id="sub_menu'+j+'" class="menu-text preview_menu_item_sub '+temp+'" data-menu_type="menu">'+childItemName+'</div>';
            }
            jQuery('#main-menu-page').hide();
            jQuery('#sub-menu-page').attr('data-index',i);
            jQuery('#sub-menu-page').html(finalHtml);
            jQuery('#sub-menu-page').show();
        }
        if($(this).hasClass("preview_menu_item_sub")){
            /*this is a child class get its index value and find sub child elements then add
            *
             */
            var titleName = $(this).html();
            var temp = $(this).attr('id');
            var i = jQuery('#sub-menu-page').attr('data-index');
            var j = temp.replace('sub_menu', '');
            var countSubChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children().length;
            var finalHtml = '<div id="sub_sub_back" class="menu-text preview_menu_subsub_back">'+titleName+'</div>';
            for(var k=1;k<=countSubChild;k++){
                var subChildItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children('li:nth-child('+k+')').find('input.menu_txt_input').first().val();
                finalHtml = finalHtml+'<div id="sub_sub_menu'+k+'" class="menu-text preview_menu_item_subsub">'+subChildItemName+'</div>';
            }
            jQuery('#sub-menu-page').hide();
            jQuery('#sub-sub-menu-page').html(finalHtml);
            jQuery('#sub-sub-menu-page').show();
        }
    });
    $(document).on('click', '.add_menu_weblink', function (){
        var thisItem = $(this);
        var childCount = thisItem.closest('li').first().find('ol').first().children().length;
        if(childCount>0){
            modalConfirm("It seems this menu item has child item(s), if you create a weblink for this item, it will delete the child items of this item, if you wish to continue please click 'Ok'",
                function(){
                    //user confirmed
                    resetWeblinkModal();
                    globalFieldSelector = thisItem.parents('div.input-group').find('input.menu_txt_input').first();
                    if($(globalFieldSelector).attr('data-itemtype')=='web_url'){
                        $('#button_link').val($(globalFieldSelector).attr('data-url'));
                        $('#webview_height_ratio').val($(globalFieldSelector).attr('data-webview_height_ratio'));
                    }
                    $('#persistent_menu_weblink_modal').modal();
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            resetWeblinkModal();
            globalFieldSelector = $(this).parents('div.input-group').find('input.menu_txt_input').first();
            if($(globalFieldSelector).attr('data-itemtype')=='web_url'){
                $('#button_link').val($(globalFieldSelector).attr('data-url'));
                $('#webview_height_ratio').val($(globalFieldSelector).attr('data-webview_height_ratio'));
            }
            $('#persistent_menu_weblink_modal').modal();
        }


    });
    $(document).on('click', '.save_weblink', function (){
        if($('#button_link').val()!='' && $('#webview_height_ratio').val()!=null){
            if(!isValidURL($('#button_link').val())){
                $('#button_link').css('border','1px solid red');
                toastr.error("Make sure you specify a valid URL.<br>eg: https://clevermessenger.com", "Invalid URL");
                return false;
            }
            $(globalFieldSelector).attr('data-itemtype', 'web_url');
            var temp = $('#button_link').val();
            $('#button_link').val('');
            $(globalFieldSelector).attr('data-url', temp);
            temp = $('#webview_height_ratio').val();
            $(globalFieldSelector).attr('data-webview_height_ratio', temp);
            $('#webview_height_ratio').val('');
            $(globalFieldSelector).parents('li').first().find('ol').first().html('');
            $(globalFieldSelector).parents('li').first().find('span.draghandle').first().children('i').removeClass().addClass('fa icon-link').addClass("menu-icon");
            globalFieldSelector = '';
            resetFlowCardsVisual();
            $('#persistent_menu_weblink_modal').modal('toggle');
          //  toastr.success("Added weblink to the menu item.", "Success!");
        }
        else{
            $('#button_link').css('border','1px solid red');
            toastr.error("Please specify a URL in URL field", "Error");
            return false;
        }
    });
    $(document).on('click','.add_menu_message', function(){
        var thisItem = $(this);
        var childCount = thisItem.closest('li').first().find('ol').first().children().length;
        if(childCount>0){
            modalConfirm("It seems this menu item has child item(s), if you create a message for this item, it will delete the child items of this item, if you wish to continue please click 'Ok'",
                function(){
                    //user confirmed
                    globalFieldSelector = thisItem.parents('div.input-group').find('input.menu_txt_input').first();
                    resetFlowSelectorModal();
                    if($(globalFieldSelector).attr('data-itemtype')=='postback'){
                        var flowtype = globalMsgData[$(globalFieldSelector).attr('data-payload')]['type'];
                        $('#select_reference_type').val(flowtype).trigger('change');
                        if(flowtype == 'flow'){
                            var flowid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['flowid'];
                            $('#select_reference_flow').val(flowid).trigger('change');
                        }
                        if(flowtype == 'flowcard'){
                            var flowid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['flowid'];
                            var msgid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['msgid'];
                            $('#select_reference_flow').val(flowid).trigger('change',{cardToSelect:msgid});
                            //   $('#select_reference_flow_card').val(msgid).trigger('change');
                        }

                    }
                    $('#persistent_menu_flow_selector').modal();
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            globalFieldSelector = $(this).parents('div.input-group').find('input.menu_txt_input').first();
            resetFlowSelectorModal();
            if($(globalFieldSelector).attr('data-itemtype')=='postback'){
                var flowtype = globalMsgData[$(globalFieldSelector).attr('data-payload')]['type'];
                $('#select_reference_type').val(flowtype).trigger("change");
                if(flowtype == 'flow'){
                    var flowid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['flowid'];
                    $('#select_reference_flow').val(flowid).trigger("change");
                }
                if(flowtype == 'flowcard'){
                    var flowid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['flowid'];
                    var msgid = globalMsgData[$(globalFieldSelector).attr('data-payload')]['msgid'];
                    $('#select_reference_flow').trigger('change',{cardToSelect:msgid,flowId:flowid,flowType:flowtype});
                }

            }
            $('#persistent_menu_flow_selector').modal();
        }
    });
    $(document).on('click', '.save_menu_message', function (){
        if($('#select_reference_type').val() == 'select'){
            toastr.error("Please specify a message type", "Error");
            $('#select_reference_type').css('border','1px solid red');
            return false;
        }
        if($('#select_reference_type').val() == 'flow'){
            $(globalFieldSelector).attr('data-itemtype','postback');
            $(globalFieldSelector).attr('data-payload',flowId);
            var flowId = $('#select_reference_flow').val();
            globalMsgData[flowId]={};
            globalMsgData[flowId]['type'] = 'flow';
            globalMsgData[flowId]['flowid'] = flowId;
            $(globalFieldSelector).attr('data-payload',flowId);
            $(globalFieldSelector).parents('li').first().find('ol').first().html('');
            $(globalFieldSelector).parents('li').first().find('span.draghandle').first().children('i').removeClass().addClass('fa icon-bubble').addClass('menu-icon');
            globalFieldSelector='';
            resetFlowCardsVisual();
            $('#persistent_menu_flow_selector').modal('toggle');
           // toastr.success("Added flow to the menu item.", "Success!");
        }
        if($('#select_reference_type').val() == 'flowcard'){
            $(globalFieldSelector).attr('data-itemtype','postback');
            $(globalFieldSelector).attr('data-payload',flowId+":"+msgId);
            var flowId = $('#select_reference_flow').val();
            var msgId = $('#select_reference_flow_card').val();
            globalMsgData[flowId+":"+msgId] = {};
            globalMsgData[flowId+":"+msgId]['type']= 'flowcard';
            globalMsgData[flowId+":"+msgId]['flowid'] = flowId;
            globalMsgData[flowId+":"+msgId]['msgid'] = msgId;
            $(globalFieldSelector).attr('data-payload',flowId+":"+msgId);
            $(globalFieldSelector).parents('li').first().find('ol').first().html('');
            $(globalFieldSelector).parents('li').first().find('span.draghandle').first().children('i').removeClass().addClass('fa icon-bubble').addClass("menu-icon");
            globalFieldSelector='';
            resetFlowCardsVisual();
            $('#persistent_menu_flow_selector').modal('toggle');
          //  toastr.success("Added message to the menu item.", "Success!");
        }


    });
    $(document).on({
        mouseover: function() {
            event.preventDefault();
            //$(this).parent('span').children('a:first-child').css('margin-top','-20px');
            $(this).parent('span').children('a:first-child').css({'display':'none'});
            $(this).parent('span').children('a:not(:first-child)').css('display','flex');
        },
        mouseout: function() {
            event.preventDefault();
            //$(this).parent('span').children('a:first-child').css('margin-top','');
            $(this).parent('span').children('a:first-child').css({'display':'flex'});
            $(this).parent('span').children('a:not(:first-child)').css('display','none');
        }
    },'.hoveraddon');
    // Main Menu enable/disable
    $(document).on('change','#main_menu_check',function() {
        if(this.checked) {
            toastr.success("Persistent menu is enabled", "Enabled");
            var ThisAction = 1;
            MainMenuOnOff(ThisAction);
        }
        if(!this.checked) {
            toastr.warning("Persistent menu is disabled", "Disabled");
            var ThisAction = 0;
            MainMenuOnOff(ThisAction);

        }
    });
    /*
    // composer_input_disabled enable/disable
    $(document).on('change','#composer_input_disabled',function() {
        if(this.checked) {
            toastr.success("User Input is disabled", "Success!");
            var ThisAction = 0;
            UserInputOnOff(ThisAction);
        }
        if(!this.checked) {
            toastr.warning("User Input is allowed", "Paused!");
            var ThisAction = 1;
            UserInputOnOff(ThisAction);

        }
    });
    // customer_chat_plugin enable/disable
    $(document).on('change','#customer_chat_plugin',function() {
        if(this.checked) {
            toastr.success("Customer chat plugin is enabled", "Success!");
            var ThisAction = 1;
            customerChatPluginOnOff(ThisAction);
        }
        if(!this.checked) {
            toastr.warning("Customer chat plugin is disabled", "Paused!");
            var ThisAction = 0;
            customerChatPluginOnOff(ThisAction);

        }
    });
    */

    // Prevent line return on content editable
    $(document).on('keypress', '.emoji-wysiwyg-editor', function(e){
        return e.which != 13;
    });
    // composer_input_disabled enable/disable
    $(document).on('change','#composer_input_disabled',function() {
        if(this.checked) {
        //    toastr.success("User Input is disabled", "Disabled");
            $('.customerChatToggleDiv').css('display','none');
            $('#customer_chat_plugin').prop('checked', false);
        }
        if(!this.checked) {
         //   toastr.warning("User Input is allowed", "Enabled");
            $('.customerChatToggleDiv').css('display','');

        }
    });
    // customer_chat_plugin enable/disable
    $(document).on('change','#customer_chat_plugin',function() {
        if(this.checked) {
          //  toastr.success("Persistent menu is disabled in Customer Chat plugin", "Success");
        }
       else if(!this.checked) {
            //toastr.success("Persistent menu is enabled in Customer Chat plugin", "Success");
        }
    });
    $(document).on('click','.save_the_menu', function(){
        $.blockUI({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Saving data...</span>',
            overlayCSS: {opacity: .5}
        });
        var locale = window.urlParams.lang;
        var call_to_actions = saveMenuData();
        if(call_to_actions == false){
            $.unblockUI();
            toastr.error("Could not save the persistent menu", "Error");
            return false;
        }
        var flowsdata = JSON.stringify(globalMsgData);
        var ajax_url='includes/admin-ajax.php';
        var data = {'action': 'save_persistent_menu',
            'locale':locale,
            'call_to_actions':call_to_actions ,
            'flowsdata': flowsdata
        };
        jQuery.post(ajax_url, data, function(res) {
            $.unblockUI();
            if(res=='updated'){
                toastr.success("Persistent menu has been successfully saved", "Success");
            }
            if(res=='error'){
                toastr.error("Could not save the persistent menu", "Error");
            }
        });

    });

    $(document).on('mouseenter','.show-flow-msg-onhover',function(){
        var flowId = $(this).attr('data-flowid');
        var type = $(this).attr('data-type');
            if(type === 'flow'){
                buildFlowPreview(flowId,'#flow_msg_phone_preview');
            }
            else if(type === 'card'){
                var cardId = $(this).attr('data-cardid');
                buildCardPreview(flowId,cardId,'#flow_msg_phone_preview');
            }
        $('#flow_msg_phone_preview').css('display','');
    });

    $(document).on('mouseleave','.show-flow-msg-onhover',function(){
        $('#flow_msg_phone_preview').html('');
        $('#flow_msg_phone_preview').css('display','none');
    });

});

$(window).bind("load", function() {
    charCountSet("[data-charcounter='true']");
});
//testing drag and drop menu
$(function () {
    var oldContainer;
    $("ol.nested_with_switch").sortable({
        group: 'nested',
        containerSelector: 'ol',
        itemSelector:'li',
        handle: 'span.draghandle',
        afterMove: function (placeholder, container) {
            if(oldContainer != container){
                if(oldContainer)
                    oldContainer.el.removeClass("active");
                container.el.addClass("active");

                oldContainer = container;
            }
        },
        onDrop: function ($item, container, _super) {
            $(container.target).closest('li').first().find('span.draghandle > i').first().removeClass().addClass('fa icon-menu').addClass("menu-icon");
            $(container.target).closest('li').first().find('input.menu_txt_input').first().attr('data-itemtype','');
            container.el.removeClass("active");
            _super($item, container);
            resetMenuPreview();
        },
        isValidTarget: function ($item, container) {
            //get the depth  of the item we have picked up
            var itemLevel = 0;
            $('li.dragged').find('ol').each(function() {
                if( !this.firstChild || this.firstChild.nodeType !== 1  ) {
                    var levelsFromThis = $(this).parentsUntil('li.dragged').length;
                    if(levelsFromThis > itemLevel) {
                        itemLevel = levelsFromThis;
                    }
                }
            });
            //count number of children in the item which we are dropping
            var itemChildCount = $('li.dragged > ol').children().length;

            //get the number of items in the container in which we are dropping
            containerCount = container.items.length;

            //get the level container (parent0/child1/sub-child2)
            var containerLevel = $(container.target).parentsUntil('.nested_with_switch', 'li');
            containerLevel = containerLevel.length;
            if(containerLevel === 0){
                if(containerCount<3 && itemLevel<=4){
                    //it item has less than 5 children then + should be visible
                    if(itemChildCount<5){
                        $('li.dragged').find('.icon-plus').first().css('color','');
                    }
                    return true;
                }
            }
            if(containerLevel === 1){
                if(containerCount<5 && itemLevel<=2){
                    //it item has less than 5 children then + should be visible
                    if(itemChildCount<5){
                        $('li.dragged').find('.icon-plus').first().css('color','');
                    }
                    return true;
                }
            }
            if(containerLevel === 2){
                if(containerCount<5 && itemLevel<=0){
                    $('li.dragged').find('.icon-plus').first().css('color','white');
                    return true;
                }
            }

        }
    });

});
function refreshMenuData(){
    var countParent = $('.nested_with_switch').children().length;
    for(var i=1;i<=countParent;i++){
        var parentItemName =$('.nested_with_switch').children('li:nth-child('+i+')').find('input.menu_txt_input').first().val();
        var countChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children().length;
        for(var j=1;j<=countChild;j++){
            var childItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('input.menu_txt_input').first().val();
            var countSubChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children().length;
            for(var k=1;k<=countSubChild;k++){
                var subChildItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children('li:nth-child('+k+')').find('input.menu_txt_input').first().val();
            }
        }

    }
}
function saveMenuData(){
    //$('input.menu_txt_input').next().css('border','');
    $('input.menu_txt_input ~ div.emoji-wysiwyg-editor').css('border','');
    var jsonData = {};
    jsonData.locale = window.urlParams.lang;
    jsonData.disabled_surfaces = [];
    if($('#composer_input_disabled').prop('checked')==true){
    jsonData.composer_input_disabled = true;
    }
    else{
    jsonData.composer_input_disabled = false;
        if($('#customer_chat_plugin').prop('checked')==true){
        jsonData.disabled_surfaces = ["CUSTOMER_CHAT_PLUGIN"];
        }
    }

    jsonData.call_to_actions =[];
    var countParent = $('.nested_with_switch').children().length;
    for(var i=1;i<=countParent;i++){
        var parentItem =$('.nested_with_switch').children('li:nth-child('+i+')').find('input.menu_txt_input').first();
        var parentItemName =$('.nested_with_switch').children('li:nth-child('+i+')').find('input.menu_txt_input').first().val();
        var countChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children().length;
        if(parentItemName != ''){
            jsonData.call_to_actions[i-1] = {};
            jsonData.call_to_actions[i-1].title = parentItemName;
            if(countChild==0){
                if($(parentItem).attr('data-itemtype')){
                    jsonData.call_to_actions[i-1].type = $(parentItem).attr('data-itemtype');
                    if($(parentItem).attr('data-itemtype')=='postback'){
                        jsonData.call_to_actions[i-1].payload = $(parentItem).attr('data-payload');
                    }
                    if($(parentItem).attr('data-itemtype')=='web_url'){
                        jsonData.call_to_actions[i-1].url = $(parentItem).attr('data-url');
                        jsonData.call_to_actions[i-1].webview_height_ratio = $(parentItem).attr('data-webview_height_ratio');
                    }
                }
                else{
                    //$(parentItem).next().css('border','1px solid red');
                    parentItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
                    //enter a warning that there are no children for this menu and no item has been selected
                    toastr.error("Please specify at least one data type", "Error");
                    return false;
                }
            }
            else{
                jsonData.call_to_actions[i-1].type = 'nested';
                jsonData.call_to_actions[i-1].call_to_actions =[];
            }
        }
        else{
            //$(parentItem).next().css('border','1px solid red');
            parentItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
            //enter some notification this field is empty in title
            toastr.error("Please specify all title fields", "Error");
            return false;
        }
        for(var j=1;j<=countChild;j++){
            var childItem = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('input.menu_txt_input').first();
            var childItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('input.menu_txt_input').first().val();
            var countSubChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children().length;
            if(childItemName !=''){
                jsonData.call_to_actions[i-1].call_to_actions[j-1]={};
                jsonData.call_to_actions[i-1].call_to_actions[j-1].title = childItemName;
                if(countSubChild==0){
                    if($(childItem).attr('data-itemtype')){
                        jsonData.call_to_actions[i-1].call_to_actions[j-1].type = $(childItem).attr('data-itemtype');
                        if($(childItem).attr('data-itemtype')=='postback'){
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].payload = $(childItem).attr('data-payload');
                        }
                        if($(childItem).attr('data-itemtype')=='web_url'){
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].url = $(childItem).attr('data-url');
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].webview_height_ratio = $(childItem).attr('data-webview_height_ratio');
                        }
                    }
                    else{
                        //$(childItem).next().css('border','1px solid red');
                        childItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
                        //enter a warning that there are no children for this menu and no item has been selected
                        toastr.error("Please specify at least one data type", "Error");
                        return false;
                    }
                }
                else{
                    jsonData.call_to_actions[i-1].call_to_actions[j-1].type = 'nested';
                    jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions =[];
                }
            }
            else{
                //$(childItem).next().css('border','1px solid red');
                childItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
                //enter some notification this field is empty in title
                toastr.error("Please specify all title fields", "Error");
                return false;
            }
            for(var k=1;k<=countSubChild;k++){
                var subChildItem = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children('li:nth-child('+k+')').find('input.menu_txt_input').first();
                var subChildItemName = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children('li:nth-child('+j+')').find('ol').first().children('li:nth-child('+k+')').find('input.menu_txt_input').first().val();
                if(subChildItemName !=''){
                    jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1]={};
                    jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1].title = subChildItemName;
                    if($(subChildItem).attr('data-itemtype')){
                        jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1].type = $(subChildItem).attr('data-itemtype');
                        if($(subChildItem).attr('data-itemtype')=='postback'){
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1].payload = $(subChildItem).attr('data-payload');
                        }
                        if($(subChildItem).attr('data-itemtype')=='web_url'){
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1].url = $(subChildItem).attr('data-url');
                            jsonData.call_to_actions[i-1].call_to_actions[j-1].call_to_actions[k-1].webview_height_ratio = $(subChildItem).attr('data-webview_height_ratio');
                        }
                    }
                    else{
                        //$(subChildItem).next().css('border','1px solid red');
                        subChildItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
                        //enter a warning that there are no children for this menu and no item has been selected
                        toastr.error("Please specify at least one data type", "Error");
                        return false;
                    }
                }
                else{
                    //$(subChildItem).next().css('border','1px solid red');
                    subChildItem.siblings('div.emoji-wysiwyg-editor').css('border','1px solid red');
                    //enter some notification this field is empty in title
                    toastr.error("Please specify all title fields", "Error");
                    return false;
                }
            }
        }

    }
    jsonData = JSON.stringify(jsonData);
    return jsonData;
}
function resetMenuPreview(){
    jQuery('#sub-sub-menu-page').hide();
    jQuery('#sub-menu-page').hide();
    jQuery('.menu-text').removeClass('preview-has-children');
    jQuery('.menu-text.preview_menu_item').html('');
    var countParent = $('.nested_with_switch').children().length;
    if(countParent<3){
        $('.add_parent_menu').css('display', '');
    }
    if(countParent==3){
        $('.add_parent_menu').css('display', 'none');
    }
    for(var i=1;i<=countParent;i++) {
        var parentItemName = $('.nested_with_switch').children('li:nth-child(' + i + ')').find('input.menu_txt_input').first().val();
        var countChild = $('.nested_with_switch').children('li:nth-child('+i+')').find('ol').first().children().length;
        var selector = '#main_menu'+i;
        jQuery(selector).html(getImage(parentItemName));
        if(countChild>0){
            jQuery(selector).addClass('preview-has-children');
        }
    }
    window.emojiPicker.discover();
    jQuery('#main-menu-page').show();
}
function preloadMenuData(){
    $.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Loading data...</span>',
        overlayCSS: {opacity: .5}
    });
    var locale = window.urlParams.lang;
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'preload_persistent_menu',
        'locale':locale
    };
    jQuery.post(ajax_url, data, function(res) {
        var obj = JSON.parse(res);
        if(obj[0].flowdata == '{}' || obj[0].flowdata ==null){
            globalMsgData = {};
        }
        else{
            globalMsgData = JSON.parse(obj[0].flowdata);
        }
        var menuData = JSON.parse(obj[0].call_to_actions);
        var status = obj[0].status;
        var composer_input_disabled = menuData.composer_input_disabled;
        var customer_chat_plugin = menuData.customer_chat_plugin;
        if(status == '1'){
            $('#main_menu_check').prop('checked', true);
        }
        if(composer_input_disabled == true){
            $('#composer_input_disabled').prop('checked', true);
            $('.customerChatToggleDiv').css('display','none');
        }
        if(jQuery.inArray("CUSTOMER_CHAT_PLUGIN", customer_chat_plugin) != -1){
            $('#customer_chat_plugin').prop('checked', true);
        }
        if(menuData.call_to_actions.length < 3){
            $('.add_parent_menu').css('display','');
        }
        else{
            $('.add_parent_menu').css('display','none');
        }
        var htmlData = '';
        var dataattr ='';
        for(var i=0;i<=menuData.call_to_actions.length-1;i++){
            dataattr='';
            var whitePlus='';
            var type = menuData.call_to_actions[i].type;
            var draghandlerIcon = 'icon-menu';
            if(type=='web_url'){
                dataattr = 'data-itemtype="web_url" data-url="'+menuData.call_to_actions[i].url+'" data-webview_height_ratio="'+menuData.call_to_actions[i].webview_height_ratio+'"';
                draghandlerIcon = 'icon-link';
            }
            if(type=='postback'){
                dataattr = 'data-itemtype="postback" data-payload="'+menuData.call_to_actions[i].payload+'"';
                draghandlerIcon = 'icon-bubble';
            }
            if(type == 'nested') {
                if (menuData.call_to_actions[i].call_to_actions.length == 5) {
                    var whitePlus = 'style="color:white;"';
                }
            }
            htmlData = htmlData+'<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="font-size: 14px;width: 20px;height: 60px;"><i class="fa '+draghandlerIcon+' menu-icon" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" data-emojiable="true" data-charcounter="true" placeholder="Enter text here (max. 30 characters)" value="'+menuData.call_to_actions[i].title+'" size="30" maxlength="30" '+dataattr+' style="height: 60px;z-index:0;"><span class="input-group-addon input-lg settings-handle" style="padding: 0;"><a class="hoveraddon" style="display: flex;padding: 20px 16px;"><i class="fa icon-cog"></i></a><a class="hoveraddon add_menu_list_item" style="margin-top: -31px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-plus" '+whitePlus+'></i></a><a class="hoveraddon add_menu_weblink" style="margin-top: -117px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-link"></i></a><a class="hoveraddon add_menu_message" style="margin-top: -119px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-bubble "></i></a></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol>';
                if(type == 'nested') {
                    for (var j = 0; j <= menuData.call_to_actions[i].call_to_actions.length - 1; j++) {
                        dataattr='';
                        var whitePlus='';
                        var type = menuData.call_to_actions[i].call_to_actions[j].type;
                        var draghandlerIcon = 'icon-menu';
                        if(type=='web_url'){
                            dataattr = 'data-itemtype="web_url" data-url="'+menuData.call_to_actions[i].call_to_actions[j].url+'" data-webview_height_ratio="'+menuData.call_to_actions[i].call_to_actions[j].webview_height_ratio+'"';
                            var draghandlerIcon = 'icon-link';
                        }
                        if(type=='postback'){
                            dataattr = 'data-itemtype="postback" data-payload="'+menuData.call_to_actions[i].call_to_actions[j].payload+'"';
                            var draghandlerIcon = 'icon-bubble';
                        }
                        if(type == 'nested'){
                            if(menuData.call_to_actions[i].call_to_actions[j].call_to_actions.length == 5){
                                var whitePlus = 'style="color:white;"';
                            }
                        }

                        htmlData = htmlData+'<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="width: 20px;height: 60px;"><i class="fa '+draghandlerIcon+' menu-icon" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" data-emojiable="true" data-charcounter="true" placeholder="Enter text here (max. 30 characters)" value="'+menuData.call_to_actions[i].call_to_actions[j].title+'" size="30" maxlength="30" '+dataattr+' style="height: 60px;z-index:0;"><span class="input-group-addon input-lg settings-handle" style="padding: 0;"><a class="hoveraddon" style="display: flex;padding: 20px 16px;"><i class="fa icon-cog"></i></a><a class="hoveraddon add_menu_list_item" style="margin-top: -31px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-plus" '+whitePlus+'></i></a><a class="hoveraddon add_menu_weblink" style="margin-top: -117px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-link"></i></a><a class="hoveraddon add_menu_message" style="margin-top: -119px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-bubble"></i></a></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol>';
                        if(type == 'nested') {
                            for (var k = 0; k <= menuData.call_to_actions[i].call_to_actions[j].call_to_actions.length - 1; k++) {
                                dataattr ='';
                                var type = menuData.call_to_actions[i].call_to_actions[j].call_to_actions[k].type;
                                if(type=='web_url'){
                                    dataattr = 'data-itemtype="web_url" data-url="'+menuData.call_to_actions[i].call_to_actions[j].call_to_actions[k].url+'" data-webview_height_ratio="'+menuData.call_to_actions[i].call_to_actions[j].call_to_actions[k].webview_height_ratio+'"';
                                    var draghandlerIcon = 'icon-link';
                                }
                                if(type=='postback'){
                                    dataattr = 'data-itemtype="postback" data-payload="'+menuData.call_to_actions[i].call_to_actions[j].call_to_actions[k].payload+'"';
                                    var draghandlerIcon = 'icon-bubble';
                                }
                                htmlData = htmlData+'<li><div class="input-group"><span class="input-group-addon input-lg draghandle" style="width: 20px;height: 60px;"><i class="fa '+draghandlerIcon+' menu-icon" aria-hidden="true"></i></span><input class="form-control input-lg menu_txt_input input_dropdown " type="text" data-emojiable="true" data-charcounter="true" placeholder="Enter text here (max. 30 characters)" value="'+menuData.call_to_actions[i].call_to_actions[j].call_to_actions[k].title+'" size="30" maxlength="30" '+dataattr+' style="height: 60px;z-index:0;"><span class="input-group-addon input-lg settings-handle" style="padding: 0;"><a class="hoveraddon" style="display: flex;padding: 20px 16px;"><i class="fa icon-cog"></i></a><a class="hoveraddon add_menu_list_item" style="margin-top: -31px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-plus" style="color:white;"></i></a><a class="hoveraddon add_menu_weblink" style="margin-top: -117px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-link"></i></a><a class="hoveraddon add_menu_message" style="margin-top: -119px;background-color: #fff;border: 1px solid #f5f6f9;"><i class="fa icon-bubble"></i></a></span><span class="input-group-addon input-lg delete_menu_list_item"><i class="fa icon-cross"></i></span></div><ol>';
                                htmlData = htmlData + '</ol></li>';
                            }
                        }
                        htmlData = htmlData + '</ol></li>';
                    }
                }
            htmlData = htmlData + '</ol></li>';
        }
        $('.nested_with_switch').html(htmlData);
        resetMenuPreview();
        resetFlowCardsVisual();
    });

    $.unblockUI();
}
function MainMenuOnOff(ThisAction){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'set_persistent_menu_on_off',
        'status':ThisAction ,
        'locale': window.urlParams.lang
    };
    jQuery.post(ajax_url, data, function(res) {
    });
}

function resetWeblinkModal(){
    $('#button_link').css('border','');
    $('#button_link').val('');
    $('#webview_height_ratio').val('full');
}
function resetFlowSelectorModal(){
$('#select_reference_type').css('border','');
$('#select_reference_type').val('select').trigger('change');
}
function getImage(input) {

    if(!input)

        return "";

    if(!Config.rx_codes)

        Config.init_unified();


    return input.replace(Config.rx_codes, function(m)

    {

        var val = Config.reversemap[m];


        if (val) {

            val = ":" + val + ":";

            return $.emojiarea.createIcon($.emojiarea.icons[val]);

        }

        else

            return "";

    });



}

function resetFlowCardsVisual(){
    if(globalFlowMsgData==undefined){
        var ajax_url='includes/admin-ajax.php';
        var data = {'action': 'get_all_cards_data'
        };
        jQuery.post(ajax_url, data, function(res) {
            res = JSON.parse(res);
            globalFlowMsgData = res;
            applyFlowCardsStructure(res);
        });

    }
    else{
        applyFlowCardsStructure(globalFlowMsgData);
    }
}

function applyFlowCardsStructure(res){
    $('div').remove('.link-flow-cards-name');
    $('input.menu_txt_input').each(function(){
        var itemType = $(this).attr('data-itemtype');
        if(itemType=='postback'){
            var payload = $(this).attr('data-payload');
            if(payload!=undefined){
                payload = payload.toString();
                if(payload.indexOf(':')>=0){
                    var arr =  payload.split(':');
                    var flowId = arr[0];
                    var cardId = arr[1];
                    if(typeof res[flowId] !=='undefined' && typeof res[flowId]['name'] !== 'undefined' && typeof res[flowId]['card_id_'+cardId] !== 'undefined') {
                        var flowName = res[flowId]['name'];
                        var cardName = res[flowId]['card_id_' + cardId];
                        $(this).before('<div class="link-flow-cards-name show-flow-msg-onhover" data-type="card" data-flowid="'+flowId+'" data-cardid="'+cardId+'" data-toggle="tooltip" data-placement="top" data-original-title="' + flowName + ' ➜ ' + cardName + '">' + flowName + ' ➜ ' + cardName + '</div>');
                    }
                }
                else{
                    var flowId = payload;
                    if(typeof res[flowId] !=='undefined' && typeof res[flowId]['name'] !== 'undefined') {
                        var flowName = res[flowId]['name'];
                        $(this).before('<div class="link-flow-cards-name show-flow-msg-onhover" data-type="flow" data-flowid="'+flowId+'" data-toggle="tooltip" data-placement="top" data-original-title="' + flowName + '">' + flowName + '</div>');
                    }
                }
            }
        }
        else if(itemType == 'web_url'){
            var link = $(this).attr('data-url');
            if(link!=undefined){
                link = link.toString();
                $(this).before('<div class="link-flow-cards-name" data-toggle="tooltip" data-placement="top" data-original-title="'+link+'">'+link+'</div>');
            }
        }

    });
    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });
}