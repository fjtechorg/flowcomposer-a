<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12/07/18
 * Time: 07:17 م
 */


function smartbot_rename_flow($page_id,$flow_id,$flow_name){
    global $wpdb;
    if($page_id!="" && $flow_id!="" && $flow_name != ""){
        //we already double checked so let's delete the profile from our list
        $wpdb->query($wpdb->prepare("UPDATE smartbot_flows SET name = %s WHERE page_id = %s AND id=%d",$flow_name, $page_id, $flow_id));
        //if all is ok we let it know
        return 'success';
    }
}


function smartbot_delete_flow($page_id,$flow_id){
    global $wpdb;
    if($page_id!="" && $flow_id!=""){
        require __DIR__.'/vendor/autoload.php';
        $mongoClient = new MongoDB\Client;
        $collection = $mongoClient->clevermessenger->flows;
        $flowId = getFlowData($page_id,$flow_id)->id;

        //we already double checked so let's delete the profile from our list
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_flows WHERE page_id = %s AND id=%d", $page_id, $flow_id));

        if ($flowId) {

             $collection->deleteOne(['flow' => "$flowId"]);
        }

        return 'success';
    }
}


function createFlow($page_id,$name){
    global  $wpdb;
    $wpdb->insert('smartbot_flows', array('page_id' => $page_id,'name' => $name,'main'=>0));
    return $wpdb->insert_id;
}


function smartbot_show_flows($page_id){
    global $wpdb;

    $this_id = $_SESSION['user']['id'];
    $level = $wpdb->get_var($wpdb->prepare("SELECT membership_level from smartbot_membership_users WHERE user_id=%s",$this_id));
    if($level=="9"){
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_flows WHERE page_id =%s",$page_id),ARRAY_A);
    }else{
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_flows WHERE page_id = '%s' ",$page_id),ARRAY_A);
    }
    $x=0;
    $show='';
    foreach($results as $rij){
        $show='yes';
        $flow_id = $rij['id'];
        $flow_name = $rij['name'];
        $flow_main = $rij['main'];

        if ($flow_main){$disable = "disabled";
            $x++;
            if($x>1){
                //we have a double Main flow due to some previous error or mistake...lets delete it
                $wpdb->query($wpdb->prepare("DELETE FROM smartbot_flows WHERE id=%s", $flow_id));
                $show='no';
            }
        } else {$disable = "";}
        if ($flow_main) $delete="";
        else  $delete = "<li><a data-flow_id=\"".$flow_id."\" data-flow_name=\"".$flow_name."\" class=\"delete_flow\" >Delete</a></li>";

        if($show=='yes'){
            echo "<tr id=\"flow_".$flow_id."\"><td><input $disable type=\"checkbox\" value=\"".$rij['id']."\" class=\"i-checks action_single_check\"/></td>
				<td id='flowname_$flow_id'> ".$flow_name."</td>
                <td>
                <div class=\"btn-group\">
                    <button data-toggle=\"dropdown\" class=\"btn btn-default btn-sm dropdown-toggle\" aria-expanded=\"false\" style=\"background: white;color: grey;\">Actions <span class=\"caret\"></span></button>
                    <ul class=\"dropdown-menu pull-right\">
                    <li><a onclick=\"window.location='composer.php?flow=$flow_id'\" data-flow_id=\"".$flow_id."\" class=\"build_flow\" >Edit</a></li>
				    
				    <li><a class=\"rename_flow\" data-flow_id=\"".$flow_id."\" >Rename</a></li>
				    ".$delete."
				   
                    </ul>
                </div>
                
                </td>
                </tr>
				";
        }
    }
}



function smartbot_get_import_into_flows($user_id,$flow_id){
    global $wpdb;$flows="";
    $results = $wpdb->get_results($wpdb->prepare("SELECT *,smartbot_flows.id as flow_id FROM smartbot_flows,smartbot_pages WHERE smartbot_flows.page_id=smartbot_pages.page_id AND smartbot_flows.page_id IN(SELECT DISTINCT smartbot_page_owners.page_id FROM smartbot_page_owners,smartbot_pages WHERE smartbot_page_owners.user_id=%s AND smartbot_page_owners.page_id=smartbot_pages.page_id AND smartbot_pages.webhook_url='subscribed') GROUP BY smartbot_flows.id ORDER BY smartbot_pages.page_title,smartbot_flows.name",$user_id),ARRAY_A);
    if(isset($results) && is_array($results)){
        foreach($results as $this_result){
            $flows.='<option value="'.$this_result['flow_id'].'">'.$this_result['page_title'].' - '.$this_result['name'].'</option>';
        }
    }
    return $flows;
}


function smartbot_share_flow($user_id,$user_name, $flow_id,$share_type,$page_id){
    global $wpdb;
    $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_flow_share WHERE flow_id=%s", $flow_id));
    if($num_rows <1){
        $flow_name = smartbot_get_flow_name($flow_id,$page_id);
        $share_date = date("Y-m-d");
        $wpdb->insert('smartbot_flow_share', array('flow_id' => $flow_id,'flow_name' => $flow_name,'user_id' => $user_id,'user_name' => $user_name,'share_type' => $share_type,'approved'=>1,'share_date'=>$share_date));
    }
}

