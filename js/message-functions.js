function CreateUniqID(){
    var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter2 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    var randLetter3 = String.fromCharCode(65 + Math.floor(Math.random() * 26));
    return randLetter + randLetter2 +randLetter3 +Date.now();
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
            ChangePreviewText(ThisItemId,'text');
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

function ChangePreviewText(uniqid,msgType){

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

function ButtonPreviewStyling(ThisItemId,NewButtonNum,uniqID){
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

function AddButtonMsgItem(ThisItemId,ItemType,NumButtons,ThisItemTitle,ThisItemSubTitle,ThisItemImgUrl,ThisItemUrl,ThisButtonTitle,ThisButtonType,ThisButtonMsg,ThisButtonUrl,ThisButtonPhone,TheseButtons){
    var uniqID = CreateUniqID();

	if(ItemType==="list"){
    $('#'+ThisItemId+'_list_settings_table').append('<tr id="'+uniqID+'" class="list"><td width="70%">' +
        '<input type="hidden" name="'+ThisItemId+'_items[]" value="'+uniqID+'" /> ' +
        '<input type="text" name="'+uniqID+'_list_title" id="'+uniqID+'_list_title" onkeyup="ChangePreviewText(\''+uniqID+'\',\'list_title\');" class="form-control input-lg m-b" placeholder="List title" size="50" maxlength="80" data-item-type="title" value="'+ThisItemTitle+'">' +
        '<input type="text" name="'+uniqID+'_list_subtitle" id="'+uniqID+'_list_subtitle" onkeyup="ChangePreviewText(\''+uniqID+'\',\'list_subtitle\');" class="form-control input-lg m-b" placeholder="Subtext" size="50" maxlength="80" value="'+ThisItemSubTitle+'">' +
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
        ButtonPreviewStyling(ThisItemId,NumButtons + 1,uniqID);
    }

    if(ItemType==="quick"){
        $('#'+ThisItemId+'_quick_settings_table').append('<tr id="'+uniqID+'">' +
            '<td width="60%"><input type="hidden" name="'+ThisItemId+'_button_items[]" value="'+uniqID+'" /> ' +
            '<input type="text" name="'+uniqID+'_quick_title" id="'+uniqID+'_quick_title" onkeyup="ChangePreviewText(\''+uniqID+'\',\'quick_title\');" class="form-control input-lg" placeholder="Button text" size="30"  maxlength="20"  data-item-type="buttons"  value="'+ThisButtonTitle+'">' +
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
            '<input type="text" name="'+uniqID+'_carousel_title" id="'+uniqID+'_carousel_title" onkeyup="ChangePreviewText(\''+uniqID+'\',\'carousel_title\');" class="form-control input-lg m-b" placeholder="Carousel title" size="50" maxlength="80" data-item-type="title"  value="'+ThisItemTitle+'">' +
            '<input type="text" name="'+uniqID+'_carousel_subtitle" id="'+uniqID+'_carousel_subtitle" onkeyup="ChangePreviewText(\''+uniqID+'\',\'carousel_subtitle\');" class="form-control input-lg m-b" placeholder="Subtext" size="50"  maxlength="80"  value="'+ThisItemSubTitle+'">' +
            '<input type="text" name="'+uniqID+'_carousel_url" id="'+uniqID+'_carousel_url" onkeyup="ChangePreviewText(\''+uniqID+'\',\'carousel_url\');" class="form-control input-lg" placeholder="Url to Link to"   value="'+ThisItemUrl+'"/>' +
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

$(document).on('click', '.OpenUploadModal', function () {

    jQuery(".UploadModal #inputFile").val( '');

    var ThisItemId = $(this).data('itemid');

    var ThisMsgId =$(this).data('itemid');

    var ThisType = $(this).data('msgtype');

    jQuery(".UploadModal #item_id").val( ThisItemId );

    jQuery("#upload_message").html('');

    jQuery(".UploadModal #msg_id").val( ThisMsgId );

    jQuery(".UploadModal #msg_type").val(ThisType );



    var ajax_url='../includes/admin-ajax.php';
    var user_id = jQuery('#user_id').val();

    var data={

        'action':'show_file_library',

        'user_id': user_id,

        'msg_type': ThisType

    };

    jQuery.post(ajax_url, data, function(response) {

        jQuery('#file_library_results').html(response);

    });

    jQuery('#myModalUpload').modal();

});

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


function sendOrderToInput(MsgType){
    var tr_id_order = "";
    var AddThis="";
    if(MsgType==="new"){AddThis="_new";}

    $('#vertical-timeline'+AddThis+' .vertical-timeline-block').each(function (i, row) {

        var $row = $(row);
        var $tr_id = $row.attr('id');
        if($tr_id!==""){tr_id_order +=','+$tr_id;}

        var PreviewSelector = $('#preview_'+$tr_id);
        var $ThisPreview = PreviewSelector.html();
        if(typeof $ThisPreview !== "undefined"){

            var NextId = PreviewSelector.next('div').attr('id');
            if( NextId==="" || NextId === "undefined") {
                PreviewSelector.next('div').remove();//this should be the clear both div
            }
            PreviewSelector.remove();
            //not sure why this was there anymore...removed it for now as it created an issue after creating the quick reply
            $('#broadcast_msg_preview'+AddThis).append('<div id="preview_'+$tr_id+'">'+$ThisPreview+'</div><div style="clear:both;"></div>');
        }
    });

    $('#tr_order'+AddThis).val(tr_id_order);
}


function ShowMsgInput(ThisType,uniqid,ThisValue){
    var ThisInput="";


    if(ThisType==='audio'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="audio" class="OpenUploadModal upload_btn"><i class="icon-file-audio"></i><input type="button" class="btn btn-primary form-control" value="Upload Audio"></span><input type="hidden" id="'+uniqid+'_audio_url" name="'+uniqid+'_audio_url" value="'+ThisValue+'"><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="audio" />';}

    if(ThisType==='buttons'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Button message</span><textarea class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" data-emojiable="true" placeholder="Enter text" oninput="ChangePreviewText(\''+uniqid+'\',\'buttons\');"  maxlength="640" style="height:46px;" value="'+ThisValue+'">'+ThisValue+'</textarea> '+PersonalizationHTML()+'<div id="'+uniqid+'_button_settings"><div class="table-noborder" id="'+uniqid+'_button_settings_table"></div></div><span id="'+uniqid+'_AddButton" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="buttons" class="btn btn-primary AddButton">+Add Button</span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="buttons" /><input type="hidden" name="'+uniqid+'_num_buttons" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='carousel' || ThisType==='structured'){ThisInput ='<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="carousel" /><div id="'+uniqid+'_carousel_settings"><span style="float:left; margin-bottom:10px;" class="badge badge-primary">Carousel message</span><table class="table-noborder ibox_table_fix" id="'+uniqid+'_carousel_settings_table" ></table></div><span id="'+uniqid+'_AddCarouselItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="carousel" class="btn btn-primary AddCarouselItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='file'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="file" class="OpenUploadModal upload_btn"><i class="icon-file-empty"></i><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" id="'+uniqid+'_file_url" name="'+uniqid+'_file_url" value="'+ThisValue+'"/><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="file" />';}

    if(ThisType==='image'){ThisInput ='<span href="#myModalImg" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="simple_image" class="OpenImgModal upload_btn"><i class="icon-picture fa-2x"></i><input type="button" class="btn btn-primary form-control" value="Upload Image"></span><input type="hidden" id="'+uniqid+'_img_url" name="'+uniqid+'_img_url"  value="'+ThisValue+'"/><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="img" />';}

    if(ThisType==='list'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">List message</span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="list" /><div id="'+uniqid+'_list_settings"><table class="table-noborder" style="width:100%;" id="'+uniqid+'_list_settings_table"></table></div><span id="'+uniqid+'_AddListItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="list" class="btn btn-primary AddListItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" />';}

    if(ThisType==='products'){ThisInput ='<span href="#myModalUpload" data-itemid="'+uniqid+'"  data-msgid="" data-msgtype="products" class="OpenUploadModal upload_btn"><i class="icon-picture fa-2x"></i><input type="button" class="btn btn-primary form-control" value="Upload File"></span><input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="products" />';}

    if(ThisType==='quick'){
        jQuery('.draggable_operator').addClass('operators_overlay');
        ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Quick Reply message</span><textarea data-emojiable="true" class="form-control broadcast-input" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Button title text" oninput="ChangePreviewText(\''+uniqid+'\',\'quick\');"  maxlength="640" value="'+ThisValue+'">'+ThisValue+'</textarea> '+PersonalizationHTML()+'<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="quick" /><div id="'+uniqid+'_quick_settings"><table class="table-noborder" id="'+uniqid+'_quick_settings_table"></table></div><span id="'+uniqid+'_AddQuickItem" data-itemid="'+uniqid+'"  data-msgid="'+uniqid+'" data-msgtype="quick" class="btn btn-primary AddQuickItem">+Add Item</span><input type="hidden" name="'+uniqid+'_num_items" id="'+uniqid+'_num_items" value="0" /><div style="clear:both;"></div><div id="qr_alert" class="alert alert-warning fade in alert-dismissable"></div>';}

    if(ThisType==='text'){ThisInput ='<span style="float:left; margin-bottom:10px;" class="badge badge-primary">Text message</span><textarea class="form-control" name="text_'+uniqid+'" id="text_'+uniqid+'" placeholder="Enter text" data-emojiable="true" oninput="ChangePreviewText(\''+uniqid+'\',\'text\');"  maxlength="640" style="height:46px;"  value="'+ThisValue+'">'+ThisValue+'</textarea>'+PersonalizationHTML()+'<input type="hidden" name="'+uniqid+'_msg_type" id="'+uniqid+'_msg_type" value="text" />';}

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

function ButtonSelectedItem(ItemID,MsgType,TypeButton,ButtonValue,CurrentButton){

    var ButtonTypesArr = {"button_share":"share", "button_phone":"phone", "button_msg":"postback", "button_link":"web_url"};
        for (var key in ButtonTypesArr) {
            var ThisType = key;
            var ThisTypeValue = ButtonTypesArr[key];
            $('#'+ItemID+'_'+ThisType+'_select'+CurrentButton).removeClass('sticky_menu_item_selected');
            if(ThisTypeValue===TypeButton){$('#'+ItemID+'_'+ThisType+'_select'+CurrentButton).addClass('sticky_menu_item_selected');}
        }
}


$(document).on('click', '.button_list_msg', function () {

    var ItemId = $(this).data('item_id');

    jQuery('#current_item').val(ItemId);

    var MsgId = $(this).data('msg_id');

    jQuery('#current_msg').val(MsgId);

    var MsgTitle = jQuery('#'+ItemId+'_list_title').val();
    if(MsgTitle!==""){

    var ListSelector = $('#list_button_title');

        ListSelector.removeClass('missing_title');
        ListSelector.removeAttr("style");
        ListSelector.attr('placeholder', 'Button text');

        jQuery('#modal_button_list_msg').modal();

        var buttonTitle = jQuery('#'+ItemId+'_button_title').val();

        jQuery('#list_button_title').val(buttonTitle);

        var buttonUrl = jQuery('#'+ItemId+'_button_url').val();

        jQuery('#list_button_url').val(buttonUrl);

        var buttonMsg = jQuery('#'+ItemId+'_button_msg').val();

        jQuery('#list_button_msg').val(buttonMsg);

        var buttonType = jQuery('#'+ItemId+'_button_type').val();

        ButtonSelectedItem(ItemId,'list',buttonType,buttonTitle,'');

        jQuery('#list_button_type').val(buttonType);
    }else{
        var ThisItemId = ItemId+'_list_title';
        MissingTitle(ThisItemId,'Title');
    }

});

$(document).on('click', '.save_button_link', function () {

    var ItemID = jQuery('#current_item').val();
    var MsgID = jQuery('#current_msg').val();
    var MsgType = jQuery('#'+MsgID+'_msg_type').val();
    var ButtonLink = jQuery('#button_link').val();

    if(MsgType==="list"){
        ButtonSelectedItem(ItemID,'list','web_url','','');
        jQuery('#'+ItemID+'_button_url').val(ButtonLink);
        jQuery('#'+ItemID+'_button_type').val('web_url');
    }

    if(MsgType==="carousel" || MsgType==='structured'){
        var CurrentButton = jQuery('#current_button').val();
        jQuery('#'+ItemID+'_button_url_'+CurrentButton).val(ButtonLink);
        jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('web_url');

        ButtonSelectedItem(ItemID,'carousel','web_url','',CurrentButton);
    }

    if(MsgType==="buttons"){
        jQuery('#'+ItemID+'_button_url').val(ButtonLink);
        jQuery('#'+ItemID+'_button_type').val('web_url');
        $('#'+ItemID+'_button_phone_item').removeClass('sticky_menu_item_selected');
        $('#'+ItemID+'_button_msg_select').removeClass('sticky_menu_item_selected');
        $('#'+ItemID+'_button_link_select').addClass('sticky_menu_item_selected');
    }

    jQuery('#button_link').val('');

});

$(document).on('click', '.save_button_phone', function () {

    var ItemID = jQuery('#current_item').val();
    var MsgID = jQuery('#current_msg').val();
    var MsgType = jQuery('#'+MsgID+'_msg_type').val();
    var CurrentButton = jQuery('#current_button').val();
    var ButtonPhone = jQuery('#phone_number').val();

    if(CurrentButton>0){

        if(MsgType==="carousel" || MsgType==="structured"  ){
            jQuery('#'+ItemID+'_button_phone_'+CurrentButton).val(ButtonPhone);
            jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('phone');
        }

        if(MsgType==="list"){
            jQuery('#'+ItemID+'_button_phone').val(ButtonPhone);
            jQuery('#'+ItemID+'_button_type').val('phone');
            ButtonSelectedItem(ItemID,'list','phone','','');
        }

    }else{

        jQuery('#'+ItemID+'_button_phone').val(ButtonPhone);
        jQuery('#'+ItemID+'_button_type').val('phone');

    }

    $('#'+ItemID+'_button_phone_item').addClass('sticky_menu_item_selected');
    $('#'+ItemID+'_button_msg_select').removeClass('sticky_menu_item_selected');
    $('#'+ItemID+'_button_link_select').removeClass('sticky_menu_item_selected');

    ButtonSelectedItem(ItemID,'carousel','phone','',CurrentButton);

    jQuery('#button_phone').html('');

});


$(document).on('click', '.save_button_share', function () {

    var ItemID = jQuery('#current_item').val();

    var CurrentButton = jQuery('#current_button').val();

    if(CurrentButton>0){

        jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('share');
        jQuery('#'+ItemID+'_button_type').val('share');
        jQuery('#'+ItemID+'_preview_button'+CurrentButton).html('Share');
    }else{
        jQuery('#'+ItemID+'_button_type').val('share');
    }

    ButtonSelectedItem(ItemID,'carousel','share','',CurrentButton);
    $('#carousel_button_title'+CurrentButton).val('');
    $('#carousel_button_title'+CurrentButton).attr('placeholder', 'Share Item - No Title Possible');
    ButtonSelectedItem(ItemID,'list','share','','');
    $('#list_button_title').val('');
    $('#list_button_title').attr('placeholder', 'Share Item - No Title Possible');

});



$(document).on('click', '.delete_list_button', function () {

    var ItemId = jQuery('#current_item').val();


    jQuery('#list_button_title').val('');
    jQuery('#'+ItemId+'_button_title').val('');
    jQuery('#'+ItemId+'_button_type').val('');
    jQuery('#'+ItemId+'_preview_button').hide();
    jQuery('#'+ItemId+'_button_list_msg').removeClass('sticky_menu_item_selected');


    jQuery('#list_button_title').val('');

    jQuery('#list_button_url').val('');

    jQuery('#list_button_msg').val('');

    jQuery('#list_button_type').val('');

});


$(document).on('click', '.save_list_button', function () {

    var ItemId = jQuery('#current_item').val();
    var buttonTitle = jQuery('#list_button_title').val();
    jQuery('#'+ItemId+'_button_title').val(buttonTitle);

    if(buttonTitle!==""){
        jQuery('#'+ItemId+'_preview_button').show(); //just in case we have hide it before
        jQuery('#'+ItemId+'_preview_button').html(buttonTitle);
        jQuery('#'+ItemId+'_preview_button').addClass('preview_list_button');

    }else{
        //maybe we have a share item

        var ButtonType = jQuery('#'+ItemId+'_button_type').val();
        if(ButtonType==="share"){
            jQuery('#'+ItemId+'_preview_button').show(); //just in case we have hide it before
            jQuery('#'+ItemId+'_preview_button').html('Share');
            jQuery('#'+ItemId+'_preview_button').addClass('preview_list_button');
        }
    }

    jQuery('#list_button_title').val('');

    jQuery('#list_button_url').val('');

    jQuery('#list_button_msg').val('');

    jQuery('#list_button_type').val('');

});



$(document).on('click', '.save_button_msg', function () {

    var ItemID = jQuery('#current_item').val();

    var MsgID = jQuery('#current_msg').val();

    var ButtonMsg = jQuery('#button1_msg_select').val();

    var CurrentButton = jQuery('#current_button').val();

    var MsgType = jQuery('#'+MsgID+'_msg_type').val();


    if(MsgType==="buttons" || MsgType==="quick"){

        jQuery('#'+ItemID+'_button_msg').val(ButtonMsg);

        jQuery('#'+ItemID+'_button_type').val('postback');



        $('#'+ItemID+'_button_phone_item').removeClass('sticky_menu_item_selected');

        $('#'+ItemID+'_button_msg_select').addClass('sticky_menu_item_selected');

        $('#'+ItemID+'_button_link_select').removeClass('sticky_menu_item_selected');

        if(ButtonMsg!==""){
            $('#'+ItemID+'_quick_msg_select').addClass('sticky_menu_item_selected');
        }else{
            $('#'+ItemID+'_quick_msg_select').removeClass('sticky_menu_item_selected');
        }


    }

    if(MsgType==="carousel"||MsgType==="products"||MsgType==="structured"){
        jQuery('#'+ItemID+'_button_msg_'+CurrentButton).val(ButtonMsg);
        jQuery('#'+ItemID+'_button_type_'+CurrentButton).val('postback');
        ButtonSelectedItem(ItemID,'carousel','postback','',CurrentButton);

    }

    if(MsgType==="list"){
        jQuery('#'+ItemID+'_button_msg').val(ButtonMsg);
        jQuery('#'+ItemID+'_button_type').val('postback');
        ButtonSelectedItem(ItemID,'list','postback','','');
    }

    jQuery('#menu_item_msg_result').html('');

});

jQuery(document).on('change', '#button1_msg_select', function (){
    var ItemID = jQuery('#current_item').val();
    var MsgID = jQuery('#current_msg').val();


    var ButtonVal = this.value;

    if(ButtonVal==="new"){

        jQuery('#new_msg_name').val('');

        jQuery("#new_msgs tr").remove();

        jQuery('#new_msg_id').val('');

        jQuery('#new_tr_order').val('');

        jQuery('#broadcast_msg_preview_new').html('');

        jQuery('#vertical-timeline_new').html('');

        jQuery('#operator_new_message').modal();

    }

}).change();


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


$('#broadcast_msgs_table').on('click','.smartmessenger-delete',function(){
    var Msg = $(this).closest('.vertical-timeline-block');
    var MsgId =$(this).closest('.vertical-timeline-block').attr('id');
    deleteMsgRow(Msg,MsgId,'normal');
});

$('#broadcast_msgs_table_new').on('click','.smartmessenger-delete',function(){

    var Msg = $(this).closest('.vertical-timeline-block');
    var MsgId =$(this).closest('.vertical-timeline-block').attr('id');
    deleteMsgRow(Msg,MsgId,'new');
});

function deleteMsgRow(Msg,MsgId,OrderType){
    var MsgType = jQuery('#'+MsgId+'_msg_type').val();
    if(MsgType==='quick'){jQuery('.draggable_operator').removeClass('operators_overlay');jQuery('.broadcast_alert_msg').html('');}
    Msg.remove();
    $('#msg_id_'+MsgId).remove();

    $('#preview_'+MsgId).next().remove();

    $('#preview_'+MsgId).remove();

    sendOrderToInput(OrderType);


}

$(document).on('click', '.AddQuickItem', function () {

    jQuery('#qr_alert').remove();

    var ThisItemId = $(this).data('itemid');

    var ButtonTitle = jQuery('#text_'+ThisItemId).val();

    if(ButtonTitle!==""){

        var NumItems = jQuery('#'+ThisItemId+'_num_items').val();

        if (NumItems===''){NumItems=Number("1");}else{NumItems=Number(NumItems);}

        if(NumItems<11){
            AddButtonMsgItem(ThisItemId,'quick',NumItems+1,'','','','','','','','','');
            AddNewItemsNum(ThisItemId,NumItems);

            if(NumItems===10){jQuery('.AddQuickItem').hide();}

        }

    }else{
        MissingTitle('text_'+ThisItemId,'Button Text');
    }

});



$(document).on('click', '.AddButton', function () {

    var ThisItemId = $(this).data('itemid');

    var ButtonTxt = jQuery('#text_'+ThisItemId).val();

    if(ButtonTxt!==""){

        var NumButtons = jQuery('#'+ThisItemId+'_num_items').val();

        if (NumButtons===''){NumButtons=Number("1");}else{NumButtons=Number(NumButtons);}

        if(NumButtons<3){

            AddButtonMsgItem(ThisItemId,'buttons',NumButtons+1,'','','','','','','','','');
            AddNewItemsNum(ThisItemId,NumButtons);


            if(NumButtons===2){jQuery('#'+ThisItemId+' .AddButton').hide();}

        }

    }else{
        var EmojiID = jQuery('#text_'+ThisItemId).data('id');
        if(EmojiID!==""){
            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').addClass('missing_title');
            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').attr('placeholder', 'Enter text');
            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').css({"background-color": "#f2dede", "color": "#a94442", "border-color": "#a94442"});
        }
    }

});



$(document).on('click', '.button_share_item', function (e) {

    jQuery('#current_button').val('');
    var CurrentItem = jQuery('#current_item').val();
    var trid,MsgID,MsgType,button_link,ButtonTitle,TitleItem="";
    trid=$(this).data('item_id');
    if(CurrentItem===""){
        if(trid!==""){jQuery('#current_item').val(trid);}
    }else {
        trid=CurrentItem;
        if(trid===null|| trid==="" || typeof trid === "undefined"){trid = jQuery('#current_item').val();}
        if(trid===null || trid===""){ trid = $(this).closest('tr').attr('id');}
    }

    var CurrentButton = $(this).data('button');
    if(CurrentButton >0){
        jQuery('#current_button').val(CurrentButton);
    }

    jQuery('#modal_button_share').modal();
});

$(document).on('click', '.button_phone_item', function (e) {
    jQuery('#phone_number').val('');
    jQuery('#current_button').val('');
    var trid=$(this).data('item_id');
    if(trid===null){trid = jQuery('#current_item').val();}
    var CurrentButton = $(this).data('button'); if(CurrentButton >0){jQuery('#current_button').val(CurrentButton);}

    if(CurrentButton >0){
        jQuery('#current_button').val(CurrentButton);
        var MsgID = jQuery('#current_msg').val();
        var MsgType = jQuery('#'+MsgID+'_msg_type').val();

        if(MsgType==="list" || MsgType==="carousel" || MsgType==="products"){
            var ButtonTitle = jQuery('#'+MsgType+'_button_title'+CurrentButton).val();
            var TitleItem = MsgType+'_button_title'+CurrentButton;
            var button_phone = $('#'+trid+'_button_phone').val();
        }

    }else{
        var button_phone = $('#'+trid+'_button_phone').val();
        var ButtonTitle = jQuery('#'+trid+'_button_title').val();
        var TitleItem = trid+'_button_title';
        jQuery('#current_item').val(trid);
    }


    if(ButtonTitle!==""){
        jQuery('#phone_number').val(button_phone);
        jQuery('#modal_button_phone').removeClass('missing_title');
        jQuery('#modal_button_phone').modal();
    }else{

        MissingTitle(TitleItem,'Button');
    }

});

$(document).on('click', '.add_tag', function () {

    var ThisTagID = $(this).data('tag_id');

    var ThisItemAdded = $(this).data('added');

//selected or not selected...lets see

    if(ThisItemAdded===""){

        $(this).data("added","added");

        $(this).addClass('btn-primary');

        $('#vertical-timeline').append('<input type="hidden" name="tags[]" value="'+ThisTagID+'" id="'+ThisTagID+'_tag" />');



    }else{

        $('#'+ThisTagID+'_tag').remove();

        $(this).data("added","");

        $(this).removeClass('btn-primary');

    }

});

$(document).on('click', '.button_link_item', function (e) {

    jQuery('#current_button').val('');
    var CurrentItem = jQuery('#current_item').val();
    var trid,MsgID,MsgType,button_link,ButtonTitle,TitleItem="";
    trid=$(this).data('item_id');
    if(CurrentItem===""){
        if(trid!==""){jQuery('#current_item').val(trid);}
    }else {
        trid=CurrentItem;
        if(trid===null|| trid==="" || typeof trid === "undefined"){trid = jQuery('#current_item').val();}
        if(trid===null || trid===""){ trid = $(this).closest('tr').attr('id');}
    }

    MsgID=$(this).data('msg_id');

    var CurrentButton = $(this).data('button');
    if(CurrentButton >0){
        jQuery('#current_button').val(CurrentButton);
        MsgID = jQuery('#current_msg').val();
        MsgType = jQuery('#'+MsgID+'_msg_type').val();


        if(MsgType==="list"){button_link = $('#'+trid+'_button_url').val();	ButtonTitle = jQuery('#list_button_title').val();TitleItem = 'list_button_title';}
        if(MsgType==="carousel"){button_link = $('#'+trid+'_button_url_'+CurrentButton).val();	ButtonTitle = jQuery('#carousel_button_title'+CurrentButton).val();TitleItem = 'carousel_button_title'+CurrentButton;}

    }else{
        button_link = $('#'+trid+'_button_url').val();
        ButtonTitle = jQuery('#'+trid+'_button_title').val();
        TitleItem = trid+'_button_title';
    }

    MsgType = jQuery('#'+MsgID+'_msg_type').val();

    if(MsgType==="buttons"){
        trid=$(this).data('item_id');
        button_link = $('#'+trid+'_button_url').val();
        ButtonTitle = jQuery('#'+trid+'_button_title').val();
        TitleItem = trid+'_button_title';
    }

    if(ButtonTitle!==""){
        if(button_link!==''){jQuery('#button_link').val(button_link);}else{jQuery('#button_link').val('');}
        jQuery('#current_item').val(trid);
        jQuery('#current_msg').val(MsgID);
        jQuery('#modal_button_link').removeClass('missing_title');
        jQuery('#modal_button_link').modal();

    }else{
        var EmojiID = jQuery('#'+trid+'_button_title').data('id');
        if(EmojiID!==""){
            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').addClass('missing_title');

            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').attr('placeholder', 'Button text');
            $('.emoji-wysiwyg-editor[data-id="'+EmojiID+'"]').css({"background-color": "#f2dede", "color": "#a94442", "border-color": "#a94442"});
        }
        MissingTitle(TitleItem,'Button');
    }
});




$(document).on('click', '.button_msg_item', function () {


    var CurrentButton = $(this).data('button');
    var MsgID,MsgType="";
    if(CurrentButton >0){
        //we have a list or carousel item here...the item id and msg id are already filled

        MsgID = jQuery('#current_msg').val();
        MsgType = jQuery('#'+MsgID+'_msg_type').val();

        jQuery('#current_button').val(CurrentButton);

        if(MsgType==="list"){var ButtonTitle = jQuery('#list_button_title').val();var TitleItem = 'list_button_title';}
        if(MsgType==="carousel"){var ButtonTitle = jQuery('#carousel_button_title'+CurrentButton).val();var TitleItem = 'carousel_button_title'+CurrentButton;}

    }else{
        //we have a button item here or quick button
        var trid=$(this).data('item_id');
        jQuery('#current_button').val('');
        var CurrentItem = $(this).data('item_id');
        var MsgItem = $(this).data('msg_id');

        MsgType = jQuery('#'+MsgItem+'_msg_type').val();

        jQuery('#current_item').val(CurrentItem);
        jQuery('#current_msg').val(MsgItem);
        ButtonMsg = jQuery('#'+CurrentItem+'_button_msg').val();
        if(MsgType==="quick"){
            var ButtonTitle = jQuery('#'+trid+'_quick_title').val();
            var TitleItem = trid+'_quick_title';
        }else{
            var ButtonTitle = jQuery('#'+trid+'_button_title').val();
            var TitleItem = trid+'_button_title';
        }
    }

    if(ButtonTitle!==""){
        jQuery('#modal_button_msg >input').removeClass('missing_title');
        $('#modal_button_msg >input').removeAttr("style");
        $('#modal_button_msg >input').attr('placeholder', 'Button text');

        jQuery('#modal_button_msg').modal();

        var ajax_url='../includes/admin-ajax.php';
        var user_id=jQuery('#user_id').val();
        var page_id=jQuery('#page_id').val();
        var data = {'action': 'menu_item_msg',

            'user_id': user_id,

            'page_id': page_id,

            'bot_id': '',

            'msg_id': MsgID,

            'msg_type':MsgType,

            'button_msg':ButtonMsg,

            'item_id': CurrentItem

        };


        jQuery.post(ajax_url, data, function(response) {

            var response_arr = response.split("|", 3);

            jQuery("#menu_item_msg_result").html(response_arr[0]);

        });
    }else{

        MissingTitle(TitleItem,'Button');
    }




});




$(document).on('click', '.delete_item', function (e) {

    var ThisItemId = $(this).data('itemid');

    var trid = $(this).data('trid');

    var MsgType = jQuery('#'+ThisItemId+'_msg_type').val();

    $('#'+trid).remove();

    $('#'+trid+'_preview').remove();

    var NumItems = Number(jQuery('#'+ThisItemId+'_num_items').val());

    var NewNum = Number("1");

    NewItemsNum = NumItems-NewNum;

    ButtonPreviewStyling(ThisItemId,NewItemsNum,'');

    jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);

    if(MsgType==="buttons"){ButtonPreviewStyling(ThisItemId,NewItemsNum,'');if(NewItemsNum<3){jQuery('#'+ThisItemId+' .AddButton').show();}}
    if(MsgType==="carousel"){$('#slide_'+trid).remove();if(NewItemsNum<11){jQuery('#'+ThisItemId+' .AddCarouselItem').show();}}
    if(MsgType==="list"){if(NewItemsNum<4){jQuery('#'+ThisItemId+' .AddListItem').show();}}
    if(MsgType==="quick" && NewItemsNum<11){jQuery('.AddQuickItem').show();}

});


$(document).on('click', '.inputFileSelect', function () {

    jQuery("#upload_message").html('');

});


jQuery("#inputFile").change(function() {

    jQuery("#upload_message").html('');

    jQuery("#upload_message").html('uploading.....');

    jQuery.ajax({url: "../includes/admin-ajax.php",type: "POST",data: new FormData($('#uploadfile')[0]), contentType: false,cache: false,processData:false,success: function(response)

    {

        var ThisItemId =jQuery(".UploadModal #item_id").val();

        var ThisMsgId =jQuery('#edit_msgid').val();

        var response_arr = response.split("|", 4);

        jQuery("#upload_message").html(response_arr['0']);

        var UploadUrl = response_arr['1'];



        if(UploadUrl!==""){

            var ThisType = response_arr['2'];

            var UploadName = response_arr['3'];

            if(ThisType==='audio'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,'','audio');
            }

            if(ThisType==='video'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,'','video');
            }

            if(ThisType==='file'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,UploadName,'file');
            }

        }



    }});

});

jQuery("#uploadfile").on('submit',(function(e) {

    e.preventDefault();

    jQuery("#upload_message").html('');

    jQuery.ajax({url: "../includes/admin-ajax.php",type: "POST",data: new FormData(this), contentType: false,cache: false,processData:false,success: function(response)

    {

        var ThisItemId =jQuery(".UploadModal #item_id").val();

        var response_arr = response.split("|", 4);

        jQuery("#upload_message").html(response_arr['0']);

        var UploadUrl = response_arr['1'];

        if(UploadUrl!==""){

            var ThisType = response_arr['2'];
            var UploadName = response_arr['3'];
            if(ThisType==='audio'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,'','audio');
            }

            if(ThisType==='video'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,'','video');
            }

            if(ThisType==='file'){
                ChangePreviewPrefill(ThisItemId,UploadUrl,UploadName,'file');
            }

        }


    }});

}));

$(document).on('click', '.quick_next', function (e) {
    var MsgID  = jQuery(this).data('slide_id');
    var currentSlide = Number($('#'+MsgID+'_currentSlide').val());
    var NumSlides = $('#'+MsgID+'_num_items').val();
    if(currentSlide==NumSlides){
        currentSlide = 0; //if we reach the max number of cards and a new next is clicked we loop back to #1. 0 * -100 = 0 thus we have a margin left of 0
        jQuery('#'+MsgID+'_quick_previous').css('opacity', '0');
    }else{
        jQuery('#'+MsgID+'_quick_previous').css('opacity', '1');
    }
    var NewSlide = currentSlide + 1;
    $('#'+MsgID+'_currentSlide').val(NewSlide);
    var MarginLeft = currentSlide * -100;
    $(".broadcast_preview_quick").animate({'marginLeft': MarginLeft}, 500);

});

$(document).on('click', '.quick_previous', function (e) {
    var MsgID  = jQuery(this).data('slide_id');
    var currentSlide = Number($('#'+MsgID+'_currentSlide').val());
    var NumSlides = $('#'+MsgID+'_num_items').val();
    var NewSlide = currentSlide - 1;
    if(NewSlide<2){jQuery('#'+MsgID+'_quick_previous').css('opacity','0');}
    if(NewSlide > 0){ //only do a previous if we are at #2 or higher
        $('#'+MsgID+'_currentSlide').val(NewSlide);
        var MarginLeft =( NewSlide - 1) * -100;
        $(".broadcast_preview_quick").animate({    'marginLeft': MarginLeft}, 500);
    }
});



function setEndOfContenteditable(contentEditableElement)
{
    var range,selection;
    if(document.createRange)//Firefox, Chrome, Opera, Safari, IE 9+
    {
        range = document.createRange();//Create a range (a range is a like the selection but invisible)
        range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        selection = window.getSelection();//get the selection object (allows you to change selection)
        selection.removeAllRanges();//remove any selections already made
        selection.addRange(range);//make the range you have just created the visible selection
    }
    else if(document.selection)//IE 8 and lower
    {
        range = document.body.createTextRange();//Create a range (a range is a like the selection but invisible)
        range.moveToElementText(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        range.select();//Select the range (make it the visible selection
    }
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


    jQuery("#flowselector_welcome_message, #flowselector_default_reply").on("change", function(e) {

        var id = $(this,':selected').val();



        if (id=="createnewflow"){

            modalPrompt("Please enter your new flow's name:","",
                function(flowname){
                    if(flowname === null || flowname === "") {
                        //user did not enter anything
                        txt = "User cancelled the prompt.";
                    }
                    else{
                        window.location = "create_flow.php?name="+flowname;
                    }
                },
                function(value){
                    //user clicked cancel
                });
        } else if(id!="select") {

            var ajax_url = 'includes/admin-ajax.php';
            var data = {
                'action': 'Get_flow_preview',
                'flow_id': id
            };
            jQuery.post(ajax_url, data, function (response) {
                if (response !== "") {

                    MsgObj = JSON.parse(response);

                    window.emojiPicker = new EmojiPicker({

                        emojiable_selector: '[data-emojiable=true]',

                        assetsPath: 'img/',

                        popupButtonClasses: 'icon-smile'

                    });

                    $('#broadcast_msg_preview').html("");

                    for (i in MsgObj) {
                        ThisMsgObj = JSON.parse('{' + MsgObj[i] + '}');
                        var PreviewContainer = $('#broadcast_msg_preview');
                        CreatePreviewMsgElements(ThisMsgObj, PreviewContainer, '');
                    }


                }


            });
        }

        });


    $(document).on('mouseover', '.emoji-picker-icon', function () {
        elem = $(this).parent().find('.emoji-wysiwyg-editor');
        if (!elem.is(":focus")) {

            elem.focus();
            setEndOfContenteditable(elem.get(0));
        }

    });


    $(document).on('mouseover', '.personalization-tag-picker', function () {
        elem = $(this).closest(".personalization-tag-menu").parent().find('.emoji-wysiwyg-editor');
        if (!elem.is(":focus")) {
            elem.focus();
            setEndOfContenteditable(elem.get(0));
        }


    });


});



function OurStripslashes(str) {
    str = str.replace(/\\+'<br\/>'+/g, '<br/>');
    return str;
}