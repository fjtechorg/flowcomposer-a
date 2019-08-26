//global variables
var open_profiles_data,closed_profiles_data,open_profiles_index,closed_profiles_index,
    search_type,search_result,search_result_index,active_profile_index,
    page_load_first_profile, status_change_profile_id;

jQuery(document).ready(function(){
    //Call Functions on pageload here
    retrieveChatProfileList();
    checkLivechatWarningMsg();

});

//Event Handlers Start Here

$(document).on('click', '#action_important_livechat_dontshow_again', function (e){
    e.preventDefault();
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'new_livechat_optout'};
    jQuery.post(ajax_url, data, function(response) {
    });
});
$(window).resize(function() {
    inititalizeSlimScroll();
});

//search Subs
/*$('#searchSubClick').on('click', function(){
    searchLivechatSubs();
});
*/
$('#searchSubEnter').keyup(function (e) {
    searchLivechatSubs();
});

//Open Chat Profiles tab clicked
$('#tab-1-button').on('click',function(){
    var status = $(this).hasClass('active');
    if(status){
        return false;
    }
    $('#searchSubEnter').val('');
    if(search_type === 1){
        search_type = undefined;
        search_result = undefined;
        search_result_index = undefined;
        resetOpenChatProfiles();
    }
});

//Closed Chat Profiles tab clicked
$('#tab-2-button').on('click',function(){
    var status = $(this).hasClass('active');
    if(status){
        return false;
    }
    $('#searchSubEnter').val('');
    if(search_type === 0){
        search_type = undefined;
        search_result = undefined;
        search_result_index = undefined;
        resetClosedChatProfiles();
    }
});

function pollNewChats(){
    if(typeof(EventSource) !== "undefined") {

        var source = new EventSource("endpoint/chat_stream.php?profile_id="+active_profile_index);

        source.addEventListener('message', function() {

            //ok we are having an issue with the getting of the last msg id at times...if it is undefined it fetches everything so lets check first if we have a last msg id
            var last_msg_id = $('.chat-message:not(.last_send)').last().attr("id");

            //just for fun in case jquery is not getting it...are we able to do it an other way
            var lastMsg = document.getElementsByClassName("chat-message");
            //var lastMsgID = //find the length opf the above and get the last out of it and then use that id..first see if this works
            if(typeof lastMsg !=="undefined"){
                //we have to find the last one that holds an id.
            }

            if(last_msg_id > 0){

                var ajax_url='includes/admin-ajax.php';

                var data = {'action': 'update_live_chat',


                    'profile_index': active_profile_index,

                    'last_msg_id' : last_msg_id


                };


                jQuery.post(ajax_url, data, function(response) {

                    response = response.trim();


                    if (response.length>0) {

                        jQuery('.last_send').remove();
                        jQuery('.chat-discussion').append(response);

                        slimScrollResetPosition();

                        $(".chat_view_text, .broadcast_preview_text").each(function (index) {

                            if (!$(this).hasClass("converted")) {

                                $(this).html(getImage($(this).html()));

                                $(this).addClass("converted");

                                $(this).show();

                            }

                        });



                        Plyr.setup((".audio-message-preview-container audio"), {
                            controls: ['play','progress', 'current-time' ]
                        });
                        Plyr.setup((".video-message-preview-container video"), {
                            controls: ['play-large']
                        });
                        $(".unrendered").removeClass("unrendered");

                    }

                });



            }

            $(".contact_active .label-info.pull-right").hide();

        }, false);

    }

}

//Any Profile is Clicked
$(document).on('click', '.live_chat', function (e){
    e.preventDefault();
    var profileName = $(this).data("profile_name");
    var profileIndexId = $(this).data("profile_id");
    var currentStatus = $(this).data("profile_status");
    if(active_profile_index === profileIndexId){
        return false;
    }
    //$('.chat_profile_name').html(profileName);
    $('.table-col-title').html(profileName);
    active_profile_index = profileIndexId;
    $('.scroll_content_livechat2').slimScroll({scrollTo: '0px'});

    window.stopPrevious = false;
    pollNewChats();
    $(".contact_active").removeClass("contact_active");
    $(this).addClass("contact_active");
    changeProfileStatusButton(currentStatus);
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'load_livechat_profile_data',
        'profile_index_id': profileIndexId
    };
    jQuery.post(ajax_url, data, function(response) {
        response = JSON.parse(response);
        var profileId = response.profileId;
        $('#form_profile_id').val(profileId);
        getProfileDetailsBlock(profileId);
        $('#chat-discussion-area').html(response.messages);
        $(".chat_view_text, .broadcast_preview_text").each(function () {
            if (!$(this).hasClass("converted")) {
                $(this).html(getImage($(this).html()));
                $(this).addClass("converted");
                $(this).show();
            }
        });
        Plyr.setup((".audio-message-preview-container audio"), {
            controls: ['play','progress', 'current-time' ]
        });
        Plyr.setup((".video-message-preview-container video"), {
            controls: ['play-large']
        });

        $(".unrendered").removeClass("unrendered");
        if(response.subscribe && response.status!== ''){
            $('#inactive_alert').remove();
            $('#chat_reply_area').prepend(response.status);
        }
        slimScrollResetPosition();
    });
    conversationDivSwitchCss('on');
});