function smartbot_get_shared_flows(){
    global $wpdb;$flows="";
    $results = $wpdb->get_results("SELECT * FROM smartbot_flow_share,smartbot_flows WHERE smartbot_flow_share.flow_id=smartbot_flows.id ORDER By approved, flow_name",ARRAY_A);

    if(isset($results) && is_array($results)){
        foreach($results as $this_flow){
            if($this_flow['approved']==1){$status="approved";}if($this_flow['approved']==0){$status="pending";}if($this_flow['approved']==2){$status="rejected";}
            $profile_id =  smartbot_get_profile_id($this_flow['user_id']);
            $flows .='<tr id="flow_'.$this_flow['flow_id'].'"><td width="10"></td>
<td width="300">'.$this_flow['flow_name'].'</td>
<td width="300">'.$this_flow['user_name'].'</td>
<td width="300"><span id="share_type_'.$this_flow['flow_id'].'">'.$this_flow['share_type'].'</span></td>
<td width="300"><span id="status_'.$this_flow['flow_id'].'">'.$status.'</span></td>
<td width="150">'.$this_flow['share_date'].'</td>
<td width="50"><i data-flow_id="'.$this_flow['flow_id'].'" class="manage_flow fa icon-pencil fa-3"></i></td>
<td width="50"><i data-flow_id="'.$this_flow['flow_id'].'" data-page_id="'.$this_flow['page_id'].'" data-profile_id="'.$profile_id.'" class="view_flow fa icon-zoom-in fa-3"></i></td>
<td width="50"><i data-flow_id="'.$this_flow['flow_id'].'" data-flow_name="'.$this_flow['flow_name'].'" class="delete_flow fa icon-cross fa-3"></i></td></tr>';
        }
    }
    return $flows;
}

function smartbot_share_flow_content($flow_id, $flow_name){
    global $wpdb;$flows="";
    //first check if we have this flow already in our db as a share flow
    $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_flow_share WHERE flow_id=%s",$flow_id));
    if($num_rows>0){
        //ok we have results and thus we need to show something....
        $share_flow = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_flow_share WHERE flow_id=%s",$flow_id),ARRAY_A);
        $last_q = $wpdb->last_query;
        //now if it is not yet approved we show it...
        if($share_flow['approved']==0){
            $flows='<p><strong>Shareing This Flow is Pending</strong><br>We received your request and will look into it asap and when approved it will show here. If we do not react in 24 hours send us a message</br>';
        }else{
            //ok the sharing is approved....if it is a public share then we have 2 options...remove sharing or change the share to private
            $flows='<p><strong>Shared Flow</strong><br>';
            if($share_flow['share_type']=='public'){
                $flows.='This flow is shared <strong>publicly</strong>. This means everyone can use it. To edit or delete this use the buttons below</p>
				<p><button class="btn btn-danger share_flow_delete" data-flow_id="'.$flow_id.'">Stop Sharing This Flow</button> <button class="btn btn-primary share_flow_private" data-flow_id="'.$flow_id.'">Change to Private Sharing This Flow</button></p>
				';
            }else{
                $flows.='<p>This flow is shared <strong>privately</strong>. This means only users you invate can use it. To edit or delete this use the buttons below</p>
				<p><button class="btn btn-danger share_flow_delete" data-flow_id="'.$flow_id.'">Stop Sharing This Flow</button> <button class="btn btn-primary share_flow_public" data-flow_id="'.$flow_id.'">Change to Public Sharing This Flow</button></p>
				<strong>Manage Users</strong><br>
				<span id="share_users_response"></span>
				<div id="share_users">
				';
                $flows.=smartbot_get_private_shares($flow_id);
                $flows.='</div>
				<div id="chat_tags_button" class="input-group" style="margin-bottom: 20px;">
                    <input id="user_name" value="" class="form-control share_user_name" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" placeholder="Enter the username here...">
                    <span class="input-group-btn" id="add_user"><button type="button" class="btn btn-primary add_user" style="padding: 6px 16px;border-radius: 0 4px 4px 0;" data-flow_id="'.$flow_id.'">Add User</button></span>
                 </div>';
            }
        }
    }

    return $flows;
}

function smartbot_get_private_shares($flow_id){
    global $wpdb;$users="";
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_flow_share_private WHERE flow_id=%s ORDER BY user_name", $flow_id), ARRAY_A);
    if(isset($results) && is_array($results)){
        $users .='<div id="flow_users">';
        foreach ($results as $this_user){
            $users .= '<span class="chat_tags" id="user_'.$this_user['user_id'].'">'.$this_user['user_name'].' <span class="delete_user" data-user_id="'.$this_user['user_id'].'" data-flow_id="'.$flow_id.'"> <i class="fa icon-cross"></i></span></span>';

        }
        $users .='</div>';
    }
    return $users;
}

function  smartbot_flow_delete_user($flow_id,$user_id){
    global $wpdb;
    if($flow_id!="" && $user_id!=""){
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_flow_share_private WHERE flow_id=%s AND user_id=%s",$flow_id,$user_id));
    }
}

