function CreateUniqID(){
    var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter2 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter3 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    return randLetter + randLetter2 +randLetter3 +Date.now();
}


function AddPreviewImage(ThisItemId,ThisType,imgurl){

    if(ThisType==="simple_image" || ThisType==="image"){
        jQuery('#simple_image_url').val(imgurl);
        jQuery('#preview_'+ThisItemId+' .broadcast_preview_img').html('<img id="'+ThisItemId+'preview_image" class="broadcast_preview_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="carousel"){
        jQuery('#'+ThisItemId+'_preview_image').css("background-image", "url("+imgurl+")");
        //jQuery('#'+ThisItemId+'_preview_image').html('<img id="'+ThisItemId+'preview_img" class="preview_carousel_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="list"){
        jQuery('#'+ThisItemId+'_preview_list_image').html('<img id="'+ThisItemId+'preview_image" class="preview_list_image img-responsive" src="'+imgurl+'">');
    }

    if(ThisType==="quick"){
        jQuery('#'+ThisItemId+'_preview_quick_image').html('<img id="'+ThisItemId+'preview_image" class="preview_quick_image img-responsive" src="'+imgurl+'">');
        jQuery('#'+ThisItemId+'_preview_quick_image').addClass('preview_quick_image_div');
        jQuery('#'+ThisItemId+'_img_url').val(imgurl);
    }
}

function CreatePreviewMsgElements(ThisMsgObj,PreviewContainer,ThisType){
    if(ThisType===""){ThisType = PreviewGetMsgType(ThisMsgObj);}
    var TxtAboveButtons ="";
    if (ThisType !== "undefined") {

        ThisType = ThisType.replace("simple_", "");
        if(ThisType ==="button"){ThisType="buttons";}
        var ThisValue = GetTypeValue(ThisType, ThisMsgObj);



        var uniqid = CreateUniqID();
        PreviewContainer.append(ShowMsgPreview(ThisType, uniqid, ThisValue));
        if (ThisType === "audio" || ThisType === "video" || ThisType === "file" || ThisType === "image" ) {
            ChangePreviewPrefill(uniqid, ThisValue, '', ThisType);
        }

        if (ThisType === "quick" ) {
            TxtAboveButtons = getImage(ThisMsgObj['message']['text']);
            jQuery('#preview_'+uniqid+' .broadcast_preview_text').html(TxtAboveButtons);
        }

        if (ThisType === "buttons" || ThisType === "quick" || ThisType === "carousel" || ThisType === "list" || ThisType === "products" || ThisType === "structured") {
            var TheseElements="";
            if (ThisType === "buttons"){
                if(typeof ThisMsgObj['message'] !== "undefined"){TheseElements = ThisMsgObj['message']['attachment']['payload']['buttons'];}
                if(typeof ThisMsgObj['attachment'] !== "undefined"){TheseElements = ThisMsgObj['attachment']['payload']['buttons'];}
            }

            if (ThisType === "quick"){

                if(typeof ThisMsgObj['message'] !== "undefined"){ TheseElements = ThisMsgObj['message']['quick_replies'];}
                if(typeof ThisMsgObj['quick_replies'] !== "undefined"){TheseElements = ThisMsgObj['quick_replies'];}
            }

            if (ThisType === "list" || ThisType === "carousel" || ThisType === "structured" || ThisType === "products"){
                TheseElements = ThisMsgObj['message']['attachment']['payload']['elements'];}

            ChangePreviewElements(uniqid, TheseElements, ThisType);
        }

        if(ThisType === "buttons"){
            TxtAboveButtons = getImage(ThisMsgObj['message']['attachment']['payload']['text']);
            jQuery('#preview_'+uniqid+' .broadcast_preview_text').html(TxtAboveButtons);
            //we need the id's of the buttons (children and add the class broadcast_preview_buttons to them
            $("#buttons_"+uniqid).children().each(function() {
                var ThisButtonID = $(this).attr('id');
                $('#'+ThisButtonID).addClass('broadcast_preview_buttons');
            });
        }

    }

}

function ChangePreviewPrefill(ThisItemId,ThisValue,FileName,ThisType){
    if(ThisType==="audio"||ThisType==="video"||ThisType==="file"){jQuery('#'+ThisType+'_url').val(ThisValue);    jQuery('#'+ThisItemId+'_'+ThisType+'_url').val(ThisValue);}

    if(ThisType==="audio"){
        jQuery('#preview_'+ThisItemId+' .broadcast_preview_audio' ).html('<span class="chat_view_audio"><audio controls class="audio_'+ThisItemId+'"><source id="'+ThisItemId+'_audio_src" src="'+ThisValue+'" type="audio/mp3"></audio></span>');
        Plyr.setup('.audio_'+ThisItemId, {		controls: ['play-large', 'play', 'progress', 'current-time']	});
    }

    if(ThisType==="video"){
        jQuery('#'+ThisItemId+'_video_src').attr('src', ThisValue);
        jQuery('#preview_'+ThisItemId+' .broadcast_preview_video').html('<video class="video_'+ThisItemId+'" poster="" controls><source id="'+ThisItemId+'_video_src" src="'+ThisValue+'" type="video/mp4"></video>');
        Plyr.setup('.video_'+ThisItemId);
    }

    if(ThisType==="file"){
        if(FileName===""){
            FileName=ThisValue;
            FileName = FileName.replace("https://dev.clevermessenger.com//media/", "");
            FileName = FileName.replace("https://dev.clevermessenger.com/media/", "");
        }
        jQuery('#preview_'+ThisItemId+' .broadcast_preview_file').html('<a href="'+ThisValue+'" target="_blank"><i class="icon-file-empty"></i> '+FileName+'</a>');
    }

    if(ThisType==="image"){
        AddPreviewImage(ThisItemId,'simple_image',ThisValue);
    }

    if(ThisType==="text" || ThisType==="simple" || ThisType==="buttons" || ThisType==="quick"){

        if(ThisValue===""){
            ChangePreviewText_PREV(ThisItemId,'text');
        }else{
            //   OrgText = ThisValue.replace("\n", "<br/>");
            OrgText = getImage(ThisValue);
            jQuery('#preview_'+ThisItemId+' .broadcast_preview_text').html(OrgText);
        }

    }

}

function IsElementJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return "true"; //we already have json
    }
    return "false"; //this is not json yet
}