$('[data-toggle="tooltip"]').tooltip({
    'container': 'body'
});

$(document).on('mouseenter', '.message-content', function () {
    if ($(this).attr('data-toggle')=== 'tooltip')
    {
        $(this).tooltip({
            container: 'body',
            placement: 'right',
            trigger: 'hover'
        }).tooltip('show');
    }
});

$(document).on("keypress",$('.msg_text'), function(e) {
    if(e.which === 13 && !e.shiftKey && $(e.target).is('.msg_text')) {
        slimScrollResetPosition();
        e.preventDefault();
        sendMessageText();
    }
    else if (e.which===13 && e.shiftKey && $(e.target).is('.msg_text')){
        var currentVAL  = $('#msg_text').val();
        $('#msg_text').val(currentVAL+ "\\n" );
    }
});

//close all conversations
$(document).on('click', '#livechat_closeall',function(){
    conversationDivSwitchCss('off');
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'livechat_close_all'
    };
    jQuery.post(ajax_url, data, function() {
        var accordion2=$('#accordion2');
        accordion2.toggle("slide", {direction:'right'}, function(){
            toastr.success("All conversations have been closed.","Success");
            retrieveChatProfileList();
        });

    });
});

//open all conversations
$(document).on('click', '#livechat_openall',function(){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'livechat_open_all'
    };
    jQuery.post(ajax_url, data, function() {
        var accordion2=$('#accordion2_closed');
        accordion2.toggle("slide", {direction:'left'}, function(){
            toastr.success("All chats opened","Success");
            page_load_first_profile = undefined;
            retrieveChatProfileList();
            conversationDivSwitchCss('on');
        });
    });
});

//open/close conversation
$(document).on('click','#chat_status_control',function(){
    if(active_profile_index === undefined){
        return false;
    }
    else{
        var profileIndexId = active_profile_index;
        active_profile_index = undefined;

        var currentStatus = $('#chat_status_control').attr('data-chat_status');
        if(currentStatus === "1"){
            //new status is closed
            var newStatus = 0;
            conversationDivSwitchCss('off');
        }
        else{
            //new status is open
            var newStatus = 1;
            status_change_profile_id = profileIndexId;
        }
        changeChatProfileStatus(profileIndexId,newStatus);
    }
});

$(document).on('click', '.quick_next', function () {
    var MsgID = $(this).data("msg_id");
    GoSlides('next','quick',MsgID);
});

$(document).on('click', '.quick_previous', function () {
    var MsgID = $(this).data("msg_id");
    GoSlides('previous','quick',MsgID);
});


$(document).on('click','#chat_send_flow_msg', function(){
    resetFlowSelectorModal();
    $('#persistent_menu_flow_selector').modal();
});

$(document).on('click', '.send_menu_message', function (){
    if($('#select_reference_type').val() == 'select'){
        toastr.error("Please specify a message type", "Error");
        $('#select_reference_type').css('border','1px solid red');
        return false;
    }
    else if($('#select_reference_type').val() == 'flow'){
        var data = {};
        data.type = 'flow';
        data.flowId = $('#select_reference_flow').val();
        sendFlowData(data);
        $('#persistent_menu_flow_selector').modal('toggle');
    }
    else if($('#select_reference_type').val() == 'flowcard'){
        var data = {};
        data.type = 'flowcard';
        data.flowId = $('#select_reference_flow').val();
        data.msgId = $('#select_reference_flow_card').val();
        sendFlowData(data);
        $('#persistent_menu_flow_selector').modal('toggle');
    }
});

