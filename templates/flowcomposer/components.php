<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19/07/18
 * Time: 09:25 م
 */

function getCardTitleInput(){
    return '<label>Card Title</label><input class="form-control input-lg"  data-action="card-title-input" type="text" maxlength="45"  placeholder="Message name">';
}

function getHeadersInput(){
    return '<label>Headers</label><div id="headers_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-header"  data-type="button" >Add Header</span>';

}

function getFormDataInput($display="none",$label="Form Data"){
    return '<div  id="form_data_container" style="display: '.$display.' "><label>'.$label.'</label><div id="form_values_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-form-value"  data-type="button" >Add Form Value</span></div>';

}


function getVariantsInput(){
    return '<div  id="form_data_container"><div id="variants_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-variant"  data-type="button" >Add Variant</span></div>';

}

function getButtonsInput(){
    return '<div id="buttons_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-button" data-target="button" data-type="button" >Add Button</span>';

}

function getQuickRepliesInput(){
    return '<div id="quickreplies_container" class="quickreplies-container"></div><span data-msgtype="buttons" class="btn btn-primary add-quickreply-button m-t" data-action="add-quickreply" data-target="quickreply" data-type="quickreply" >Add Quick Reply</span>';

}

function getTemplateElement(){
    return '<div id="template_element_container" class="template-element"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t add-template-element-button" data-action="add-template-element" data-target="template-element" data-type="template-element" >Add Element</span>';

}

function getModalFooter(){

    return '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal" data-action="save-card-settings" >Save</button></div>';

}

function getModalClose($type=false){
    return '
    <button type="button" class="close" data-dismiss="modal"><i class="userpilot-breadcrumb-i icon-cross"></i></button>
                <button type="button" class="close tour-trigger" data-target="'.$type.'-card"><i class="userpilot-breadcrumb-i icon-menu-square medium"></i></button>';

}
function getNavigationTabs($type,$tabs="extended",$airVariables=false){
    $var = "";
    if ($airVariables) {
        $var = '<li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_air_variables" aria-expanded="false">Air Variables</a></li>';
    }
    switch ($tabs) {
        case "limited":
            return '<ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_settings" aria-expanded="true">Settings</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_triggers" aria-expanded="false">Triggers</a></li>
                      '.$var.'
                      </ul>';
            break;
        case "snippet":
            return '<ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_settings" aria-expanded="true">Settings</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_triggers" aria-expanded="false">Triggers</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_clever_snippet" aria-expanded="false">Clever Snippet</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_documentation" aria-expanded="false">Documentation</a></li>
'.$var.'
                      </ul>';
            break;
        default:
            return '<ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_settings" aria-expanded="true">Settings</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_triggers" aria-expanded="false">Triggers</a></li>
                      <li class=""><a data-toggle="tab" class="navigation-anchor" href="#' . $type . '_ads_json" aria-expanded="false">Click-to-Messenger Ads</a></li>
                     '.$var.'
                      </ul>';
    }
}