function ChangePreviewElements(uniqid, TheseElements, ThisType){
    var ButtonsObj, i, MsgRaw,msgoutput = "";

    var CheckJson = IsElementJsonString(TheseElements);
    if(CheckJson==="false"){ButtonsObj = JSON.parse(TheseElements);}
    if(CheckJson==="true"){ButtonsObj = TheseElements;}


    var ItemType,ItemTitle,ThisItemTitle,ItemSubTitle,ItemImgUrl,ItemUrl,ThisButtonTitle,ThisButtonType,ThisButtonMsg,ThisButtonUrl,ThisButtonPhone="";
    if(typeof ButtonsObj !== "undefined") {
        if (ThisType === "buttons" || ThisType === "quick") {
            for (i in ButtonsObj) {
                ItemTitle = ButtonsObj[i]['title'];
                ThisButtonType = ButtonsObj[i]['type'];


                if (ThisButtonType === "phone_number") {ThisButtonPhone = ButtonsObj[i]['payload'];}
                if (ThisButtonType === "web_url") {ThisButtonUrl = ButtonsObj[i]['url'];}
                if (ThisButtonType === "postback") {MsgRaw = ButtonsObj[i]['payload'].split("_", 3);ThisButtonMsg = MsgRaw[1];
                }

                AddButtonMsgItem(uniqid, ThisType, i + 1, '', '', '', '', ItemTitle,ThisButtonType, ThisButtonMsg, ThisButtonUrl, ThisButtonPhone,'');

            }
        }

        if (ThisType === "list") {
            for (i in ButtonsObj) {
                ItemTitle = ButtonsObj[i]['title'];
                if(typeof ButtonsObj[i]['subtitle'] !== "undefined") {ItemSubTitle = ButtonsObj[i]['subtitle'];}
                if(typeof ButtonsObj[i]['image_url'] !== "undefined") {ItemImgUrl = ButtonsObj[i]['image_url'];}
                if(typeof ButtonsObj[i]['url'] !== "undefined") {ItemUrl = ButtonsObj[i]['url'];}

                //do we have a button?

                if(typeof ButtonsObj[i]['buttons'] !== "undefined") {ThisButtonTitle= ButtonsObj[i]['buttons'][0]['title'];
                    if(typeof ButtonsObj[i]['buttons'][0]['type'] !== "undefined") {ThisButtonType= ButtonsObj[i]['buttons'][0]['type'];}
                    if (ThisButtonType === "phone") {ThisButtonPhone = ButtonsObj[i]['buttons'][0]['phone'];}
                    if (ThisButtonType === "web_url") {ThisButtonUrl = ButtonsObj[i]['buttons'][0]['url'];}
                    if (ThisButtonType === "postback") { MsgRaw = ButtonsObj[i]['buttons'][0]['payload'].split("_", 3);ThisButtonMsg = MsgRaw[1];}
                }

                AddButtonMsgItem(uniqid,ThisType,i+1,ItemTitle,ItemSubTitle,ItemImgUrl,ItemUrl,ThisButtonTitle,ThisButtonType,ThisButtonMsg,ThisButtonUrl,ThisButtonPhone,'');
                ButtonSelectedItem(uniqid,ThisType,ThisButtonType,'','');
            }
        }

        if (ThisType === "carousel" || ThisType === "products" || ThisType === "structured") {
            for (i in ButtonsObj) {
                ItemTitle,ItemSubTitle,ItemImgUrl,ItemUrl="";
                ItemTitle = ButtonsObj[i]['title'];
                if(typeof ButtonsObj[i]['subtitle'] !== "undefined") {ItemSubTitle = ButtonsObj[i]['subtitle'];}
                if(typeof ButtonsObj[i]['image_url'] !== "undefined") {ItemImgUrl = ButtonsObj[i]['image_url'];}
                if(typeof ButtonsObj[i]['item_url'] !== "undefined") {ItemUrl = ButtonsObj[i]['item_url'];}

                var TheseButtons= ButtonsObj[i]['buttons'];
                AddButtonMsgItem(uniqid,ThisType,i+1,ItemTitle,ItemSubTitle,ItemImgUrl,ItemUrl,'','','','',ThisButtonPhone,TheseButtons);
            }
        }
        return ThisButtonType;
    }
}