function reAdjustScroll(){

    $('.scroll_chat_content').slimscroll({
        start : 'top',

        height: 'auto'

    });
    var scroll2height = $('.fullheight').height() - 97 /*61*/;
    $('.scroll_chat_content2').slimscroll({
        start : 'bottom',
        height: scroll2height
    });

}

//Functions Start Here
function sendFlowData(data){
    var jsonData = JSON.stringify(data);
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'send_livechat_flow_msg',
        'jsonData': jsonData,
        'profileIndexId': active_profile_index
    };
    jQuery.post(ajax_url, data, function (res) {
        toastr.options.closeDuration = 2000;
        toastr.success("Selected flow/card is scheduled for dispatching, messages on your selected flow will be visible on the live chat area as soon as they are delivered to your subscriber.","Dispatching...");
    });

}
function resetFlowSelectorModal(){
    $('#select_reference_type').css('border','');
    $('#select_reference_type').val('select').trigger("change");
}

//Reset the position of the scroll in slimscroll divs
function slimScrollResetPosition(){
    setTimeout(function() {
        $('.scroll_chat_content2').slimScroll({scrollTo: '80000px'});
        //$('.scroll_content_livechat2').slimScroll({scrollTo: '0px'});
    },500);
}

//chat div Switch on or off
function conversationDivSwitchCss(status){
    if(status == 'on'){
        $('.chat_area').css('display','');
        $('.profile-details-area').css('display','');
        $('.no-active-chat').css('display','none');
    }
    if(status == 'off'){
        $('.chat_area').css('display','none');
        $('.profile-details-area').css('display','none');
        $('.no-active-chat').css('display','');
    }
}

//Send Msg
function sendMessageText() {
    var ThisMsgSelector = jQuery('#msg_text');
    var ThisMsg = ThisMsgSelector.val();
    if (ThisMsg.trim().length===0) return;
    var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter2 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter3 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var PostId = randLetter + randLetter2 + randLetter3 + Date.now();
    var tmpMsg = ThisMsg.replace(/(?:\r\n|\r|\n)/g, '<br />');
    $('.chat-discussion').append('<div class="chat-message last_send"><div class="message-right"><span class="message-content nowrap" data-toggle="tooltip" data-placement="right" title="" data-original-title=""><div class="chat_view_text">' + tmpMsg + '</div></span></div></div><div style="clear:both;"><a name="' + PostId + '" id="' + PostId + '"> </a></div>');
    slimScrollResetPosition();
    ThisMsgSelector.val('');
    jQuery('.msg_text').html('');
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'send_livechat_msg',
        'msg_text': ThisMsg,
        'profileIndexId': active_profile_index
    };

    jQuery.post(ajax_url, data, function (response) {
        if(response!==""){

        }
    });
    $(".chat_view_text").each(function (index) {
        if (!$(this).hasClass("converted")) {
            $(this).html(getImage($(this).html()));
            $(this).addClass("converted");
            $(this).show();
        }
    });
}

//check if there are any open or closed conversations and set the msg in the tab content area accordingly
function emptySubListChecker(){
    var openConvs = $('#accordion2 > .live_chat').length;
    var closedConvs = $('#accordion2_closed > .live_chat').length;
    if (openConvs===0) {
        $('#accordion2').html("<div id='no_open_chats' style='padding:30px;text-align: center;'><b style='color: #A7B1C2;'>You do not have any open conversations</b></div>");
    }
    else {
        $('#no_open_chats').remove();
    }
    if (closedConvs===0) {
        $('#accordion2_closed').html("<div id='no_closed_chats' style='padding:30px;text-align: center;'><b style='color: #A7B1C2;'>You do not have any closed conversations</b></div>");
    }
    else {
        $('#no_closed_chats').remove();
    }
    $('#accordion2').css('display','');
    $('#accordion2_closed').css('display','');
}

//check if warning msg should display or not
function checkLivechatWarningMsg(){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'livechat_check_warning_message'};
    jQuery.post(ajax_url, data, function(response) {
        if(response === "yes"){
            jQuery('#important_about_livechat_alert').modal();
        }
    });
}