function smartbot_flow_add_user($flow_id,$user_name){
    global $wpdb;$users='';$user_id="";$last_q="";
    if($flow_id!="" && $user_name!=""){
        $user_id = smartbot_get_user_id($user_name);
        //do we have a result?..if not we need to say so
        if($user_id==""){
            //we ave not found this user....
            $users ='fail|'.$user_name;
        }else{

            //do we have this combo already? ....
            $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_flow_share_private WHERE flow_id=%s AND user_id=%s",$flow_id,$user_id));
            if($num_rows <1 ){
                $share_date = date("Y-m-d");
                $wpdb->insert('smartbot_flow_share_private', array('flow_id' => $flow_id,'user_name' => $user_name,'user_id'=>$user_id,'share_date'=>$share_date));
                $last_q=$wpdb->last_query;
                $users = 'success|<span class="chat_tags" id="user_'.$user_id.'">'.$user_name.' <span class="delete_user" data-user_id="'.$user_id.'" data-flow_id="'.$flow_id.'"> <i class="fa icon-cross"></i></span></span>';
            }
        }
    }
    return $users;
}


function  smartbot_flow_delete_share($flow_id){
    global $wpdb;
    if($flow_id!=""){
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_flow_share WHERE flow_id=%s",$flow_id));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_flow_share_private WHERE flow_id=%s",$flow_id));
    }
}

function  smartbot_flow_public_share($flow_id){
    global $wpdb;
    if($flow_id!=""){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_flow_share SET share_type='public' WHERE flow_id=%s",$flow_id));
    }
}

function  smartbot_flow_private_share($flow_id){
    global $wpdb;
    if($flow_id!=""){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_flow_share SET share_type='private' WHERE flow_id=%s",$flow_id));
    }
}

function smartbot_import_flow_yes($user_id,$page_id,$flow_id,$share_id){
    global $wpdb;
    if($flow_id!="" && $page_id!="" && $share_id!=""){
        //we need to import the flow from the share_id into the flow_id flow
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_msgs WHERE flow_id=%s",$share_id), ARRAY_A);
        $msg_arr = smartbot_clone_msgs($results,$page_id,$user_id,$flow_id);
        return $msg_arr;
    }
}

function smartbot_import_into_flow_yes($user_id,$flow_id,$share_id){
    global $wpdb;
    if($flow_id!="" && $user_id!="" && $share_id!=""){
        //we need to import the flow from the share_id into the flow_id flow
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_msgs WHERE flow_id=%s",$share_id), ARRAY_A);

        //we need the page id from the flow_id...lets get that
        $page_id = smartbot_get_page_flow($flow_id);
        smartbot_clone_msgs($results,$page_id,$user_id,$flow_id);
    }
}

function  smartbot_get_page_flow($flow_id){
    global $wpdb;$page_id='';
    if(isset($flow_id) && $flow_id!=""){
        $page_id=$wpdb->get_var($wpdb->prepare("SELECT page_id FROM smartbot_flows WHERE id=%s",$flow_id));
    }
    return $page_id;
}

function smartbot_manage_flow_content($flow_id){
    global $wpdb;$flows="";$status="";
    //do we have a public or private flow...and is it approved or do we need to approve or reject it
    if(isset($flow_id) && $flow_id!=""){
        $flow_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_flow_share WHERE flow_id=%s", $flow_id), ARRAY_A);
        $status_id = $flow_details['approved'];
        if($status_id=="0"){$status="pending";}
        if($status_id=="1"){$status="approved";}
        if($status_id=="2"){$status="rejected";}

        $flows.='<p>Shareing Status: <strong><span class="shareing_status">'.$status.'</span></strong></p>';

        if($status_id=="0"){
            $flows.='<div id="shareing_status_content"> <p><button class="btn btn-danger share_flow_reject" data-flow_id="'.$flow_id.'">Reject Sharing This Flow</button> <button class="btn btn-primary share_flow_approve" data-flow_id="'.$flow_id.'">Approve Sharing This Flow</button></p></div>';
        }

        if($status_id=="1"){
            $flows.='<div id="shareing_status_content"> <p><button class="btn btn-danger share_flow_reject" data-flow_id="'.$flow_id.'">Reject Sharing This Flow</button></p></div>';
        }

        if($status_id=="2"){
            $flows.='<div id="shareing_status_content"> <p><button class="btn btn-primary share_flow_approve" data-flow_id="'.$flow_id.'">Approve Sharing This Flow</button></p></div>';
        }

        if($flow_details['share_type']=='public'){
            $flows.='<p>This flow is shared <strong>publicly</strong>. This means everyone can use it. To edit or delete this use the buttons below</p>
				<p><button class="btn btn-danger share_flow_delete" data-flow_id="'.$flow_id.'">Stop Sharing This Flow</button> <button class="btn btn-primary share_flow_private" data-flow_id="'.$flow_id.'">Change to Private Sharing This Flow</button></p>
				';
        }else{
            $flows.='<p>This flow is shared <strong>privately</strong>. This means only users you invate can use it. To edit or delete this use the buttons below</p>
				<p><button class="btn btn-danger share_flow_delete" data-flow_id="'.$flow_id.'">Stop Sharing This Flow</button> <button class="btn btn-primary share_flow_public" data-flow_id="'.$flow_id.'">Change to Public Sharing This Flow</button></p>
				<strong>Manage Users</strong><br>
				<span id="share_users_response"></span>
				<div id="share_users">
				';
            $flows.=smartbot_get_private_shares($flow_id);
            $flows.='</div>
				<div id="chat_tags_button" class="input-group" style="margin-bottom: 20px;">
                    <input id="user_name" value="" class="form-control share_user_name" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" placeholder="Enter the username here...">
                    <span class="input-group-btn" id="add_user"><button type="button" class="btn btn-primary add_user" style="padding: 6px 16px;border-radius: 0 4px 4px 0;" data-flow_id="'.$flow_id.'">Add User</button></span>
                 </div>';
        }
    }
    return $flows;
}

