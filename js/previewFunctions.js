$(document).ready(function(){


$(document).on('click', '.quick_next', function () {

    let previewContainer = $(this).next("#quickreplies_preview");
    let marginLeft = parseInt(previewContainer.css("margin-left")) - 50;
    previewContainer.css("margin-left",marginLeft+"px");
    if (marginLeft <= -50)
        $(this).prev(".quick_previous").css("visibility","visible").css("display","block");
    else if (marginLeft === 0)
        $(this).prev(".quick_previous").css("visibility","hidden");


});

$(document).on('click', '.quick_previous', function () {
    let previewContainer = $(this).parent().find("#quickreplies_preview");
    let marginLeft = parseInt(previewContainer.css("margin-left")) + 50;
    previewContainer.css("margin-left",marginLeft+"px");
    if (marginLeft === 0)
        $(this).css("visibility","hidden");

});

$(document).on('click', '.carousel_next', function () {

    let marginOffset = 238;
    let previewContainer = $(this).closest("#carousel_items").find("#slides");
    let marginLeft = parseInt(previewContainer.css("margin-left")) - marginOffset;
    let $lastCarousel = $(this).closest("#carousel_items").find("#slides").find(".carouselslide").last();

    let $that = $(this);
    previewContainer.animate({
        marginLeft: marginLeft+"px"
    }, 500, function() {

        if ($lastCarousel.position().left <20)
            $that.css("display", "none");
    });


    if (marginLeft <= -marginOffset)
        $(this).prev(".carousel_previous").css("display","block");
    else if (marginLeft === 0)
        $(this).prev(".carousel_previous").css("display","none");


});

$(document).on('click', '.carousel_previous', function () {

    let marginOffset = 238;

    $(this).next(".carousel_next").css("display","block");

    let $lastCarousel = $(this).closest("#carousel_items").find("#slides").find(".carouselslide").last();

    let previewContainer = $(this).closest("#carousel_items").find("#slides");
    let marginLeft = parseInt(previewContainer.css("margin-left")) + marginOffset;
    let $that = $(this);
    if (marginLeft< 0) {
        previewContainer.animate({
            marginLeft: marginLeft + "px"
        }, 500, function() {
            if ($lastCarousel.position().left <20)
                $that.next(".carousel_next").css("display","none");
        });



    }
    else {
        previewContainer.animate({
            marginLeft: "0px"
        }, 500, function() {

            if ($lastCarousel.position().left <20)
                $that.next(".carousel_next").css("display","none");
        });
        $(this).css("display", "none");
    }


});

});

function getCardJson(flowId,cardId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_card_json',
        'flow_id': flowId,
        'card_id': cardId,
    };

    return jQuery.post(ajax_url, data);
}


function getFlowPreviewJson(flowId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_flow_preview_json',
        'flow_id': flowId,
    };

    return jQuery.post(ajax_url, data);
}

function buildFlowPreview(flowId,targetContainer){

   getFlowPreviewJson(flowId).done(function(flowJson){
       try {
           let parsedJson = JSON.parse(flowJson);
           buildPreviewFromJson(parsedJson,targetContainer);
           }
           catch (e){

           }
   });

}