//Slimscroll initialization function - also includes the
function inititalizeSlimScroll(){
    //subscribers list scroll
    var navbarHeight = 62;//height of navbar
    var navTabsHeight = 42;// height of open and close tab buttons div
    var searchContainerHeight = 48; //height of search field
    var windowHeight = window.innerHeight;// windowheight
    var subscribersDivHeight = (windowHeight - (navbarHeight+navTabsHeight+searchContainerHeight+1))+'px';
    $('.scroll_content_livechat').slimScroll({
        height: subscribersDivHeight
    }).bind('slimscroll', function(e, pos){
        if(pos === 'bottom') {
            infinityScrollChatProfile();
        }
    });
    var profileTitleDiv = 43;
    var profileDetailsHeight = (windowHeight - (navbarHeight+profileTitleDiv+1))+'px';
    $('.scroll_content_livechat2').slimScroll({
        start : 'top',
        height: profileDetailsHeight
    });

    var chatReplyBox = 76;
    var subNameBar = 42;
    var chatHistoryDivHeight = (windowHeight - (navbarHeight+subNameBar+chatReplyBox))+'px';

    // Scroll up to load more messages
    window.stopPrevious = false ;
    $('.scroll_chat_content2').slimScroll({
        start : 'bottom',
        height: chatHistoryDivHeight
    }).bind('slimscroll', function(e, pos){
        if(pos === "top" && window.stopPrevious === false) {
            if(active_profile_index === undefined){
                return false;
            }
            var ajax_url='includes/admin-ajax.php';
            var data = {'action': 'load_livechat_profile_previous_msgs',
                'profile_index_id': active_profile_index,
                'last_msg_id' : $('.chat-message').first().attr("id")
            };


            var before = $(".scroll_chat_content2").prop("scrollHeight");
            jQuery.post(ajax_url, data, function(response) {
                response = response.trim();
                if (response.length>0) {
                    jQuery('.chat-discussion').prepend(response);
                    $(".chat_view_text, .broadcast_preview_text").each(function () {

                        if (!$(this).hasClass("converted")) {
                            $(this).html(getImage($(this).html()));
                            $(this).addClass("converted");
                            $(this).show();
                        }

                    });
                    var after = $('.scroll_chat_content2').prop("scrollHeight");
                    $('.scroll_chat_content2').slimscroll({ scrollTo: after - before });

                    Plyr.setup((".audio-message-preview-container audio"), {
                        controls: ['play','progress', 'current-time' ]
                    });
                    Plyr.setup((".video-message-preview-container video"), {
                        controls: ['play-large']
                    });
                    $(".unrendered").removeClass("unrendered");
                }
                else {
                    window.stopPrevious = true;
                }
            });
        }
    });
    $('.no-active-chat').css('height',windowHeight-navbarHeight);
}

//inifinity scroll function
function infinityScrollChatProfile(){
    //check which tab is active
    if($('#tab-1-button').hasClass('active')){
        //scroll end on open tab
        //check if search
        if(search_type !== undefined && search_type === 1){
            var result = buildChatProfileHtml(search_result_index,search_result);
            if(result.html !== undefined) {
                search_result_index = result.newindex;
                jQuery('#accordion2').append(result.html);
            }
        }
        else{
            var result = buildChatProfileHtml(open_profiles_index,open_profiles_data);
            if(result.html !== undefined) {
                open_profiles_index = result.newindex;
                jQuery('#accordion2').append(result.html);
            }
        }
    }
    else if($('#tab-2-button').hasClass('active')){
        //check if search
        if(search_type !== undefined && search_type === 0){
            var result = buildChatProfileHtml(search_result_index,search_result);
            if(result.html !== undefined) {
                search_result_index = result.newindex;
                jQuery('#accordion2_closed').append(result.html);
            }
        }
        else{
            var result = buildChatProfileHtml(closed_profiles_index,closed_profiles_data);
            if(result.html !== undefined) {
                closed_profiles_index = result.newindex;
                jQuery('#accordion2_closed').append(result.html);
            }
        }
    }
}