function smartbot_share_flow_reject($flow_id){
    global $wpdb;$flows="";
    if(isset($flow_id) && $flow_id!=""){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_flow_share SET approved='2' WHERE flow_id=%s",$flow_id));
        $flows.='<p><button class="btn btn-primary share_flow_approve" data-flow_id="'.$flow_id.'">Approve Sharing This Flow</button></p>';
    }
    return $flows;
}

function smartbot_share_flow_approve($flow_id){
    global $wpdb;$flows="";
    if(isset($flow_id) && $flow_id!=""){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_flow_share SET approved='1' WHERE flow_id=%s",$flow_id));
        $flows.='<p><button class="btn btn-danger share_flow_reject" data-flow_id="'.$flow_id.'">Reject Sharing This Flow</button></p>';
    }
    return $flows;
}

function smartbot_duplicate_flow($page_id,$user_id,$flow_id,$flow_name){
    $this_id='';global $wpdb;
    if(isset($page_id) && isset($flow_id) && isset($flow_name)){
        $name = $flow_name .' - Duplicate';
        $this_id = createFlow($page_id,$name);
        $msg_arr = smartbot_import_flow_yes($user_id,$page_id,$this_id,$flow_id);
        $entrypoint = $wpdb->get_var($wpdb->prepare("SELECT entrypoint from smartbot_flows WHERE id=%s",$flow_id));
        $new_entry = $msg_arr[$entrypoint];
        if($new_entry>0){
            $wpdb->query($wpdb->prepare("UPDATE smartbot_flows SET entrypoint=%s WHERE id=%s", $new_entry, $this_id));
        }
    }
    return $this_id;
}

function getFlowNameById($pageId,$flowId){
    global $wpdb;$flowName='';
    if(isset($pageId) && isset($flowId) ){
        $flowName = $wpdb->get_var($wpdb->prepare("SELECT name from smartbot_flows WHERE page_id=%s AND id=%s", $pageId,$flowId));
    }
    return $flowName;
}

function getFlowData($pageId,$flowId,$bypass=false){
    global  $wpdb;
    if(isset($flowId) ){
        if (!$bypass)
            $query = $wpdb->prepare("SELECT page_id,name,id from smartbot_flows WHERE page_id=%s AND id=%s", $pageId,$flowId);
        else
            $query = $wpdb->prepare("SELECT page_id,name,id from smartbot_flows WHERE id=%s",$flowId);

        $result = $wpdb->get_row($query);
        return $result;
    }
    return 0;
}

function saveFlow($pageId,$flowId,$flowData){


    require_once __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;

    $flowId = getFlowData($pageId,$flowId)->id;
    $flowData = json_decode($flowData);

    foreach ($flowData->_deletedCards as $deletedCard){
        removeKeywords($pageId,$deletedCard);
    }
    unset($flowData->_deletedCards);
    if ($flowId) {


        $updateResult = $collection->replaceOne(
            array('flow' => $flowId),
            array(
                'flow' => $flowId,
                'data' => $flowData
            ),
            array('upsert' => true)
        );

        deleteFlowKeywords($flowId);
        foreach ($flowData->_cards as $cardId => $card){
            $positiveKeywords =$negativeKeywords = "";
            foreach ($card->_positiveKeywords as $positiveKeyword){
                $positiveKeywords.= $positiveKeyword.",";
            }
            $positiveKeywords = rtrim($positiveKeywords,",");

            foreach ($card->_negativeKeywords as $negativeKeyword){
                $negativeKeywords.= $negativeKeyword.",";
            }
            $negativeKeywords = rtrim($negativeKeywords,",");
            if (!empty($positiveKeywords)) {
                   $cardName = $flowData->_cards->{$cardId}->_title;
                insertKeywords($pageId, $flowId, $cardId,$cardName, $positiveKeywords, $negativeKeywords);
            }
        }

        if (CM_ENVIRONEMENT !="app" ) {
            printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
            printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
            printf("Upserted %d document(s)\n", $updateResult->getUpsertedCount());

        }
    }
    return 0;


}

