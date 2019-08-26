<?php

$action = filter_input(INPUT_POST,'action');
ini_set('upload_max_filesize', '25M');
ini_set('max_input_vars', '50000');
session_start();
include_once('function.php');
include_once('facebook/autoload.php');
global $wpdb, $conn;
$pageId='';$user_id='';
$ref = $_SERVER['HTTP_REFERER']; //we should have the SB_HOME_URL in here
if (strpos($ref, SB_HOME_URL) !== false){


//security check for user and if the request comes from where we want
//needs to be added

    //update the activity of this user
    $wpdb->query($wpdb->prepare("UPDATE smartbot_logins SET last_activity=NOW() WHERE user_id=%s",$_SESSION['user']['id'] ));


    if(isset($_SESSION['page_id'])){
        $pageId =  $_SESSION['page_id'];
    }
    if(isset($_SESSION['user_id'])){
        $user_id =  $_SESSION['user_id'];
    }

    if($action=="checkSessionActive"){
        if(!isset($_SESSION['user'])){
            echo 0;
        }else{
            echo 1;
        }
        return ;
    }

     if($action=="checkPageSessionActive"){
        if(!isset($_SESSION['page_id'])){
            echo 0;
        }else{
            echo 1;
        }
        die();
    }
    if(isset($_SESSION['user'])){

     if($action=="getCurrentEditedPage"){
        echo getCurrentEditedPage($_SESSION['user']['id'],$pageId);
    }



    else if($action=="get_user_details_with_id" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        echo json_encode(getUserDetailsById($id));
    }

    else if($action=="save_user_details_with_id" && $_SESSION['membership']['admin']===1){
        $dataArray = [];
        $dataArray['id'] = filter_input(INPUT_POST,'id');
        $dataArray['first_name'] = filter_input(INPUT_POST,'first_name');
        $dataArray['last_name'] = filter_input(INPUT_POST,'last_name');
        $dataArray['user_name'] = filter_input(INPUT_POST,'user_name');
        $dataArray['email'] = filter_input(INPUT_POST,'email');
        echo updateUserDetailsById($dataArray);
    }

    else if($action=="user_login_history" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        echo getUserLoginHistory($id);
    }

    else if($action=="admin_get_service_provider" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        echo json_encode(getServiceProvider($id));
    }

    else if($action=="admin_create_service_provider" && $_SESSION['membership']['admin']===1){
        $name=filter_input(INPUT_POST,'name');
        $img=filter_input(INPUT_POST,'img');
        $cat=filter_input(INPUT_POST,'cat');
        $json=filter_input(INPUT_POST,'json');
        $sts = createServiceProvider($name,$img,$cat,$json);
        $obj = new stdClass();
        if($sts==1){
            $obj->status = 'success';
            $obj->msg = 'Service provider added.';
        }
        else{
            $obj->status = 'error';
            $obj->msg = 'Service provider could not be added. '.$sts;
        }
        echo json_encode($obj);
    }

    else if($action=="admin_update_service_provider" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        $name=filter_input(INPUT_POST,'name');
        $img=filter_input(INPUT_POST,'img');
        $cat=filter_input(INPUT_POST,'cat');
        $json=filter_input(INPUT_POST,'json');
        $sts = updateServiceProvider($id,$name,$img,$cat,$json);
        $obj = new stdClass();
        if($sts==1){
            $obj->status = 'success';
            $obj->msg = 'Service provider updated.';
        }
        else{
            $obj->status = 'error';
            $obj->msg = 'Could not update. '.$sts;
        }
        echo json_encode($obj);
    }

    else if($action=="admin_service_provider_renamefield" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        $oldName=filter_input(INPUT_POST,'oldName');
        $newName=filter_input(INPUT_POST,'newName');
        renameIntegrationKeys($id,$newName,$oldName);
    }

    else if($action=="admin_delete_service_provider" && $_SESSION['membership']['admin']===1){
        $id=filter_input(INPUT_POST,'id');
        deleteServiceProvider($id);
    }

    else if($action=="admin_get_templates_list"){
        $status=filter_input(INPUT_POST,'status');
        echo getTemplateListForAdmin($status);
    }

    else if($action=="admin_set_template_status"){
        $templateId=filter_input(INPUT_POST,'id');
        $status = filter_input(INPUT_POST,'status');
        $comment = filter_input(INPUT_POST,'comment');
        echo setTemplateStatusForAdmin($templateId,$status,$comment);
    }

    else if($action=="admin_get_template_details"){
        $templateId=filter_input(INPUT_POST,'id');
        echo getTemplateDetailsForAdmin($templateId);
    }

    else if($action=="admin_update_template_details"){
        $templateId=filter_input(INPUT_POST,'id');
        $title = filter_input(INPUT_POST,'title');
        $shortDesc = filter_input(INPUT_POST,'shortDesc');
        $fullDesc = filter_input(INPUT_POST,'fullDesc');
        $author = filter_input(INPUT_POST,'author');
        $tags = filter_input(INPUT_POST,'tags');
        $categoryId = filter_input(INPUT_POST,'category');
        $type = filter_input(INPUT_POST,'type');
        echo updateTemplateDetailsForAdmin($templateId,$title,$shortDesc,$fullDesc,$author,$tags,$categoryId,$type);
    }

    else if($action=="template_page"){
	    $user_id=filter_input(INPUT_POST,'user_id');
	    echo smartbot_template_content($pageId,$user_id);
    }

    else if($action=="import_bot"){
        $current_page=$pageId;
        $clone_page=filter_input(INPUT_POST,'clone_page');
        if($user_id!="" && $clone_page!="" && $current_page!=""){
            //making sure we are not copying into ourselves
            if($clone_page!=$current_page){
                $msg = smartbot_clone_bot($user_id,$current_page, $clone_page);
            }
        }
        echo $msg;
        die();
    }

    else if($action=="get_templates"){
        $category = filter_input(INPUT_POST,'category');
        $offset = filter_input(INPUT_POST,'catOffset');
        $search = filter_input(INPUT_POST,'search');
        $limit = 16;
        echo getTemplates($search,$category,$offset,$limit);
    }

    else if($action=="get_public_template_modal_data"){
        $templatePageIndexId = filter_input(INPUT_POST,'page_index_id');
        echo getPublicTemplateModalData($templatePageIndexId);
    }

    else if($action=="get_private_template_modal_data"){
        $templateId = filter_input(INPUT_POST,'template');
        echo getPrivateTemplateModalData($templateId);
    }

    else if($action=="install_public_template"){
        $pageIndexId = $_SESSION['page_index_id'];
        $templateId = filter_input(INPUT_POST,'id');
        $options = filter_input(INPUT_POST,'options');
        echo installTemplate('public',$pageIndexId,$templateId,$options);
    }

    else if($action=="install_private_template"){
        $fbPageId = filter_input(INPUT_POST,'page_id');
        $pageIndexId = getPageIndexIdWithPageId($fbPageId);
        $templateId = filter_input(INPUT_POST,'template');
        $code = filter_input(INPUT_POST,'code');
        $options = filter_input(INPUT_POST,'options');
        $user = $_SESSION['user'];
        $ownerFbId = $user['fb_id'];
        $userIndex = $user['id'];
        //check page owner
        $checkOwner = validatePageOwner($userIndex,$fbPageId);
        if($checkOwner!==true){
            return 'not exists';
        }
        //install
        echo installTemplate('private',$pageIndexId,$templateId,$options,$code);
    }

    else if($action=="uninstall_template"){
        $pageIndex = $_SESSION['page_index_id'];
        $installedTemplateId = filter_input(INPUT_POST,'id');
        $installedTemplateRow = getInstalledTemplateData($installedTemplateId);
        uninstallTemplate($pageId,$installedTemplateRow->template_id);
        deleteTemplateFromPage($pageIndex,[$installedTemplateId]);
    }


    else if($action=="get_subscribers_from_ids"){
        $recipients = filter_input(INPUT_POST,'recipients');
        echo json_encode(getSubscribersFromIds($recipients));

    }

    else if($action=="bulk_uninstall_templates"){
            $pageIndex = $_SESSION['page_index_id'];
            $ids = json_decode(filter_input(INPUT_POST,'ids'));
            foreach($ids as $id){
                uninstallTemplate($pageIndex,$id);
            }
            deleteTemplateFromPage($pageIndex,$ids);
    }

    else if($action=="check_private_template"){
        $templateId = filter_input(INPUT_POST,'template');
        $code =  filter_input(INPUT_POST,'code');
        echo checkPrivateTemplate($templateId,$code);
        die();
    }

    else if($action=="get_private_templates_pages_html"){
        echo getPageListForTemplatesHtml();
    }

    else if($action=="get_page_template_id"){
        $pageIndexId = $_SESSION['page_index_id'];
        echo getTemplateId($pageIndexId);
    }

    else if ($action=="testing"){
     echo $_SESSION['page_id'];
     die();
    }

    else if ($action=="hidewarning"){
        $_SESSION["hidewarning"] = 1;
        die();
    }

    else if($action=="create_template"){
        $template_name=filter_input(INPUT_POST,'template_name');
        $template_alias=filter_input(INPUT_POST,'template_alias');
        $template_descr=filter_input(INPUT_POST,'template_descr');
        $template_icon=filter_input(INPUT_POST,'template_icon');
	    $template_type=filter_input(INPUT_POST,'template_type');
	    echo smartbot_create_template($template_name,$template_alias,$template_descr,$template_icon,$template_type);
    }

    else if($action=="create_template_page"){
		$template_name=filter_input(INPUT_POST,'template_name');
		$template_alias=filter_input(INPUT_POST,'template_alias');
		$template_descr=filter_input(INPUT_POST,'template_descr');
		$template_icon=filter_input(INPUT_POST,'template_icon');
		$template_type=filter_input(INPUT_POST,'template_type');

		echo smartbot_create_template_page($template_name,$template_alias,$template_descr,$template_icon,$template_type,$pageId);
	}

    else if($action=="import_page"){
		echo smartbot_template_content($pageId,$user_id);
	}

    else if($action=="update_template"){
        $template_name=filter_input(INPUT_POST,'template_name');
        $template_id=filter_input(INPUT_POST,'template_id');
        $template_alias=filter_input(INPUT_POST,'template_alias');
        $template_descr=filter_input(INPUT_POST,'template_descr');
        $template_icon=filter_input(INPUT_POST,'template_icon');
	    $template_type=filter_input(INPUT_POST,'template_type');
        echo smartbot_update_template($template_id,$template_name,$template_alias,$template_descr,$template_icon,$template_type);
    }

    else if($action=="delete_template"){
        $template_id=filter_input(INPUT_POST,'template_id');
        echo smartbot_template_delete($template_id);
    }

    else if($action=="share_template_approve"){
		$template_id=filter_input(INPUT_POST,'template_id');
		smartbot_template_approve($template_id);
	}

    else if($action=="share_template_reject"){
		$template_id=filter_input(INPUT_POST,'template_id');
		smartbot_template_reject($template_id);
	}

    else if($action=="hide_auto_responses_warning"){
        $_SESSION["auto_responses_status"] = "hide";
        die();
    }

    else if($action=="get_auto_responses_status"){
        if(!isset($_SESSION["auto_responses_status"])){
            $_SESSION["auto_responses_status"] = checkActiveOrPaused($_SESSION['page_id']);
        }
        echo $_SESSION["auto_responses_status"];
        die();
    }

    else if($action=="get_active_state"){
		echo checkActiveOrPaused($_SESSION['page_id']);
	}

    else if($action=="activate_responses"){
        $res = activatePausedResponses($_SESSION['page_id']);
        if($res===1){
            $_SESSION["auto_responses_status"] = "1";
        }
		echo $res;
    }

	else if($action=="pause_responses"){
        $res = pauseResponses($_SESSION['page_id']);
        if($res===1){
            $_SESSION["auto_responses_status"] = "2";
        }
		echo $res;
	}

	else if($action=="delete_user_template"){
		$templateId=filter_input(INPUT_POST,'templateId');
		$userId=filter_input(INPUT_POST,'userId');
		templateDeleteUser($templateId,$userId);
	}
	else if($action=="add_user_template"){
		$templateId=filter_input(INPUT_POST,'templateId');
		$userName=filter_input(INPUT_POST,'userName');
		echo templateAddUser($templateId,$userName);
	}
    else if($action=="get_page_template_data"){
	    $pageIndexId = $_SESSION['page_index_id'];
        echo getPageTemplateData($pageIndexId);
        die();
    }
    else if($action=="save_page_template_data"){
        $pageIndexId = $_SESSION['page_index_id'];
        $pageId = $_SESSION['page_id'];
        $imageBase64 = filter_input(INPUT_POST,'imageBase64');
        $name = filter_input(INPUT_POST,'name');
        $shortDesc = filter_input(INPUT_POST,'shortDesc');
        $shortDesc = strip_tags($shortDesc, '<div><span><pre><p><br><hr><h1><h2><h3><h4><h5><h6>
            <ul><ol><li><dl><dt><dd><strong><em><b><i><u>
            <img><a><abbr><address><blockquote><area><audio><video>
            <form><fieldset><label><input><textarea>
            <caption><table><tbody><td><tfoot><th><thead><tr>');
        $fullDesc = filter_input(INPUT_POST,'fullDesc');
        $fullDesc = strip_tags($fullDesc, '<div><span><pre><p><br><hr><h1><h2><h3><h4><h5><h6>
            <ul><ol><li><dl><dt><dd><strong><em><b><i><u>
            <img><a><abbr><address><blockquote><area><audio><video>
            <form><fieldset><label><input><textarea>
            <caption><table><tbody><td><tfoot><th><thead><tr>');
        $author = filter_input(INPUT_POST,'author');
        $tags = filter_input(INPUT_POST,'tags');
        $categoryId = filter_input(INPUT_POST,'categoryId');
        $type = filter_input(INPUT_POST,'type');

        echo savePageTemplateData($pageIndexId,$pageId,$imageBase64,$name,$shortDesc,$fullDesc,$author,$tags,$categoryId,$type);
        die();
    }
    else if($action=='set_public_share_template_status'){
        $status = filter_input(INPUT_POST,'status');
        $pageIndexId = $_SESSION['page_index_id'];
        echo setPublicTemplateShareStatus($pageIndexId,$status);
        die();
    }
    else if($action=='regenerate_template_code'){
        $oldCode = filter_input(INPUT_POST,'code');
        $pageIndexId = $_SESSION['page_index_id'];
        echo refreshTemplateCode($pageIndexId,$oldCode);
        die();
    }
    else if($action=='template_request_approval'){
        $pageIndexId = $_SESSION['page_index_id'];
        requestTemplateApproval($pageIndexId);
        die();
    }
    else if($action=='create_template_share_code'){
        $type = filter_input(INPUT_POST,'type');
        $pageIndexId = $_SESSION['page_index_id'];
        $code = createTemplateNewCode($pageIndexId,$type);
        echo json_encode(getTemplateCodeDetails($code));
        die();
    }
    else if($action=='delete_template_code'){
        $codeId = filter_input(INPUT_POST,'id');
        $pageIndexId = $_SESSION['page_index_id'];
        echo deleteTemplateCode($pageIndexId,$codeId);
        die();
    }
    else if($action=='delete_bulk_template_codes'){
        $pageIndexId = $_SESSION['page_index_id'];
        $ids = json_decode(filter_input(INPUT_POST,'ids'));
        echo deleteBulkTemplateCodes($pageIndexId,$ids);
        die();
    }
    else if($action=="get_global_field"){
        $pageIndex = $_SESSION['page_index_id'];
        $id = filter_input(INPUT_POST,'id');
        echo json_encode(getGlobalField($id,$pageId));
        die();
    }
    else if($action=="save_global_field"){
        $pageId = $_SESSION['page_id'];
        $name = filter_input(INPUT_POST,'name');
        $type = filter_input(INPUT_POST,'type');
        $desc = filter_input(INPUT_POST,'desc');
        $value = filter_input(INPUT_POST,'value');
        echo saveGlobalField($name,$type,$desc,$value,$pageId);
        die();
    }
    else if($action=="update_global_field"){
        $pageId = $_SESSION['page_id'];
        $id = filter_input(INPUT_POST,'id');
        $name = filter_input(INPUT_POST,'name');
        $type = filter_input(INPUT_POST,'type');
        $desc = filter_input(INPUT_POST,'desc');
        $value = filter_input(INPUT_POST,'value');
        echo updateGlobalField($id,$name,$type,$desc,$value,$pageId);
        die();
    }
    else if($action=="delete_global_fields"){
        $pageId = $_SESSION['page_id'];
        $ids = filter_input(INPUT_POST,'ids');
        $ids = json_decode($ids);
        echo deleteGlobalFields($ids,$pageId);
        die();
    }
    else if($action=="truncate_text"){

        $text = filter_input(INPUT_POST,'text');
        $length = filter_input(INPUT_POST,'length');

        echo smartbot_tokenTruncate($text,$length);
    }

    else if($action=="save_agency_settings"){
        $agencyId = $_SESSION["user"]["id"];
        $agencyName = filter_input(INPUT_POST,'agency_name');
        insertAgencySettings($agencyId,$agencyName);
    }

    else if($action=="update_agency_info"){
        $agencyId = getAgencyId($_SESSION["user"]["id"]);
        $data = [];
        $data['name'] = filter_input(INPUT_POST,'name');
        $data['address'] = filter_input(INPUT_POST,'address');
        $data['logoTopBase64'] = filter_input(INPUT_POST,'imageBase64');
        $whitelabel = false;
        if($_SESSION['membership']['whitelabel']==1){
            $data['logoLeftBase64'] = filter_input(INPUT_POST,'squareLogo');
            $data['favicon'] = filter_input(INPUT_POST,'favi');
            $data['email_header_img'] = filter_input(INPUT_POST,'emailHeader');

            $data['website'] = filter_input(INPUT_POST,'website');
            $data['link_support'] = filter_input(INPUT_POST,'support');

            $data['email'] = filter_input(INPUT_POST,'email');
            $data['host'] = filter_input(INPUT_POST,'host');
            $data['port'] = filter_input(INPUT_POST,'port');
            $data['user'] = filter_input(INPUT_POST,'user');
            $data['pass'] = filter_input(INPUT_POST,'pass');
            $data['enc'] = filter_input(INPUT_POST,'enc');
            $whitelabel = true;
        }
        echo updateAgencyInfo($agencyId, $data, $whitelabel);
        die();
    }

    else if($action=="set_agency_brand_img"){
        $agencyId = getAgencyId($_SESSION["user"]["id"]);
        $imageBase64 = filter_input(INPUT_POST,'imageBase64');
        $agencyImgRole = filter_input(INPUT_POST,'agencyImgRole');
        if($_SESSION['membership']['whitelabel']==1){
            switch($agencyImgRole){
                case 'nav_logo_top':
                    $extArr = ['jpeg','jpg','png','svg'];
                    $res = setAgencyBrandImg($agencyId,$imageBase64,$agencyImgRole,$extArr);
                    echo json_encode($res);
                    break;

                case 'nav_logo_left':
                    $extArr = ['jpeg','jpg','png','svg'];
                    $res = setAgencyBrandImg($agencyId,$imageBase64,$agencyImgRole,$extArr);
                    echo json_encode($res);
                    break;

                case 'favicon':
                    $extArr = ['ico'];
                    $res = setAgencyBrandImg($agencyId,$imageBase64,$agencyImgRole,$extArr);
                    echo json_encode($res);
                    break;

                case 'email_header_img':
                    $extArr = ['jpeg','jpg','png','svg'];
                    $res = setAgencyBrandImg($agencyId,$imageBase64,$agencyImgRole,$extArr);
                    echo json_encode($res);
                    break;
            }
        }
        else if($agencyImgRole == 'nav_logo_top'){
            $extArr = ['jpeg','jpg','png','svg'];
            $res = setAgencyBrandImg($agencyId,$imageBase64,$agencyImgRole,$extArr);
            echo json_encode($res);
        }
        die();
    }

    else if($action == "inviteAgencySubUser"){
        $agencyId=filter_input(INPUT_POST,'agencyId');
        $subUserEmail = filter_input(INPUT_POST,'subUserEmail');
        $licenses = smartbot_get_agency_limits($agencyId);
        $total_license_use = $licenses["used"] + $licenses["invited"];

        if (filter_var($subUserEmail, FILTER_VALIDATE_EMAIL)) {

            if($total_license_use >= $licenses["total"]){
                $msg["status"] = 0;
                $msg["msg"] = "You reached the maximum amount of sublicenses please upgrade to continue";
                echo json_encode($msg);
            } else {
                $msg = smartbot_add_subusers($subUserEmail, $agencyId);
                echo json_encode($msg);
            }
        } else {
            $msg["status"] = 0;
            $msg["msg"] = "$subUserEmail is not a valid email address";
            echo json_encode($msg);
        }

    }

    else if($action == "loadSubUsers"){
        $agencyId=filter_input(INPUT_POST,'agencyId');
        $agencySubcriberTable = loadAgencySubUsers($agencyId);
        echo json_encode($agencySubcriberTable);
    }



    else if($action == "mark_done_experience"){
        $experience=filter_input(INPUT_POST,'experience');
        markDoneExperience($_SESSION["page_id"],"$experience");
    }

    else if($action == "disableSubUser"){
        $agencyId=filter_input(INPUT_POST,'agencyId');
        $userId=filter_input(INPUT_POST,'userId');
        $msg = agencySetSubUserStatus($agencyId,$userId,"disable");
        echo json_encode($msg);
    }
    else if($action == "enableSubUser"){
        $agencyId=filter_input(INPUT_POST,'agencyId');
        $userId=filter_input(INPUT_POST,'userId');
        $msg = agencySetSubUserStatus($agencyId,$userId,"enable");
        echo json_encode($msg);
    }

    else if($action=="connect_to_fb"){
        $userId = $_SESSION['user']['fb_id'];
        $botId = filter_input(INPUT_POST,'bot_id');
        $botPage = filter_input(INPUT_POST,'bot_page');if(!isset($bot_page)){$bot_page='';}
        $template = filter_input(INPUT_POST,'template');if(!isset($template)){$template='blank';}
        $userIndexId = $_SESSION['user']['id'];
        if(!isset($pageId)){$pageId='';}
        echo smartbot_settings_connect_to_fb($pageId,$botId,$botPage,$userId,$userIndexId,$template);
        die();
    }

    else if($action=="dashboard_graph"){
        $date_from= filter_input(INPUT_POST,'date_start');
        $date_to= filter_input(INPUT_POST,'date_end');
        echo smartbot_display_graph($date_from,$date_to,$pageId);
        die();
    }

    else if($action=="create_new_facebook_comment"){
        $name= filter_input(INPUT_POST,'name');
        echo createFacebookComment($name,$_SESSION['page_id']);
        die();
    }

    else if($action=="rename_facebook_comment"){
        $name= filter_input(INPUT_POST,'name');
        $fbcid = filter_input(INPUT_POST,'fbcid');
        echo renameFacebookComment($name,$fbcid,$_SESSION['page_id']);
        die();
    }

    else if($action=="delete_facebook_comment"){
        $fbcid = filter_input(INPUT_POST,'fbcid');
        echo deleteFacebookComment($fbcid,$_SESSION['page_id']);
        die();
    }

    else if($action=="bulk_delete_facebook_comment"){
        $idsArray = json_decode(filter_input(INPUT_POST,'fbcids'));
        echo bulkDeleteFacebookComment($idsArray,$pageId);
        die();
    }

    else if ($action === "save_flow"){
        $flowId = filter_input(INPUT_POST,'flow_id');
        $flowData = filter_input(INPUT_POST,'flow_data');
        echo saveFlow($_SESSION["page_id"],$flowId,$flowData);
    }

    else if ($action === "get_flow_data"){

        $flowId = filter_input(INPUT_POST,'flow_id');
        echo json_encode(getFlowData($_SESSION["page_id"],$flowId));
    }

    else if ($action === "get_checkbox_data"){

        $widgetId = filter_input(INPUT_POST,'id');
        echo json_encode(smartbot_get_checkboxes_data($_SESSION["page_id"],$widgetId));
    }

    else if ($action === "get_stm_data"){

        $widgetId = filter_input(INPUT_POST,'id');
        echo json_encode(getStmData($_SESSION["page_id"],$widgetId));
    }

    else if ($action === "get_cc_data"){

        $widgetId = filter_input(INPUT_POST,'id');
        echo json_encode(getCustomerChatData($_SESSION["page_id"],$widgetId));
    }



    else if ($action === "get_flow"){

        $flowId = filter_input(INPUT_POST,'flow_id');
        echo getFlow($_SESSION["page_id"],$flowId);
    }

    else if ($action === "import_flow"){

        $flowId = filter_input(INPUT_POST,'flow_id');
        $flowToImport = filter_input(INPUT_POST,'imported_flow');
        echo importFlow($_SESSION["page_id"],$flowToImport,$flowId);
    }


    else if($action === "get_flow_preview_json"){
        $flowId = filter_input(INPUT_POST,'flow_id');
        echo json_encode(getFlowPreview($_SESSION["page_id"],$flowId));
    }

    else if($action === "get_card_json"){
        $flowId = filter_input(INPUT_POST,'flow_id');
        $cardId = filter_input(INPUT_POST,'card_id');
        echo json_encode(getCardPreview($_SESSION["page_id"],$flowId,$cardId));
    }

    else if($action=="save_facebook_comment"){
        $fbcId = filter_input(INPUT_POST,'id');
        $facebookCommentSettings = json_decode(filter_input(INPUT_POST,'facebook_comment_settings'));
        echo saveFacebookComment($fbcId,$_SESSION['page_id'],$facebookCommentSettings);
        die();
    }


    else if($action=="activate_keyword"){
        $keywordId = filter_input(INPUT_POST,'keyword_id');
        echo updateKeywordStatus($_SESSION['page_id'],$keywordId,1);
        die();
    }

    else if($action=="pause_keyword"){
        $keywordId = filter_input(INPUT_POST,'keyword_id');
        echo updateKeywordStatus($_SESSION['page_id'],$keywordId,0);
        die();
    }

    else if($action=="get_saved_facebook_comment"){
        $fbcid = filter_input(INPUT_POST,'fbcid');
        echo getSavedFacebookComment($fbcid,$_SESSION['page_id']);
        die();
    }
    else if($action=="demo_get_analytics_data"){
        $date_start= filter_input(INPUT_POST,'date_start');
        $date_end= filter_input(INPUT_POST,'date_end');
        echo demo_smartbot_get_analytics_data($pageId,$_SESSION['page_token'],$date_start,$date_end);
        die();
    }
    else if($action=="get_analytics_data"){

        $date_start= filter_input(INPUT_POST,'date_start');
        $date_end= filter_input(INPUT_POST,'date_end');
        $pageToken = getPageToken($pageId);
        echo getAnalyticsData($pageId,$pageToken,$date_start,$date_end);
        die();
    }

    else if($action=="session_takeover"){
        $user_id=filter_input(INPUT_POST,'profile_id');
	    $pageId=filter_input(INPUT_POST,'page_id');
        smartbot_login_takeover_admin($user_id,$pageId);
        die();
    }

    else if($action=="get_user_profile"){
        $user_id=filter_input(INPUT_POST,'profile_id');
        echo smartbot_user_profile($user_id);
        die();
    }

    else if($action=="get_membershiplevel"){
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_get_membershiplevel($user_id);
        die();
    }

    else if($action=="delete_login_session"){
        $login_id=filter_input(INPUT_POST,'login_id');
        smartbot_delete_last_session($login_id);
        die();
    }

    else if($action=="change_password"){
        $user_id=filter_input(INPUT_POST,'user_id');
        $password = filter_input(INPUT_POST,'password');
        smartbot_change_password($user_id,$password);
        die();
    }

    else if($action=="clean_account"){
        $user_id=filter_input(INPUT_POST,'user_id');
        smartbot_user_clean_account($user_id);
        die();
    }

    else if($action=="delete_user"){
        $user_id=filter_input(INPUT_POST,'user_id');
        smartbot_user_delete_account($user_id);
        die();
    }

    else if($action=="delete_membership"){
        $user_id=filter_input(INPUT_POST,'user_id');
        $membership_id = filter_input(INPUT_POST,'membership_id');
        smartbot_user_delete_membershiplevel($user_id,$membership_id);
        die();
    }

    else if($action=="add_membership"){
        /*
        $user_id=filter_input(INPUT_POST,'user_id');
        $membership_level = filter_input(INPUT_POST,'membership_level');
        echo smartbot_user_add_membershiplevel($user_id,$membership_level);
        */
        $userId = (int)filter_input(INPUT_POST,'user_id');
        $invoiceId = filter_input(INPUT_POST,'invoice_id');
        $membershipId = (int)filter_input(INPUT_POST,'membership_id');
        $membershipExists = checkUserMembership($userId, $membershipId);
        $insertId = '';
        if (empty($membershipExists)) {
            //check if trying to add agency membership
            if($membershipId>4 && $membershipId<8){
                //check if agency exists
                if(empty(getAgencyId($userId))){
                    $insertId = addUserMembership($userId, $membershipId, time(), $invoiceId);
                    $licenseCnt = getAgencyPackageArr();
                    insertAgency($userId, $licenseCnt[$membershipId]);
                }
            }
            //check if trying to add other memberships
            elseif($membershipId<=4||$membershipId>=8){
                $insertid = addUserMembership($userId, $membershipId, time(), $invoiceId);
            }
        }
        echo getMembershipRowForAdmin($userId);
        die();
    }


    else if($action=="send_json_msg"){
        $msg_id=filter_input(INPUT_POST,'msg_id');
        $profileId=filter_input(INPUT_POST,'profile_id');
        //echo 'send json msg..at admin with page id:'.$pageId.' profile id:'.$profileId.' msg:'.$msg_id;
        echo smartbot_send_json_msg($pageId, $profileId,$msg_id);
        die();
    }

    else if($action=="livechat_check_warning_message"){
        echo livechatCheckWarningMessage();
    }

    else if($action=="get_chat_profiles_list"){
        echo getOpenChatProfiles($_SESSION['page_index_id']);
        die();
    }
    else if($action=="send_livechat_flow_msg"){
        $profileIndex = filter_input(INPUT_POST,'profileIndexId');
        $profileId = getProfileIdWithProfileIndexId($_SESSION["page_id"],$profileIndex);
        $jsonData = filter_input(INPUT_POST,'jsonData');
        $decoded = json_decode($jsonData);
        if ($decoded->type === "flow"){
            dispatchFacebookMessages($pageId,$profileId,$decoded->flowId);
        }
        elseif ($decoded->type === "flowcard"){
            dispatchFacebookMessages($pageId,$profileId,$decoded->flowId,$decoded->msgId);

        }

        die();
    }

    else if($action=="send_livechat_msg"){

        $msgText=trim(json_encode(filter_input(INPUT_POST,'msg_text')),'"');
        $profileIndexId=filter_input(INPUT_POST,'profileIndexId');
        echo livechatSendChatMsg($_SESSION["page_id"],$profileIndexId, $msgText);
        die();
    }

    else if($action=="send_live_chat_profile"){

        $msg_text=trim(json_encode(filter_input(INPUT_POST,'msg_text')),'"');
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        echo smartbot_send_chat_msg($pageId, $profileId,$msg_text);
        die();
    }

    else if($action === "load_livechat_profile_data"){
        $profileIndexId = filter_input(INPUT_POST,'profile_index_id');
        echo loadLivechatProfileData($profileIndexId);
        die();
    }


    else if($action=="update_live_chat"){
        $profileIndex=filter_input(INPUT_POST,'profile_index');
        $pageId  = $_SESSION["page_id"];
        $pageIndex  = $_SESSION["page_index_id"];
        $pageImage= $_SESSION["page_image"];
        $pageName= $_SESSION["page_name"];
        $lastMessageId= filter_input(INPUT_POST,'last_msg_id');

        echo getNextChats($profileIndex,$pageId,$pageIndex,$pageImage,$pageName,$lastMessageId);
        die();
    }

    else if($action=="get_demio_events"){
        require_once __DIR__."/integrationFunctions.php";
        $apiKey  = $_POST["api_key"];
        $apiSecret  = $_POST["api_secret"];
        echo getDemioEvents($apiKey,$apiSecret);
        die();
    }
    
    else if($action=="load_livechat_profile_previous_msgs"){
        //this is part of new livechat
        $profileIndexId = filter_input(INPUT_POST,'profile_index_id');
        $lastmsgId = filter_input(INPUT_POST,'last_msg_id');
        echo getLivechatProfilePreviousMsgs($profileIndexId,$lastmsgId);
        die();
    }

    else if($action=="load_previous_live_chat"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        $profilePicture=filter_input(INPUT_POST,'profile_pic');
        
        $user_id = filter_input(INPUT_POST,'user_id');
        $page_image= filter_input(INPUT_POST,'page_image');
        $page_name= filter_input(INPUT_POST,'page_name');
        $lastmsgid= filter_input(INPUT_POST,'last_msg_id');
        echo smartbot_get_previous_chats($profileId,$profilePicture,$pageId,$page_image,$page_name,$lastmsgid);
        die();
    }

    else if($action=="add_keyword"){
        $keyword=filter_input(INPUT_POST,'keyword');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        $user_id=$_SESSION['user_id'];
        echo smartbot_add_keyword_msg($user_id,$pageId,$msg_id,$keyword);
        die();
    }

    else if($action=="add_neg_keyword"){
        $keyword=filter_input(INPUT_POST,'keyword');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        $user_id=$_SESSION['user_id'];
        echo smartbot_add_neg_keyword_msg($user_id,$pageId,$msg_id,$keyword);
        die();
    }

    else if($action=="delete_keyword"){
        $keyword_id=filter_input(INPUT_POST,'keyword_id');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_delete_msg_keywords($keyword_id,$msg_id, $pageId,$user_id);
        die();
    }

    else if($action=="delete_neg_keyword"){
        $keyword=filter_input(INPUT_POST,'keyword');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_delete_msg_neg_keywords($keyword,$msg_id, $pageId,$user_id);
        die();
    }


    else if($action=="add_tag_msg"){
        $tag=filter_input(INPUT_POST,'tag');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        $user_id=$_SESSION['user_id'];
        echo addTagMsg($user_id,$pageId,$msg_id,$tag);
        die();
    }

    else if($action=="delete_tag_msg"){
        $tag_id=filter_input(INPUT_POST,'tag_id');
	    $msg_id=filter_input(INPUT_POST,'msg_id');
	    $user_id=$_SESSION['user_id'];
        echo deleteTagMsg($user_id,$pageId,$msg_id,$tag_id);
        die();
    }

    else if($action=="add_tag_instant"){
        $tag=filter_input(INPUT_POST,'tag');
        $post_id=filter_input(INPUT_POST,'post_id');
        $user_id=$_SESSION['user_id'];
        echo addTagInstant($user_id,$pageId,$post_id,$tag);
        die();
    }

    else if($action=="delete_tag_instant"){
        $tag=filter_input(INPUT_POST,'tag');
        $post_id=filter_input(INPUT_POST,'post_id');
        
        $user_id=filter_input(INPUT_POST,'user_id');
        echo deleteTagInstant($user_id,$pageId,$post_id,$tag);
        die();
    }

    else if($action=="add_tag_profile"){
        $tag=filter_input(INPUT_POST,'tag');
        $profileId=filter_input(INPUT_POST,'profile_id');
        echo addTagProfile($pageId,$profileId,$tag);
        die();
    }


    else if($action=="add_bulk_tag"){
        $tag=filter_input(INPUT_POST,'tag');
        $profileIds=json_decode(filter_input(INPUT_POST,'profile_ids'));

        foreach ($profileIds as $profileId) {
            addTagProfile($pageId,$profileId,$tag);
        }
        echo 'Success, <strong>'.$tag.'</strong> as a tag added to selected profiles ';
        die();
    }
	else if($action=="add_bulk_customfield"){
		$customKey=filter_input(INPUT_POST,'customfield_key');
		$customKeyName=filter_input(INPUT_POST,'customfield_key_name');
		$customValue=filter_input(INPUT_POST,'customfield_value');
		$profileIds=json_decode(filter_input(INPUT_POST,'profile_ids'));

		foreach ($profileIds as $profileId) {
			saveCustomfieldValueByID($customKey,$customValue,$profileId,$pageId);
		}
		echo 'Success, <strong>'.$customValue.' for '.$customKeyName.'</strong> as a customfield added to selected profiles ';
		die();
	}

    else if($action=="broadcast_tags"){
        $user_id=filter_input(INPUT_POST,'user_id');
        
        echo broadcastTags($pageId);
    }


    else if($action=="get_page_tags_for_segmentation"){
        $tags = getPageTags($pageId);
        $values = "{";
        $i = 0;
        $last = count($tags);
        foreach ($tags as $tag){
            $values .= $tag->id.":'".addslashes($tag->tag_name)."'";
            if (++$i!==$last)
                $values .=",";
        }
        $values .= "}";
        echo ($values);
    }

    else if ($action=="get_page_tags"){
        echo json_encode(getPageTags($_SESSION["page_id"]));
    }

    else if($action=="get_existing_languages_for_segmentation"){
        $languages = getExistingLanguages($pageId);
        $values = "{";
        $i = 0;
        $last = count($languages);
        foreach ($languages as $language){
            $values .= addslashes($language->locale).":'".addslashes($language->language_name)."'";
            if (++$i!==$last)
                $values .=",";
        }
        $values .= "}";
        echo $values;
    }

    else if($action=="get_global_fields"){
       // $values = getGlobalFieldsData($pageId);
        $values = array();
        echo json_encode($values);
    }

    else if($action=="get_customfields_for_segmentation"){
        $customfields = array();
        echo json_encode($customfields);
    }

    else if($action=="get_broadcast_recipients"){
        $user_id=filter_input(INPUT_POST,'user_id');
        
        $condition_query = filter_input(INPUT_POST,'condition_query');
        $broadcast_type = filter_input(INPUT_POST,'broadcast_type');
        $broadcast_time = filter_input(INPUT_POST,'broadcast_time');
        echo tagsToQuery($condition_query,$broadcast_type,$broadcast_time,$pageId);
    }

    else if($action=="get_segment_data"){

        $segmentID = filter_input(INPUT_POST,'id');
        echo json_encode(getSegment($_SESSION["page_id"],$segmentID));
    }

    else if($action=="get_integration_account_lists") {
        $account = filter_input(INPUT_POST,'account');
        $service = filter_input(INPUT_POST,'service');

        echo json_encode(getIntegrationLists($pageId,$account,$service));
    }

    else if($action=="save_wowing_webhook") {
        $account = filter_input(INPUT_POST,'account');
        $automation = filter_input(INPUT_POST,'automation');
        $linkReady = filter_input(INPUT_POST,'link_ready');
        $videoWatched = filter_input(INPUT_POST,'video_watched');
        $flowId = filter_input(INPUT_POST,'flow_id');
        $webhook = "https://dev.clevermessenger.com/endpoint/integrations/wowing.php?flow_id=".$flowId."&page_id=".$pageId."&link_ready=".$linkReady."&video_watched=".$videoWatched;
      echo $webhook;
        echo json_encode(saveWowingWebhook($pageId,$account,$automation,$webhook));
    }
    else if($action=="get_segmentation_recipients"){

        $broadcastType = filter_input(INPUT_POST,'broadcast_type');
        if (isset($_POST["segment_id"])){
            $segmentID = filter_input(INPUT_POST,'segment_id');
            if ($segmentID === "admins"){
                echo convertSegmentationQueryToProfiles("",$broadcastType,$_SESSION["page_id"],false,"admins");
                die();
            }
            $segmentationQuery = getSegment($_SESSION["page_id"],$segmentID);
            if (isset($segmentationQuery->rules))
                $segmentationQuery = $segmentationQuery->rules;
            else
                $segmentationQuery = "";
        }

        else if (isset($_POST["segmentation_query"])){
            $segmentationQuery = filter_input(INPUT_POST,'segmentation_query');
        }

        echo convertSegmentationQueryToProfiles($segmentationQuery,$broadcastType,$_SESSION["page_id"]);
    }

    else if($action=="get_stats_profiles"){

        $statType = filter_input(INPUT_POST,'type');
        $broadcastID = filter_input(INPUT_POST,'broadcast_id');
        echo getBroadcastStatProfiles($broadcastID,$statType,$pageId);
    }

    else if($action=="save_segment"){
        $segmentationQuery=filter_input(INPUT_POST,'segmentation_query');
        $segmentID=filter_input(INPUT_POST,'id');
        echo saveSegment($segmentID,$segmentationQuery,$pageId);
    }

    else if($action=="get_tags"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        echo getTagProfile($user_id,$pageId,$profileId);
        die();
    }
    else if($action=="add_category" && $_SESSION['membership']['admin']===1) {
        $name = filter_input( INPUT_POST, 'name' );
        $icon = filter_input( INPUT_POST, 'icon' );
        $color = filter_input( INPUT_POST, 'color' );
        $background = filter_input( INPUT_POST, 'background' );
        echo createCategory($name,$icon,$color,$background);
        die();
    }
    else if($action=="regenerate_category_css" && $_SESSION['membership']['admin']===1) {
        echo generateCategoriesCss();
        die();
    }
    else if($action=="edit_category" && $_SESSION['membership']['admin']===1) {
        $id = filter_input( INPUT_POST, 'id' );
        $name = filter_input( INPUT_POST, 'name' );
        $icon = filter_input( INPUT_POST, 'icon' );
        $color = filter_input( INPUT_POST, 'color' );
        $background = filter_input( INPUT_POST, 'background' );
        echo updateCategory($id,$name,$icon,$color,$background);
        die();
    }
    else if($action=="delete_category" && $_SESSION['membership']['admin']===1) {
        $id = filter_input( INPUT_POST, 'id' );
        echo deleteCategory($id);
        die();
    }
	else if($action=="add_customfield") {
		$profileID = filter_input( INPUT_POST, 'profile_id' );
		$customKey = filter_input( INPUT_POST, 'customKey' );
		$customValue = filter_input( INPUT_POST, 'customValue' );
		echo saveCustomfieldValueByID($customKey,$customValue,$profileID,$pageId);
		die();
	}

    else if($action=="get_customfields"){
        $profileID=filter_input(INPUT_POST,'profile_id');
        $results =  getProfileCustomfields($profileID,$pageId);

        if(is_array($results)){
            $finalHTML = "";
            foreach($results as $result){
                $customfieldName = $result->customfield_name;
                $customfieldValue = $result->customfield_value;
                $customfieldId = $result->id;
                $finalHTML .= '<div class="style_customfields_values_container" id="customfield_'.$customfieldId.'"><span class="chat_tags styling_span_tags customfield_key style_customfield_key">'.$customfieldName.'</span><span class="chat_tags styling_span_tags customfield_value style_customfield_value click-to-copy" data-field-val="'.$customfieldValue.'" data-toggle="tooltip" data-original-title="Click to copy">'.$customfieldValue.' </span><span class="delete_customfield style_customfields_delete" data-customfield_id="'.$customfieldId.'"> <i class="fa icon-cross"></i></span></span></div>';
            }
            echo $finalHTML;
        }

        die();
    }

    else if($action=="get_widget_name"){

        $widget_id=filter_input(INPUT_POST,'widget_id');
        echo smartbot_get_widget_name($pageId,$widget_id);
        die();
    }

    else if($action=="get_chatwidget_name"){

        $widget_id=filter_input(INPUT_POST,'widget_id');
        echo smartbot_get_chatwidget_name($pageId,$widget_id);
        die();
    }
     else if($action=="get_checkbox_name"){

         $widget_id=filter_input(INPUT_POST,'widget_id');

         echo smartbot_get_checkbox_name($pageId,$widget_id);
            die();
        }

    else if($action=="get_origin"){
        $profileId=filter_input(INPUT_POST,'profile_id');

        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_get_profile_origin($pageId,$profileId);
        die();
    }

    else if($action=="get_profile_id_with_indexid"){
        $profileIndexId = filter_input(INPUT_POST,'profile_index_id');
        echo getProfileIdWithProfileIndexId($pageId,$profileIndexId);
        die();
    }

    else if($action=="get_profile_details_block"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        if($profileId == NULL) {
            die();
        }
        $rows = smartbotProfileDetailsBlockData($profileId,$pageId);
        /* Profile details name etc*/
        if(is_array($rows)){
            if($rows[0]['profile_id']===null){
                $profile_psid = 'N/A';
            }
            else{
                $profile_psid = $rows[0]['profile_id'];
            }
            $profile_image = $rows[0]['profile_pic'];
            $profile_name = $rows[0]['first_name'].' '.$rows[0]['last_name'];
            $profile_gender =$rows[0]['gender'];
            $profile_locale =$rows[0]['locale'];
            $profile_time_zone =$rows[0]['timezone'];
            $offset = $profile_time_zone ." hours";
            $utc_time =  gmdate("H:i",  strtotime($offset));
            $date_added =$rows[0]['date_added']; $profile_date_added = date('Y-m-d H:i', $date_added);
            $last_contact =$rows[0]['last_contact']; $profile_last_contact= date('Y-m-d H:i', $last_contact);
            $now=time();
            $difference_in_seconds = $now - $last_contact;

            $diff_hours = $difference_in_seconds / 60 / 60;
            if($diff_hours < 24){
                $status_active = 1;
            }
            else {
                $status_active = 0;
            }

            $language = smartbot_get_lang_name($profile_locale);
            $chat_profile='
            <div class="chat_profile_content">
             <div class="chat_profile_image">
                 <span style="background-image: url('.$profile_image.'); height: 95px;    width: 95px;    background-repeat: no-repeat;    background-size: cover;      float: left;    margin-right: 12px; border-radius: 4px;   "></span>
             </div>
             <div class="chat_profile_PSID"><i class="fa icon-network-lock"></i> '.$profile_psid.'</div>
             <div class="chat_profile_gender">';
            if($profile_gender=='male'){
                $chat_profile.='<i class="fa icon-man2"></i> Male';
            }
            else if($profile_gender=='female'){
                $chat_profile.='<i class="fa icon-woman2"></i> Female';
            }
            else if($profile_gender=='undefined'){
                $chat_profile.='<i class="fa icon-man2"></i> Undefined';
            }
            $chat_profile.='</div>
		  <div class="chat_profile_locale"><i class="fa icon-earth"></i> '.$language.'</div>
		  <div class="chat_profile_time_zone"><i class="fa icon-clock3"></i> '.$utc_time.' (UTC '.$profile_time_zone.')</div>';
            $chat_profile.='<div class="chat_profile_active_status">';

            if($rows[0]['subscribe'] == 0){
                $chat_profile.='<i class="fa icon-cross-circle"></i> Unsubscribed';
            }

            if($rows[0]['subscribe'] == 1){
                if($status_active=='0'){
                    $chat_profile.='<i class="fa icon-cross-circle"></i> Inactive subscriber';
                }
                if($status_active=='1'){
                    $chat_profile.='<span style="color:#13ce66"><i class="fa icon-checkmark-circle"></i> Active subscriber</span>';
                }
            }

            if($rows[0]['subscribe'] =='2'){
                $chat_profile.='<span style="color:#FF432D"><i class="fa icon-checkmark-circle"></i> Paused subscriber</span>';
            }

            $chat_profile.='</div>';

            $chat_profile.='</div>';
        }
        /* Chat pause Status*/
        $chat_status = $rows[0]['subscribe'];
        if(isset($chat_status)){
            if($chat_status=='1'){$chat_status='<span class="chat_pause_span styling_automated_green"><small class="chat_pause_small">Automated Replies Activated</small></span>';}
            if($chat_status=='0'){$chat_status='<span class="chat_pause_span styling_automated_red"><small class="chat_pause_small">Autoreplies Off</small></span>';}
            if($chat_status=='2'){$chat_status='<span class="chat_pause_span styling_automated_red"><small class="chat_pause_small">Automated Replies Paused</small></span>';}
        }
        /* Tags*/
        if(is_array($rows)){
            $these_tags='';
            foreach($rows as $rij){
                $this_tag = $rij['tag_name'];
                //$this_tag_id = $rij['tag_id'];
                $this_id = $rij['id'];
                if($this_tag!=NULL) {
                    $these_tags .= '<span class="chat_tags" id="tag_' . $this_id . '">' . $this_tag . ' <span class="delete_tag" data-tag_id="' . $this_id . '"> <i class="fa icon-cross"></i></span></span>';
                }
            }
        }
        /* Custom Fields */
        $cfields =  getProfileCustomfields($profileId,$pageId);
        $cfieldsKeys = getAllCustomfieldsData($pageId);$customFieldsKeys='';
        if(is_array($cfieldsKeys)){
            $customFieldsKeys='<select id="customfields_keys"  class="form-control"><option value="">Select Custom Field</option>';
            foreach($cfieldsKeys as $thisKey){
	            $customFieldsKeys.='<option value="'.$thisKey->id.'">'.$thisKey->customfield_name.'</option>';
            }
	        $customFieldsKeys.='</select>';
        }

        if(is_array($cfields)){
            $customFields = "";
            foreach($cfields as $result){
                $customfieldName = $result->customfield_name;
                $customfieldValue = $result->customfield_value;
                $customfieldId = $result->id;
                $customFields .= '<div class="style_customfields_values_container" id="customfield_'.$customfieldId.'"><span class="chat_tags styling_span_tags customfield_key style_customfield_key">'.$customfieldName.'</span><span class="chat_tags styling_span_tags customfield_value style_customfield_value click-to-copy" data-field-val="'.$customfieldValue.'" data-toggle="tooltip" data-original-title="Click to copy">'.$customfieldValue.' </span><span class="delete_customfield style_customfields_delete" data-customfield_id="'.$customfieldId.'"> <i class="fa icon-cross"></i></span></span></div>';
            }
        }

        /* Entry point i.e origin */
        $entryPoint = $rows[0]["origin"].":".$rows[0]["origin_id"];
        $origin = "";
        $originid = "";
        if (strlen($rows[0]["origin"])!=0) {
            if ($rows[0]["origin"] == "checkbox") {
                $origin = "Checkbox Plugin ";
                $widget_name = smartbot_get_checkbox_name($user_id,$pageId,$rows[0]["origin_id"]);
                $originid = "<a href='checkbox_builder.php?widget=".$rows[0]["origin_id"]."'>".$widget_name."</a>";

            }
            else if ($rows[0]["origin"] == "sendtomessenger") {
                $origin = "Send to messenger plugin ";
                $widget_name = smartbot_get_widget_name($user_id,$pageId,$rows[0]["origin_id"]);
                $originid = "<a href='widget_builder.php?widget=".$rows[0]["origin_id"]."'>".$widget_name."</a>";

            }

            else if ($rows[0]["origin"] == "scancode") {
                $origin = "Messenger code";
                $scanCodeData = messengerCodeData($pageId,$rows[0]["origin_id"]);
                $originid = "<a href='messenger_code_builder.php?widget=".$rows[0]["origin_id"]."'>".stripslashes($scanCodeData["code_name"])."</a>";

            }
            elseif (strpos($rows[0]["origin"], 'mme') !== false) {

                $origin = "M.me Link";
                $link = explode("@",$rows[0]["origin"]);
                $link = "https://m.me/$pageId/?ref=$link[1]";
                $originid = "<a href='$link'>".$link."</a>";

            }
            else if ($rows[0]["origin"] == "manychat") {
                $origin = "Imported from ManyChat ";

            }
            else if ($rows[0]["origin"] == "customerchat") {
                $origin = "Customer Chat Capture tool ";
                $widget_name = smartbot_get_chatwidget_name($user_id,$pageId,$rows[0]["origin_id"]);
                $originid = "<a href='customerchat_builder.php?widget=".$rows[0]["origin_id"]."'>".$widget_name."</a>";

            }

            else if ($rows[0]["origin"] == "messenger") {
                $origin = "Direct Message ";
                    }
            else if ($rows[0]["origin"] == "instant") {
                $origin = "Post Engagement Capture Tool";
                $postEngagementRow = getPostEngagemenRow($rows[0]["origin_id"]);
                $originid = "<a href='post_engagement_builder.php?id=".$rows[0]["origin_id"]."'>".$postEngagementRow->name."</a>";
                }
        }
        /*---*/

        echo "
        <div id='edit_profile'>".$chat_profile."</div>
        <div id='edit_pause_status' style='margin-top:20px;margin-bottom:  20px;'>".$chat_status."</div>
    
        <span id='edit_email_title' style='padding-bottom: 10px;'><strong style='color: #8f939b;'>Email</strong></span>
        <hr class='datatable-hr' style='margin: 5px 0px 5px 0px;'>
        <div id='edit_email'></div>
        <div id='edit_email_button' class='input-group' style='padding-top: 10px;padding-bottom: 20px;'>
            <input id='email_value' value='' class='form-control' style='border-bottom-left-radius: 4px;border-top-left-radius: 4px;' placeholder='Enter subscriber email here...'>
            <span class='input-group-btn' id='add_email'><button type='button' class='btn btn-primary'>Save Email</button></span>
        </div>
    
        <span id='edit_tags_title'><strong style='color: #8f939b;'>Tags</strong></span>
        <hr class='datatable-hr' style='margin: 5px 0px 5px 0px;'>
        <div id='edit_tags' style='padding-top: 10px;padding-bottom: 10px;'>".$these_tags."</div>
        <div class='input-group' id='edit_tags_button' style='padding-bottom: 20px;'>
            <input id='tag_value' value='' class='form-control' style='border-bottom-left-radius: 4px;border-top-left-radius: 4px;' placeholder='Enter tag(s) here'>
            <span class='input-group-btn' id='add_tag'><button type='button' class='btn btn-primary'>Add Tag</button></span>
        </div>";

        if($customFieldsKeys!="") {
	        echo "
        <span id='edit_customfields_title'><strong style='color: #8f939b;'>Custom Fields</strong></span>
        <hr class='datatable-hr' style='margin: 5px 0px 5px 0px;'>
        <div id='edit_customfields' style='padding-top: 10px;padding-bottom: 10px;'>".$customFields."</div>
        
        <div class='input-group' id='edit_customfields_button' style='padding-bottom:  10px;width:100%;'>
            ".$customFieldsKeys."
            <input id='customfield_value' value='' class='form-control' placeholder='Enter custom field value here - i.e 555-5555'>
            <span class='' id='add_customfield'><button type='button' class='btn btn-primary' style='border-radius: 0px 0px 5px 5px;width: 100%;margin: 0px 0px 10px 0px;'>Add Custom Field</button></span>
        </div>";
        } //end if customfields are present...not added an else but we could add some notice

        echo "
    
        <span style='display:none;'>
            <strong style='color: #8f939b;'>Sequences</strong>
            <hr class='datatable-hr' style='margin: 5px 0px 15px 0px;'>
        </span>
        <div class='col-lg-12' style='padding: 0px 0px 20px 0px;display:none;'>
            <span data-profile_id='' class='btn btn-primary' style='width: 100%;'> Subscribe to Sequence</span>
        </div>
    
    
        <span id=''><strong style='color: #8f939b;'>Entry Point</strong><hr class='datatable-hr' style='margin: 5px 0px 5px 0px;'></span>
        <div id='' style='padding-top: 10px;padding-bottom: 10px;'>
            <span class='chat_tags styling_span_tags' >
                <strong id='origin' class='profile_origin_type'>".$origin."</strong>
                <span class='profile_origin_name' id='origin_id'>".$originid."</span>
                <span class='' data-tag_id='34'></span>
            </span>
        </div>
        ";

        die();
    }
    else if($action =="record_agency_checkout"){
        //$_SESSION['agency_checkout'] = [];
        $_SESSION['agency_checkout']['name'] = filter_input(INPUT_POST,'prod');
        $_SESSION['agency_checkout']['qty'] = filter_input(INPUT_POST,'qty');
        echo json_encode($_SESSION['agency_checkout']);
        die();
    }
    else if($action =="get_agency_checkout"){
        if(isset($_SESSION['agency_checkout'])) {
            $obj = new stdClass();
            $obj->name = $_SESSION['agency_checkout']['name'];
            $obj->qty = $_SESSION['agency_checkout']['qty'];
            $json = json_encode($obj);
            unset($_SESSION['agency_checkout']);
            echo $json;
        }
        die();
    }
    else if($action =="get_agency_id"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        echo $agencyId;
        die();
    }
    else if($action =="agency_client_takeover"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        $clientId = filter_input(INPUT_POST,'id');
        echo @agencyTakeoverClient($agencyId,$clientId);
        die();
    }
    else if($action =="add_agency_client"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        $firstName = filter_input(INPUT_POST,'firstName');
        $lastName = filter_input(INPUT_POST,'lastName');
        $email = filter_input(INPUT_POST,'email');
        $pass = filter_input(INPUT_POST,'password');
        $subs = filter_input(INPUT_POST,'subs');
        echo createAgencyClient($agencyId,$email,$pass,$firstName,$lastName,$subs);
        die();
    }

    else if($action =="delete_agency_clients"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        $clientIds = json_decode(filter_input(INPUT_POST,'clientIds'));
        echo deleteAgencyClients($agencyId,$clientIds);
        die();
    }
    else if($action =="agency_client_subs_upgrade"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        $clientId = filter_input(INPUT_POST,'clientId');
        $subsQty = filter_input(INPUT_POST,'qty');
        echo upgradeClientSubs($agencyId,$clientId,$subsQty);
        die();
    }
    else if($action =="agency_client_change_status"){
        $ownerId = $_SESSION["user"]["id"];
        $agencyId = getAgencyId($ownerId);
        $clientId = filter_input(INPUT_POST,'id');
        $type = filter_input(INPUT_POST,'type');
        echo changeClientStatus($agencyId,$clientId,$type);
        die();
    }
    else if($action =="get_agency_details"){
        $agencyId = getAgencyId($_SESSION["user"]["id"]);
        if($_SESSION['membership']['whitelabel']==1) {
            $whitelabel = true;
        }
        else {
            $whitelabel = false;
        }
        $agencyDetails = getAgencyDetails($agencyId,$whitelabel);
        echo json_encode($agencyDetails);
        die();
    }
    else if($action =="get_agency_products"){
        $prods = getAgencyProducts();
        echo json_encode($prods);
        die();
    }
    else if($action =="agency_enable_sub_user"){
        $mainUserId = $_SESSION["user"]["id"];
        $subUserId = filter_input(INPUT_POST,'subuserid');
        agencySetSubUserStatus($mainUserId,$subUserId,"enable");
        die();
    }
    else if($action =="agency_disable_sub_user"){
        $mainUserId = $_SESSION["user"]["id"];
        $subUserId = filter_input(INPUT_POST,'subuserid');
        agencySetSubUserStatus($mainUserId,$subUserId,"disable");
        die();
    }
    else if($action =="agency_delete_sub_user"){
        $mainUserId = $_SESSION["user"]["id"];
        $subUserId = filter_input(INPUT_POST,'subuserid');
        if (agencyAuthCheck($mainUserId, $subUserId) == true){
            $deletestatus = smartbot_delete_subuser($subUserId,$mainUserId);
        }
        die();
    }
	else if($action=="delete_customfield_profile"){
		$profileId=filter_input(INPUT_POST,'profile_id');
		$customfieldId=filter_input(INPUT_POST,'customfield_id');
		echo deleteProfileCustomfield($profileId,$pageId,$customfieldId);
		die();
	}

    else if($action=="delete_tag_profile"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        $user_id=$_SESSION['user_id'];
        $tag_id=filter_input(INPUT_POST,'tag_id');
        smartbot_delete_tag_profile($user_id,$pageId,$profileId,$tag_id);
        die();
    }

    else if($action=="get_email"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_get_email_profile($user_id,$pageId,$profileId);
        die();
    }

    else if($action=="set_email"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        $email=filter_input(INPUT_POST,'email');
        if (smartbot_set_email_profile('',$pageId,$profileId,$email))
            echo 'Successfully added email';
        else
            echo 'Error while saving email';

        die();
    }
    else if($action =="open_conversation"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        $profileIndex = getProfileIndexIdWithProfileId($_SESSION['page_id'],$profileId);
        echo insertConversation($_SESSION['page_index_id'], $profileIndex);
        die();
    }
    else if($action =="set_chat_status"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        echo smartbot_chat_set_status($profileId,$pageId);
        die();
    }

    else if($action =="set_live_chat_status"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        $status = filter_input(INPUT_POST,'status');
        echo smartbot_set_chat_status($profileId,$pageId,$status);
        die();
    }

    else if($action =="set_livechat_profile_status"){
        $profileIndexId = filter_input(INPUT_POST,'profile_index_id');
        $status = filter_input(INPUT_POST,'status');
        if($status == 0){
            echo closeConversation($_SESSION['page_index_id'],$profileIndexId);
            //echo livechatCloseProfileChat($profileIndexId, $_SESSION['page_index_id']);
        }
        elseif($status == 1){
            echo openConversation($_SESSION['page_index_id'],$profileIndexId);
            //echo livechatOpenProfileChat($profileIndexId, $_SESSION['page_index_id']);
        }
        die();
    }

    else if($action =="livechat_close_all"){
        livechatCloseAllChats($_SESSION['page_index_id']);
        die();
    }

    else if($action =="livechat_open_all"){
        livechatOpenAllChats($_SESSION['page_index_id']);
        die();
    }

    else if($action =="live_chat_close_all"){
        smartbot_close_all_chat_profiles($pageId);
        die();
    }

    else if($action =="live_chat_open_all"){
        smartbot_open_all_chat_profiles($pageId);
        die();
    }

    else if($action =="get_chat_status"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        echo smartbot_chat_status($profileId,$pageId);
        die();
    }

    else if($action=="get_chat_profile"){

        $profileId=filter_input(INPUT_POST,'profile_id');
        
        echo smartbot_chat_profile($profileId,$pageId);
        die();
    }

    else if($action=="sendmessage"){
        echo ScheduleBroadcast();
    }

    else if($action=="delete_broadcast"){
        $broadcastId=filter_input(INPUT_POST,'msg_id');
        echo deleteBroadcast($pageId,$broadcastId);
        die();
    }

    else if($action=="bulk_delete_broadcast"){
        $idsArray=json_decode(filter_input(INPUT_POST,'msg_ids'));
        echo bulkDeleteBroadcast($pageId,$idsArray);
        die();
    }

    else if($action=="delete_profile"){
        $profileId=filter_input(INPUT_POST,'profile_id');
        
        echo deleteProfile($pageId,$profileId);
        die();
    }

      else if($action=="download_profile_data"){
            $profileID=filter_input(INPUT_POST,'profile_id');

            $profileData = exportSubscriberData($profileID,$pageId);
            $path = __DIR__."/../profiles_data/".$profileID."_".$pageId.".json";

             writeToFile($path,json_encode($profileData,JSON_PRETTY_PRINT),"w");
            echo $profileID."_".$pageId;
             die();
        }


    else if($action=="delete_flow"){
        $flow_id=filter_input(INPUT_POST,'flow_id');

        echo smartbot_delete_flow($pageId,$flow_id);
        die();
    }


    else if($action=="delete_segment"){
        $flowID=filter_input(INPUT_POST,'segment_id');

        echo deleteSegment($pageId,$flowID);
        die();
    }

    else if($action=="delete_widget"){
        $widget_id=filter_input(INPUT_POST,'widget_id');

        echo smartbot_delete_widget($pageId,$widget_id);
        die();
    }

    else if($action=="delete_checkbox"){
        $widget_id=filter_input(INPUT_POST,'widget_id');

        echo smartbot_delete_checkbox($pageId,$widget_id);
        die();
    }

	else if($action=="delete_chatwidget"){
		$widget_id=filter_input(INPUT_POST,'widget_id');
		echo smartbot_delete_chatwidget($pageId,$widget_id);
		die();
	}

	else if($action=="delete_messenger_code"){
		$widget_id=filter_input(INPUT_POST,'widget_id');

		echo smartbot_delete_messenger_code($pageId,$widget_id);
		die();
	}
	else if($action=="manage_flow_content"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		echo smartbot_manage_flow_content($flow_id);
	}

	else if($action=="share_flow_reject"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		echo smartbot_share_flow_reject($flow_id);
	}

	else if($action=="share_flow_approve"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		echo smartbot_share_flow_approve($flow_id);
	}

	else if($action=="share_flow_content"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$flow_name=filter_input(INPUT_POST,'flow_name');
		echo smartbot_share_flow_content($flow_id, $flow_name);
    }

	else if($action =="share_flow"){
		$user_id=filter_input(INPUT_POST,'user_id');
		$user_name = $_SESSION['first_name'].' '. $_SESSION['last_name'];
		$pageId = $_SESSION['page_id'];
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$share_type=filter_input(INPUT_POST,'share_type');
		echo smartbot_share_flow($user_id,$user_name, $flow_id,$share_type,$pageId);
    }

    else if($action=="delete_user_flow"){
	    $flow_id=filter_input(INPUT_POST,'flow_id');
	    $user_id=filter_input(INPUT_POST,'user_id');
	    smartbot_flow_delete_user($flow_id,$user_id);
    }
	else if($action=="add_user_flow"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$user_name=filter_input(INPUT_POST,'user_name');
		echo smartbot_flow_add_user($flow_id,$user_name);
	}
    else if($action=="share_flow_delete"){
	    $flow_id=filter_input(INPUT_POST,'flow_id');
	    smartbot_flow_delete_share($flow_id);
    }

	else if($action=="share_flow_public"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		smartbot_flow_public_share($flow_id);
	}

	else if($action=="share_flow_private"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		smartbot_flow_private_share($flow_id);
	}


	else if($action=="get_import_into_flows"){
		$user_id=filter_input(INPUT_POST,'user_id');
		$flow_id=filter_input(INPUT_POST,'flow_id');
		echo smartbot_get_import_into_flows($user_id,$flow_id);
	}

	else if($action=="import_flow_yes"){
		$user_id=filter_input(INPUT_POST,'user_id');
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$share_id=filter_input(INPUT_POST,'share_id');
		echo smartbot_import_flow_yes($user_id,$pageId,$flow_id,$share_id);
	}

	else if($action=="import_into_flow_yes"){
		$user_id=filter_input(INPUT_POST,'user_id');
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$share_id=filter_input(INPUT_POST,'share_id');
		echo smartbot_import_into_flow_yes($user_id,$flow_id,$share_id);
	}

	else if($action=="duplicate_flow"){
		$flow_id=filter_input(INPUT_POST,'flow_id');
		$flow_name=filter_input(INPUT_POST,'flow_name');
		$user_id=filter_input(INPUT_POST,'user_id');
		echo smartbot_duplicate_flow($pageId,$user_id,$flow_id,$flow_name);
		die();
	}

    else if($action=="rename_flow"){
        $flow_id=filter_input(INPUT_POST,'flow_id');
        $flow_name=filter_input(INPUT_POST,'flow_name');

        echo smartbot_rename_flow($pageId,$flow_id,$flow_name);
        die();
    }

    else if($action=="rename_segment"){
        $segmentID=filter_input(INPUT_POST,'segment_id');
        $segmentName=filter_input(INPUT_POST,'segment_name');

        echo renameSegment($pageId,$segmentID,$segmentName);
        die();
    }


    else if($action=="rename_widget"){
		$widget_id=filter_input(INPUT_POST,'widget_id');
		$widget_name=filter_input(INPUT_POST,'widget_name');

		echo smartbot_rename_widget($pageId,$widget_id,$widget_name);
		die();
	}

    else if($action=="generate_fb_access_token"){
         require_once __DIR__."/classes/FacebookAccessTokenHelper.php";
        $username=filter_input(INPUT_POST,'username');
        $password=filter_input(INPUT_POST,'password');
        $accessTokenHelper = new FacebookAccessTokenHelper($username,$password);
        $accessTokenHelper->generateAccessToken();
        $result = new stdClass();
        $result->status = $accessTokenHelper->getStatus();
        if ($accessTokenHelper->getStatus()){
             $result->accessToken = $accessTokenHelper->getAccessToken();
        }
        else{
            $result->errorCode = $accessTokenHelper->getErrorCode();
            $result->errorMessage = $accessTokenHelper->getErrorMessage();

        }
        echo json_encode($result);
        die();
    }

	else if($action=="rename_checkbox"){
		$widget_id=filter_input(INPUT_POST,'widget_id');
		$widget_name=filter_input(INPUT_POST,'widget_name');

		echo smartbot_rename_checkbox($pageId,$widget_id,$widget_name);
		die();
	}

	else if($action=="rename_chatwidget"){
		$widget_id=filter_input(INPUT_POST,'widget_id');
		$widget_name=filter_input(INPUT_POST,'widget_name');

		echo smartbot_rename_chatwidget($pageId,$widget_id,$widget_name);
		die();
	}

	else if($action=="rename_messenger_code"){
		$code_id=filter_input(INPUT_POST,'code_id');
		$code_name=filter_input(INPUT_POST,'code_name');

		echo smartbot_rename_messenger_code($pageId,$code_id,$code_name);
		die();
	}

    else if($action=="delete_bulk_profile"){
        $profileIdsArray=json_decode(filter_input(INPUT_POST,'profile_ids'));
        echo json_encode(bulkDeleteProfiles($pageId, $profileIdsArray));
        die();
    }

    else if($action=="delete_bulk_memberships"){
        $membership_ids=json_decode(filter_input(INPUT_POST,'membership_ids'));
        $user_id=filter_input(INPUT_POST,'user_id');
        foreach ($membership_ids as $membership_id) {
            echo "membership $membership_id";
            echo "userid $user_id";
            $pos = strpos($membership_id, "subs");
            if($pos === false){
                echo smartbot_user_delete_membership($user_id,$membership_id);
            } else {
                $membership_id = ltrim($membership_id,"subs");
                echo smartbot_user_delete_subscribers($user_id,$membership_id);
            }

        }
        die();
    }

    else if($action=="delete_membership"){
        $membership_id=json_decode(filter_input(INPUT_POST,'membership_id'));
        $user_id=filter_input(INPUT_POST,'user_id');
            echo "membership $membership_id";
            echo "userid $user_id";
            $pos = strpos($membership_id, "subs");
            if($pos === false){
                echo smartbot_user_delete_membership($user_id,$membership_id);
            } else {
                $membership_id = ltrim($membership_id,"subs");
                echo smartbot_user_delete_subscribers($user_id,$membership_id);
            }

        die();
    }

    else if($action=="delete_bulk_flows"){
        $flow_ids=json_decode(filter_input(INPUT_POST,'flow_ids'));

        foreach ($flow_ids as $flow_id) {
            echo smartbot_delete_flow($pageId, $flow_id);
        }
        die();
    }

    else if($action=="delete_bulk_segment"){
        $segmentIDs=json_decode(filter_input(INPUT_POST,'segment_ids'));
        echo bulkDeleteSegment($pageId,$segmentIDs);
        die();
    }

    else if($action=="delete_bulk_widgets"){
        $widget_ids=json_decode(filter_input(INPUT_POST,'widget_ids'));
        echo bulkDeleteWidgets($pageId,$widget_ids);
        die();
    }

    else if($action=="get_segment_name"){
        $segmentId=filter_input(INPUT_POST,'segment_id');
        echo getSegmentName($_SESSION['page_id'],$segmentId);
        die();
    }


    else if($action=="get_tag_name"){
        $tagId=filter_input(INPUT_POST,'tag_id');
        echo getTagName($_SESSION['page_id'],$tagId);
        die();
    }

    else if($action=="get_custom_field_name"){
        $customFieldId=filter_input(INPUT_POST,'custom_field_id');
        echo getCustomfieldName($_SESSION['page_id'],$customFieldId);
        die();
    }

    else if($action=="get_global_field_name"){
        $id=filter_input(INPUT_POST,'global_field_id');
        echo getGlobalFieldName($_SESSION['page_id'],$id);
        die();
    }
    else if($action=="create_tag"){
        $tag = filter_input(INPUT_POST,'tag');
        $check = checkTagName($_SESSION['page_id'], $tag);
        if($check === 'exists'){
            echo $check;
        }
        else{
            $id = createTag($_SESSION['page_id'],$tag);
            echo json_encode(getTagById($id));
        }
        die();
    }

    else if($action=="rename_tag"){
        $newName=filter_input(INPUT_POST,'name');
        $tagId=filter_input(INPUT_POST,'id');
        echo renameTag($_SESSION['page_id'], $tagId, $newName);
        die();
    }

    else if($action=="delete_tag"){
        $tagId=filter_input(INPUT_POST,'id');
        echo deleteTag($_SESSION['page_id'], $tagId);
        die();
    }

    else if($action=="delete_bulk_tags"){
        $ids = json_decode(filter_input(INPUT_POST,'ids'));
        echo bulkDeleteTags($_SESSION['page_id'],$ids);
        die();
    }

	else if($action=="create_customfield"){
		$customFieldName=filter_input(INPUT_POST,'customFieldName');
		$customFieldType=filter_input(INPUT_POST,'customFieldType');
		echo insertNewCustomfield($_SESSION['page_id'],$customFieldName,$customFieldType);
		die();
	}

    else if($action=="delete_bulk_customfields"){
        $customFieldIDsArray=json_decode(filter_input(INPUT_POST,'ids'));
        echo bulkDeleteCustomfields($_SESSION['page_id'],$customFieldIDsArray);
        die();
    }

    else if($action=="delete_customfield"){
        $customFieldID=filter_input(INPUT_POST,'customFieldID');
        echo deleteCustomfieldRow($_SESSION['page_id'],$customFieldID);
        die();
    }
    else if($action=="delete_bulk_checkboxes"){
        $idsArray=json_decode(filter_input(INPUT_POST,'widget_ids'));
        echo bulkDeleteCheckbox($pageId,$idsArray);
        die();
    }

	else if($action=="delete_bulk_chatwidgets"){
        $idsArray=json_decode(filter_input(INPUT_POST,'widget_ids'));
        echo bulkDeleteChatwidget($pageId,$idsArray);
		die();
	}

	else if($action=="delete_bulk_messenger_code"){
        $idsArray=json_decode(filter_input(INPUT_POST,'widget_ids'));
        echo bulkDeleteMessengerCode($pageId,$idsArray);
		die();
	}

    else if($action=="get_active_integrations"){
        echo smartbot_get_integrations($user_id,$pageId);
    }

    else if($action=="return_integration_get_vars"){
        $getParams = filter_input(INPUT_POST,'getParams');
        echo returnApiKeytoForm($getParams);
    }

    else if($action=="save_integration_keys"){
        $id = filter_input(INPUT_POST,'id');
        $name = filter_input(INPUT_POST,'name');
        $formData = filter_input(INPUT_POST,'formData');
        $pageIndex = $_SESSION['page_index_id'];
        $pageId = $_SESSION['page_id'];
        echo captureIntegrationFormData($name,$pageIndex,$pageId,$id,$formData);
        die();
    }

    else if($action=="get_ar_lists"){
        $ar_type=filter_input(INPUT_POST,'ar_type');
        if($ar_type=="raw_html"){
            echo '<div class="styling_configurationpage_savear_div"><span class="btn btn-primary save_ar_settings styling_configurationpage_savear">Save</span></div>';
        }
        else if($ar_type=="campaignmonitor"){
            echo '<input type="text" name="ar_list_name" class="form-control" placeholder="Please enter your list ID" >';
            echo '<div class="styling_configurationpage_savear_div"><span class="btn btn-primary save_ar_settings styling_configurationpage_savear">Save</span></div>';
        }
        else if($ar_type=="markethero"){
            echo '<div class="styling_configurationpage_savear_div"><span class="btn btn-primary save_ar_settings styling_configurationpage_savear">Save</span></div>';
        }
        else{
            $ar_lists = smartbot_get_ar_list($ar_type,'',$user_id);
            if(($ar_lists)){
                echo '<select id="ar_settings_list"  class="form-control input-lg">';
                echo smartbot_format_lists($ar_type,$ar_lists);
                echo '</select><div class="styling_configurationpage_savear_div"><span class="btn btn-primary save_ar_settings styling_configurationpage_savear">Save</span></div>';
                die();
            }
        }
    }

    else if($action =="save_ar_list"){
        $ar_list=filter_input(INPUT_POST,'ar_list');
        $ar_list_name=filter_input(INPUT_POST,'ar_list_name');
        $ar_type=filter_input(INPUT_POST,'ar_type');
        $bot_id = filter_input(INPUT_POST,'bot_id');
        smartbot_save_ar_list($ar_type,$ar_list,$ar_list_name,$user_id,$bot_id,$pageId);
        die();
    }

    else if($action =="detectLoop"){
        $data=filter_input(INPUT_POST,'data');
        $linkData=filter_input(INPUT_POST,'linkData');
        echo detectLoop($data,$linkData);
        die();
    }

    else if($action =="get_integrations"){
        echo smartbot_get_integrations($user_id,$pageId);
        die();
    }

    else if($action =="delete_integration"){
        $int_id = filter_input(INPUT_POST,'int_id');
        echo smartbot_delete_ar_list($int_id,$user_id,$pageId);
        die();
    }

    else if($action =="delete_page_integration"){
        $id = filter_input(INPUT_POST,'id');
        echo deletePageIntegration($id,$_SESSION['page_index_id']);
        die();
    }

    else if($action =="bulk_delete_page_integrations"){
        $ids = json_decode(filter_input(INPUT_POST,'ids'));
        echo bulkDeleteIntegrations($_SESSION['page_index_id'],$ids);
        die();
    }

    else if($action =="change_integration_status"){
        $id = filter_input(INPUT_POST,'id');
        $newStatus = filter_input(INPUT_POST,'newStatus');
        echo changeIntegrationStatus($id,$newStatus,$_SESSION['page_index_id']);
        die();
    }

    else if($action =="get_integration_form"){
        $id = filter_input(INPUT_POST,'id');
        echo getServiceFieldsForm($id);
        die();
    }

    else if($action=="Get_Card_Message"){
        $user_id=filter_input(INPUT_POST,'user_id');
        $msg_id=filter_input(INPUT_POST,'msg_id');
        echo smartbot_view_msg($user_id,$msg_id);
        die();
    }
/*
    else if($action=="Get_Sent_Message"){
        $user_id=$_SESSION['user_id'];
        $msg_id=filter_input(INPUT_POST,'msg_id');
        echo smartbot_view_ar_msg($user_id,$pageId,$msg_id);
        die();
    }*/

    else if($action=="Get_Sent_Message"){
        $user_id=$_SESSION['user_id'];
        $msg_id=filter_input(INPUT_POST,'msg_id');
        echo ViewHistorialBroadcastMessage($user_id,$pageId,$msg_id);
        die();
    }

    else if($action=="get_messenger_code"){
        $size=filter_input(INPUT_POST,'size');
        if(empty($size))
        {
            $size="500";
        }
        $refType=filter_input(INPUT_POST,'refType');
        $flowID=filter_input(INPUT_POST,'flowID');
        $msgID=filter_input(INPUT_POST,'msgID');
	    $widgetID=filter_input(INPUT_POST,'widgetID');
	    $update=filter_input(INPUT_POST,'update');
	    if($update=="true")
        updateMessengerCode($size,$refType,$flowID,$msgID,$widgetID,$_SESSION["page_id"]);
	    $pageToken = getPageToken($pageId);
        if ($refType=="flow" || $refType=="flowcard")
            echo smartbot_get_page_messenger_code($_SESSION['page_id'] ,$pageToken,$size,"clevercode_$widgetID");
        else if ($refType=="general")
            echo smartbot_get_page_messenger_code($_SESSION['page_id'] ,$pageToken,$size);
        else
            echo 0;
        die();
    }

    else if($action=="search_giphy"){
        $keyword=filter_input(INPUT_POST,'keyword');
	    $offset= filter_input(INPUT_POST,'offset');
        echo smartbot_search_giphy($keyword,$offset);
        die();
    }

    else if($action=="youzign_lib"){
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_show_youzign($_SESSION['user_id']);
        die();
    }

    else if($action=="image_lib"){
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_show_youzign($user_id);
        die();
    }

    else if($action=="get_triggers"){
        $msg_id = filter_input(INPUT_POST,'msg_id');
        $msg_type = filter_input(INPUT_POST,'msg_type');
        $bot_id = filter_input(INPUT_POST,'bot_id');
        
        echo smartbot_get_triggers($msg_id,$bot_id,$pageId,$msg_type);
        die();
    }


    else if($action=="show_file_library"){
        $msg_type = filter_input(INPUT_POST,'msg_type');
        $user_id = filter_input(INPUT_POST,'user_id');
        smartbot_show_file_library($user_id, $msg_type);
        die();
    }

    else if($action=="show_image_library"){
        echo smartbot_show_image_library($user_id);
        die();
    }

    else if($action=="get_subs"){
	   if(isset($_POST['page_id'])){$pageId= filter_input(INPUT_POST,'page_id'); }
        //we are overriding the session page id here in case it is not set as then we are on the main dashboard and do not have a page id
        echo smartbot_get_subscribers($pageId);
        die();
    }

    else if($action=="upload_file"){
        $this_file = $_FILES;
        $user_id=filter_input(INPUT_POST,'user_id');
        $msg_type=filter_input(INPUT_POST,'msg_type');
        smartbot_upload_file($user_id,$this_file,$msg_type);
        die();
    }

    else if($action=="delete_bot"){
        $botId = filter_input(INPUT_POST,'bot_id');
        $pageId= filter_input(INPUT_POST,'page_id'); //we are overriding the session page id here as we are on the dashboard and do not have a page id
        $userId=$_SESSION['user_id'];
        $userIndex = $_SESSION['user']['id'];
        echo deleteBot($pageId,$userIndex);
        die();
    }

    else if($action=="get_the_posts"){
        
        $userID = $_SESSION['user_id'];
        $after=filter_input(INPUT_POST,'after');
        $fbcId=filter_input(INPUT_POST,'fbcid');
        $excludedPosts = getUsedFBPosts($fbcId,$pageId);
        $excludedPostsString = "";
        foreach ($excludedPosts as $excludedPost){
            $excludedPostsString .= $excludedPost[0].",";
        }

        if (!empty($after)){
            echo smartbot_get_fb_posts($pageId,$userID,$excludedPostsString,$after);
        }else{
            echo smartbot_get_fb_posts($pageId,$userID,$excludedPostsString);
        }
        die();
    }


    else if($action=="get_page_welcome"){
        if($pageId!="") {
            echo getWelcomeMessage($pageId);
        }
    }

    else if($action=="get_page_welcome_on_off"){
        if($pageId!="") {
            echo getWelcomeMessageStatus($pageId);
        }
    }

    else if($action=="welcome_on"){
        if($pageId!="") {
            echo enableWelcomeMessage($pageId);
        }
    }

    else if($action=="welcome_off"){
        if($pageId!="") {
            echo disableWelcomeMessage($pageId);
        }
    }

	else if($action=="save_welcome_message_flow") {
		$flow = filter_input(INPUT_POST, 'flow_id');
		echo smartbot_save_keys('',$pageId,'',"welcome_message_flow",$flow);
	}

	else if($action=="save_default_reply_flow") {
		$flow = filter_input(INPUT_POST, 'flow_id');
		echo smartbot_save_keys('',$pageId,'',"default_reply_flow",$flow);

	}

    else if($action=="get_default_reply"){
        if($pageId!="") {
            echo getDefaultReply($pageId);
        }
    }

    else if($action=="get_default_reply_on_off"){
        if($pageId!="") {
            echo getDefaultReplyStatus($pageId);
        }
    }

    else if($action=="default_on"){
        if($pageId!="") {
            echo enableDefaultReply($pageId);
        }
    }

    else if($action=="default_off"){
       if($pageId!="") {
            echo disableDefaultReply($pageId);
        }
    }

    else if($action=="user_timezone_get") {
        if($user_id!='') {
            echo json_encode(smartbot_configurationfunctions_get_user_timezone($user_id,""));
        }
    }

    else if($action=="user_timezone_set") {
        $timezone=filter_input(INPUT_POST,'timezone');
        $country=filter_input(INPUT_POST,'country');
        echo smartbot_configurationfunctions_update_user_timezone($user_id,$pageId,$timezone,$country);
    }

    else if($action=="domain_whitelisting_add"){
        $domain=filter_input(INPUT_POST,'domain');
        if($domain!="" && $pageId!='') {
            echo updateWhitelistedDomains($pageId,$domain);
        }
    }

    else if($action=="domain_whitelisting_delete"){
        $domain=filter_input(INPUT_POST,'domain');
        if($domain!="" && $pageId!='') {
            echo smartbot_configurationfunctions_delete_whitelistdomain($pageId,$user_id,$domain);
        }
    }

    else if($action =="greeting_text"){
        if(!empty($pageId)){
            $greeting_text = filter_input(INPUT_POST,'greeting_text');
            $msg = insertGreetingText($pageId,$greeting_text);
        }else{$msg ="Error, no page selected"; }
        echo $msg;
        die();
    }

    else if($action =="greeting_template"){
        $pageId=filter_input(INPUT_POST,'page_id');
        if($pageId!=""){
            $greeting_text = filter_input(INPUT_POST,'greeting_text');
            $msg = smartbot_template_greeting_create_text($pageId,$greeting_text);
        }else{$msg ="Error, no page selected"; }
        echo $msg;
        die();
    }

    else if($action=="get_page_locals"){
        if($pageId!=""){
         echo smartbot_get_greeting_local($pageId);
        }
    }

    else if($action =="greeting_locale"){
        
        if($pageId!=""){
            $lang_code=filter_input(INPUT_POST,'lang_code');
            $lang_name=filter_input(INPUT_POST,'lang_name');
            $greeting_text = filter_input(INPUT_POST,'greeting_text');
            $msg = smartbot_insert_greeting_local('',$pageId,$user_id,$greeting_text,$lang_code,$lang_name);
        }else{$msg ="0"; }
        echo $msg;
        die();
    }

    else if($action=="delete_greeting"){
        if($pageId!=""){
            $greeting_id=filter_input(INPUT_POST,'greeting_id');
            $lang_code=filter_input(INPUT_POST,'lang_code');
            echo smartbot_delete_greeting_local($pageId,$user_id, $greeting_id,$lang_code);
        }
    }


    else if($action=="get_greeting_preview"){
        $pageId = $_SESSION["page_id"];
        $greetingText = smartbot_get_greeting($_SESSION['page_id']);
        $pageCover = smartbot_get_page_cover($pageId);
        echo
        '<div class="top_welcome_preview" id="welcome_preview_img">
        <div style="background-image: url('.$pageCover.');background-size: cover;background-position: center;width: 307px;height: 100px;margin-top: -20px;"></div>
        </div>
        <div id="welcome_preview_icon"><img class="img-circle" src="'. $_SESSION['page_image'].'" height="75px"></div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_title"><'. $_SESSION['page_name'].'></div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_cat"><'. $_SESSION['page_cat'].'</div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_likes">12345 people like this, including R2D2, C3PO and 17 friends</div>
        <div style="clear: both"></div>
        <div id="welcome_preview_status"><div class="welcome_preview_status"><img src="./images/cm-greeting-getstarted11.png"></div><div class="welcome_preview_status">Typically replies instantly</div></div>
        <div style="clear: both"></div>
        <div id="welcome_preview_greeting"><div class="welcome_preview_greeting"><img src="./images/cm-greeting-getstarted22.png"></div><div  class="welcome_preview_greeting" id="greeting_preview_txt">'. $greetingText .'</div></div>
        <div style="clear: both"></div>
        <div id="welcome_preview_get_started">When you tap Get Started, '. $_SESSION['page_name'].' will see your public information.
            <span class="btn btn-primary styling_welcome_mobilepreview_getstartedbtn">Get Started</span>
        </div>';


    }
    else if($action=="get_all_cards_data") {
        echo json_encode(getAllCardsLinksInArrayStructure($_SESSION['page_id']));
    }
    else if($action=="get_page_cover"){
        $page_token = $_SESSION['page_token'];
        echo smartbot_get_page_cover($pageId,$page_token);
    }

    else if($action=="get_default_greeting_status"){
        echo getDefaultGreetingStatus($pageId);
        die();
    }

    else if($action=="set_default_greeting_status"){
        $status = filter_input(INPUT_POST,'status');
        if($status=='enable'){
            $status = 1;
        }
        else{
            $status = 0;
        }
        echo setDefaultGreetingStatus($pageId,$status);
        die();
    }

    else if($action=="get_greeting_text"){
        echo smartbot_get_greeting($pageId);
        die();
    }

    else if($action=="get_sticky_greeting"){
        $bot_id = filter_input(INPUT_POST,'bot_id');
        //smartbot_sticky_menu($pageId,$bot_id);
        echo '|';
        smartbot_greeting_text($pageId,$bot_id);
        die();
    }

    else if($action=="get_user_input_on_off"){
        echo getAllowUserInputOnOff($pageId);
        die();
    }

    else if($action=="allow_user_input_on"){
        echo setAllowUserInputOn($pageId);
        die();
    }

    else if($action=="allow_user_input_off"){
        echo setAllowUserInputOff($pageId);
        die();
    }

    else if($action=="get_menu"){
        echo smartbot_sticky_menu_prefill($pageId,'');
    }

    else if($action=="num_menu_items"){
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $type=filter_input(INPUT_POST,'menu_type');
        echo smartbot_sticky_menu_prefill_num($pageId,$menu_id,$type);
    }

    else if($action=="new_menu_row"){
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $main_id=filter_input(INPUT_POST,'main_id');
        $menu_type=filter_input(INPUT_POST,'menu_type');
        echo smartbot_new_menu_row($pageId,$user_id,$menu_id,$main_id,$menu_type);


    }

    else if($action=="get_type_icon"){
        $item_type=filter_input(INPUT_POST,'item_type');
        echo smartbot_get_type_icon($item_type);
    }

    else if($action=="update_menu_title"){
        
        $item_id=filter_input(INPUT_POST,'item_id');
        $menu_title=filter_input(INPUT_POST,'menu_title');
        if($pageId!="" && $item_id!=""){
            echo smartbot_update_menu_title($pageId,$item_id,$menu_title);
        }
    }

    else if($action=="update_menu_item"){
        
        $main_id = filter_input(INPUT_POST,'main_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_id=filter_input(INPUT_POST,'item_id');
        $item_type=filter_input(INPUT_POST,'item_type');
        $menu_title=filter_input(INPUT_POST,'menu_title');
        $menu_type=filter_input(INPUT_POST,'menu_type');
        $menu_url=filter_input(INPUT_POST,'menu_url');
        $menu_msg=filter_input(INPUT_POST,'menu_msg');
        $item_order = filter_input(INPUT_POST,'item_order');
        smartbot_create_sticky_menu($pageId,$user_id,$item_id,$main_id,$menu_title,$menu_type,$menu_url,$menu_msg,$item_order,$item_type);
        die();
    }

    else if($action=="edit_menu"){
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $item_type=filter_input(INPUT_POST,'item_type');
        echo smartbot_edit_menu_item($pageId,$user_id,$menu_id,$item_type);
    }

    else if($action=="delete_menu"){
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        echo smartbot_delete_menu_item($pageId,$menu_id);
    }

    else if($action=="delete_sticky_menu_item"){
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_id=filter_input(INPUT_POST,'item_id');
        //smartbot_sticky_delete_item($bot_id,$pageId,$user_id,$item_id);
        die();
    }

    else if($action=="delete_sticky_submenu_item"){
        
        $menu_id = filter_input(INPUT_POST,'menu_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_id=filter_input(INPUT_POST,'item_id');
        //echo smartbot_sticky_delete_submenu_item($pageId,$user_id,$menu_id,$item_id);
        die();
    }

    else if($action=="create_sticky_submenu"){
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $main_menu_id=filter_input(INPUT_POST,'main_menu_id');
        $menu_title=filter_input(INPUT_POST,'menu_title');
        $item_ids=filter_input(INPUT_POST, 'item_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_title=filter_input(INPUT_POST,'submenu_title', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_type=filter_input(INPUT_POST,'submenu_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_url=filter_input(INPUT_POST,'submenu_url', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_msg=filter_input(INPUT_POST,'submenu_msg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $item_order = filter_input(INPUT_POST,'submenu_order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        smartbot_create_sticky_submenu($bot_id,$pageId,$user_id,$menu_id,$main_menu_id,$menu_title,$item_ids,$submenu_title,$submenu_type,$submenu_url,$submenu_msg,$item_order,'submenu');
        die();
    }

    else if($action=="submenu_item"){
        
        $menu_id = filter_input(INPUT_POST,'menu_id');
        //echo smartbot_sticky_submenu($pageId,$menu_id);
        die();
    }


    else if($action=="create_sticky_subsubmenu"){
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $main_menu_id=filter_input(INPUT_POST,'main_menu_id');
        $menu_title=filter_input(INPUT_POST,'menu_title');
        $item_ids=filter_input(INPUT_POST, 'item_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_title=filter_input(INPUT_POST,'submenu_title', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_type=filter_input(INPUT_POST,'submenu_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_url=filter_input(INPUT_POST,'submenu_url', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $submenu_msg=filter_input(INPUT_POST,'submenu_msg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $item_order = filter_input(INPUT_POST,'submenu_order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        smartbot_create_sticky_submenu($bot_id,$pageId,$user_id,$main_menu_id,$menu_id,$menu_title,$item_ids,$submenu_title,$submenu_type,$submenu_url,$submenu_msg,$item_order,'subsubmenu');
        die();
    }

    else if($action=="menu_item_msg"){
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id = $_SESSION['user_id'];
        $msg_id = filter_input(INPUT_POST, 'msg_id');
        $msg_type = filter_input(INPUT_POST, 'msg_type');
        $button_msg = filter_input(INPUT_POST, 'button_msg');
        if(isset($_SESSION["flow_id"])){$flow_id = $_SESSION["flow_id"];}else{$flow_id='';}
        echo smartbot_get_messages_callback($user_id,$bot_id,$pageId,$msg_id,$msg_type,$button_msg,$flow_id);
        die();
    }

    else if($action=="menu_item_msg_all"){

        $user_id=filter_input(INPUT_POST,'user_id');
        $msg_id = filter_input(INPUT_POST, 'msg_id');
        $msg_type = filter_input(INPUT_POST, 'msg_type');
        $button_msg = filter_input(INPUT_POST, 'button_msg');
        echo smartbot_get_messages_callback($user_id,$bot_id,$pageId,$msg_id,$msg_type,$button_msg,$flow_id);
        die();
    }

    else if($action=="save_menu"){
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        //$user_id=filter_input(INPUT_POST,'user_id');
        $user_id=$_SESSION["user_id"];
        echo smartbot_sticky_menu_creation($bot_id,$user_id,$pageId);
    }

    else if($action=="Get_flow_preview"){
        $user_id = $_SESSION["user_id"];
        $flow_id = filter_input(INPUT_POST,'flow_id');
        $json_msg =  smartbot_build_msgs_json_from_flow($user_id,$pageId,$flow_id);
        $json_msg =str_replace("\\r","",$json_msg);
        $json_msg =str_replace("\\n","<br/>",$json_msg);
        echo json_encode($json_msg);
    }

    else if($action=="Get_flow_preview_single"){
        $user_id = $_SESSION["user_id"];
        $msg_id = filter_input(INPUT_POST,'msg_id');
        $json_msg =  smartbot_get_single_message($user_id,$pageId,$msg_id);
        $json_msg =str_replace("\\r","",$json_msg);
        $json_msg =str_replace("\\n","<br/>",$json_msg);
        echo json_encode($json_msg);
    }

    else if($action=="delete_user_agency"){
        $userId=filter_input(INPUT_POST,'userId');
        $agencyId=filter_input(INPUT_POST,'agencyId');
        if(agencyAuthCheck($agencyId,$userId)){

            $msg["status"] = 1;
            $msg["msg"] = smartbot_user_delete_account($userId);
        } else {
            $msg["status"] = 0;
            $msg["msg"] = "You have no insufficient rights to delete this user.";
        }
        echo json_encode($msg);
        die();
    }

    else if($action=="Get_flow_cards_new"){
        $flowId = filter_input(INPUT_POST,'flow_id');
        $flowCards =  json_encode(getFlowCards($pageId,$flowId));
        echo $flowCards;
    }
    else if($action=="Get_flow_cards"){
        $user_id = $_SESSION["user_id"];
        $flow_id = filter_input(INPUT_POST,'flow_id');
        $json_msg =  smartbot_get_flow_msgs($user_id,$pageId,$flow_id);
        echo ($json_msg);
    }
    else if($action=="create_new_persistent_menu_locale"){
        $locale = filter_input(INPUT_POST,'locale');
        echo createNewPersistantMenuLocale($locale,$pageId);
        die();
    }
    else if($action=="delete_persistent_menu"){
        $id = filter_input(INPUT_POST,'id');
        echo deletePersistentMenu($id,$pageId);
        die();
    }
    else if($action=="delete_bulk_persistent_menus"){
        $ids = json_decode(filter_input(INPUT_POST,'ids'));
        echo deleteBulkPersistentMenu($ids,$pageId);
        die();
    }
    else if($action=="set_persistent_menu_on_off"){
        $status = filter_input(INPUT_POST,'status');
        $locale = filter_input(INPUT_POST,'locale');
        echo setPersistentMenuOnOff($status,$locale,$pageId);
        die();
    }
    else if($action=="set_persistent_menu_composer_input_disabled_on_off"){
        $status = filter_input(INPUT_POST,'status');
        $locale = filter_input(INPUT_POST,'locale');
        echo setPersistentMenuUserInputOnOff($status,$locale,$pageId);
        die();
    }
    else if($action=="set_persistent_menu_customer_chat_plugin_on_off"){
        $status = filter_input(INPUT_POST,'status');
        $locale = filter_input(INPUT_POST,'locale');
        echo setPersistentMenuCustomerChatOnOff($status,$locale,$pageId);
        die();
    }
    else if($action=="save_persistent_menu"){
        $locale = filter_input(INPUT_POST,'locale');
        $call_to_actions = filter_input(INPUT_POST,'call_to_actions');
        $flowsdata = filter_input(INPUT_POST,'flowsdata');
        echo savePersistentMenu($locale,$call_to_actions,$flowsdata,$pageId);
        die();
    }
    else if($action=="preload_persistent_menu"){
        $locale = filter_input(INPUT_POST,'locale');
        echo preloadPersistentMenu($locale,$pageId);
        die();
    }
    else if($action=="main_menu_on"){

        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $option_name = 'main_menu_on';
        $option_value = 'on';
        smartbot_save_keys($user_id,$pageId,$bot_id,$option_name,$option_value);
        smartbot_sticky_menu_creation($bot_id,$user_id,$pageId);
    }
    else if($action=="main_menu_off"){

        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu='delete';
        $option_name = 'main_menu_on';
        $option_value = 'off';
        smartbot_save_keys($user_id,$pageId,$bot_id,$option_name,$option_value);
        smartbot_insert_persistent_menu($bot_id,$pageId,$user_id,$menu);
    }
    else if($action=="preview_menu_page"){
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $menu_id=filter_input(INPUT_POST,'menu_id');
        $menu_type=filter_input(INPUT_POST,'menu_type');
        $menu_title=filter_input(INPUT_POST,'menu_title');
        echo smartbot_preview_menu_page($user_id,$pageId,$menu_id,$menu_type,$menu_title);
    }

    else if($action=="smartbot_buttons_modal"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $item_id = filter_input(INPUT_POST,'item_id');
        smartbot_buttons_modal($msg_id,$item_id );
        die();
    }

    else if($action=="show_edit_msg"){
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $msg_id = filter_input(INPUT_POST, 'msg_id');
        echo smartbot_show_edit_msg($msg_id,$pageId,$user_id);
    }

    else if($action=="new_livechat_optout"){
        $userId= $_SESSION["user_id"];
        livechatCheckOptOut($userId, $pageId);
    }

    else if($action=="livechat_optout"){
        $user_id= $_SESSION["user_id"];
        echo smartbot_opt_out($user_id,$pageId);
    }

    else if($action=="save_new_msg"){
        $vars['msg_id']= filter_input(INPUT_POST,'msg_id');
        $vars['msg_type']= filter_input(INPUT_POST,'msg_type');
        $vars['bot_id']= filter_input(INPUT_POST,'bot_id');
        $vars['user_id']=filter_input(INPUT_POST,'user_id');
        $vars['page_id']= filter_input(INPUT_POST,'page_id');
        $vars['left']= filter_input(INPUT_POST,'left');
        $vars['top']= filter_input(INPUT_POST,'top');
        echo smartbot_save_new_message($vars);
        die();
    }

    else if($action=="save_new_message"){
        //this is a save new msg from a button click. We need to see if it's more then one and return the uniqid of the first and the rest needs to be a msg block linking to each other
        echo smartbot_save_new_messages();
    }


    else if($action=="save_msg"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $vars['bot_id']= filter_input(INPUT_POST,'bot_id');
        $vars['user_id']=filter_input(INPUT_POST,'user_id');
        $vars['page_id']= filter_input(INPUT_POST,'page_id');
        $msg_left= filter_input(INPUT_POST,'msg_left');
        $msg_top= filter_input(INPUT_POST,'msg_top');
        $msg_type= filter_input(INPUT_POST,'msg_type');
        $msg_title=filter_input(INPUT_POST,'msg_title');
        $link_data=filter_input(INPUT_POST,'link_data');
        $vars[$msg_id.'_msg_name']=$msg_title;
        $vars[$msg_id.'_msg_text']=filter_input(INPUT_POST,'msg_text');
        $vars[$msg_id.'_trigger_keyword']=filter_input(INPUT_POST,'msg_keywords');
        $vars[$msg_id.'_trigger_neg_keyword']=filter_input(INPUT_POST,'msg_neg_keywords');

        if($msg_type=='simple_image'){$vars[$msg_id.'_image_url']=filter_input(INPUT_POST,'msg_url');}
        if($msg_type=='simple_audio'){$vars[$msg_id.'_audio_url']=filter_input(INPUT_POST,'msg_url');}
        if($msg_type=='simple_file'){$vars[$msg_id.'_file_url']=filter_input(INPUT_POST,'msg_url');}
        if($msg_type=='simple_video'){$vars[$msg_id.'_video_url']=filter_input(INPUT_POST,'msg_url');}

        echo smartbot_visual_messages('',$msg_id,$msg_type,$msg_title,$vars,$link_data,$msg_left,$msg_top);
        die();
    }

    else if($action=="quick_msg"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $item_ids = filter_input(INPUT_POST,'item_ids');
        $item_titles = filter_input(INPUT_POST,'item_titles');
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_data=filter_input(INPUT_POST,'item_data');
        $item_text=filter_input(INPUT_POST,'item_text');
        smartbot_show_quick_modal($user_id,$pageId,$bot_id,$item_ids,$item_titles,$item_data,$item_text,$msg_id);
        die();
    }

    else if($action=="list_msg"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $item_ids = filter_input(INPUT_POST,'item_ids');
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_data=filter_input(INPUT_POST,'item_data');
        $num_items=filter_input(INPUT_POST,'num_items');
        smartbot_show_list_modal($user_id,$pageId,$bot_id,$item_ids,$item_data,$msg_id,$num_items);
        die();
    }

    else if($action=="structured_msg"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $item_ids = filter_input(INPUT_POST,'item_ids');
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_data=filter_input(INPUT_POST,'item_data');
        smartbot_show_structured_modal($user_id,$pageId,$bot_id,$item_ids,$item_data,$msg_id,'structured');
        die();
    }

    else if($action=="product_msg"){
        $msg_id= filter_input(INPUT_POST,'msg_id');
        $item_ids = filter_input(INPUT_POST,'item_ids');
        $bot_id = filter_input(INPUT_POST,'bot_id');
        
        $user_id=filter_input(INPUT_POST,'user_id');
        $item_data=filter_input(INPUT_POST,'item_data');
        smartbot_show_structured_modal($user_id,$pageId,$bot_id,$item_ids,$item_data,$msg_id,'products');
        die();
    }

    else if($action=="shopify_product_msg"){
//$msg_id= filter_input(INPUT_POST,'msg_id');
//$item_ids = filter_input(INPUT_POST,'item_ids');
//$item_data=filter_input(INPUT_POST,'item_data');
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_show_structured_modal($user_id,$pageId,$bot_id,'','','','products');
        die();
    }

    else if($action=="amazon_product_msg"){
//$msg_id= filter_input(INPUT_POST,'msg_id');
//$item_ids = filter_input(INPUT_POST,'item_ids');
//$item_data=filter_input(INPUT_POST,'item_data');
        
        $bot_id = filter_input(INPUT_POST,'bot_id');
        $user_id=filter_input(INPUT_POST,'user_id');
        echo smartbot_show_structured_modal($user_id,$pageId,$bot_id,'','','','products');
        die();
    }

    else if($action=="smartbot_select_receivers"){
        
        $receivers = filter_input(INPUT_POST,'select_receivers');
        if($receivers=="manual"){
            echo smartbot_select_receivers($receivers,$pageId);
        }
        die();
    }

    else if($action=="smartbot_buttons_action"){
        
        $msg_id = filter_input(INPUT_POST, 'msg_id');
        echo smartbot_buttons_callback($pageId,$msg_id);
        die();
    }


    else if($action=="smartbot_messages_action"){

        $user_id = filter_input(INPUT_POST, 'user_id');
        $bot_id = filter_input(INPUT_POST, 'bot_id');
        $msg_id = filter_input(INPUT_POST, 'msg_id');
        $button_msg = filter_input(INPUT_POST, 'msg_type');
        $msg_type= filter_input(INPUT_POST, 'button_msg');
        $flow_id = $_SESSION["flow_id"];

        echo smartbot_get_messages_callback($user_id,$bot_id,$pageId,$msg_id,$msg_type,$button_msg,$flow_id);
        die();
    }

    else if($action=="get_uniq"){
        $msg_id=filter_input(INPUT_POST,'msg_id');
        echo smartbot_get_msg_uniqid($msg_id);
        die();
    }

    else if($action=="get_msg_id"){
        $uniq_id=filter_input(INPUT_POST,'uniq_id');
        echo smartbot_get_msg_id($uniq_id);
        die();
    }

    else if($action=="delete_msg"){
        $uniqid=filter_input(INPUT_POST,'uniqid');
        if($uniqid!=""){
            $msg_data = $wpdb->get_row($wpdb->prepare("SELECT id FROM smartbot_msgs WHERE msg_uniqid=%s",$uniqid),ARRAY_A);
            if(is_array($msg_data)){
                $msg_id=$msg_data['id'];
                smartbot_delete_msg_yes($msg_id);
            }
        }
        echo 'message deleted';
        die();
    }

    else if($action=="delete_link"){
        $uniq_id=filter_input(INPUT_POST,'uniq_id');
        $item_id=filter_input(INPUT_POST,'item_id');
        
        if($uniq_id!="" && $item_id!=""){
            $msg_id = smartbot_get_msg_id($uniq_id);
            echo smartbot_delete_link($pageId,$msg_id,$item_id);
        }
        die();
    }

    else if($action=="save_currentzoom"){
        $user_id = filter_input(INPUT_POST, 'user_id');
        
        $currentzoom= filter_input(INPUT_POST, 'currentzoom');
        smartbot_save_keys($user_id,$pageId,'','currentzoom',$currentzoom);
        die();
    }

    else if($action=="smartbot_connect_bot_page"){
        $bot_page = filter_input(INPUT_POST, 'bot_page');
        $bot_id = filter_input(INPUT_POST, 'bot_id');
        $thispage_id = filter_input(INPUT_POST, 'page_id');
        $user_id = $_SESSION['user_id'];
        $userIndexId = $_SESSION['user']['id'];
        $checkRes = smartbot_check_page($thispage_id, $user_id, $userIndexId);
        if($checkRes === false){
            return false;
        }
        echo connectBotToPage($thispage_id,$bot_id,$bot_page,$user_id,true,true);
        die();
    }

    else if($action=="smartbot_create_bot"){
        $bot_name = filter_input(INPUT_POST,'bot_name');
        $bot_type = filter_input(INPUT_POST,'bot_type');
        $user_id = $_SESSION['user']['fb_id'];
        echo createBot($user_id,$bot_name,$bot_type);
        die();
    }

    else if($action=="check_need_refresh"){
	    echo checkNeedRefresh($pageId);
	    die();
    }


    else if($action=="smartbot_check_amazon"){
        $amazon_public = filter_input(INPUT_POST,'amazon_public');
        $amazon_secret= filter_input(INPUT_POST,'amazon_secret');
        $amazon_id= filter_input(INPUT_POST,'amazon_id');
        $amazon_tld= filter_input(INPUT_POST,'amazon_tld');
        $user_id = filter_input(INPUT_POST,'user_id');
        $bot_id = filter_input(INPUT_POST, 'bot_id');
        
        echo smartbot_amazon_check_options($amazon_public,$amazon_secret,$amazon_id,$amazon_tld,$user_id,$bot_id,'');
        //echo 'at admin ajax to check amazon stuff';
        die();
    }

    else if($action=="smartbot_amazon_search_form"){
        $amazon_tld= filter_input(INPUT_POST,'amazon_tld');
        echo smartbot_amazon_search_form($amazon_tld);
        die();
    }

    else if($action=="smartbot_amazon_search"){
        $amazon_public = filter_input(INPUT_POST,'amazon_public');
        $amazon_secret= filter_input(INPUT_POST,'amazon_secret');
        $amazon_id= filter_input(INPUT_POST,'amazon_id');
        $amazon_tld= filter_input(INPUT_POST,'amazon_tld');
        $amazon_keyword= filter_input(INPUT_POST,'amazon_keyword');
        echo smartbot_amazon_search($amazon_public,$amazon_secret,$amazon_id,$amazon_tld,$amazon_keyword);
        die();
    }


    else if($action=="shopify_details"){
        
        $user_id = filter_input(INPUT_POST,'user_id');
        echo smartbot_show_shopify_details($pageId,$user_id);
        die();
    }

    else if($action=="shopify_clean_url"){
       $url= filter_input(INPUT_POST,'shopify_url');
       echo smartbot_shopify_clean_url($url);
       die();
    }


    else if($action=="smartbot_check_shopify"){
        $shopify_url=filter_input(INPUT_POST,'shopify_url');
        $shopify_api_key=filter_input(INPUT_POST,'shopify_api_key');
        $shopify_api_pass=filter_input(INPUT_POST,'shopify_api_pass');
        $bot_id = filter_input(INPUT_POST,'bot_id');

        $user_id = filter_input(INPUT_POST,'user_id');
        echo smartbot_shopify_check_connection($shopify_url,$shopify_api_key,$shopify_api_pass,$user_id,'',$bot_id);
        die();
    }

    else if($action=="smartbot_shopify_products"){
        $shopify_url=filter_input(INPUT_POST,'shopify_url');
        $shopify_api_key=filter_input(INPUT_POST,'shopify_api_key');
        $shopify_api_pass=filter_input(INPUT_POST,'shopify_api_pass');
        $products = smartbot_get_shopify_products($shopify_url,$shopify_api_key,$shopify_api_pass);
        shopify_show_products($products);
        die();
    }

    else if($action=="smartbot_shopify_all_products"){
        $shopify_url=filter_input(INPUT_POST,'shopify_url');
        $shopify_api_key=filter_input(INPUT_POST,'shopify_api_key');
        $shopify_api_pass=filter_input(INPUT_POST,'shopify_api_pass');
        $user_id = filter_input(INPUT_POST,'user_id');
        smartbot_shopify_fetch_all_products($user_id,$shopify_url,$shopify_api_key,$shopify_api_pass);
        die();
    }


    else if($action=="products_type"){
        $user_id = filter_input(INPUT_POST,'user_id');
        $bot_id = filter_input(INPUT_POST, 'bot_id');

        $products_type=filter_input(INPUT_POST,'products_type');
        //lets see what kind of prodct we need and next if we have the needed details already
        if($products_type=="amazon"){
            $amazon_public = smartbot_get_options($user_id,'','','amazon_public');
            $amazon_secret = smartbot_get_options($user_id,'','','amazon_secret');
            $amazon_id = smartbot_get_options($user_id,'','','amazon_id');
            $amazon_tld = 'com';
            if($amazon_public!="" && $amazon_secret!="" && $amazon_id!="" && $amazon_tld!="" ){
                echo '<div class="styling_flowcomposer_amazonsearch">';
                echo '<input type="hidden" id="amazon_public" value="'.$amazon_public.'">';
                echo '<input type="hidden" id="amazon_secret" value="'.$amazon_secret.'">';
                echo '<input type="hidden" id="amazon_id" value="'.$amazon_id.'">';
                smartbot_amazon_search_form($amazon_tld);
                echo '</div>';
            }
            else{
                echo 'Please connect your page to amazon first at <strong><a href="'.SB_HOME_URL.'integrations.php?page=integrations">Amazon Integration</a></strong>';
            }
        }

        if($products_type=="shopify"){
            $shopify_store=smartbot_get_options($user_id,'','','shopify_store');
            $api_key=smartbot_get_options($user_id,'','','shopify_api_key');
            $password=smartbot_get_options($user_id,'','','shopify_password');
            //if we have no details then we need to ask for it
            if($shopify_store==""){
                //asking for the Shopify details
                echo 'Please connect your page to a Shopify Store first at <strong><a href="'.SB_HOME_URL.'integrations.php?page=integrations">Shopify Integration</a></strong>';
            }else{
                ?>
                 <span class="btn btn-green">Connected to Shopify: <?php echo $shopify_store;?></span><br />
                <input type="hidden" id="ShopifyID" value="<?php echo $shopify_store;?>">
                <?php
                if($shopify_store!="" && $api_key!="" && $password!=""){
                    $products = smartbot_get_shopify_products($shopify_store,$api_key,$password);
                    shopify_show_products($products);
                }
            }
        }
        die();
    }


    else if($action=="product_message_type"){
        $products_type=filter_input(INPUT_POST,'products_type');
        $product_message_type=filter_input(INPUT_POST,'product_message_type');
        $user_id=filter_input(INPUT_POST,'user_id');
        $bot_id = filter_input(INPUT_POST, 'bot_id');
        if($products_type=="amazon"){

        }

        if($products_type=="shopify"){
            $shopify_store=smartbot_get_options($user_id,$pageId,$bot_id,'shopify_store');
            $api_key=smartbot_get_options($user_id,$pageId,$bot_id,'shopify_api_key');
            $password=smartbot_get_options($user_id,$pageId,$bot_id,'shopify_password');

            if($shopify_store!="" && $api_key!="" && $password!=""){
                //do we need the products or the categories...lets get it here and display

                if($product_message_type=="category"){
                    $cats = smartbot_get_shopify_cats($shopify_store,$api_key,$password);
                    print_r($cats );
                }else{
                    //it's products from Shopify
                    $products = smartbot_get_shopify_products($shopify_store,$api_key,$password);
                    shopify_show_products($products);
                }
            }
        }//end if is Shopify
        die();
    }

    //msg actions
    else if($action=="get_msg_action_value"){
        $msg_id=filter_input(INPUT_POST,'msg_id');
        $action_type=filter_input(INPUT_POST,'action_type');
        echo smartbot_msg_action_get_value($pageId,$msg_id,$action_type);
    }
}else{
    die();//we do not want to do one single thing if we don't have at least the session user active
}
}else{
    echo 'ref wrong'.$ref.' home url'. SB_HOME_URL;
}//end if ref server is ours
die(); //just to be sure nothing got left behind
//end if security check is cleared coz else we do not want to do 1 single thing
