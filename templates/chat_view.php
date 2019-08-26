<?php

function smartbot_chat_view($msg_avatar_class,$msg_avatar,$author,$msg_date,$this_msg,$originalmsg,$msgid){

    $originalmsg = json_decode($originalmsg);



    if (isset($originalmsg->attachments[0]->payload->sticker_id)){
        $stickerID = $originalmsg->attachments[0]->payload->sticker_id;
        if ($stickerID == "369239263222822")
            $subtype = "thumb_small";
        else if ($stickerID == "369239343222814")
            $subtype = "thumb_medium";
        else if ($stickerID == "369239383222810")
            $subtype = "thumb_big";
        else
            $subtype = "sticker";
    }else {
        $subtype = "";
    }

    if (isset($originalmsg->quick_reply))
        $type = "text";
        else {
            if (isset($originalmsg->attachments[0]->type)) {
                $type = $originalmsg->attachments[0]->type;
            } else {
                $type = "text";
            }
        }

    $this_chat_view='<div class="chat-message" id="'.$msgid.'">';
    $msg_date = date('m/d/Y H:i:s', $msg_date);
        if($msg_avatar_class=='left'){$this_chat_view.='<img class="message-avatar-'.$msg_avatar_class.'" src="'.$msg_avatar.'" alt="'.$author.'" >';}
    
    $this_chat_view.='<div class="message-'.$msg_avatar_class.'">
                                
                                            <span class="message-content '.$type.' '.$subtype.'" data-toggle="tooltip" data-placement="right" title="" data-original-title="'.$msg_date.'">
											'.$this_msg.'
                                            </span>
                                            
                                        </div>
                                    </div>
									<div style="clear:both;"></div>
									';
    return $this_chat_view;
}