function dynamicReplacer($sourcePageId,$array,$data,$path,$pageId=false){
    if (empty($array)) return $data;

    $len = count($array);
    $tags = $replacement = [];

    for ($i=0;$i<$len;$i++){
            $array1[] = '/(\\\*"_*'.$path.'\\\*")\s*:\s*(\\\*")('.$array[$i]->source_id.')(\\\*")/i';
            $array2[] = '${1}:${2}'.$array[$i]->destination_id.'${4}';
            $tags[] = $array[$i]->source_id;
            $replacement[] = $array[$i]->destination_id;
    }


 

    if (empty($array1) || empty($array2)) return $data;

    if ($path === "tag") {
         tagReplacerInSegments($tags,$replacement,$pageId);
    }

    $data =  preg_replace($array1,$array2,$data);
    return $data;

}

function resetFlowStats($data){

    $regex= '~(_deliveries|_opens|_clicks)"\s*:\s*([0-9]+|$)~';
    $replace = '${1}":0';
    $data = preg_replace($regex,$replace,$data);
    return $data;

}

function scrapeFlowData($data){
    $importantData = new stdClass();
    $importantData->custom_fields = array();
    $pattern = '~"(_flowId)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);

    if ($success) {
         $importantData->flows = array_unique($match[2]);
    }

    $pattern = '~(\\\*"_*attachmentId\\\*")\s*:\s*(\\\*")([0-9]+)(\\\*")\s*,\s*"_url"\s*:\s*"(.+?)"~';
    $success = preg_match_all($pattern, $data, $match);
    if ($success) {
        $importantData->attachments = $match[3];
        $importantData->attachmentsUrl = $match[5];
    }


    $pattern = '~\s*\\\*"media_type\\\*"\s*:\s*\\\*"([A-z]+?)\\\*"\s*,\s*\\\*"attachment_id\\\*"|\\\*"_*attachment_id\\\*"\s*:\s*\\\*"[0-9]+\\\*"}\s*,\s*\\\*"type\\\*"\s*:\s*\\\*"([A-z]+?)\\\*"}~';
    $success = preg_match_all($pattern, $data, $match);
    if ($success) {

        $types = array();

    foreach ($match[1] as $matchType){
        if (!empty($matchType))
            $types[] = $matchType;
    }

    foreach ($match[2] as $matchType){
        if (!empty($matchType))
            $types[] = $matchType;

    }

        $importantData->attachmentsType = $types;
    }



    $pattern = '~"(_customfield)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);


    if ($success) {
         $importantData->custom_fields = array_unique($match[2]);
    }

    $pattern = '~"(custom_field)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);


    if ($success) {
        $importantData->custom_fields = array_merge($importantData->custom_fields,array_unique($match[2]));
    }

    $pattern = '~"(global_field)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);


    if ($success) {
        $importantData->global_fields = array_unique($match[2]);
    }


    $pattern = '/\[{([^"}]*)}\]/';
    $success = preg_match_all($pattern, $data, $match);

    if ($success) {
        $importantData->global_fields_names = array_unique($match[1]);
    }

    $pattern = '~"(tag)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);

    if ($success) {
         $importantData->tags = array_unique($match[2]);
    }

    $pattern = '~"(tag)"\s*:\s*"([\s\S]+?|$)"~';
    $success = preg_match_all($pattern, $data, $match);

    if ($success) {
        $importantData->raw_tags = array_unique($match[2]);
    }

    $pattern = '~"(_segment)"\s*:\s*"([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);

        if ($success) {
             $importantData->segments = array_unique($match[2]);
        }

    return $importantData;

}

function tagReplacerInSegments($tags,$replacement,$pageId){
    global $wpdb;
    $replaceString = "";
    foreach ($tags as $key => $tag){
        if ($key == 0)
            $replaceString.="replace(rules,'$tag','$replacement[$key]')";
        else
            $replaceString="replace($replaceString,'$tag','$replacement[$key]')";
    }
    $result = $wpdb->query("UPDATE segments SET rules = $replaceString WHERE page_id = $pageId");

    return $result;
}

function scrapeFlowsInFlow($data){
    $pattern = '~"(_flowId)"\s*:\s*"(.*?)([0-9]+|$)"~';
    $success = preg_match_all($pattern, $data, $match);

    if ($success) {
        return ($match[3]);
    }
    else
        return 0;

}

function createFlowMapObject(){
    $importedFlows = new stdClass();
    $importedFlows->flows = $importedFlows->tags = $importedFlows->segments = $importedFlows->custom_fields = $importedFlows->global_fields = array();
    return $importedFlows;
}

function flowMapObjectUnique($obj){
    $obj->flows = array_unique($obj->flows);
    $obj->tags = array_unique($obj->tags);
    $obj->custom_fields = array_unique($obj->custom_fields);
    $obj->segments = array_unique($obj->segments);
    return $obj;
}

function mergeFlowMapObjects($obj1,$obj2){
    $obj1->flows = array_merge($obj1->flows,$obj2->flows);
    $obj1->segments = array_merge($obj1->segments,$obj2->segments);
    $obj1->custom_fields = array_merge($obj1->custom_fields,$obj2->custom_fields);
    $obj1->tags = array_merge($obj1->tags,$obj2->tags);
    return $obj1;
}