//retrieve chat profiles list in open and closed chats
function retrieveChatProfileList(){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'get_chat_profiles_list'};
    active_profile_index = undefined;
    open_profiles_index = undefined;
    closed_profiles_index = undefined;
    open_profiles_data = undefined;
    closed_profiles_data = undefined;
    jQuery.post(ajax_url, data, function(res) {
        res = JSON.parse(res);
        var count = res.length;
        open_profiles_data =res.open;
        closed_profiles_data =res.closed;
        jQuery('#accordion2').html('');
        jQuery('#accordion2_closed').html('');
        var result = buildChatProfileHtml(0,open_profiles_data);
        if(result.html !== undefined) {
            var html = result.html;
            open_profiles_index = result.newindex;
            $('#accordion2').html(html);
            emptySubListChecker();
        }
        var result2 = buildChatProfileHtml(0,closed_profiles_data);
        if(result2.html !== undefined) {
            var html2 = result2.html;
            closed_profiles_index = result2.newindex;
            $('#accordion2_closed').html(html2);
            emptySubListChecker();
        }

        if(page_load_first_profile === undefined){
            if($('#accordion2 > .live_chat').length>0){
                //load the first chat if there are any open chats
                $('#accordion2 > .live_chat').first().trigger('click');
            }
            else{
                conversationDivSwitchCss('off');
            }
            page_load_first_profile = true;
        }
        if(status_change_profile_id !== undefined){
            active_profile_index = status_change_profile_id;
            $('.scroll_content_livechat2').slimScroll({scrollTo: '0px'});

            status_change_profile_id=undefined;
            $('#'+active_profile_index).addClass('contact_active');
        }
        countOpenClosedChats();
        inititalizeSlimScroll();
    });
}

function resetOpenChatProfiles(){
    open_profiles_index = 0;
    var result = buildChatProfileHtml(open_profiles_index,open_profiles_data);
    if(result.html != undefined) {
        var html = result.html;
        open_profiles_index = result.newindex;
        jQuery('#accordion2').html(html);
    }
}

function resetClosedChatProfiles(){
    closed_profiles_index = 0;
    var result = buildChatProfileHtml(closed_profiles_index,closed_profiles_data);
    if(result.html != undefined) {
        var html = result.html;
        closed_profiles_index = result.newindex;
        jQuery('#accordion2_closed').html(html);
    }
}

function changeProfileStatusButton(newStatus){
    $('#chat_status_control').attr('data-chat_status',newStatus);
    if(newStatus==1){
        $('#chat_status_control').html('<i class="fa icon-cross"></i>');

    }
    else{
        $('#chat_status_control').html('<i class="fa icon-launch"></i>');
    }
}

function changeChatProfileStatus(profileIndexId,newStatus){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'set_livechat_profile_status',
    'profile_index_id': profileIndexId,
    'status': newStatus};
    jQuery.post(ajax_url, data, function() {
        retrieveChatProfileList();
        changeProfileStatusButton(newStatus);
    });
}

//build chat profile html for given profile data array of arrays and index i.e from where to start the loop
function buildChatProfileHtml(index,dataArray){
    var totalcount = dataArray.length;
    var returnval = {html:'',newindex:0};
    var count = totalcount - index;
    if(count <= 0){
        return false;
    }
    else if(count<15){
        var limit = totalcount;
    }
    else{
        var limit = index+15;
    }
    for(var i=index;i<limit;i++){
        var id = dataArray[i][0];
        var name = dataArray[i][1];
        var pic = dataArray[i][2];
        var status = dataArray[i][3];
        var unread = dataArray[i][4];
        if(unread==0){
            var unread_style = 'display:none;';
        }
        else{
            var unread_style = '';
        }
        returnval.html = returnval.html+'<div class="panel panel-default chat_panel_profile live_chat" id="'+id+'" data-profile_id="'+id+'" data-profile_status="'+status+'" data-profile_name="'+name+'"><div class="panel-heading" style="padding:5px 10px;"><img src="'+pic+'" class="img-circle" width="50px" height="50px" style="margin:10px 10px 10px 0;float:left;"><h5 style="margin-top:10px;line-height:30px; float:left;">'+name+'</h5><span class="label label-info pull-right" style="'+unread_style+'">'+unread+'</span><br style="clear:both;" /></div></div>';
    }
    returnval.newindex=limit;
    return returnval;
}

//count open and closed
function countOpenClosedChats(){
    var openCount = 'Open ('+open_profiles_data.length+')';
    $('#profiles_count').html(openCount);
    var closedCount = 'Closed ('+closed_profiles_data.length+')';
    $('#profiles_count_2').html(closedCount);
}

function checkString(value) {
    var checkstr = value[1].toLowerCase();
    return checkstr.includes(this);
}