function buildCardPreview(flowId,cardId,targetContainer){

    getCardJson(flowId,cardId).done(function(flowJson){
        buildPreviewFromJson(JSON.parse(flowJson),targetContainer);
    });

}

 function buildPreviewFromJson(messages,targetContainer){
    let htmlOutput = [], html = "";

    $(targetContainer).html("");
    for (let i=0;i<messages.length;i++){
        try {

            let body = JSON.parse(messages[i]);
            let text = _.get(body, 'message.text') || _.get(body, 'message.attachment.payload.text');
            let media = _.get(body, 'message.cm_preview_url') || _.get(body, 'message.attachment.payload.elements[0].url');
            let mediaType = _.get(body, 'message.attachment.payload.elements[0].media_type') || _.get(body, 'message.attachment.type') ;
            let templateType = _.get(body, 'message.attachment.payload.template_type');
            let templateElements = _.get(body, 'message.attachment.payload.elements');
            let templateButtons = _.get(body, 'message.attachment.payload.buttons');
            let buttons = _.get(body, 'message.attachment.payload.elements[0].buttons') || _.get(body, 'message.attachment.payload.buttons');
            let fileUrl = _.get(body, 'message.attachment.payload.url');
            let fileType = _.get(body, 'message.attachment.type');
            let webhook = _.get(body, 'request');

            let quickReplies = _.get(body, 'message.quick_replies');
            let flow = _.get(body, 'message.flow_data');
            let delay = _.get(body, 'delay_data');
            let action = _.get(body, 'action_type');

            if (webhook){
                html = previewTemplates("webhook",webhook);
                $(targetContainer).append(html);

            }
            if (templateElements && templateType === "list"){
                templateElements.buttons = templateButtons;
                html = previewTemplates("list",templateElements);
                $(targetContainer).append(html);

            }

            if (templateElements && templateType === "generic"){
                html = previewTemplates("carousel",templateElements);
                $(targetContainer).append(html);

            }

            if (fileUrl && fileType === "file"){
                html = previewTemplates("file",fileUrl);
                $(targetContainer).append(html);

            }


            if (action){
                html = previewTemplates("action",body,targetContainer);
                $(targetContainer).append(html);

            }



            if (fileUrl && fileType === "audio"){
                html = previewTemplates("audio",fileUrl);
                $(targetContainer).append(html);

            }



            if (text){
                html = previewTemplates("text",text);
                $(targetContainer).append(html);

            }

            if (fileUrl && fileType === "image"){
                html = previewTemplates("image",fileUrl);
                $(targetContainer).append(html);

            }

            if (fileUrl && fileType === "video"){
                html = previewTemplates("video",fileUrl);
                $(targetContainer).append(html);

            }

            if (mediaType && !fileUrl){
                html = previewTemplates(mediaType,media);
                $(targetContainer).append(html);

            }

            if (buttons && templateType !== "list" && templateType !== "generic" ){

                html = previewTemplates("buttons",buttons);
                $(targetContainer).append(html);

            }
            if (quickReplies){
                html = previewTemplates("quick_replies",quickReplies);
                $(targetContainer).append(html);


            }


            if (flow){
                html = previewTemplates("flow",flow);
                $(targetContainer).append(html);

            }

            if (delay){
                html = previewTemplates("delay",delay);
                $(targetContainer).append(html);


            }



        } catch(e) {

        }

    }

     var draggable = $('.carouselslides '); //element

     draggable.on('mousedown', function(e){
         let dr = $(this).addClass("drag").css("cursor","move");
         let carouselItems = $(this).find(".carouselslide").length;
         if (carouselItems <=1) return;
         let maxWidth = 547 - ( carouselItems -1) * $(this).find(".carouselslide").width();
         let height = dr.outerHeight();
         let width = dr.outerWidth();
         let xpos = dr.offset().left + width - e.pageX;
         $(document.body).on('mousemove', function(e){
             let ileft = e.pageX + xpos - width;

             if (ileft>537) ileft = 537;
             else if (ileft<maxWidth) ileft = maxWidth;
                 if(dr.hasClass("drag")){
                         dr.offset({left: ileft});
                 }
         }).on('mouseup', function(e){
             dr.removeClass("drag");
         });
     });
    Plyr.setup((".audio-message-preview-container audio"), {
        controls: ['play','progress', 'current-time' ]
    });
    Plyr.setup((".video-message-preview-container video"), {
        controls: ['play-large']
    });

    let height = $(targetContainer).closest(".scroll_content2").height();
    $(targetContainer).slimscroll({
        height: height,
        color: '#00f',
        alwaysVisible: true

    });

    return htmlOutput;


}


 function previewTemplates(type,content,targetContainer=false){
    let htmlToReturn = "";

    switch (type){

        case "list":
            htmlToReturn += '<div class="phone-list-preview"><div id="template_element_preview" style="display: block;" class="preview_list preview_list_container">';
            for (let a=0;a<content.length;a++) {

                htmlToReturn += '<div id="CMmzv2qPxxjK_preview" class="preview_list preview-list-element " style="">' +
                    '<div id="preview_list_content" class="">' +
                    '<div id="CMmzv2qPxxjK_preview_title" class="template-element-title">'+content[a].title+'</div>' +
                    '<div id="CMmzv2qPxxjK_preview_subtitle" class="template-element-subtitle">'+content[a].subtitle+'</div>' +
                    '<div id="CMmzv2qPxxjK_preview_url" class="template-element-url" style="display: none"></div></div>' +
                    '<div id="CMmzv2qPxxjK_preview_list_image" class="" style=""><div id="CMmzv2qPxxjK_preview_list_image">' +
                    '<img id="CMmzv2qPxxjK_preview_image" class="preview_list_image" src="'+content[a].image_url+'"></div></div>' +
                    '<div class="button_container_preview">';
                if (typeof content[a].buttons !== "undefined") {
                    htmlToReturn += '<button id="elbtCMmzv2qPxxjK_CM0Ne_preview_button" class="preview_list_button" style="">' + content[a].buttons[0].title + '</button>';
                }
                htmlToReturn+='</div><div class="preview_list_footer "></div></div>';

            }
            htmlToReturn += '</div>';
            if (typeof content.buttons !== "undefined"){
                htmlToReturn +='<div id="buttons_preview" class="buttons-container-preview"><div id="CMTMva6TG3Vf_preview" class="broadcast_preview_buttons_bottom">'+content.buttons[0].title+'</div></div></div>';
            }
            break;

        case "carousel":
            htmlToReturn += '<div id="carousel_preview_container" style="display: block;" class="carousel-preview carousel-preview-container">' +
                '<div id="preview_carousel">' +
                '<div class="broadcast_preview_carousel">' +
                '<div id="carousel_items" class="preview_slider">' +
                '<div id="slides" class="carouselslides"><div id="slides_container">';
            let fullClass = 'carousel-full';
            if (content.length>1)
                fullClass = '';
            for (let c=0;c<content.length;c++) {

                    htmlToReturn+= '<div id="CMKMUgh6exsI_slide" class="carouselslide '+fullClass+'">' +
                        '<div id="CMKMUgh6exsI_preview" class="broadcast_preview_carousel_item">' +
                        '<span id="CMKMUgh6exsI_preview_item"><span id="CMKMUgh6exsI_preview_image" class="template-element-image-url" style="background-image: url(\'' + content[c].image_url + '\')"></span>' +
                        '<span id="CMKMUgh6exsI_preview_title" class="template-element-title">' + content[c].title + '</span>' +
                        '<span id="CMKMUgh6exsI_preview_subtitle" class="template-element-subtitle">' + content[c].subtitle + '</span>' +
                        '<span id="CMKMUgh6exsI_preview_url" class="template-element-url" style="display: none;"></span>' +
                        '<div class="button_container_preview">';

                    if (typeof content[c].buttons !== "undefined") {
                        for (let cc = 0; cc < content[c].buttons.length; cc++)
                            htmlToReturn += '<button id="elbtCMKMUgh6exsI_CMEYJ_preview_button" class="preview_list_button" style="">' + content[c].buttons[cc].title + '</button>';

                    }


                htmlToReturn += '</div></span></div></div>';


            }
            htmlToReturn+='</div></div></div> </div> </div> </div>';
            break;
        case "text":
            content = convertEmojiUtfToImage(content);
            htmlToReturn = '<div id="text_message_preview" style="display: block;" class="broadcast_preview_text message-left">'+content+'</div>';
            break;
            case "image":
            htmlToReturn = '<div class="broadcast_preview_img" style="display: block;">' +
                '        <img id="image_message_preview" class="image-modal-preview" src="'+content+'">' +
                '    </div>';
            break;
        case "video":
            htmlToReturn = '<div class="video-message-preview-container" style="display: block;" id="video_message_preview_container">' +
                '<video controls><source src="' + content + '" type="video/mp4">Your browser does not support the video tag</video>' +
                '</div>';
            break;

            case "audio":
                htmlToReturn = '<div class="audio-message-preview-container" style="display: block;" id="audio_message_preview_container">' +
                '<audio controls ><source src="' + content + '" type="audio/mp3">Your browser does not support the audio tag</audio>' +
                '</div>';
                break;

            case "file":
                htmlToReturn = '<div class="file-message-preview-container" style="display: block;" id="file_message_preview_container">' +
                '        <a id="file_message_preview" class="file-message-preview" target="_blank" href="'+content+'"><i class="icon-file-empty"></i><span id="file_name_preview">'+getFileNameFromUrl(content)+'</span></a>' +
                '    </div>';
                break;

        case "buttons":
            buttonsList = '';

            for (let i=0;i<content.length;i++)
                content[i].title = convertEmojiUtfToImage(content[i].title);

            if (content.length === 1){
                buttonsList +=('<div class="broadcast_preview_buttons_bottom">'+content[0].title+'</div>');

            }


            else if (content.length === 2){
                buttonsList +=('<div class="broadcast_preview_buttons_top">'+content[0].title+'</div>');
                buttonsList +=('<div class="broadcast_preview_buttons_bottom">'+content[1].title+'</div>');
            }

            else if (content.length === 3){
                buttonsList +=('<div class="broadcast_preview_buttons_top">'+content[0].title+'</div>');
                buttonsList +=('<div class="broadcast_preview_buttons_middle">'+content[1].title+'</div>');
                buttonsList +=('<div class="broadcast_preview_buttons_bottom">'+content[2].title+'</div>');

            }

            htmlToReturn = '<div id="buttons_preview" class="buttons-container-preview">' +
                buttonsList +
                '</div>';
            break;

        case "quick_replies":
            quickRepliesList = [];
            for (let i=0;i<content.length;i++){
                    content[i].title = convertEmojiUtfToImage(content[i].title);
                quickRepliesList.push('<div class="preview_quick_item">'+content[i].title+'</div>');
            }

            htmlToReturn = '<div class="qslides">'+
                '<span id="quick_previous" class="controls quick_previous" data-slide_id="" style="display: none;">&lt;</span>'+
            '<span class="controls quick_next" data-slide_id="">&gt;</span>'+
            '<div id="quickreplies_preview" class="broadcast_preview_quick quickreplies-container-preview">' + quickRepliesList+ '</div></div>';
            break;

        case "flow":
            if (content.flow_type ==="flowcard" && content.card_id)
                htmlToReturn = '<div class="flow-card-preview"><b>Go to Card </b><a href="#">'+content.card_name +'</a></div>';
            else if (content.flow_type ==="flow" && content.flow_id)
                htmlToReturn = '<div class="flow-card-preview"><b>Go to Flow </b><a target="_blank" href="composer.php?flow='+content.flow_id+'">'+content.flow_name+'</a></div>';
            break;

        case "webhook":
                htmlToReturn = '<div class="flow-card-preview">Webhook <b>'+content.method.toUpperCase()+' </b>Request <a href="#" target="_blank">'+content.url +'</a></div>';
                break;

        case "action":
            switch (content.action_type){
                case "add_tag":
                    htmlToReturn = "<div class='action-card-preview'> Add Tag <b> "+ content.action_settings.tag+"</b></div>";
                    break;

                case "remove_tag":
                    if (typeof window.requestedTag === "undefined") {
                        window.requestedTag = 1;
                        getTagName(content.action_settings.tag).done(function (tagName) {
                            htmlToReturn = "<div class='action-card-preview'>Remove Tag <b> " + tagName + "</b></div>";
                            $(targetContainer).append(htmlToReturn);
                            delete window.requestedTag;
                        });
                    }

                    break;


                case "notify_admin":
                        let person = "person";
                    let number = "1";
                        if (typeof content.action_settings.notification_recipient_ids !== "undefined" && content.action_settings.notification_recipient_ids.length) {
                            if (Object.prototype.toString.call(content.action_settings.notification_recipient_ids) === '[object Array]') {
                                person = "people";
                                number = content.action_settings.notification_recipient_ids.length;
                            }
                            htmlToReturn = ("<div class='action-card-preview'>Notify <b> " + number + " " + person + "</b> via Messenger</div>");
                        }

                        else
                            htmlToReturn = ("<div class='action-card-preview'>Select Notification <b>Recipients</b></div>");

                        $(targetContainer).append(htmlToReturn);
                        htmlToReturn = "";

                    break;

                case "set_custom_field_value":
                    getCustomFieldName(content.action_settings.custom_field).done(function(name){
                        htmlToReturn = ("<div class='action-card-preview'>Set Custom Field <b> " +name+"</b> to <b> "+content.action_settings.custom_field_value+"</b></div>");
                        $(targetContainer).append(htmlToReturn);

                    });
                    break;

                case "set_global_field_value":
                    getGlobalFieldName(content.action_settings.global_field).done(function(name){
                        htmlToReturn = ("<div class='action-card-preview'>Set Global Field <b> " +name+"</b> to <b> "+content.action_settings.name+"</b></div>");
                        $(targetContainer).append(htmlToReturn);

                    });
                    break;

                case "clear_custom_field":
                    getCustomFieldName(content.action_settings.custom_field).done(function(name){
                        htmlToReturn =("<div class='action-card-preview'>Clear Custom Field <b> " +name+"</b></div>");
                        $(targetContainer).append(htmlToReturn);

                    });
                    break;


                case "clear_global_field":
                    getGlobalFieldName(content.action_settings.global_field).done(function(name){
                        htmlToReturn =("<div class='action-card-preview'>Clear Global Field <b> " +name+"</b></div>");
                        $(targetContainer).append(htmlToReturn);

                    });
                    break;


                case "pause_automation":
                    htmlToReturn ="<div class='action-card-preview'>Pause Bot Automation</div>";
                    break;

                case "resume_automation":
                    htmlToReturn ="<div class='action-card-preview'>Resume Bot Automation</div>";
                    break;

                case "unsubscribe_flow":
                    htmlToReturn ="<div class='action-card-preview'>Unsubscribe from Flow</div>";
                    break;

                case "subscribe":
                    htmlToReturn ="<div class='action-card-preview'>Subscribe to Bot</div>";
                    break;

                case "clear_input":
                    htmlToReturn ="<div class='action-card-preview'>Clear Required Input</div>";
                    break;

                case "unsubscribe":
                    htmlToReturn ="<div class='action-card-preview'>Unsubscribe from Bot</div>";
                    break;

                case "export_profile":
                    htmlToReturn ="<div class='action-card-preview'>Send Profile Data</div>";
                    $(previewSelector).html("");
                    break;

                case "delete_profile":
                    htmlToReturn ="<div class='action-card-preview'>Delete Profile</div>";
                    break;
                default :
                    htmlToReturn ="<div class='action-card-preview'> Specify a valid <b>action </b></div>";
                    break;
            }
                break;

        case "delay":
            htmlToReturn = '<div class="delay-preview"><span>Wait '+content.delay_value +' '+content.delay_type +'</span></div><div style="clear:both;"></div>';
            break;
    }

    return htmlToReturn+'<div style="clear:both;" class="message-separator"></div>';


}