function importFlows($sourcePageId,$destinationPageId){
    $sourceFlows = getAllFlows($sourcePageId);
    $importedFlowsMap = createFlowMapObject();

    foreach ($sourceFlows as $sourceFlow){
        if (!isset($importedFlowsMap->flows[$sourceFlow->id])) {
            $importedFlowsMap = importFlow($destinationPageId, $sourceFlow->id, false, $importedFlowsMap,"map");
        }

    }

    return $importedFlowsMap;
}

function importFlow($pageId,$flowToImport,$flowId,$importedFlows=false,$return="id"){

    //TODO Reset all stats

    $flag = false;

    if (!$flowId)
        $flag = true;

    require_once __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;

    $flowData = getFlowData(false, $flowToImport, true);
    $sourcePageId = $flowData->page_id;

    if ($flowId)
        $flowId = getFlowData($pageId,$flowId)->id;
    else
        $flowId = strval(createFlow($pageId,"$flowData->name"));


    if (isset($importedFlows) && empty($importedFlows)) {
        $importedFlows = createFlowMapObject();
    }

    $importedFlows->flows[$flowToImport] = $flowId;

    if ($flowId) {

        $document = $collection->findOne(array('flow' => $flowToImport));
        if (!isset($document->data)){
            if ($return === "id") {
                if ($flag)
                    return $flowId;
                else
                    return "";
            }
            else if ($return === "map")
                return $importedFlows;
        }

        $data = json_encode($document->data);


        if ($sourcePageId != $pageId) {

            $importantData = scrapeFlowData($data);

            if (isset($importantData->flows)) {
                $recursiveFlowsMap = array();
                foreach ($importantData->flows as $recursiveFlow) {
                    if (!isset($importedFlows->flows[$recursiveFlow])) {
                        $newFlowId = importFlow($pageId, $recursiveFlow, false,$importedFlows);
                        $recursiveFlowsMap[$recursiveFlow] = $newFlowId;
                    } else continue;

                }
            }


            if (isset($importantData->custom_fields) && !empty($importantData->custom_fields)) {
                $customFields = duplicateCustomFields($sourcePageId, $pageId, $importantData->custom_fields);
                foreach ($customFields as $customField)
                $importedFlows->custom_fields[$customField->source_id] = $customField->destination_id;
            }

            if (isset($importantData->global_fields) && !empty($importantData->global_fields)) {
                $globalFields = duplicateGlobalFields($sourcePageId, $pageId, $importantData->global_fields);
                foreach ($globalFields as $globalField)
                    $importedFlows->global_fields[$globalField->source_id] = $globalField->destination_id;
            }

            if (isset($importantData->global_fields_names) && !empty($importantData->global_fields_names)) {
                $globalFields = duplicateGlobalFieldsByName($sourcePageId, $pageId, $importantData->global_fields_names);
                foreach ($globalFields as $globalField)
                    $importedFlows->global_fields[$globalField->source_id] = $globalField->destination_id;
            }




            if (isset($importantData->segments) && !empty($importantData->segments)) {
                $segments = duplicateSegments($sourcePageId, $pageId, $importantData->segments);
                foreach ($segments as $segment)
                    $importedFlows->segments[$segment->source_id] = $segment->destination_id;

            }
            if (isset($importantData->tags) && !empty($importantData->tags)) {
                $tags = duplicateTags($sourcePageId, $pageId, $importantData->tags);
                foreach ($tags as $tag)
                    $importedFlows->tags[$tag->source_id] = $tag->destination_id;
            }

            if (isset($importantData->raw_tags) && !empty($importantData->raw_tags)) {
                $rawTags = duplicateRawTags($sourcePageId, $pageId, $importantData->raw_tags);
                foreach ($rawTags as $tag)
                    $importedFlows->tags[$tag->source_id] = $tag->destination_id;
            }



            $triggers = duplicateTriggers($sourcePageId, $pageId, $flowToImport, $flowId);



            if (isset($customFields) && !empty($customFields)) {
                $data = dynamicReplacer($sourcePageId, $customFields, $data, "customfield");
                $data = dynamicReplacer($sourcePageId, $customFields, $data, "custom_field");
            }

            if (isset($globalFields) && !empty($globalFields)) {
                $data = dynamicReplacer($sourcePageId, $globalFields, $data, "global_field");
            }

            if (isset($tags) && !empty($tags)) {
                $data = dynamicReplacer($sourcePageId, $tags, $data, "tag", $pageId);
            }


            if (isset($segments) && !empty($segments)) {
                $data = dynamicReplacer($sourcePageId, $segments, $data, "segment");
            }


            //Reset Stats
            $data = resetFlowStats($data);


        $array1[] = '';


        if (isset($importantData->attachments)) {
            $attachmentsMap = array();
            $pageToken = getPageToken($pageId);
            foreach ($importantData->attachments as $key => $attachment) {
                $result = uploadFileFacebook($importantData->attachmentsUrl[$key], $importantData->attachmentsType[$key], $pageId, $pageToken);
                if (isset($result["attachment_id"])) {
                    $attachmentsMap[$attachment] = $result["attachment_id"];
                    insertFileEntry($pageId, $importantData->attachmentsUrl[$key], $result["attachment_id"]);
                }
            }
        }
    }

        // Recursive flows replacement
        if (isset($recursiveFlowsMap)) {
               foreach ($recursiveFlowsMap as $recursiveFlowKey => $recursiveFlowValue){
                   $search = array();
                   $search[] = '/(\\\*"_*flowId\\\*")\s*:\s*(\\\*")('.$recursiveFlowKey.')(\\\*")/i';
                   $search[] = '/(\\\*"_*flow_id\\\*")\s*:\s*(\\\*")('.$recursiveFlowKey.')(\\\*")/i';
                   $replace = '${1}:${2}'.$recursiveFlowValue.'${4}';

                   $data = preg_replace($search,$replace,$data);
               }

           }

        if (isset($importedFlows->flows)) {
            foreach ($importedFlows->flows as $recursiveFlowKey => $recursiveFlowValue){
                $search = array();
                $search[] = '/(\\\*"_*flowId\\\*")\s*:\s*(\\\*")('.$recursiveFlowKey.')(\\\*")/i';
                $search[] = '/(\\\*"_*flow_id\\\*")\s*:\s*(\\\*")('.$recursiveFlowKey.')(\\\*")/i';
                $replace = '${1}:${2}'.$recursiveFlowValue.'${4}';
                $data = preg_replace($search,$replace,$data);
            }


        }

           if (isset($attachmentsMap)){

               foreach ($attachmentsMap as $key => $value){
                   $search = array();
                   $search[] = '/(\\\*"_*attachmentId\\\*")\s*:\s*(\\\*")('.$key.')(\\\*")/i';
                   $search[] = '/(\\\*"_*attachment_id\\\*")\s*:\s*(\\\*")('.$key.')(\\\*")/i';
                   $replace = '${1}:${2}'.$value.'${4}';

                   $data = preg_replace($search,$replace,$data);
               }


           }

        $search = "/($flowToImport)(\s*:\s*)/i";
        $replace = $flowId.'${2}';

        $data = preg_replace($search,$replace,$data);

        $document->data = json_decode($data);


        if (isset($document->data)) {
            $updateResult = $collection->replaceOne(
                array('flow' => $flowId),
                array(
                    'flow' => $flowId,
                    'data' => $document->data
                ),
                array('upsert' => true)
            );


            if ($return === "id") {
                if ($flag)
                    return $flowId;
                else
                    return $data;
            }
            else if ($return === "map")
                return $importedFlows;
        }
        else {
            return 0;
        }

    }

    return 0;

}