function ChangePreviewListButtonTitle(){
    var ItemID = jQuery('#current_item').val();
    var OrgText = jQuery('#list_button_title').val();
    jQuery('#'+ItemID+'_preview_button_title').html(OrgText);
}


function ChangePreviewTextValues(uniqid,OrgText,PreviewValue){
    var ThisOrgText = jQuery('#'+uniqid+'_'+OrgText).val();
    jQuery('#'+uniqid+'_'+PreviewValue).html(ThisOrgText);
}

function ChangePreviewText_PREV(uniqid,msgType){

    if(msgType=='carousel_title'){
        ChangePreviewTextValues(uniqid,'carousel_title','preview_title');
    }

    if(msgType=='carousel_subtitle'){
        ChangePreviewTextValues(uniqid,'carousel_subtitle','preview_subtitle');
    }

    if(msgType=='carousel_url'){
        ChangePreviewTextValues(uniqid,'carousel_url','preview_url');
    }

    if(msgType=='quick_title'){
        ChangePreviewTextValues(uniqid,'quick_title','preview_title');
    }

    if(msgType=='list_title'){
        ChangePreviewTextValues(uniqid,'list_title','preview_title');
        jQuery('#'+uniqid+'_preview_title').addClass('preview_title');
    }

    if(msgType=='list_subtitle'){
        ChangePreviewTextValues(uniqid,'list_subtitle','preview_subtitle');
        jQuery('#'+uniqid+'_preview_subtitle').addClass('preview_subtitle');
    }

    if(msgType=='buttons' || msgType=='text' || msgType=='quick'){
        var EmojiID = jQuery('#text_'+uniqid).data('id');
        var OrgText = $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').html();
        if(OrgText!=="" && typeof OrgText !== "undefined"){
            //lets replace the /n with br
            //OrgText = OrgText.replace("\n", "<br/>");
        }

        jQuery('#preview_'+uniqid+' .broadcast_preview_text').html(OrgText);
    }

    if(msgType=='buttons'){
        jQuery('#preview_'+uniqid).addClass('button_wrapper');
    }
}

function AddNewItemsNum(ThisItemId,NumItems){
    var NewNum = Number("1");
    NewItemsNum = NumItems+NewNum;
    jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);
    return NewItemsNum;
}

function PreviewGetMsgType(MsgData){
    var MsgType="";

    //we might be in for a very easy or a difficult one...lets check
    if(typeof MsgData['message']!== "undefined") {

        if (typeof MsgData['message']['text'] !== "undefined") {
            //simple message of a quick reply?
            if (typeof MsgData['message']['quick_replies'] !== "undefined") {
                MsgType = 'quick';
            } else {
                MsgType = 'simple';
            }
        } else {

            // see if we have an attachment or a template
            if (typeof MsgData['message']['attachment']['type'] !== "undefined") {
                MsgType = MsgData['message']['attachment']['type'];
            }
            //now all template types will have as Type = template..let's adjust that by running over the second part of the check
            if (typeof MsgData['message']['attachment']['payload']['template_type'] !== "undefined") {
                //we have a template type
                if (MsgData['message']['attachment']['payload']['template_type'] === "generic") {
                    MsgType = 'structured';
                }
                if (MsgData['message']['attachment']['payload']['template_type'] === "list") {
                    MsgType = 'list';
                }
                if (MsgData['message']['attachment']['payload']['template_type'] === "button") {
                    MsgType = 'buttons';
                }
                if (MsgData['message']['attachment']['payload']['template_type'] === "receipt") {
                    MsgType = 'receipt';
                }
            }
        }
    }
    return MsgType;

}

function GetTypeIcon(ThisType){
    var icon='';
    switch (ThisType) {
        case 'text':
            icon = "icon-text-format";
            break;
        case 'image':
            icon = "icon-file-image";
            break;
        case 'audio':
            icon = "icon-file-audio";
            break;
        case 'video':
            icon = "icon-file-video";
            break;
        case 'file':
            icon = "icon-file-empty";
            break;
        case 'buttons':
            icon = "icon-pointer-up";
            break;
        case 'carousel':
            icon = "icon-map2";
            break;
        case 'structured':
            icon = "icon-map2";
            break;
        case 'list':
            icon = "icon-menu";
            break;
        case 'quick':
            icon = "icon-ellipsis";
            break;
        case 'typing':
            icon = "icon-bubble-dots";
            break;
    }
    return icon;
}

