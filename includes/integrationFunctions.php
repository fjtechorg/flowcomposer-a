<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17/12/18
 * Time: 02:21 ص
 */

//constant contact usecase
use Ctct\Auth\CtctOAuth2;
use Ctct\Exceptions\OAuth2Exception;


function getIntegrationName($pageId, $id)
{
    global $wpdb;

    $name = $wpdb->get_var($wpdb->prepare("SELECT name FROM integrations WHERE  page_id=%s AND id=%s", $pageId, $id));

    return $name;
}


function getIntegrationsByServiceProviderName($pageId,$name){
     $list = new stdClass();
    $lists = array();
    $list->id = 10;
    $list->name = "Account 1";
    $lists[] = $list;
    $list = new stdClass();

    $list->id = 11;
    $list->name = "Account 2";
    $lists[] = $list;
    return ($lists);
}


/*
 * gets the list of service provider categories/types
 * returns object with type name and ids
 */
function getProviderTypes(){
    global $wpdb;
    $res = $wpdb->get_results('SELECT id,name FROM integration_type');
    return $res;
}

/*
 * get service provider
 */
function getServiceProvider($id){
    global $wpdb;
    $query = $wpdb->prepare('SELECT name,logo,type_id,details FROM service_provider WHERE id=%d',$id);
    $res = $wpdb->get_row($query);
    return $res;
}

/*
 * create service provider
 */
function createServiceProvider($name,$img,$catId,$json){
    global $wpdb;
    $res = $wpdb->insert('service_provider',['name'=>$name,'type_id'=>$catId,'logo'=>$img,'details'=>$json]);
    return $res;
}

/*
 * update service provider
 */
function updateServiceProvider($id,$name,$img,$catId,$json){
    global $wpdb;

    $query = $wpdb->prepare('SELECT details FROM service_provider WHERE id=%d',$id);
    $details = $wpdb->get_var($query);
    $obj = json_decode($details);
    $count = count($obj->fields);
    if(!empty($count)) {
        $indexes = $count - 1;
        $objNew = json_decode($json);
        //check if any of new key names exists in old names
        foreach ($objNew->fields as $field) {
            foreach ($obj->fields as $oldField) {
                if ($oldField === $field) {
                    return 'Duplicate field name, it is already used.';
                }
            }
        }
        //Compare old key names and update with new key names
        for ($i = 0; $i <= $indexes; $i++) {
            if ($obj->fields[$i]->name != $objNew->fields[$i]->name) {
                //update old key names with new name
                renameIntegrationKeys($id, $objNew->fields[$i]->name, $obj->fields[$i]->name);
            }
        }
    }
    //update with new data
    $res = $wpdb->update('service_provider',['name'=>$name,'type_id'=>$catId,'logo'=>$img,'details'=>$json],['id'=>$id]);
    return $res;
}
/*
 * Rename Integration Key Name
 * $intId is the id from the service_provider table
 * $newName is the new name for the Key
 * $oldName is the old name of the key which will be used to search and replace
 */

function renameIntegrationKeys($intId, $newName, $oldName){
    global $wpdb;
    $query = $wpdb->prepare('UPDATE integration_keys ik INNER JOIN integrations i ON ik.integration_id=i.id SET ik.option_key=%s WHERE ik.option_key=%s AND i.service_provider_id=%d',$newName,$oldName,$intId);
    $wpdb->query($query);
}

/*
 * delete service provider
 */
function deleteServiceProvider($id){
    global $wpdb;
    $wpdb->delete('service_provider',['id'=>$id]);
}


/*
 * retrieves the service provider list with its groups
 */
function getIntegrationsListWithGroups(){
    global $wpdb;
    $query = 'SELECT service_provider.*,integration_type.name AS type_name FROM service_provider INNER JOIN integration_type ON service_provider.type_id=integration_type.id';
    $results = $wpdb->get_results($query,ARRAY_A);
    return $results;
}

/*
 * delete the integration for the passed id and page index id
 */
function deletePageIntegration($id,$pageIndex){
    global $wpdb;
    $res = $wpdb->delete('integrations',array('id'=>$id,'page_index'=>$pageIndex));
    return $res;
}