function changeReferenceFlowCard(flowid, flowcard) {

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'Get_flow_cards_new',
        'flow_id': flowid
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response) {
            try {
                let messages = JSON.parse(response);

            }
            catch (e){
            }
        }


    });
}

function initializeEmojiObject() {
    window.emojiPicker = new EmojiPicker({

        emojiable_selector: '[data-emojiable=true]',

        assetsPath: 'img/',

        popupButtonClasses: 'icon-smile'

    });
}

function createGreetingPreview() {
    $('#broadcast_msg_preview').html("");

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_greeting_preview',
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {
            $('#broadcast_msg_preview').html(response);
        }
    });

}

function createPreviewFromObject(msgObj) {
    $('#broadcast_msg_preview').html("");
    for (i in msgObj) {
        if (!isNaN(i)) {
            thisMsgObj = JSON.parse('{' + msgObj[i] + '}');
            var previewContainer = $('#broadcast_msg_preview');
            CreatePreviewMsgElements(thisMsgObj, previewContainer, '');
        }
    }
}

function flowCardChange(referenceType, id) {
    if (referenceType === "general" || referenceType === "select") {
        return;
    }

    $("#phone_preview_button_image").show();


    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'Get_flow_preview_single',
        'msg_id': id
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {

            msgObj = JSON.parse(response);

            createPreviewFromObject(msgObj);


        }


    });
}

function populateFlowCards(selector,messages,selectedCard){
    $(selector).html("");
    if (messages && messages !== "null") {
        try {
            messages = JSON.parse(messages);
            for (let id in messages) {
                if ( messages[id]._type === "phone" ||  messages[id]._type === "url") continue;
                if (selectedCard && messages[id]._id === selectedCard)
                    $(selector).append("<option selected value='" + id + "'>" + messages[id]._title + "</option>");
                else
                $(selector).append("<option value='" + id + "'>" + messages[id]._title + "</option>");

            }

            $(selector).show().change();

        }
        catch (e) {
        }
    }


}



jQuery(document).ready(function () {


    initializeEmojiObject();

});