function ButtonPreviewStyling_PREV(ThisItemId,NewButtonNum,uniqID){
    var Items = Number("1");
    var AddThis="";
    NewButtonNum=0;
    $('#broadcast_msgs_table #'+ThisItemId+'_button_settings_table > .button_row').each(function() {
        NewButtonNum++;
    }); //I know this is a bit duck-taping as we go into the same loop again in a split second but I needed a good way to get the actual count of the rows for this styling and the count children did not gave me always an acurate #

    if( NewButtonNum===0){
        //we don't have the buttons here...maybe this is a new msg item ..let's try that
        $('#broadcast_msgs_table_new #'+ThisItemId+'_button_settings_table > .button_row').each(function() {
            NewButtonNum++;
        });
        if(NewButtonNum >0){
            //ok we had a new msg..lets add _new to the selector for the rest
            AddThis="_new";
        }
    }

    $('#broadcast_msgs_table'+AddThis+' #'+ThisItemId+'_button_settings_table > .button_row').each(function(i, tr) {
        //looping through the table and getting the item id's

        var ThisId = $(tr).attr('id');
        var ThisPreview = jQuery('#'+ThisId+'_preview');

        ThisPreview.removeClass('broadcast_preview_buttons');
        ThisPreview.removeClass('broadcast_preview_buttons_top');
        ThisPreview.removeClass('broadcast_preview_buttons_middle');
        ThisPreview.removeClass('broadcast_preview_buttons_bottom');

        if(Items===1 && NewButtonNum===1){
            jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons');
        }

        if(Items===1 && NewButtonNum >1){
            jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_top');
        }

        if(Items===2 && NewButtonNum===2){
            jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_bottom');
        }

        if(Items===2 && NewButtonNum===3){
            jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_middle');
        }

        if(Items===3){
            jQuery('#'+ThisId+'_preview').addClass('broadcast_preview_buttons_bottom');
        }
        Items++;
    });

}


function ButtonSelectedItem(ItemID,MsgType,TypeButton,ButtonValue,CurrentButton){
    var ButtonTypesArr = {"button_share":"share", "button_phone":"phone", "button_msg":"postback", "button_link":"web_url"};
    for (var key in ButtonTypesArr) {
        var ThisType = key;
        var ThisTypeValue = ButtonTypesArr[key];
        $('#'+MsgType+'_'+ThisType+'_select'+CurrentButton).removeClass('sticky_menu_item_selected');
        if(ThisTypeValue===TypeButton){$('#'+MsgType+'_'+ThisType+'_select'+CurrentButton).addClass('sticky_menu_item_selected');}
    }
}