/**
 * Bulk delete integrations , mandatory parameters -  page index id and ids, $ids should be a one dimension array which has the list of integration ids to be deleted.
 */
function bulkDeleteIntegrations($pageIndex, $ids)
{
    global $wpdb;
    $string= implode(",",$ids);
    $query = $wpdb->prepare("DELETE FROM integrations WHERE id IN (".$string.") AND page_index=%d", $pageIndex);
    $res = $wpdb->query($query);
    return $res;
}

/*
 * Changes the status of the user integration, requires id from integrations table, new status value (0 or 1), and page index
 * 0 - disable
 * 1 - enable
 */
function changeIntegrationStatus($id,$newStatus,$pageIndex){
    if($newStatus == 0||$newStatus == 1){
        global $wpdb;
        $res = $wpdb->update('integrations',array('status'=>$newStatus),array('id'=>$id,'page_index'=>$pageIndex));
        return $res;
    }
    return false;
}

/*
 * creates the json structure with form fields to retrieve data from user for integration key and values
 */

function getServiceFieldsForm($id){
    global $wpdb;
    $query = $wpdb->prepare('SELECT name,logo,details FROM service_provider WHERE id=%s',$id);
    $details = $wpdb->get_row($query);
    //create button for POST type integrations
    $type = json_decode($details->details);
    if($type->type=='post'){
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $callbackUrl = $protocol.$_SERVER['HTTP_HOST'].'/integrations_manager.php?int_id='.$id;

        switch($details->name){
            //aweber
            case 'Aweber':
                $details->button = generateAweberButton($callbackUrl);
                break;
            //constant contact
            case 'Constant Contact':
                $details->button = generateConstantContactButton($callbackUrl);
                break;
            //Goto Webinar
            case 'GotoWebinar':
                $details->button = generateGotoWebinarButton($callbackUrl);
                break;
            //Campaign Monitor
            case 'Campaign Monitor':
                $details->button = generateCampaignMonitorButton($callbackUrl);
                break;
        }
    }
    return json_encode($details);
}
/*
 * process and return integration keys
 * $getParams is a json encoded object with all the get parameters from the redirected page
 * $getParams->int_id is the service provider id from service_provider table
 */

function returnApiKeytoForm($getParams){
    $getParams = json_decode($getParams);
    $getParamsArray = [];
    foreach($getParams as $key=>$value){
        $getParamsArray[$key] = $value;
    }
    $id = $getParamsArray['int_id'];
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $callbackUrl = $protocol.$_SERVER['HTTP_HOST'].'/integrations_manager.php?int_id='.$id;

    global $wpdb;
    $query = $wpdb->prepare('SELECT name FROM service_provider WHERE id=%s',$id);
    $name = $wpdb->get_var($query);

    switch($name){
        //aweber
        case 'Aweber':
            $fields = getAweberKeys($getParamsArray);
            return json_encode($fields);
            break;
        //constant contact
        case 'Constant Contact':
            $fields = getConstantContactKeys($getParamsArray,$callbackUrl);
            return json_encode($fields);
            break;
        //Goto Webinar
        case 'GotoWebinar':
            $fields = getGotoWebinarKeys($getParamsArray);
            return json_encode($fields);
            break;
        //Campaign Monitor
        case 'Campaign Monitor':
            $fields = getCampaignMonitorKeys($getParamsArray,$callbackUrl);
            return json_encode($fields);
            break;
    }
}

/*
 * gets the integration keys for the particular service provider from form data
 * $id is the id of the service provider from service_provider table
 * $formData is json encoded form data from the form in integrations_manager.php page, eg: {[{name: "activecampaign-url", value: "shan"}]}
 */

function captureIntegrationFormData($name,$pageIndex,$pageId,$providerId,$formData){
    global $wpdb;
    $query = $wpdb->prepare('SELECT name,details FROM service_provider WHERE id=%d',$providerId);
    $obj = $wpdb->get_row($query);
    $serviceName = $obj->name;
    $obj = json_decode($obj->details);
    $keys = json_decode($formData);
    //verify keys before storing the keys in if condition
    $res = json_decode(verifyIntegrationTokens($keys,$obj));

    //todo remove TRUE
    if((isset($res->message) && $res->message == "VERIFIED") || true){
        //keys are valid
        $integrationId = insertIntegration($name,$providerId,$pageIndex,$pageId);
        if($integrationId == false){
            return 'Name already exists.';
        }
        saveIntegrationKeys($integrationId,$keys);
        return 'saved';
    }
    else if(isset($res->error->message)){
        //keys are not valid
        //$errorMsg = 'Keys not valid.';
        return $res->error->message;
    }
}