//search subs function
function searchLivechatSubs(){
    var searchString = $('#searchSubEnter').val();
    var dataset = [];
    if(searchString != '') {
        if($('#tab-1-button').hasClass('active') == true){
            var status = 1;
            search_type = 1;
            dataset = open_profiles_data;
        }
        if($('#tab-2-button').hasClass('active') == true){
            var status = 0;
            search_type = 0;
            dataset = closed_profiles_data;
        }
        searchString = searchString.toLowerCase();
        search_result = dataset.filter(checkString,searchString);
        var html = '';
        if(search_result.length>0){
            search_result_index = 0;
            var result = buildChatProfileHtml(search_result_index,search_result);
            if(result.html != undefined) {
                html = result.html;
                search_result_index = result.newindex;
            }
        }
        if(status===0) {
            jQuery('#accordion2_closed').html(html);
        }
        else {
            jQuery('#accordion2').html(html);
        }
    }
    else{
        if($('#tab-1-button').hasClass('active') == true){
            resetOpenChatProfiles();
        }
        else if($('#tab-2-button').hasClass('active') == true){
            resetClosedChatProfiles();
        }
    }
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
        else {
            return "";
        }
    });
}

function GoSlides(SlideDirection,SlideType,MsgID){

    var currentSlide = Number($('#'+MsgID+'_currentSlide').val());
    var NumSlides = Number($('#'+MsgID+'_numSlides').val()); var maxClicksNext=NumSlides;
    var MarginLeft,NewSlide,NextNewCount ='';
    var AreaWidth = jQuery('#chat-discussion-area').width();
    var ThisWidth = jQuery('#'+MsgID+'_broadcast_preview').width();
    //if the width of the quick div is bigger then the width of the area then we show the previous button else there is no need as we show everything already

    //NOW IF WE SLIDE WE SHOULD NOT GO FURTHER THEN THE DIFFERENCE BETWEEN THE WIDTH OF THE AREA AND THE WIDTH OF THE QUICK REPLY MSG. For the width of the area though we only get 75% at first so we need to take that in account of how much is hidden of the message
    // so we get a formula that is like this...hidden part of the quick reply is ThisWidth - (AreaWidth * 75%) ..and maybe add just 25 px more to have the last button move away from the next button
    // this should be a negative as we are comparing the value with the increasing MargingLeft which is a negative as well to make it move to the left more and more

    var maxSlideWidth = ((ThisWidth - (AreaWidth * 0.75))+25) * -1; //see above for the explenation of this formula
    //ok now we have the max slding with and based on that we can calulate how many clicks are needed to show this, as we are redirected to 0 when we reach the max and we want that after we have reached the max forst
    if(SlideType==="quick"){NextNewCount=((maxSlideWidth/70) * -1)+1; maxClicksNext = Math.ceil(NextNewCount);}else{NextNewCount=(maxSlideWidth/200) * -1;maxClicksNext = Math.ceil(NextNewCount);}

    if(SlideDirection==="next"){
        if(currentSlide===maxClicksNext){
            currentSlide = 0; //if we reach the max number of cards and a new next is clicked we loop back to #1. 0 * -200 = 0 thus we have a margin left of 0
            if(SlideType==="quick"){jQuery('#quick_previous_'+MsgID).css('opacity', '0');}
        }else{

            if(ThisWidth > AreaWidth){if(SlideType==="quick"){jQuery('#quick_previous_'+MsgID).css('opacity', '1');}}

        }
        NewSlide = currentSlide + 1;
        if(SlideType!=="quick"){MarginLeft = currentSlide * -200; if (MarginLeft < maxSlideWidth){MarginLeft = maxSlideWidth;}}else{MarginLeft = currentSlide * -70; if (MarginLeft < maxSlideWidth){MarginLeft = maxSlideWidth;}}
    }


    if(SlideDirection==="previous"){
        if(currentSlide>0){NewSlide = currentSlide - 1;}else{NewSlide=0;}
        if(NewSlide > 0){ //only do a previous if we are at #2 or higher
            if(SlideType!=="quick"){MarginLeft =( NewSlide - 1) * -200;}else{MarginLeft = ( NewSlide - 1) * -70;}
            if(SlideType==="quick"){if(NewSlide<2){jQuery('#quick_previous_'+MsgID).css('opacity', '0');}}
        }
    }

    $('#'+MsgID+'_currentSlide').val(NewSlide);
    if(SlideType==="carousel"){$(".carouselslides").animate({'marginLeft': MarginLeft}, 500);}
    if(SlideType==="product"){$(".productslides").animate({'marginLeft': MarginLeft}, 500);}
    if(SlideType==="quick"){$("#"+MsgID+"_broadcast_preview").animate({'marginLeft': MarginLeft}, 500);}
}