function AddButtonMsgItem(ThisItemId,ItemType,NumButtons,ThisItemTitle,ThisItemSubTitle,ThisItemImgUrl,ThisItemUrl,ThisButtonTitle,ThisButtonType,ThisButtonMsg,ThisButtonUrl,ThisButtonPhone,TheseButtons){
    var uniqID = CreateUniqID();

    if(ItemType==="list"){
        $('#'+ThisItemId+'_list_settings_table').append('<tr id="'+uniqID+'" class="list"><td width="70%">' +
            '<input type="hidden" name="'+ThisItemId+'_items[]" value="'+uniqID+'" /> ' +
            '<input type="text" name="'+uniqID+'_list_title" id="'+uniqID+'_list_title" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'list_title\');" class="form-control input-lg m-b" placeholder="List title" size="50" maxlength="80" data-item-type="title" value="'+ThisItemTitle+'">' +
            '<input type="text" name="'+uniqID+'_list_subtitle" id="'+uniqID+'_list_subtitle" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'list_subtitle\');" class="form-control input-lg m-b" placeholder="Subtext" size="50" maxlength="80" value="'+ThisItemSubTitle+'">' +
            '<input type="hidden" name="'+uniqID+'_button_type" id="'+uniqID+'_button_type" value="'+ThisButtonType+'">' +
            '<input type="hidden" name="'+uniqID+'_button_title" id="'+uniqID+'_button_title" value="'+ThisButtonTitle+'">' +
            '<input type="hidden" name="'+uniqID+'_button_msg" id="'+uniqID+'_button_msg" value="'+ThisButtonMsg+'">' +
            '<input type="hidden" name="'+uniqID+'_button_url" id="'+uniqID+'_button_url" value="'+ThisButtonUrl+'">' +
            '<input type="hidden" name="'+uniqID+'_button_phone" id="'+uniqID+'_button_phone" value="'+ThisButtonPhone+'">' +
            '</td><td valign="middle" width="10%"><div class="input-lg m-b OpenImgModal" data-itemid="'+uniqID+'" data-msgtype="list" id="'+uniqID+'_list_img_select"><i class="icon-picture list_img_item" aria-hidden="true"></i></div>' +
            '<input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url" value="'+ThisItemImgUrl+'"></td>	 ' +
            '<td valign="middle" width="10%"><div class="input-lg m-b" id="'+uniqID+'_button_list_msg"><i class="icon-pointer-up button_list_msg" aria-hidden="true" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"></i></div></td> ' +
            '<td valign="middle" width="10%"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
        if(ThisItemImgUrl===""){ThisItemImgUrl="./images/preview.png";}
        $('#preview_'+ThisItemId+' .broadcast_preview_list').append('<div id="'+uniqID+'_preview" class="preview_list"><div id="preview_list_content"><div id="'+uniqID+'_preview_title" style="font-weight: bold;font-size: 12px;padding:0 5px">'+ThisItemTitle+'</div><div id="'+uniqID+'_preview_subtitle" style="color:#90949c;font-size: 12px;padding:0 5px">'+ThisItemSubTitle+'</div><div id="'+uniqID+'_preview_url" style="color:#90949c;font-size: 12px;margin-top:10px;padding:0 5px;height:15px;overflow:hidden"></div><span id="'+uniqID+'_preview_button"  class="preview_list_button">'+ThisButtonTitle+'</span></div><div id="'+uniqID+'_preview_list_image"><img id="'+uniqID+'_preview_image" class="preview_list_image" src="'+ThisItemImgUrl+'"></div><div class="preview_list_footer"></div>	</div>');
    }

    if(ItemType==="buttons"){

        $('#'+ThisItemId+'_button_settings_table').append('<div id="'+uniqID+'" class="button_row"><div class="input_wrap">' +
            '<input type="hidden" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> ' +
            '<input type="text" name="'+uniqID+'_button_title" id="'+uniqID+'_button_title" onkeyup="ChangePreviewTextValues(\''+uniqID+'\',\'button_title\',\'preview\');" class="form-control button_icons" placeholder="Button text" size="30" maxlength="20" data-item-type="buttons" value="'+ThisButtonTitle+'">' +
            '<input type="hidden" name="'+uniqID+'_button_msg" id="'+uniqID+'_button_msg"  value="'+ThisButtonMsg+'"/><input type="hidden" name="'+uniqID+'_button_phone" id="'+uniqID+'_button_phone"  value="'+ThisButtonPhone+'"/> ' +
            '<input type="hidden" name="'+uniqID+'_button_url" id="'+uniqID+'_button_url"  value="'+ThisButtonUrl+'"/><input type="hidden" name="'+uniqID+'_button_type" id="'+uniqID+'_button_type"  value="'+ThisButtonType+'"/></div>' +
            '<div class="button_icons button_link_item" id="'+uniqID+'_button_link_select" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"><i class="icon-link2" aria-hidden="true"></i></div>' +
            '<div class="button_icons button_msg_item" id="'+uniqID+'_button_msg_select" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"><i class="icon-bubbles" aria-hidden="true" ></i></div>' +
            '<div class="button_icons button_phone_item" id="'+uniqID+'_button_phone_select" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"><i class="icon-telephone" aria-hidden="true"></i></div>' +
            '<div class="button_icons"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></div>');
        $('#buttons_'+ThisItemId).append('<div id="'+uniqID+'_preview">'+ThisButtonTitle+'</div>');
        ButtonPreviewStyling_PREV(ThisItemId,NumButtons + 1,uniqID);
    }

    if(ItemType==="quick"){
        $('#'+ThisItemId+'_quick_settings_table').append('<tr id="'+uniqID+'">' +
            '<td width="60%"><input type="hidden" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> ' +
            '<input type="text" name="'+uniqID+'_quick_title" id="'+uniqID+'_quick_title" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'quick_title\');" class="form-control input-lg" placeholder="Button text" size="30"  maxlength="20"  data-item-type="buttons"  value="'+ThisButtonTitle+'">' +
            '<input type="hidden" name="'+uniqID+'_quick_msg" id="'+uniqID+'_button_msg"  value="'+ThisButtonMsg+'">' +
            '<input type="hidden" name="'+uniqID+'_button_type" id="'+uniqID+'_button_type"></td>' +
            '<td valign="middle" width="10%"><div class="input-lg  OpenImgModal" data-itemid="'+uniqID+'" data-msgtype="quick" id="'+uniqID+'_quick_img_select"><i class="icon-picture quick_img_item" aria-hidden="true"></i></div>' +
            '<input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url" value="'+ThisItemImgUrl+'"/></td>' +
            '<td valign="middle" width="10%"><div class="input-lg" id="'+uniqID+'_quick_msg_select"><i class="icon-pointer-up button_msg_item" aria-hidden="true" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"></i></div></td> ' +
            '<td valign="middle" width="10%"><div class="input-lg"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td></tr>');
        $('#preview_'+ThisItemId+' .broadcast_preview_quick').append('<div id="'+uniqID+'_preview" class="preview_quick_item"><div id="preview_quick_content"><div id="'+uniqID+'_preview_quick_image" ></div><div id="'+uniqID+'_preview_title" class="preview_quick_title">'+ThisButtonTitle+'</div></div>');

    }

    if(ItemType==="carousel" || ItemType==="structured" || ItemType==="products"){

        $('#'+ThisItemId+'_carousel_settings_table').append('<tr id="'+uniqID+'" class="carousel">' +
            '<td width="70%">' +
            '<input type="hidden" name="'+ThisItemId+'_items[]" value="'+uniqID+'" /> ' +
            '<input type="text" name="'+uniqID+'_carousel_title" id="'+uniqID+'_carousel_title" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'carousel_title\');" class="form-control input-lg m-b" placeholder="Carousel title" size="50" maxlength="80" data-item-type="title"  value="'+ThisItemTitle+'">' +
            '<input type="text" name="'+uniqID+'_carousel_subtitle" id="'+uniqID+'_carousel_subtitle" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'carousel_subtitle\');" class="form-control input-lg m-b" placeholder="Subtext" size="50"  maxlength="80"  value="'+ThisItemSubTitle+'">' +
            '<input type="text" name="'+uniqID+'_carousel_url" id="'+uniqID+'_carousel_url" onkeyup="ChangePreviewText_PREV(\''+uniqID+'\',\'carousel_url\');" class="form-control input-lg" placeholder="Url to Link to"   value="'+ThisItemUrl+'"/>' +
            '</td>' +
            '<td valign="middle" width="10%">' +
            '<div class="input-lg m-b OpenImgModal" data-itemid="'+uniqID+'" data-msgtype="carousel" id="'+uniqID+'_carousel_img_select"><i class="icon-picture carousel_img_item" data-itemid="'+ThisItemId+'" aria-hidden="true"></i></div>' +
            '<input type="hidden" id="'+uniqID+'_img_url" name="'+uniqID+'_img_url"  value="'+ThisItemImgUrl+'"/></td>	 ' +
            '<td valign="middle" width="10%"><div class="input-lg m-b" id="'+uniqID+'_button_msg_select"><i class="icon-pointer-up button_carousel_msg" aria-hidden="true" data-item_id="'+uniqID+'" data-msg_id="'+ThisItemId+'"></i></div></td> ' +
            '<td valign="middle" width="10%"><div class="input-lg m-b"><i class="icon-cross delete_item" aria-hidden="true" data-itemid="'+ThisItemId+'" data-trid="'+uniqID+'"></i></div></td>' +
            '</tr>');

        var $y=0;
        for ($x=0;$x <3; $x++){
            $y = $x+1;
            ThisButtonTitle,ThisButtonType,ThisButtonUrl,ThisButtonPhone,ThisButtonMsg="";
            if(typeof TheseButtons!== "undefined" && typeof TheseButtons[$x] !=="undefined") {
                if(typeof TheseButtons[$x]['title'] !== "undefined") {ThisButtonTitle= TheseButtons[$x]['title'];
                    if(typeof TheseButtons[$x]['type'] !== "undefined") {ThisButtonType= TheseButtons[$x]['type'];}
                    if(typeof TheseButtons[$x]['phone'] !== "undefined") {ThisButtonPhone = TheseButtons[$x]['phone'];}
                    if(typeof TheseButtons[$x]['url'] !== "undefined") {ThisButtonUrl = TheseButtons[$x]['url'];}
                    if(typeof TheseButtons[$x]['payload'] !== "undefined") {var MsgRaw = TheseButtons[$x]['payload'].split("_", 3);ThisButtonMsg = MsgRaw[1];}
                }
            }
            $('#'+ThisItemId+'_carousel_settings_table').append('<input type="hidden" name="'+uniqID+'_button_type_'+$y+'" id="'+uniqID+'_button_type_'+$y+'" value="'+ThisButtonType+'"/>' +
                '<input type="hidden" name="'+uniqID+'_button_title_'+$y+'" id="'+uniqID+'_button_title_'+$y+'" value="'+ThisButtonTitle+'"/>' +
                '<input type="hidden" name="'+uniqID+'_button_msg_'+$y+'" id="'+uniqID+'_button_msg_'+$y+'" value="'+ThisButtonMsg+'"/>' +
                '<input type="hidden" name="'+uniqID+'_button_url_'+$y+'" id="'+uniqID+'_button_url_'+$y+'" value="'+ThisButtonUrl+'"/>' +
                '<input type="hidden" name="'+uniqID+'_button_phone_'+$y+'" id="'+uniqID+'_button_phone_'+$y+'" value="'+ThisButtonPhone+'"/>');
            $y++;
        }

        if(ThisItemImgUrl===""){ThisItemImgUrl="./images/preview.png";}

        var ThisButtonTitle1= '';if(typeof TheseButtons !== "undefined" && typeof TheseButtons[0] !== "undefined") {ThisButtonTitle1= TheseButtons[0]['title'];}
        var ThisButtonTitle2= '';if(typeof TheseButtons !== "undefined" && typeof TheseButtons[1] !== "undefined") {ThisButtonTitle2= TheseButtons[1]['title'];}
        var ThisButtonTitle3= '';if(typeof TheseButtons !== "undefined" && typeof TheseButtons[2] !== "undefined") {ThisButtonTitle3= TheseButtons[2]['title'];}

        $('#carousel_items_'+ThisItemId+' > div.carouselslides').append('' +
            '<div id="slide_'+uniqID+'" class="carouselslide">' +
            '<div id="'+uniqID+'_preview" class="broadcast_preview_carousel_item">' +
            '<span id="'+uniqID+'_preview_item">' +
            '<span id="'+uniqID+'_preview_image" style="height:150px;text-align:center;overflow:hidden; display: block; width:100%;background-image: url(\''+ThisItemImgUrl+'\'); background-size:cover; background-position: center center;"></span>' +
            '<span id="'+uniqID+'_preview_title" style="font-weight: bold;font-size: 15px;padding: 8px 13px 0 13px; display:block;">'+ThisItemTitle+'</span>' +
            '<span id="'+uniqID+'_preview_subtitle" style="color:#90949c;font-size: 13px;display: block; padding: 0 13px 8px 13px;">'+ThisItemSubTitle+'</span>' +
            '<span id="'+uniqID+'_preview_url" style="color:#90949c;font-size: 12px;">'+ThisItemUrl+'</span>' +
            '<span id="'+uniqID+'_preview_buttons">' +
            '<span id="'+uniqID+'_preview_button1" class="preview_buttons">'+ThisButtonTitle1+'</span>' +
            '<span id="'+uniqID+'_preview_button2" class="preview_buttons">'+ThisButtonTitle2+'</span>' +
            '<span id="'+uniqID+'_preview_button3" class="preview_buttons">'+ThisButtonTitle3+'</span>' +
            '</span>' +
            '</span>' +
            '</div></div>');

    }
    ButtonSelectedItem(uniqID,ItemType,ThisButtonType,'','');
}


function GetTypeValue(ThisType,ThisObj){

    var ThisValue="";
    if(ThisType==="image"){
        if(typeof ThisObj['image_url'] !== "undefined"){ThisValue=ThisObj['image_url'];}
        if(typeof ThisObj['message'] !== "undefined"){ThisValue=ThisObj['message']['attachment']['payload']['url'];}

    }

    if(ThisType==="audio"){
        if(typeof ThisObj['audio_url'] !== "undefined"){ThisValue=ThisObj['audio_url'];}
        if(typeof ThisObj['message'] !== "undefined"){ThisValue=ThisObj['message']['attachment']['payload']['url'];}
    }

    if(ThisType==="file"){
        if(typeof ThisObj['file_url'] !== "undefined"){ThisValue=ThisObj['file_url'];}
        if(typeof ThisObj['message'] !== "undefined"){ThisValue=ThisObj['message']['attachment']['payload']['url'];}
    }

    if(ThisType==="video"){
        if(typeof ThisObj['video_url'] !== "undefined"){ThisValue=ThisObj['video_url'];}
        if(typeof ThisObj['message'] !== "undefined"){ThisValue=ThisObj['message']['attachment']['payload']['url'];}
    }

    if(ThisType==="text" || ThisType==="simple" ||ThisType==="buttons" || ThisType==="quick"){
        if(typeof ThisObj['msg_text'] !== "undefined"){ThisValue=ThisObj['msg_text'];}
        if(typeof ThisObj['message'] !== "undefined"){ThisValue=ThisObj['message']['text'];}
    }

    return ThisValue;
}



function ShowMsgInput(ThisType,uniqid,ThisValue){
    var ThisInput="";


    if(ThisType==='audio'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="audio" class="OpenUploadModal upload_btn"><i class="icon-file-audio"></i><input type="button" class="btn btn-primary form-control" value="Upload Audio"></span><input type="hidden" id="'+uniqid+'_audio_url" name="'+uniqid+'_audio_url" value="'+ThisValue+'"><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="audio" />';}

    if(ThisType==='buttons'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Button message</span><textarea class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" data-emojiable="true" placeholder="Enter text" oninput="ChangePreviewText_PREV(\''+uniqid+'\',\'buttons\');"  maxlength="640" style="height:46px;" value="'+ThisValue+'">'+ThisValue+'</textarea> '+PersonalizationHTML()+'<div id="'+uniqid+'_button_settings"><div class="table-noborder" id="'+uniqid+'_button_settings_table"></div></div><span id="'+uniqid+'_AddButton" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="buttons" class="btn btn-primary AddButton">+Add Button</span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="buttons" /><input type="hidden" name="'+uniqid+'_num_buttons" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='carousel' || ThisType==='structured'){ThisInput ='<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="carousel" /><div id="'+uniqid+'_carousel_settings"><span style="float:left; margin-bottom:10px;" class="badge badge-primary">Carousel message</span><table class="table-noborder ibox_table_fix" id="'+uniqid+'_carousel_settings_table" ></table></div><span id="'+uniqid+'_AddCarouselItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="carousel" class="btn btn-primary AddCarouselItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='file'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="file" class="OpenUploadModal upload_btn"><i class="icon-file-empty"></i><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" id="'+uniqid+'_file_url" name="'+uniqid+'_file_url" value="'+ThisValue+'"/><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="file" />';}

    if(ThisType==='image'){ThisInput ='<span href="#myModalImg" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="simple_image" class="OpenImgModal upload_btn"><i class="icon-picture fa-2x"></i><input type="button" class="btn btn-primary form-control" value="Upload Image"></span><input type="hidden" id="'+uniqid+'_img_url" name="'+uniqid+'_img_url"  value="'+ThisValue+'"/><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="img" />';}

    if(ThisType==='list'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">List message</span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="list" /><div id="'+uniqid+'_list_settings"><table class="table-noborder" style="width:100%;" id="'+uniqid+'_list_settings_table"></table></div><span id="'+uniqid+'_AddListItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="list" class="btn btn-primary AddListItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='products'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="products" class="OpenUploadModal upload_btn"><i class="icon-picture fa-2x"></i><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="products" />';}

    if(ThisType==='quick'){
        jQuery('.draggable_operator').addClass('operators_overlay');
        ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Quick Reply message</span><textarea data-emojiable="true" class="form-control broadcast-input" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Button title text" oninput="ChangePreviewText_PREV(\''+uniqid+'\',\'quick\');"  maxlength="640" value="'+ThisValue+'">'+ThisValue+'</textarea> '+PersonalizationHTML()+'<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="quick" /><div id="'+uniqid+'_quick_settings"><table class="table-noborder" id="'+uniqid+'_quick_settings_table"></table></div><span id="'+uniqid+'_AddQuickItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="quick" class="btn btn-primary AddQuickItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" /><div style="clear:both;"></div><div id="qr_alert" class="alert alert-warning fade in alert-dismissable"></div>';}

    if(ThisType==='text'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Text message</span><textarea class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Enter text" data-emojiable="true" oninput="ChangePreviewText_PREV(\''+uniqid+'\',\'text\');"  maxlength="640" style="height:46px;"  value="'+ThisValue+'">'+ThisValue+'</textarea>'+PersonalizationHTML()+'<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="text" />';}

    if(ThisType==='typing'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Typing action</span><select id="'+uniqid+'_typing_view" name="'+uniqid+'_typing_view" class="form-control input-lg" ><option>Select the seconds the typing action should show</option><option value="1">1 Second</option><option value="2">2 Seconds</option><option value="3">3 Seconds</option><option value="4">4 Seconds</option><option value="5">5 Seconds</option><option value="6">6 Seconds</option><option value="7">7 Seconds</option><option value="8">8 Seconds</option><option value="9">9 Seconds</option><option value="10">10 Seconds</option><option value="11">11 Seconds</option><option value="12">12 Seconds</option><option value="13">13 Seconds</option><option value="14">14 Seconds</option><option value="15">15 Seconds</option><option value="16">16 Seconds</option><option value="17">17 Seconds</option><option value="18">18 Seconds</option><option value="19">19 Seconds</option></select><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="typing" />';}

    if(ThisType==='video'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="video" class="OpenUploadModal upload_btn"><i class="icon-file-video"></i><input type="button" class="btn btn-primary form-control" value="Upload Video"></span><input type="hidden" id="'+uniqid+'_video_url" name="'+uniqid+'_video_url" value="'+ThisValue+'"/><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="video" />';}

    return ThisInput;

}

function ShowMsgPreview(ThisType,uniqid,ThisValue){
    var ThisInput="";
    ThisValue = getImage(ThisValue);

    if(ThisType==='audio'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_audio message-left"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='buttons'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text  button_header message-left"></div><div style="clear:both;"></div><div id="buttons_'+uniqid+'"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='carousel' || ThisType==='structured'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_carousel"><div id="carousel_items_'+uniqid+'" class="preview_slider"><input type="hidden" id="'+uniqid+'_currentSlide" value="1"/><div id="slides_'+uniqid+'" class="carouselslides"></div><span class="controls carousel_previous" data-slide_id="'+uniqid+'"><</span><span class="controls carousel_next" data-slide_id="'+uniqid+'">></span></div></div></div><div style="clear:both;"></div>';}

    if(ThisType==='image'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_img"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='file'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_file"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='list'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_list"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='products'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_products message-left"></div></div><div style="clear:both;"></div>';}

    if(ThisType==='quick'){	ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text message-left"></div><div style="clear:both;"></div><div id="slides_'+uniqid+'" class="qslides"><span id="'+uniqid+'_quick_previous" class="controls quick_previous" data-slide_id="'+uniqid+'">&lt;</span><span class="controls quick_next" data-slide_id="'+uniqid+'">&gt;</span><input type="hidden" id="'+uniqid+'_currentSlide" value="1"/><div class="broadcast_preview_quick"></div></div><div style="clear:both;"></div>';	}

    if(ThisType==='text' || ThisType==='simple'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_text message-left">'+ThisValue+'</div></div><div style="clear:both;"></div>';}

    if(ThisType==='typing'){ThisInput ='<div id="preview_'+uniqid+'"><img class="broadcast_preview_typing message-left styling_broadcast_preview_typing" src="/images/typing-indicator.png" /></div><div style="clear:both;"></div>';}

    if(ThisType==='video'){ThisInput ='<div id="preview_'+uniqid+'"><div class="broadcast_preview_video message-left"></div></div><div style="clear:both;"></div>';}

    return ThisInput;

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



            var $img = $.emojiarea.createIcon($.emojiarea.icons[val]);


            return $img;

        }

        else

            return "";

    });



}











$(document).ready(function(){


    setTimeout(function(){

            window.emojiPicker = new EmojiPicker({

                emojiable_selector: '[data-emojiable=true]',

                assetsPath: 'img/',

                popupButtonClasses: 'icon-smile'

            });

            window.emojiPicker.discover();

        }



        , 200);





});



function OurStripslashes(str) {
    str = str.replace(/\\+'<br\/>'+/g, '<br/>');
    return str;
}

$(document).on('click', '.quick_next', function () {
    var MsgID = $(this).data("msg_id");
    GoSlides('next','quick',MsgID);
});

$(document).on('click', '.quick_previous', function () {
    var MsgID = $(this).data("msg_id");
    GoSlides('previous','quick',MsgID);
});

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