/*
 * Insert into Integrations table and returns the new inserted row's id
 * $name is the name provided by user for the integration
 * $providerId is the id from service_provider table
 * $pageIndex is the page index id
 */
function insertIntegration($name,$providerId,$pageIndex,$pageId){
    global $wpdb;
    $query = $wpdb->prepare('SELECT id FROM integrations WHERE name = %s AND page_index = %s',$name,$pageIndex);
    $check = $wpdb->get_var($query);
    if(empty($check)) {
        $time = time();
        $wpdb->insert('integrations', array('name' => $name, 'date_added' => $time, 'service_provider_id' => $providerId, 'page_index' => $pageIndex, 'page_id' => $pageId));
        $id = $wpdb->insert_id;
        return $id;
    }
    else{
        return false;
    }
}
/*
 * saves the integration keys in integration_keys table
 * $keys are array of objects eg: [{name: "activecampaign-url", value: "shan"}]
 */
function saveIntegrationKeys($id,$keys){
    global $wpdb;
    $values = '';
    foreach($keys as $key){
        $values = $values.'('.$id.',"'.$key->name.'","'.$key->value.'"),';
    }
    $values = rtrim($values,",");
    $query = 'INSERT INTO integration_keys (integration_id,option_key,option_value) VALUES '.$values;
    $wpdb->query($query);
}

/*
 * functions for integration service providers
 */

/*
 * generates a button to request tokens from aweber
 * parameter $callbackUrl is the postbackurl with parameter int_id in the url to identify the service provider
*/
function generateAweberButton($callbackUrl){
    $consumerKey    = "AkUl7RsnEdUm1ath1F36NQCB";
    $consumerSecret = "PcVSstRKLy1RVizPh6NQkryXmZ5RA6ShIfJ1YdNx";
    if (!class_exists('AWeberAPI')) {
        require_once(__DIR__."/ar/aweber/aweber_api.php");
    }

    $aweber = new AWeberAPI($consumerKey, $consumerSecret);

    list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
    $_SESSION["requestTokenSecret"] = $requestTokenSecret;
    $_SESSION["callbackUrl"] = $callbackUrl;
    $button =  ' <input class="btn btn-primary" onclick="location=\''. $aweber->getAuthorizeUrl().'\'" type="button" value="Authorize your account">';
    return $button;
}

/*
 * gets the fields from aweber and returns array of fields
 * parameter $getParamsArray is an array of $_GET parameters from the postback URL
 */
function getAweberKeys($getParamsArray){
    global $user_id;
    $consumerKey    = "AkUl7RsnEdUm1ath1F36NQCB";
    $consumerSecret = "PcVSstRKLy1RVizPh6NQkryXmZ5RA6ShIfJ1YdNx";
    if (!class_exists('AWeberAPI')) {
        require_once(__DIR__."/ar/aweber/aweber_api.php");
    }
    $aweber = new AWeberAPI($consumerKey, $consumerSecret);
    $aweber->user->tokenSecret = $_SESSION['requestTokenSecret'];
    $aweber->user->requestToken = $getParamsArray['oauth_token'];
    $aweber->user->verifier = $getParamsArray['oauth_verifier'];
    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
    $fields = [];
    $fields['aweber-token'] = $accessToken;
    $fields['aweber-tokenSecret'] = $accessTokenSecret;
    return $fields;
}