function getTriggersInputs($type){
    return '<p class="settings-p">Keywords</p><span>Subscribers who interact with your chatbot using the keywords you specify here will trigger this card</span><hr class="settings-hr">
            <div class="triggers-containers">
          
		    <div class="input-group keywords_input">
		    
			<input data-action="positive-keywords-input" value="" class="form-control add_pos_keyword_field" placeholder=" Enter positive keyword(s) - this will trigger your message." size="30">
			<span class="input-group-btn" data-action="add-positive-keyword" ><button type="button" class="btn btn-primary">Add</button></span>
		       
			</div>
			       <div style="clear:both;"></div>

		  <div id="" class="positive-keywords-container"></div>
<br>

				 <div class="input-group keywords_input">
						 <input data-action="negative-keywords-input" value="" class="form-control add_neg_keyword_field" placeholder="Enter negative keyword(s) - this will STOP the positive keyword." size="30">
						 	  <span class="input-group-btn" data-action="add-negative-keyword" ><button type="button" class="btn btn-primary styling_model_tags_fieldbutton">Exclude</button></span>
				 </div>
				 <div style="clear:both;"></div>
				 <div id="" class="negative-keywords-container"></div>
				 <br>  
</div>

<p class="settings-p">M.me link</p><span>Any user (even not subscribed yet) to your chatbot will receive this message by clicking this M.me link </span><hr class="settings-hr">
<div class="mmlink-container"><a data-action="mmelink" href="#" target="_blank"> https://m.me/'.$_SESSION["page_id"].'?ref=<span class="mmlink"></span></a></div>
<p class="settings-p">M.me link parameters</p><span>You can add parameters to your M.me links in order to dynamically set values to existing custom fields and tags, all parameters need to be separated by <b>--</b> for example to set both the email and phone custom fields as well as attach few tags to your subscriber, your M.me link will be :  
<div class="mmlink-container"><a data-action="mmelink" href="#" target="_blank"> https://m.me/'.$_SESSION["page_id"].'?ref=<span class="mmlink2"></span>--email=test@test.com--phone=066666665--tags=tag1,tag2</a></div>
<hr class="settings-hr">


<br><p class="settings-p">API Action JSON</p><span>Use Clever Messenger <a target="_blank" href="https://clevermessenger.docs.apiary.io">API</a> to send this card programmatically</span><hr class="settings-hr">
<div class=""><span>{"action" : "send_card","flow_id":"<span class="flow-id"></span>","card_id":"<span class="card-id"></span>"}</div>


				 ';
}

function getJsonPreviewContainer($type){
    return '<span>Use the following JSON code to send this card in your click-to-Messenger ads</span><div style="clear:both"></div><span id="json_personalization_warning" style="
    color: red;display: none;
">Please note that you need to replace the personalization text with your own text since it is not yet supported by Click-to-messenger Ads</span><input type="hidden" class="'.$type.'_display_json_codes_raw">
                        <pre class="json-container"><code class="'.$type.'_display_json_codes"></code>
                        </pre>';
}

function getCleverSnippetContainer($type){
    return '<span>Clever Snippets allow powerful community driven webhooks (Share or insert)</span><div style="clear:both"></div><span style="
    color: red;display: none;
">Only paste snippets from trusted people/companies</span><input type="hidden" class="'.$type.'_display_json_codes_raw">
                        <pre  contenteditable="true" class="clever-snippet-container snippet-container"><code class="'.$type.'_display_json_codes"></code>
                        </pre>';
}

function getAccountSelectorDiv($type){
    $div = '<div id="'.$type.'_account_container">
                                        <label>Select an Account</label>
                                        <select class="demo-default form-control input" data-action="integration-account-select" >
                                            <option value="select">Select</option>';

                                            $accounts = getIntegrationsByServiceProviderName($_SESSION["page_id"],"$type");
                                            foreach ($accounts as $account){
                                                $div .= "<option value='$account->id'>$account->name</option>";
                                            }

                            $div .='
                                        </select>
                                    </div>';
                                            return $div;
}

function getServiceActions($types){
    $div  = '
                                    <div id="<?php echo $cardType ?>_type_container">
                                        <label>Select an action</label>
                                        <select class="demo-default action-type form-control input"  data-action="integration-action-type-select" >

                                            <option value="select">Select Action</option>
                                            ';
            switch ($types) {
                case 'active-campaign':
                case 'contact-reach':
                    $div .= '
                                            <option value="create_list_contact">Add Subscriber to Campaign</option>
                                            <option value="delete_list_contact">Remove Subscriber from Campaign</option>';

                    break;

                case 'wowing':
                    $div .= '
                                            <option value="create_list_contact">Add Subscriber to automation</option>
                                            <option value="delete_list_contact">Remove Subscriber from automation</option>';

                    break;
            }
            $div.='
                                        </select>
                                    </div>';
        return $div;
}

function getServiceLists($listName){

    $div ='<label>Select '.$listName.'</label>
                                            <select class="form-control input" data-action="autoresponder-list-select" >
                                                <option value="select">Select</option>


                                            </select>';
    return $div;
}