function getFlow($pageId,$flowId,$ignoreEncode=false){

    require_once __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;

    $flowId = getFlowData($pageId,$flowId)->id;

    if ($flowId) {

        $document = $collection->findOne(array('flow' => $flowId));
        if (isset($document->data)) {
            if (!$ignoreEncode)
            $data = json_encode($document->data);
            else
                $data = $document->data;

            return $data;
        }
        else return 0;

    }

    return 0;

}


function getSharableFlows($status,$userId=false){
    global $wpdb;

    if ($userId) {
        $pagesIds = "";
        $pages = getUserPages($userId);
        if ($pages) {
            foreach ($pages as $page) {
                $pagesIds .= "$page->page_id,";
            }
            $pagesIds = trim($pagesIds,",");
            $query = $wpdb->prepare("SELECT smartbot_pages.page_title,smartbot_flows.id,smartbot_flows.name,smartbot_flows.share_status FROM smartbot_flows JOIN smartbot_pages on smartbot_flows.page_id = smartbot_pages.page_id WHERE smartbot_flows.share_status =%d OR smartbot_flows.page_id in ($pagesIds)", $status);
        }
    }
    else
        $query = $wpdb->prepare("SELECT smartbot_pages.page_title,smartbot_flows.id,smartbot_flows.name,smartbot_flows.share_status FROM smartbot_flows JOIN smartbot_pages on smartbot_flows.page_id = smartbot_pages.page_id WHERE smartbot_flows.share_status =%d", $status);


    $results = $wpdb->get_results($query);
    return $results;

}
function getLinkedCards($pageId,$flowId,$cardId=false,$keepPreview=false,$buttonId=false){

    require __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;
    $flowId = getFlowData($pageId,$flowId)->id;
    $usedCards = array();

    if ($flowId) {


        $document = $collection->findOne(['flow' => $flowId]);


        $data = $document->data;

        if (!$cardId)
            $nextCard = $document->data->_firstCard;
        else
            $nextCard = $cardId;

        $linkedList = array();

        if ($buttonId) {

            if (in_array($document->data->data->links->{$buttonId}->delayType,["seconds","minutes","hours","days"])) {
                $delayData = new stdClass();
                $delayData->delay_data = new stdClass();
                $delayData->delay_data->delay_value = $document->data->data->links->{$buttonId}->delayValue;
                $delayData->delay_data->delay_type = $document->data->data->links->{$buttonId}->delayType;
                $delayData->delay_data->typing_indicator = $document->data->data->links->{$buttonId}->typingIndicator;
                $delayData->delay_data->flow_id = $flowId;
                $delayData->delay_data->card_id = $document->data->data->links->{$buttonId}->toOperator;

                if ($document->data->data->links->{$buttonId}->delayType!=="seconds") {
                    $nextCard = $document->data->data->links->{$buttonId}->toOperator;
                    $delayData->delay_data->next_card = $nextCard;
                    $element = new stdClass();
                    $element->card_id = $nextCard;
                    $element->json = json_encode($delayData);
                    $linkedList[] = $element;
                    return $linkedList;
                }

                $element = new stdClass();
                $element->card_id = $nextCard;
                $element->json = json_encode($delayData);
                $linkedList[] = $element;
                return array_merge($linkedList, getLinkedCards($pageId, $flowId,$cardId,$keepPreview,false));

            }



        }

        while (isset($data->_cards->{$nextCard}->_json)){

            if (!in_array($nextCard,$usedCards)) {

                $usedCards[] = $nextCard;
            }
            else
                break;


            $flowData = json_decode($data->_cards->{$nextCard}->_json);
            if (isset($flowData->message->flow_data)){
                $element = new stdClass();
                $element->card_id = $nextCard;
                $element->json = $document->data->_cards->{$nextCard}->_json;
                $linkedList[] = $element;

                if ($flowData->message->flow_data->flow_type === "flow")
                 return array_merge($linkedList,getLinkedCards($pageId,$flowData->message->flow_data->flow_id,false));
                else if ($flowData->message->flow_data->flow_type === "flowcard")
                   return  array_merge($linkedList,getLinkedCards($pageId,$flowData->message->flow_data->flow_id,$flowData->message->flow_data->card_id));
                else return $linkedList;

            }
            elseif (isset($flowData->message->cm_preview_url) && !$keepPreview){
                unset($flowData->message->cm_preview_url);
                $document->data->_cards->{$nextCard}->_json = json_encode($flowData);
            }
            $element = new stdClass();
            $element->card_id = $nextCard;
            $element->json = $document->data->_cards->{$nextCard}->_json;
            if (isset($document->data->_cards->{$nextCard}->_nextOnSuccess)){
                $element->next_on_success = $document->data->_cards->{$nextCard}->_nextOnSuccess;
                $element->input_type = str_replace("-input","",$document->data->_cards->{$nextCard}->_type);
                if ($element->input_type === "multiple"){
                    $element->rules = json_encode($document->data->_cards->{$nextCard}->_rules);
                }
            }
            if (isset($document->data->_cards->{$nextCard}->_nextOnFailure)){
                $element->next_on_failure = $document->data->_cards->{$nextCard}->_nextOnFailure;
            }
            if (isset($document->data->_cards->{$nextCard}->_customfield)){
                $element->customfield = $document->data->_cards->{$nextCard}->_customfield;
            }

            $linkedList[] = $element;
            if (!isset($document->data->data->links->{$nextCard}->delayType)) break;

            if (in_array($document->data->data->links->{$nextCard}->delayType,["seconds","minutes","hours","days"])) {
                $delayData = new stdClass();
                $delayData->delay_data = new stdClass();
                $delayData->delay_data->delay_value = $document->data->data->links->{$nextCard}->delayValue;
                $delayData->delay_data->delay_type = $document->data->data->links->{$nextCard}->delayType;
                $delayData->delay_data->typing_indicator = $document->data->data->links->{$nextCard}->typingIndicator;
                $delayData->delay_data->flow_id = $flowId;
                $delayData->delay_data->card_id = $document->data->data->links->{$nextCard}->toOperator;

                if ($document->data->data->links->{$nextCard}->delayType!=="seconds") {
                    $nextCard = $document->data->data->links->{$nextCard}->toOperator;
                    $delayData->delay_data->next_card = $nextCard;
                    $element = new stdClass();
                    $element->card_id = $nextCard;
                    $element->json = json_encode($delayData);
                    $linkedList[] = $element;
                    return $linkedList;
                }

                $element = new stdClass();
                $element->card_id = $nextCard;
                $element->json = json_encode($delayData);
                $linkedList[] = $element;

            }

            $nextCard = $document->data->data->links->{$nextCard}->toOperator;



        }

        return $linkedList;

    }

    return 0;


}

function getFlowCards($pageId,$flowId){


    require_once __DIR__.'/vendor/autoload.php';
    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;
    $flowData = getFlowData($pageId,$flowId);

    if(isset($flowData->id)){
        $flowId = $flowData->id;
    }
    else{
        $flowId = null;
    }


    if ($flowId) {

        $document = $collection->findOne(array('flow' => $flowId),array('data._cards'));

        return $document->data->_cards;

    }
    return 0;

}

function getAllFlows($pageId){
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT id,name FROM smartbot_flows WHERE page_id =%s",$pageId));
    return $results;
}