/*
 * generates a button to request tokens from ConstantContact
 * parameter $callbackUrl is the postbackurl with parameter int_id in the url to identify the service provider
*/
function generateConstantContactButton($callbackUrl){
    if (!class_exists('CtctOAuth2')) {
        require_once(__DIR__."/ar/constantcontact_official/src/Ctct/autoload.php");
        require_once(__DIR__."/ar/constantcontact_official/vendor/autoload.php");
    }
    define("APIKEY", "xgcnar4wvcutepw9zghjrdxv");
    define("CONSUMER_SECRET", "qkvYxaPECRu55qwpGnPJBHvQ");
    $oauth = new CtctOAuth2("2b7b6601-e84c-40e6-938b-7ec86243562f", "C-QenEPBBZuqkHshk150Hw", $callbackUrl);
    $button =  ' <input class="btn btn-primary" onclick="location=\''. $oauth->getAuthorizationUrl().'\'" type="button" value="Authorize your account">';
    return $button;
}

/*
 * gets the fields from ConstantContact and returns array of fields
 * parameter $getParamsArray is an array of $_GET parameters from the postback URL
 */
function getConstantContactKeys($getParamsArray,$callbackUrl){
    global $user_id;
    if (!class_exists('CtctOAuth2')) {
        require_once(__DIR__."/ar/constantcontact_official/src/Ctct/autoload.php");
        require_once(__DIR__."/ar/constantcontact_official/vendor/autoload.php");
    }
    define("APIKEY", "xgcnar4wvcutepw9zghjrdxv");
    define("CONSUMER_SECRET", "qkvYxaPECRu55qwpGnPJBHvQ");
    $oauth = new CtctOAuth2(APIKEY, CONSUMER_SECRET, $callbackUrl);
    if (!empty($getParamsArray['code'])) {
        try {
            $accessToken = $oauth->getAccessToken($getParamsArray['code']);
            $fields = [];
            $fields['constantcontact-accesstoken'] = $accessToken['access_token'];
            return $fields;
        }
        catch (OAuth2Exception $ex) {
            die("Authorization Error");
        }
    }
}

/*
 * generates a button to request tokens from GotoWebinar
 * parameter $callbackUrl is the postbackurl with parameter int_id in the url to identify the service provider
*/
function generateGotoWebinarButton($callbackUrl){
    $consumerKey    = "J1AmaWFGVnOKW4BROHfy2GB064mxXjo2";
    $consumerSecret = "hHCjHopSjPGe7S8V";
    if (!class_exists('Citrix')) {
        require_once(__DIR__."/webinars/gotowebinar/citrix.php");
    }

    $goto = new Citrix($consumerKey, $consumerSecret);
    $button =  ' <input class="btn btn-primary" onclick="location=\''. $goto->getAuthorizationURL().'\'" type="button" value="Authorize your account">';
    return $button;
}

/*
 * gets the fields from GotoWebinar and returns array of fields
 * parameter $getParamsArray is an array of $_GET parameters from the postback URL
 */
function getGotoWebinarKeys($getParamsArray){
    $consumerKey    = "J1AmaWFGVnOKW4BROHfy2GB064mxXjo2";
    $consumerSecret = "hHCjHopSjPGe7S8V";
    if (!class_exists('Citrix')) {
        require_once(__DIR__."/webinars/gotowebinar/citrix.php");
    }

    $goto = new Citrix($consumerKey, $consumerSecret);
    if (!empty($getParamsArray['code'])) {

        $responsecode = $getParamsArray['code'];
        $data  = $goto->getKeys($responsecode);
        $fields = [];
        $fields['gotowebinar-accessToken'] = $data->access_token;
        $fields['gotowebinar-refreshToken'] = $data->refresh_token;
        $fields['gotowebinar-organizerKey'] = $data->organizer_key;
        $fields['gotowebinar-accountKey'] = $data->account_key;
        return $fields;
    }
}

/*
 * generates a button to request tokens from Campaign Monitor
 * parameter $callbackUrl is the postbackurl with parameter int_id in the url to identify the service provider
*/
function generateCampaignMonitorButton($callbackUrl){
    $clientId    = '118013';

    $scope = 'ViewReports,ManageLists,CreateCampaigns,ImportSubscribers,SendCampaigns,ViewSubscribersInReports,ManageTemplates,AdministerPersons,AdministerAccount,ViewTransactional,SendTransactional';
    $authUrl = 'https://api.createsend.com/oauth?type=web_server&client_id='.$clientId.'&redirect_uri='.$callbackUrl.'&scope='.$scope;
    $button =  ' <input class="btn btn-primary" onclick="location=\''. $authUrl.'\'" type="button" value="Authorize your account">';
    return $button;
}

/*
 * gets the fields from Campaign Monitor and returns array of fields
 * parameter $getParamsArray is an array of $_GET parameters from the postback URL
 */
function getCampaignMonitorKeys($getParamsArray,$callbackUrl){
    $clientId    = '118013';
    $clientSecret = 'M566OnL3HhH07FmrUg37Md63KsL89261Bd8uP67SgymFqY3Vwmd0kZQo6158n6Vk4379P7a5808R6sUM';
    if (!empty($getParamsArray['code'])) {
        $code = $getParamsArray['code'];
        //$state = $getParamsArray['state'];

        // set post fields
        $post = [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $callbackUrl
        ];

        $ch = curl_init('https://api.createsend.com/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // execute!
        $response = curl_exec($ch);

        $response = json_decode($response);

        // close the connection, release resources used
        curl_close($ch);

        $fields = [];
        $fields['access_token'] = $response->access_token;
        $fields['expires_in'] = $response->expires_in;
        $fields['refresh_token'] = $response->refresh_token;
        return $fields;
    }
}


/*
 * Verify if the passed keys/tokens are valid or not
 * $keys is an associative array where key is the name of the field and value holds value
 * $obj is the json decoded value of 'details' column in 'service_provider' for the specific service provider
 */
function verifyIntegrationTokens($keys,$obj){
    $request = new stdClass();
    /*
    $request->url = 'https://cm-service-api.herokuapp.com/ping/active-campaign';
    $request->method = "post";
    $request->body_type = "raw";
    $request->raw_data = '{
            "credentials": {
                "api_key": "44e9e8ef2d1c5d73987ff8ca7f99c684fce851c7686a9f8cc79131488172f847b5519fa0",
                "api_url": "https://paplow01.api-us1.com"
            }
        }';
    */

    $raw = json_encode($obj->raw);
    foreach($keys as $key){
        $raw = str_replace("%".$key->name."%",$key->value,$raw);
    }
    $request->url = 'https://cm-service-api.herokuapp.com'.$obj->verify_url;
    $request->method = "post";
    $request->body_type = "raw";
    $request->raw_data = $raw;

    $content = new stdClass();
    $content->_key = 'Content-Type';
    $content->_value = 'application/json';

    $auth = new stdClass();
    $auth->_key = 'Authorization';
    $auth->_value = 'secret-key CleverMessenger';

    $request->headers = [$content,$auth];

    $resBody = sendWebhookRequestModified($request);
    return $resBody;
}


function sendIntegrationRequest($requestType,$endpointType,$endpoint,$body,$postType="raw",$auth=false){
    $request = new stdClass();
    if ($endpointType === "relative")
    $request->url = 'https://integrations.clevermessenger.com/'.$endpoint;
    else
        $request->url = $endpoint;

    $request->method = $requestType;
    $request->body_type = $postType;
    if ($postType === "raw")
    $request->raw_data = $body;
    else
        $request->form_data = $body;


    $content = new stdClass();
    $content->_key = 'Content-Type';
    $content->_value = 'application/json';

    if (!$auth) {
        $auth = new stdClass();
        $auth->_key = 'Authorization';
        $auth->_value = 'secret-key CleverMessenger';
    }
    $request->headers = [$content,$auth];

    $resBody = sendWebhookRequestModified($request);
    return $resBody;
}

function getIntegrationKeys($pageId,$integrationId){
    global $wpdb;

    $res = $wpdb->get_results($wpdb->prepare('SELECT option_key,option_value FROM integration_keys WHERE integration_id = (SELECT id from integrations WHERE id = %d AND page_id = %s)',$integrationId,$pageId));
    return $res;
}

function createIntegrationAuthenticationObject($pageId,$account){
    $authenticationKeys = getIntegrationKeys($pageId,$account);
    $credentialsObject = new stdClass();
    foreach ($authenticationKeys as $authenticationKey){
        $credentialsObject->{$authenticationKey->option_key} = $authenticationKey->option_value;
    }

    return $credentialsObject;

}


function subscribeContact(){

}

function createContactInList($profileId,$pageId,$account,$contact, $listId,$service){
    $credentialsObject = createIntegrationAuthenticationObject($pageId,$account);
    $body = new stdClass();
    $body->credentials = $credentialsObject;
    $body->query = json_decode('{"limit":500}');
    $body->api_key = $credentialsObject->{"api-key"};
    $body = (object) array_merge(
        (array) $body, (array) $contact);

    switch ($service){
        case "contact-reach":
            $body->campaign_id = $listId;
            unset($body->credentials);
            unset($body->query);
            $body = (array) $body;

            $query = (http_build_query($body));
            $data =  json_decode(sendIntegrationRequest("post", "direct","https://contactreach.co/app/api/subscribers?".$query,$body,"form-data"));
            if (isset($data->success) && $data->success== true )
                return $data;
            else return 0;
            break;

        case "wowing":
            $body->automation_id = $listId;
            $body->external_id = $profileId;
            $body->origin = "Clever Messenger";
            unset($body->credentials);
            unset($body->query);
            //$body = (array) $body;
            $auth = new stdClass();
            $auth->_key = 'authorization';
            $auth->_value = 'Bearer '.$credentialsObject->{"api-key"};
            $body = json_encode($body);
            //$query = (http_build_query($body));
            $data =  json_decode(sendIntegrationRequest("post", "direct","https://app.wowing.io/api/contacts",$body,"raw",$auth));
            if (isset($data->success) && $data->success== true )
                return $data;
            else return 0;
            break;

        case "default";
            (sendIntegrationRequest("post", "relative","lists/retrieve/$service",$body));
            break;
    }

}
function saveWowingWebhook($pageId,$account, $automation,$webhook){
    $credentialsObject = createIntegrationAuthenticationObject($pageId,$account);
    $body = new stdClass();
    $body->credentials = $credentialsObject;
    $body->query = json_decode('{"limit":500}');
    $body->api_key = $credentialsObject->{"api-key"};

     $body->outbound_webhook_url = $webhook;
            unset($body->credentials);
            unset($body->query);
            //$body = (array) $body;
            $auth = new stdClass();
            $auth->_key = 'authorization';
            $auth->_value = 'Bearer '.$credentialsObject->{"api-key"};
            $body = json_encode($body);
            //$query = (http_build_query($body));
            $data =  (sendIntegrationRequest("put", "direct","https://app.wowing.io/api/automations/$automation",$body,"raw",$auth));
            if (isset($data) )
                return $data;
            else return 0;



}

function removeContactFromList($pageId,$account,$contact, $listId,$service){
    $credentialsObject = createIntegrationAuthenticationObject($pageId,$account);
    $body = new stdClass();
    $body->credentials = $credentialsObject;
    $body->query = json_decode('{"limit":500}');
    $body->api_key = $credentialsObject->{"api-key"};
    $body = (object) array_merge(
        (array) $body, (array) $contact);

    switch ($service){
        case "contact-reach":
            $body->campaign_id = $listId;
            unset($body->credentials);
            unset($body->query);
            $body = (array) $body;

            $query = (http_build_query($body));
            $data =  json_decode(sendIntegrationRequest("delete", "direct","https://contactreach.co/app/api/subscribers?".$query,$body,"form-data"));
            if (isset($data->success) && $data->success== true )
                return $data;
            else return 0;
            break;

        case "wowing":
            $body->automation_id = $listId;
            unset($body->credentials);
            unset($body->query);
            //$body = (array) $body;
            $auth = new stdClass();
            $auth->_key = 'authorization';
            $auth->_value = 'Bearer '.$credentialsObject->{"api-key"};
            $body = json_encode($body);
            //$query = (http_build_query($body));
            $data =  json_decode(sendIntegrationRequest("post", "direct","https://app.wowing.io/api/contacts",$body,"raw",$auth));
            if (isset($data->success) && $data->success== true )
                return $data;
            else return 0;
            break;
        case "default";
            (sendIntegrationRequest("post", "relative","lists/retrieve/$service",$body));
            break;
    }

}

function getIntegrationLists($pageId,$account,$service){

                $list = new stdClass();
                $lists = array();
                $list->id = 123;
                $list->name = "list 1";
                $lists[] = $list;
                $list = new stdClass();

                $list->id = 124;
                $list->name = "list 2";
                $lists[] = $list;
                return $lists;


}

