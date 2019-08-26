<?php
require_once('config.php');
require_once('function.php');
require_once('wp-db.php');
require_once('facebook/autoload.php');
include_once('membership_functions.php');
/**
 * All user related functions like login, get details from facebook, add new etc are here
 * todo changing user->profile in the function names for the functions that deal not with our users but with the people the pages interact with
 */


/**
 * Login Funtion
 * expects the variables $username & $password and checks for valid
 * on false returns an error message $msg
 *  on true creates session $_SESSION['username']=$username; $_SESSION['user']=$user; (holds all userdata) and $_SESSION['user_id']=$user['fb_id']; and after that redirects to index.php
 */


Function subscriberLimitCheck($UserId)
{
    //Grab current limits for user by membership level
    $subscriberLimit = getSubscriberLimit($UserId);
    //Grab number of suscribers
    $subscribers = getSubscriberCount($UserId);
    //Calculate subscriber usage percentage
    $subscribersUsagePercentage = $subscribers / $subscriberLimit * 100;
    //Set array with
    if ($subscribersUsagePercentage >= 90) {
        $r = array("usage" => $subscribersUsagePercentage, "warning" => true );
    } else {
        $r = array("usage" => $subscribersUsagePercentage, "warning" => false );
    }
    if ($subscribersUsagePercentage >= 100){
        disableUser($UserId);

    }
    return $r;
}

function saveTransactionIpn ($transId, $amount, $taxAmount, $taxPercentage, $quantity, $customerEmail, $productId, $buyerId, $orderSymbol, $status, $currentTimestamp, $invoiceId=null){
    global $wpdb;
    $user = getUserOfPkemail($customerEmail);
    $userId = $user->id;
    if(empty($invoiceId)){
        $wpdb->insert('user_purchases', ['trans_id' => $transId, 'amount' => $amount, 'amount_without_tax' => $taxAmount, 'tax_percentage' => $taxPercentage, 'quantity' => $quantity, 'email' => $customerEmail, 'symbol' => $orderSymbol, 'prod_id' => $productId, 'buyer_id' => $buyerId, 'status' => $status, 'dateadded' => $currentTimestamp, 'user_id'=>$userId]);
    }
    else{
        $wpdb->insert('user_purchases', ['trans_id' => $transId, 'amount' => $amount, 'amount_without_tax' => $taxAmount, 'tax_percentage' => $taxPercentage, 'quantity' => $quantity, 'email' => $customerEmail, 'symbol' => $orderSymbol, 'prod_id' => $productId, 'buyer_id' => $buyerId, 'status' => $status, 'dateadded' => $currentTimestamp, 'user_id'=>$userId,'invoice_id'=>$invoiceId]);
    }
    $insertId = $wpdb->insert_id;
    return $insertId;
}

function saveFraudTransactionIpn ($transId,$agencyId,$json){
    global $wpdb;
    $wpdb->insert('fraud_purchases',['transaction_id'=>$transId, 'agency_id'=>$agencyId, 'json_data'=>$json]);
}

function getSubscriberCount($UserId)
{

    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM user_counts WHERE user_id=%s ",$UserId),ARRAY_A);
    if(!empty($user)){
        $R = $user["subscriber_count"];
    } else {
        $R = 0;
    }
    return $R;
}


function getSubscriberLimit($UserId)
{

    $membershiplimits = getMembershipLimits(getMemberships($UserId));
    //var_dump($membershiplimits);
    return $membershiplimits;
}


function printSubscriberLimitWarning($limitCheckArray)
{
    if($limitCheckArray["warning"] == true ){
        //check if url contains any parameters
        if(count($_GET)>0){
            $sym = '&';
        }
        else{
            $sym = '?';
        }
        //print warning
        if($limitCheckArray["usage"] >= 90 and $limitCheckArray["usage"] < 95){
            $warning = "<div class=\"warning1\">Notice: You're about to exceed the amount of subscribers you can hold. Make sure you upgrade your account in time, so you can keep generating subscribers.</div>";
        }
        elseif($limitCheckArray["usage"] >= 95 and $limitCheckArray["usage"] < 100){
            $warning = "<div class=\"warning2\">Notice: You're about to exceed the amount of subscribers you can hold. Make sure you upgrade your account in time, so you can keep generating subscribers.</div>";
        }
        elseif($limitCheckArray["usage"] >= 100){
            $warning = "<div class=\"warning3\">Warning: You exceeded the amount of subscribers you can hold. Make sure you upgrade your account now in order to keep adding new subscribers.</div>";

        } else {
            $warning ='';
        }

    } else {
        $warning = '';
    }
    return $warning;
}



function smartbot_login_old()
{
    global $wpdb;
    $loginSession='';
    $msg='';
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');


    if (isset($_SESSION['login_session'])) {
        $loginSession = $_SESSION['login_session'];
    }
    if (!isset($username) || !isset($password)) {
        $msg = "empty";
    }
    if (empty($username) || empty($password)) {
        $msg = "user_pass_notfilled";
    }else{
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE smartbot_users.user_name=%s AND smartbot_users.user_pass=%s",$username,md5($password)),ARRAY_A);
        $thisId=$user['id'];
	    $userName = $user['first_name'] .' '.$user['last_name'];
        $agencySubUser = agencyGetSubUser($thisId);

        if (isset($agencySubUser)){
            if($agencySubUser["disabled"] == 1){
                $msg="subuser_disabled";
                $thisId = 0;
            }
        }
	    if($thisId>0){
            //we have a user but is this person not already logged in?
            $login = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_logins WHERE user_id=%s",$thisId),ARRAY_A);
            $thisIp=$_SERVER['REMOTE_ADDR'];
            $forwardedIp = '';

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $forwardedIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if(is_array($login)&& isset($login['id']) && $login['id']>0){
                //we have this person n the login table. Lets see if we need to deny or if we need to go on
                $thisLoginSession = $login['login_session'];


                if ($thisLoginSession == $loginSession) {
                    smartbot_login_ok($username, $user, $loginSession);
                }//login session is ok

                //we are still here...possible duplicate login...but first...how old is the session in our db?
                //if it's older then 10 minutes I am going to destroy the old login and allow this new login.
                $toTime = strtotime($login['login_date']);
                $now=date("Y-m-d H:i:s");
                $fromTime = strtotime($now);
                $diff =  round(abs($toTime - $fromTime) / 60,2);
                if($diff>10){
                    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_logins WHERE user_id=%s", $thisId));
                    $loginSession = smartbot_create_login_session($thisId,$thisIp,$forwardedIp,$userName);
                    smartbot_login_ok($username,$user,$loginSession);
                }else{
                    $msg="user_logged_in";
                }
            }else{
                //all ok let's move on

                $loginSession = smartbot_create_login_session($thisId,$thisIp,$forwardedIp,$userName);
                smartbot_login_ok($username,$user,$loginSession);
            }
        }elseif(!isset($msg)){

            $msg = "user_pass_notok";
            exit;
        }
    }
    return $msg;
}

function smartbot_login_old_2019()
{
    global $wpdb;
    $msg = '';
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');


    if (!isset($username) || !isset($password)) {
        $msg = "empty";
    }
    elseif (empty($username) || empty($password)) {
        $msg = "user_pass_notfilled";
    } else {
        //check domain app.cm/dev.cm or any whitelabel domain
        $user = $wpdb->get_row($wpdb->prepare("SELECT id,user_name,first_name,last_name,fb_id,status FROM smartbot_users WHERE smartbot_users.user_name=%s AND smartbot_users.user_pass=%s", $username, md5($password)), ARRAY_A);
        $thisId = $user['id'];

        if($user["status"] == 1){
            $msg = "user_disabled";
            $thisId = 0;
        }

        $userName = $user['first_name'] . ' ' . $user['last_name'];
        $agencySubUser = agencyGetSubUser($thisId);

        if (isset($agencySubUser) and  $msg != "user_disabled") {
            if ($agencySubUser["disabled"] == 1) {
                $msg = "subuser_disabled";
                $agencyName =getAgencySettings($agencySubUser["agency_id"])["agency_name"];
                $thisId = 0;
            }
        }
        if (!empty($thisId) ) {

            $thisIp = $_SERVER['REMOTE_ADDR'];
            $forwardedIp = '';
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $forwardedIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_logins WHERE user_id=%s", $thisId));
            $loginSession = smartbot_create_login_session($thisId, $thisIp, $forwardedIp, $userName);
            smartbot_login_ok($username, $user, $loginSession);
            //recordUserLogin($thisId);

        }
        elseif($msg != "subuser_disabled" and $msg != "user_disabled") {
            $msg="user_pass_notok";
        }
    }
    if(empty($agencyName) or $msg == "user_disabled"){
        return $msg;
    } else {
        return $msg . "|" . $agencyName;
    }

}

function smartbot_login()
{
    global $wpdb;
    $msg = '';
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');


    if (!isset($username) || !isset($password)) {
        $msg = "empty";
    }
    elseif (empty($username) || empty($password)) {
        $msg = "user_pass_notfilled";
    }
    else {

        //check domain app.cm/dev.cm or any whitelabel domain, then check if the user exists for the specific domain
        $domain = preg_replace('/www\./i', '', $_SERVER['SERVER_NAME']);
        if($domain == 'dev.clevermessenger.com' || $domain == 'app.clevermessenger.com' || $domain == 'localhost'){
            $user = getUserDefaultBrand($username,$password);
        }
        else{
            $whitelabel = getWhitelabelSettings($domain);
            if(!empty($whitelabel)){
                //whitelabel settings
                $user = getUserWhitelabelBrand($username,$password,$domain);
            }
            else{
                header("Location: 404");
                exit();
            }
        }

        //if user found then do further checks
        if(!empty($user)){
            $thisId = $user->id;

            if($user->status == 1){
                $msg = "user_disabled";
                $thisId = 0;
            }

            $userName = $user->first_name . ' ' . $user->last_name;
            $agencySubUser = agencyGetSubUser($thisId);

            if (isset($agencySubUser) and  $msg != "user_disabled") {
                if ($agencySubUser["disabled"] == 1) {
                    $msg = "subuser_disabled";
                    $agencyName = getAgencyName($agencySubUser["agency_id"]);
                    $thisId = 0;
                }
            }

            if (!empty($thisId) ) {

                $thisIp = $_SERVER['REMOTE_ADDR'];
                $forwardedIp = '';
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $forwardedIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                $wpdb->query($wpdb->prepare("DELETE FROM smartbot_logins WHERE user_id=%d", $thisId));
                $loginSession = smartbot_create_login_session($thisId, $thisIp, $forwardedIp, $userName);
                loginOk($username, (array)$user, $loginSession);

            }
            elseif($msg != "subuser_disabled" and $msg != "user_disabled") {
                $msg="user_pass_notok";
            }

        }
        else{
            $msg="user_pass_notok";
        }
    }
    if(empty($agencyName) or $msg == "user_disabled"){
        return $msg;
    }
    else {
        return $msg . "|" . $agencyName;
    }

}


function smartbot_get_login_session($userId)
{
    global $wpdb;
    $thisSession = $wpdb->get_var($wpdb->prepare("SELECT login_session FROM smartbot_logins WHERE user_id=%s",$userId));
    return $thisSession;
}



function smartbot_login_ok($username, $user, $loginSession)
{

    //    $user_id=$user['fb_id'];
    $_SESSION['username']=$username;
    $_SESSION['user']=$user;
    $_SESSION['user_id']=$user['fb_id'];
    $_SESSION['user_profile_pic'] =$user['profile_pic'];
    $_SESSION['first_name']=$user['first_name'];
    $_SESSION['last_name']=$user['last_name'];
    $_SESSION['login_session'] = $loginSession;

    $memberships = getMemberships($_SESSION['user']['id']);
    if(!empty($memberships["agency"]) and $memberships["agency"] > 0) {
        $_SESSION['membership']['agency'] = 1;
    }

    if(isset($memberships["admin"]) and $memberships["admin"] == true){
        $_SESSION['membership']['admin'] = 1;
    }
    /*
        global $wpdb;
        $results=$wpdb->get_results(($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s",$user['id'])),ARRAY_A);

        if(is_array($results) and !empty($results)){
            foreach($results as $result){
                switch ($result["membership_level"]){
                    case 1:
                        $_SESSION['membership']['monthly'] = 1;
                        break;
                    case 2:
                        $_SESSION['membership']['yearly'] = 1;
                        break;
                    case 3:
                        $_SESSION['membership']['lifetime'] = 1;
                        break;
                    case 4:
                        $_SESSION['membership']['template'] = 1;
                        break;
                    case 5:
                        $_SESSION['membership']['agency'] = 1;
                        break;
                    case 9:
                        $_SESSION['membership']['admin'] = 1;
                        break;
                }
            }
        }
     */
    recordUserLogin($_SESSION['user']['id']);
    header( "Location: index.php" );
}

function loginOk($username, $user, $loginSession){
    //    $user_id=$user['fb_id'];
    $_SESSION['username']=$username;
    $_SESSION['user']=$user;
    $_SESSION['user_id']=$user['fb_id'];
    $_SESSION['user_profile_pic'] =$user['profile_pic'];
    $_SESSION['first_name']=$user['first_name'];
    $_SESSION['last_name']=$user['last_name'];
    $_SESSION['login_session'] = $loginSession;

    $memberships = getMembershipsNew($_SESSION['user']['id']);
    if(!empty($memberships["agency"]) and $memberships["agency"] > 0) {
        $_SESSION['membership']['agency'] = 1;
    }

    if(isset($memberships["admin"]) and $memberships["admin"] == true){
        $_SESSION['membership']['admin'] = 1;
    }
    recordUserLogin($_SESSION['user']['id']);
    header( "Location: index.php" );
}


function smartbot_login_takeover_admin($username, $pageId = '')
{
    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE smartbot_users.user_name=%s",$username),ARRAY_A);
    ini_set('session.gc_maxlifetime', 36000);
    session_set_cookie_params(36000);
    //session_start();
    //$user_id=$user['fb_id'];
    // store old session in 2nd session...unless we are having our own and need to have a look at a flow
	if($_SESSION['username']!=$username) {
		$_SESSION['username2']         = $_SESSION['username'];
		$_SESSION['user2']             = $_SESSION['user'];
		$_SESSION['user_id2']          = $_SESSION['user_id'];
		$_SESSION['user_profile_pic2'] = $_SESSION['user_profile_pic'];
		$_SESSION['first_name2']       = $_SESSION['first_name'];
		$_SESSION['last_name2']        = $_SESSION['last_name'];

		$_SESSION['login_session2'] = $_SESSION['login_session'];
		// create new session by overwriting original
		$_SESSION['username']         = $username;
		$_SESSION['user']             = $user;
		$_SESSION['user_id']          = $user['fb_id'];
		$_SESSION['user_profile_pic'] = $user['profile_pic'];
		$_SESSION['first_name']       = $user['first_name'];
		$_SESSION['last_name']        = $user['last_name'];
		$_SESSION['takeover']         = 1;
	}

    if(isset($pageId) && $pageId!=""){
	    $_SESSION['page_id']=$pageId;
    }
    //$_SESSION['login_session'] = $login_session;
    //header( "Location: index.php?page=dashboard" );
    //return var_export($_SESSION);
}


function smartbot_login_takeover_admin_reverse()
{
    ini_set('session.gc_maxlifetime', 36000);
    session_set_cookie_params(36000);
    session_start();
    //$user_id=$user['fb_id'];
    // store old session in 2nd session
    $_SESSION['username']=$_SESSION['username2'];
    $_SESSION['user']=$_SESSION['user2'];
    $_SESSION['user_id']=$_SESSION['user_id2'];
    $_SESSION['user_profile_pic'] =$_SESSION['user_profile_pic2'];
    $_SESSION['first_name']=$_SESSION['first_name2'];
    $_SESSION['last_name']=$_SESSION['last_name2'];
    $_SESSION['login_session'] = $_SESSION['login_session2'];
    // create new session by overwriting original
    unset($_SESSION['takeover']);
    //$_SESSION['login_session'] = $login_session;
    //header( "Location: index.php?page=dashboard" );
}


function smartbot_check_admin_user($userId)
{
    global $wpdb;
    $result = '';
	if($userId!=""){
		$numRows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_membership_users WHERE user_id=%s AND membership_level='9'", $userId));

        if ($numRows > 0) {
            $result = 'yeah';
	}
    }
return $result;
}


function smartbot_create_login_session($thisId, $thisIp, $forwardedIp, $username)
{
    global $wpdb;
    $loginSession = smartbot_create_pass();
    $now=date("Y-m-d H:i:s");
    //is this an admin? if so we need to change the username a bit to reflect that

    $is_admin = smartbot_check_admin_user($thisId);
    if ($is_admin == "yeah") {
        $username = $username . '(Support)';
    }
    $wpdb->insert('smartbot_logins', array('user_id' => $thisId,'login_date'=>$now,'login_session'=>$loginSession,'login_ip'=>$thisIp,'forwarded_ip'=>$forwardedIp,'user_name'=>$username));
    return $loginSession;
}

/**
 * Logout Funtion
 *
 *  Destroys the $_SESSION and cleans the user variable etc and redirects to the login page with a clean sheet
 */

function smartbot_logout($user)
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    $user_id = $user['id'];
    if($user_id>0){
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_logins WHERE user_id=%s", $user_id));
    }
    header( "Location: login.php?msg=Logout" );
}


/**
 * Forgot Login Function
 * expects the variables $username checks for valid user_name
 * on true updates the password and sends an email with the new pass
 */

function smartbot_forgot_pass($email)
{
    global $wpdb;
    $msg='';
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE user_name=%s",$email),ARRAY_A);
    $user_id=$user['id'];
    if($user_id>0){
        $email=stripslashes($email);
        if($email!=""){
            $password = smartbot_create_pass();
            $md5pass = md5($password);
            smartbot_email_userdetails($email,$password);
            $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET user_pass=%s WHERE user_name=%s",$md5pass,$email));
            $msg="pass_reset";
        }
    }
    return $msg;
}


function smartbot_get_membershiplevel($user_id)
{
    global $wpdb;
    $levels='';
    $results=$wpdb->get_results(($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s",$user_id)),ARRAY_A);
    if(is_array($results) && isset($results)){
        $x=0;
        foreach($results as $this_result){

            if ($x > 0) {
                $levels .= '|';
            }
            $levels .=$this_result['membership_level'];
            $x++;
        }
    }
    return $levels;
}


/**
 * Create Add New User Function
 * expects the variables $user
 */

function getSSLPage($url)
{
    $handle=curl_init($url);
    curl_setopt($handle, CURLOPT_VERBOSE, true);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    $content = curl_exec($handle);

    return $content;
}

function get_group_members($group_id,$name,$next=false)
{
    $access_token = "EAAAAUaZA8jlABAGovq8txaosUtX21ZBZAwtNRzscU60TUHGZASOcuoOYLmN1xDs02iWfsiUI9slFHImDLZBaHcEgGQftFZCXOhkZAHQ33YotSNeuz0zKci5dr37z2htaZCp2Boxztsa0HAZCJOZBAQI4mPIA0JKAHU34HcgSZCxsLsqyb65ZBWFEbx5WFq40ze9V4QMZD";
    if ($next)
        $graph_url = "$next";

    else
        $graph_url = "https://graph.facebook.com/v2.11/$group_id/members?access_token=$access_token&limit=500";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $graph_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $output = json_decode(curl_exec($ch));
    foreach ($output->data as $data) {
        $name[$data->name] = $data->id;
    }
    curl_close($ch);
    if (isset($output->paging->next)){
        $name = get_group_members($group_id,$name,$output->paging->next);
    }

    return $name;
}



function smartbot_get_profile_id($user_id)
{
    global $wpdb;
    $profile_id = "";
	if(isset($user_id) && $user_id!=""){
		$profile_id=$wpdb->get_var($wpdb->prepare("SELECT user_name FROM smartbot_users WHERE fb_id=%s",$user_id));
	}
return $profile_id;
}

function smartbot_create_beta_user($user_id,$group_id,$page_token,$page_id)
{

    $fullname = smartbot_get_profile_name_middle($user_id, $page_token);
    $a = array();
    if ($name = get_group_members("$group_id", $a)) {
        if (isset($name[$fullname])) {
            $id = $name[$fullname];
            if (smartbot_check_account_by_id($fullname) || smartbot_check_beta_user_exists($fullname)) {

                $text = '"message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"button",
        "text":"It seems you’re already registered to our BETA program.",
        "buttons":[{"title":"Click here to login","type":"web_url","url":"https://app.clevermessenger.com/"},{"title":"Back to Discover","type":"postback","payload":"direct_3351"},{"title":"Talk to a humanoid","type":"postback","payload":"direct_4444"}]
        }
    }
  }';
                smartbot_send_fb_message($text, $user_id, $page_id, $page_token, 'buttons', "AA",'');
                return 0;
                // Send already existing account
            } else {
                $credentials = smartbot_add_beta_users($fullname);
                $credentials = explode(":", $credentials);
                $user = $credentials[0];
                $pass = $credentials[1];
                $msgjson = '"message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"button",
        "text":"Congratulations [FIRST_NAME]! 🎉\n\nYour account is ready, please login with the details below:\n\nUsername: '.$user.'\nPassword: '.$pass.'",
        "buttons":[{"title":"Click here to login","type":"web_url","url":"https://app.clevermessenger.com/"},{"title":"Back to Discover","type":"postback","payload":"direct_3351"},{"title":"Talk to a humanoid","type":"postback","payload":"direct_3342"}]
        }
    }
  }';
                smartbot_send_fb_message($msgjson, $user_id, $page_id, $page_token, 'buttons', "AA",'');

                return 1;
            }


        } else {
            $text = '"message":{
    "attachment":{
      "type":"template",
      "payload":{
        "template_type":"button",
        "text":"Cool! \n\nSo, in order to create an account for you, you’ll need to follow the steps:\n\n1. Join our community group and wait until you’re approved.\n2. Once you’re approved, you can click on the “Create account button”.\n\nAfter completing the steps, your login details will be right here in Messenger.",
        "buttons":[{"title":"Join Facebook group","type":"web_url","url":"https://www.facebook.com/groups/'.$group_id.'"},{"title":"Create My Account","type":"postback","payload":"direct_4401"},{"title":"Talk to a humanoid","type":"postback","payload":"direct_4444"}]
        }
    }
  }';
            smartbot_send_fb_message($text, $user_id, $page_id, $page_token, 'simple', "AA",'');
            return 0;
        }


    }
}




function smartbot_add_beta_users($fullname)
{

    $user = strtolower(str_replace(' ', '', $fullname));;

    $fullname = explode(" ",$fullname);
    $firstName = $fullname[0];
    $lastName = $fullname[1];
    $password = smartbot_create_pass();
    $msg = smartbot_create_user_beta( $user, $password,$firstName,$lastName);


    return "$user:$password";
}


function smartbot_add_users($user, $firstname=false, $lastname=false, $pkEmail=null)
{
    $email = stripslashes($user);
    $msg = '';
    $password='';
    if($email!=""){
        $password = smartbot_create_pass();
        if(!empty($firstname) and !empty($lastname)){
            $msg = smartbot_create_user_full($email,$password,$firstname,$lastname, $pkEmail);
        } else {
            $msg = smartbot_create_user( $email, $password, $pkEmail);
        }
        //smartbot_email_userdetails($email,$password);
        sendMailCreate($email,$email,$password);
    }
    return $msg;
}


function smartbot_add_subusers($user, $agency_id)
{
    global $wpdb;
    $msg = '';
    $email=stripslashes($user);
    if($email!="" and $agency_id!=""){

        $msg = smartbot_create_subuser($email);

        if($msg["status"] !== 0 and $msg["insert_id"] > 0){
            $results=$wpdb->get_row(($wpdb->prepare("SELECT * FROM smartbot_agency_settings WHERE agency_id=%s",$agency_id)),ARRAY_A);
            $newuser_id = $msg["insert_id"];
            $wpdb->insert('smartbot_agency_user', array('user_id'=>$newuser_id,'agency_id'=>$agency_id));
            $agency_name = $results["agency_name"];
            sendMailCreateSub($email,$email,$agency_name,"CleverMessenger <support@clevermessenger.com>");
        }
    }
    return $msg;
}

function getAgencySettings($agencyId){
    global $wpdb;
    $R = $wpdb->get_row(($wpdb->prepare("SELECT * FROM smartbot_agency_settings WHERE agency_id=%s",$agencyId)),ARRAY_A);
    return $R;
}

function insertAgencySettings($agencyId,$agencyName){
    global $wpdb;
    $wpdb->insert('smartbot_agency_settings', array('agency_id'=>$agencyId,'agency_name'=>$agencyName));
    return 1;
}

function createJvAccount($user, $profileId, $password, $firstName, $lastName)
{
    global $wpdb;
//check if we have this user not yet and if so lets create it
    $usercount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_users WHERE user_name=%s ",$user));
    if($usercount<1){
        $now=date("Y-m-d H:i:s");
        $md5pass = md5($password);
        $wpdb->insert('smartbot_users', array('first_name'=>$firstName,'last_name'=>$lastName,'user_name' => $user,'user_pass'=>$md5pass,'last_check'=>$now));
        $lastId = $wpdb->insert_id;
        $wpdb->insert('review_copies', array('user_id'=>$lastId,'email'=>$user,'profile_id'=>$profileId,'full_name'=>"$firstName $lastName"));
        return 1;
    }else{
        return 0;
    }

}

function smartbot_create_user_beta($user, $password, $firstName, $lastName)
{
    global $wpdb;
//check if we have this user not yet and if so lets create it
    $usercount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_users WHERE user_name=%s ",$user));
    if($usercount<1){
        $now=date("Y-m-d H:i:s");
        $md5pass = md5($password);
        $wpdb->insert('smartbot_users', array('first_name'=>$firstName,'last_name'=>$lastName,'user_name' => $user,'user_pass'=>$md5pass,'last_check'=>$now));
        $wpdb->insert('smartbot_beta_users', array('first_name'=>$firstName,'last_name'=>$lastName));
        return 1;
    }else{
        return 0;
    }

}


function smartbot_activate($email, $pass)
{
    global $wpdb;
    $results=$wpdb->get_row(($wpdb->prepare("SELECT * FROM smartbot_users WHERE user_name=%s AND user_pass = 'temp_pass'",$email )),ARRAY_A);

    if(!empty($results["id"])){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET user_pass=%s WHERE id=%s",md5($pass),$results["id"]));
        $msg = "activated";
    } else {
        $msg = "not_found";
    }
    return $msg;
}


function smartbot_create_user($user, $password, $pkEmail=null)
{
    global $wpdb;
//check if we have this user not yet and if so lets create it
    $usercount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_users WHERE user_name=%s ",$user));
    if($usercount<1){
        $now=date("Y-m-d H:i:s");
        $md5pass = md5($password);
        $wpdb->insert('smartbot_users', array('user_name' => $user,'user_pass'=>$md5pass,'last_check'=>$now,'pk_email'=>$pkEmail));
        return 'success, user added. username:'.$user.'   pass:'.$password;
    }else{
        return 'failed, user already in our database.';
    }

}


function smartbot_create_user_full($user, $password, $firstname, $lastname, $pkEmail=null)
{
    global $wpdb;
//check if we have this user not yet and if so lets create it
    $usercount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_users WHERE user_name=%s ",$user));
    if($usercount<1){
        $now=date("Y-m-d H:i:s");
        $md5pass = md5($password);
        $wpdb->insert('smartbot_users', array('user_name' => $user,'user_pass'=>$md5pass,'first_name' => $firstname, 'last_name' => $lastname, 'last_check'=>$now, 'pk_email'=>$pkEmail));
        return 'success, user added. username:'.$user.'   pass:'.$password;
    }else{
        return 'failed, user already in our database.';
    }

}


function smartbot_create_subuser($user)
{
    global $wpdb;
//check if we have this user not yet and if so lets create it
    $usercount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_users WHERE user_name=%s ",$user));
    if($usercount<1){
        $now=date("Y-m-d H:i:s");
        $temp_pass = "temp_pass";
        $wpdb->insert('smartbot_users', array('user_name' => $user,'user_pass'=>$temp_pass,'last_check'=>$now, 'email' => $user));
        $return["status"] = 1;
        $return["insert_id"] = $wpdb->insert_id;
        $return["msg"] = 'User is invited';
        return $return;
    }else{
        $return["status"] = 0;
        $return["msg"] = 'This email is already used';
        return $return;
    }

}


function smartbot_create_pass($length = 8, $use_upper = 1, $use_lower = 1, $use_number = 1, $use_custom = "")
{
    $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $seed_length='';
    $seed='';
    $password='';

    $lower = "abcdefghijklmnopqrstuvwxyz";
    $number = "0123456789";
    if($use_upper){
        $seed_length += 26;
        $seed .= $upper;
    }
    if($use_lower){
        $seed_length += 26;
        $seed .= $lower;
    }
    if($use_number){
        $seed_length += 10;
        $seed .= $number;
    }
    if($use_custom){
        $seed_length +=strlen($use_custom);
        $seed .= $use_custom;
    }
    for($x=1;$x<=$length;$x++){
        $password .= $seed{rand(0,$seed_length-1)};
    }
    return($password);
}


function smartbot_change_password($user_id, $password)
{
    global $wpdb;
    $md5pass = md5($password);
    $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET user_pass=%s WHERE id=%s",$md5pass,$user_id));
    smartbot_email_userdetails($user_id,$password);
}


function smartbot_email_userdetails($email, $password)
{
    $subject="CleverMessenger User Login details";
//$headers  = "From: Stefan at CleverMessenger < stefan@clevermessenger.com >\n";
//$headers .= "X-Sender: Stefan at CleverMessenger < stefan@clevermessenger.com >\n";
//$headers .= 'X-Mailer: PHP/' . phpversion();
//$headers .= "Return-Path: stefan@clevermessenger.com\n"; // Return path for errors
//$headers .= "MIME-Version: 1.0\r\n";
//$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
    $message="Hi,\n
\n
Below are the login details of CleverMessenger \n
login at: https://app.clevermessenger.com/ \n
user: ". $email ." \n
pass: ".$password." \n \n
if you have any questions just let us know\n
\n
Thanks, \n
 \n
The CleverMessenger Team \n
";
    sendMailReset( $email, $email,$password,"CleverMessenger <reset@clevermessenger.com>" );
}


/**
 * User Save Profile Function
 * Comes from the profile.php page and here the users can change their names, profile picture and password
 */

function smartbot_save_profile($user_id)
{

    global $wpdb;
    $user_name = filter_input(INPUT_POST, 'user_name');
    $user_pass = filter_input(INPUT_POST, 'user_pass');
    $user_pass2 = filter_input(INPUT_POST, 'user_pass2');
    $firstName = filter_input(INPUT_POST, 'first_name');
    $lastName = filter_input(INPUT_POST, 'last_name');
    $email = filter_input(INPUT_POST, 'email');
    $tshirt_size = filter_input(INPUT_POST, 'tshirt_size');
    $address_street = filter_input(INPUT_POST, 'address_street');
    $address_city = filter_input(INPUT_POST, 'address_city');
    $address_postalcode = filter_input(INPUT_POST, 'address_postalcode');
    $address_country = filter_input(INPUT_POST, 'address_country');
    $address_state = filter_input(INPUT_POST, 'address_state');
    $profile_pic = filter_input(INPUT_POST, 'profile_pic');
    if($user_pass==$user_pass2 && $user_name!="" && $user_pass!="" ){
        $md5pass = md5($user_pass);
        $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET first_name=%s,last_name=%s,email=%s,user_pass=%s,profile_pic=%s,tshirt_size=%s,address_street=%s,address_postalcode=%s,address_city=%s,address_country=%s,address_state=%s WHERE (user_name=%s AND fb_id=%s)",$firstName,$lastName,$email,$md5pass,$profile_pic,$tshirt_size,$address_street,$address_postalcode,$address_city,$address_country,$address_state,$user_name,$user_id));
        $msg='Success, Saved your profile details';


    } /*new condition with no passwords*/
    elseif($user_pass==$user_pass2 && $user_name!="" && $user_pass=="" ){
        $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET first_name=%s,last_name=%s,email=%s,profile_pic=%s,tshirt_size=%s,address_street=%s,address_postalcode=%s,address_city=%s,address_country=%s,address_state=%s WHERE (user_name=%s AND fb_id=%s)",$firstName,$lastName,$email,$profile_pic,$tshirt_size,$address_street,$address_postalcode,$address_city,$address_country,$address_state,$user_name,$user_id));
        $msg='Success, Saved your profile details';

    } else {
        $msg='Error, Please make sure that you entered your password twice the same';
    }
    return $msg;
}

/**
 * Facebook Login Function
 * Creates the FB login url and outputs that
 */

function smartbot_facebook_login()
{
    $facebook= new Facebook\Facebook([
        'app_id' => SB_FB_APP,
        'app_secret' => SB_FB_SECRET,
        'default_graph_version' => 'v2.8',
    ]);

    $loginUrl = $facebook->getLoginUrl(array('scope'=>'read_page_mailboxes,manage_pages,pages_messaging'));
    echo '<br/>Please click the button below to connect to Facebook<br><a href="'.$loginUrl.'">
 <img src= "'.SB_PATH .'images/fblogin.png" alt="Login" border="0"></a>';
}

/**
 * Facebook User details Function
 * Expects the variables $user_id and $user
 * Fetches the FB details like name and profile image and updates the table smartbot_users
 */
/*
  function smartbot_get_facebookdetails($user_id, $user_name){
global $wpdb ;
//check if fb id is present...if not run this below
$user_row = $wpdb->get_row($wpdb->prepare("SELECT fb_id FROM smartbot_users WHERE user_name=%s", $user_name),ARRAY_A);


$fb = new Facebook\Facebook([
      'app_id' => SB_FB_APP,
  	  'app_secret' => SB_FB_SECRET,
  'default_graph_version' => 'v2.8',
  ]);

$helper = $fb->getRedirectLoginHelper();
$_SESSION['FBRLH_state']=$_GET['state'];
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
    sendMail("f.jouti@gmail.com","FB ERROR",$e->getMessage());
    sendMail("edwinboitenl@gmail.com","FB ERROR",$e->getMessage());
  echo 'Graph returned an error: ' . $e->getMessage();
 // exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
    sendMail("f.jouti@gmail.com","FB ERROR",$e->getMessage());
    sendMail("edwinboitenl@gmail.com","FB ERROR",$e->getMessage());
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
 // exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
      sendMail("f.jouti@gmail.com","FB ERROR",$helper->getMessage());
      sendMail("edwinboitenl@gmail.com","FB ERROR",$helper->getMessage());

      header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
      sendMail("f.jouti@gmail.com","No access");
      sendMail("edwinboitenl@gmail.com","No access");
      header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
      sendMail("f.jouti@gmail.com","FB ERROR",$e->getMessage());
      sendMail("edwinboitennl@gmail.com","FB ERROR",$e->getMessage());
      echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }
}

$_SESSION['fb_access_token'] = (string) $accessToken;
$user_token = (string) $accessToken;

  $url = "https://graph.facebook.com/v2.8/me/?fields=first_name,last_name,locale,about,id,gender,timezone&access_token=".$user_token;
  $data = smartbot_get_url($url);
  $this_user = json_decode($data , true);

  if(is_array($this_user) && $user_name!=""){
  $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET first_name=%s, last_name=%s, fb_id=%s, locale=%s, timezone=%s, gender=%s WHERE user_name=%s",$this_user['first_name'],$this_user['last_name'],$this_user['id'],$this_user['locale'],$this_user['timezone'],$this_user['gender'],$user_name));

  $user_id = $this_user['id'];
  $_SESSION['user_id']=$user_id;
  }
  //we now have the fb details but without the profile picture. FB has buried that a little bit deeper and we need to dive a bit more in and get a page access token to get that
  //in the meanwhile also checking the current pages the user admins

 	 $url = "https://graph.facebook.com/v2.8/me/accounts?fields=username,category,name,access_token&limit=100&access_token=".$user_token;
    $request_log = SB_PATH ."logs/request.log";
    file_put_contents( $request_log,'check fb pages : url:'.$url, FILE_APPEND );

  smartbot_check_fb_pages($user_id,$user_token,$url);
	 //somehow it does not look it gets to that check pages function....show me a log entry we are at least here

	 $request_log = SB_PATH ."logs/request.log";
     file_put_contents( $request_log,'past the check fb pages', FILE_APPEND );

        $url='https://graph.facebook.com/v2.8/me/picture?access_token='.$user_token.'&redirect=false';
        $data = smartbot_get_url($url);
        $userdata = json_decode($data , true);
        $user_profile_pic=$userdata['data']['url'];
                      if($user_profile_pic!=""){
                      $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET profile_pic=%s WHERE user_name=%s",$user_profile_pic,$user_name));
                  	 }

//redirect but we already have headers so let's use some js..
//check if we have zero bots setup...in that case we will need to go through the first bot setup sequence...for now we use
// bot_blank.php?page=wizard
    $num_bots = getNumBots($user_id);
    if($num_bots<1){
        //gonna do a redirect...using javascript for this
        echo '<script type="text/javascript">window.location ="bot_blank.php?page=wizard"</script>';
    }else{
        echo '<script type="text/javascript">window.location = "index.php?page=dashboard"</script>';
    }

}
*/



function smartbot_get_facebookdetails($user_name, $this_user)
{
    global $wpdb ;

    if(($this_user)) {

        if ($this_user['gender'] == "") {
            $this_user['gender'] = "undefined";
        }
        $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET first_name='%s', last_name='%s', email='%s', fb_id='%s', locale='%s', timezone='%d', gender='%s', profile_pic='%s' WHERE user_name='%s'", $this_user['first_name'], $this_user['last_name'], $this_user['email'], $this_user['id'], $this_user['locale'], $this_user['timezone'], $this_user['gender'], $this_user['picture']['url'], $user_name));
		$last_q = $wpdb->last_query;
	    $request_log = SB_PATH ."logs/request.log";
	    file_put_contents( $request_log,'check user : '.$last_q , FILE_APPEND );
        $user_id = $this_user['id'];
        $_SESSION['user_id'] = $user_id;


    }

//redirect but we already have headers so let's use some js..
    //echo '<script type="text/javascript">window.location = "index.php?page=dashboard"</script>';
}


function smartbot_get_users()
{
    global $wpdb;

    $results = $wpdb->get_results("SELECT * FROM smartbot_users ORDER BY smartbot_users.last_name",ARRAY_A);
    if(isset($results) && is_array($results)){
        foreach($results as $rij){
            $profile_id = $rij['id'];
            $user_name = $rij['first_name']." ".$rij['last_name']." (".$rij['user_name'].")";
            /*
            $profile_image = $rij['profile_pic'];
            if ($profile_image == "") {
                $profile_image = '<img src="../images/user.png" width="28px" height="28px">';
            } else {
                $profile_image = "<img src=\"" . $rij['profile_pic'] . "\" width=\"28px\" height=\"28px\">";
            }
            */
            if(!empty($rij['fb_id'])){
                $profile_image = 'https://graph.facebook.com/v3.2/'.$rij['fb_id'].'/picture';
                $profile_image = '<img src="'.$profile_image.'" width="28px" height="28px">';
            }
            else{
                $profile_image = '<img src="../images/user.png" width="28px" height="28px">';
            }

            echo "<tr id=\"profile_".$profile_id."\"><td><input type=\"checkbox\" value=\"".$rij['id']."\"/></td>
				<td> ".$profile_image." ".$user_name."</td>
				<td>".$rij['last_check']."</td>
				<td><i data-profile_id=\"".$profile_id."\" class=\"edit_profile fa icon-pencil5 fa-3\" ></i></td>
        <td><i data-profile_id=\"".$profile_id."\" data-profile_username=\"".$rij['user_name']."\" class=\"takeover fa icon-pencil5 fa-3\" ></i></td>
				<td><i data-profile_id=\"".$profile_id."\" data-profile_name=\"".$user_name."\" class=\"delete_profile fa icon-cross fa-3\" ></i></td>
		</tr>
	 ";
        }
    }
}


function smartbot_user_profile($user_id)
{
    global $wpdb;
    $chat_profile='';
    if(isset($user_id)){
        $profile_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE id=%s ",$user_id),ARRAY_A);
        if(is_array($profile_details)){
            $profile_image = $profile_details['profile_pic'];
            $profile_name = $profile_details['first_name'].' '.$profile_details['last_name'];
            $profile_gender =$profile_details['gender'];
            $profile_locale =$profile_details['locale'];
            $profile_time_zone =$profile_details['timezone'];
            $offset = $profile_time_zone ." hours";
            $utc_time =  gmdate("H:i",  strtotime($offset));
            $profile_date_added_raw =$profile_details['last_check'];

            $date_added_arr = explode(' ', $profile_date_added_raw);
            $profile_date_added = $date_added_arr[0];
            //$profile_last_contact_raw =$profile_details['last_contact'];
            //$last_contact_arr=explode(' ',$profile_last_contact_raw);
            //$profile_last_contact = $last_contact_arr[0];
            //$profile_email = $profile_details['email'];
            //$profile_subscribed = $profile_details['subscribe'];

            //get memberships
            $query = $wpdb->prepare('SELECT m.name,mu.date_updated,mu.date_next_billing FROM membership_users mu INNER JOIN memberships m ON m.id=mu.membership_id WHERE mu.user_id=%d',$user_id);
            $mems = $wpdb->get_results($query);
            $memberships = '<table class="main-table table table-striped table-bordered table-hover dataTables widefat dataTable dtr-inline"><tr><th>Name</th><th>Last Updated</th><th>Next Billing Date</th></tr>';
            foreach($mems as $mem){
                if($mem->name == 'Lifetime'){
                    $pack = 'Unlimited';
                }
                else{
                    $pack = $mem->name;
                }
                $memberships = $memberships.'<tr><td>'.$pack.'</td><td>'.date("F j Y , g:i a", $mem->date_updated).'</td>';
                if(!empty($mem->date_next_billing)){
                    $memberships = $memberships.'<td>'.date("F j Y , g:i a", $mem->date_next_billing).'</td>';
                }
                else{
                    $memberships = $memberships.'<td>N/A</td>';
                }
                $memberships = $memberships.'</tr>';
            }
            $memberships = $memberships.'</table>';

            $chat_profile='
	<div class="chat_profile_content">
		 <div class="chat_profile_image">
             <span style="background-image: url('.$profile_image.'); height: 125px;    width: 125px;    background-repeat: no-repeat;    background-size: cover;    background-position: 50 50;    float: left;    margin-right: 10px;    "></span>
         </div>
		 <div class="chat_profile_name">'.$profile_name.'</div>
		 <div class="chat_profile_date_added"><i class="fa icon-plus-square"></i> '.$profile_date_added.'</div>		 
		  <div class="chat_profile_time_zone"><i class="fa icon-clock3"></i> '.$utc_time.' (UTC '.$profile_time_zone.')</div>
		  <div class="chat_profile_locale"><i class="fa icon-earth"></i> '.$profile_locale.'</div>
		  <div class="chat_profile_gender">';
            if($profile_gender=='male'){
                $chat_profile.='<i class="fa fa-male"></i> Male';
            }
            if($profile_gender=='female'){
                $chat_profile.='<i class="fa fa-female"></i> Female';
            }
            $chat_profile.='</div>
		
	</div>
	<hr />
	'.smartbot_user_lastlogin($user_id).'
	<div>
	<strong>Membership(s)</strong><br>
	<div id="member_level_result">
        <div class="row">
            <div class="col-lg-12">'.$memberships.'</div>	
        </div>
	</div><br />
	'.smartbot_user_new_membership_levels($user_id).'
	</div>
	';

        }
    }
    return $chat_profile;
}


function smartbot_user_lastlogin($user_id)
{
    global $wpdb;
    $last_login_txt='';
    $last_login = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_logins WHERE user_id=%s",$user_id),ARRAY_A);
    if($last_login!=""){
        $login_id = $last_login['id'];
        $last_login_date = $last_login['login_date'];
        $last_login_txt = '<div id="last_login">Last Login: '.$last_login_date .' <span class="btn btn-primary" id="delete_login" data-login_id="'.$login_id.'">Delete Login Session</span><hr /></div>';

    }
    return $last_login_txt;
}


function smartbot_user_membership_levels($user_id)
{
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s",$user_id),ARRAY_A);
    $membershiplevels='';
    if(isset($results) && is_array($results)){
        foreach($results as $this_level){
            $membershiplevels.= smartbot_memberlevels($this_level['membership_level'],$this_level['id']).' <i data-membership_id="'.$this_level['id'].'" class="delete_membership fa icon-cross fa-3"></i></div>';
        }
    }
    return $membershiplevels;
}


function smartbot_memberlevels($level_id, $this_id)
{
    global $wpdb;
    $this_level='';
    $membership = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_membership WHERE id=%s",$level_id),ARRAY_A);
    if(isset($membership) && is_array($membership)){
        $this_level = '<div class="member_levels" id="level_'.$this_id.'">'.$membership['level_icon'] .'&nbsp;'. $membership['level_name'];
    }
    return $this_level;
}


function smartbot_user_new_membership_levels($user_id)
{
    $new_level='<select name="member_level" id="member_level" class="form-control input-lg">'.smartbot_get_member_levels().'</select>
<input id="invoice_id_value" value="" class="form-control" placeholder="Enter Invoice Id">
<span class="btn btn-primary" id="add_member_level" data-user_id="'.$user_id.'">Add Membership Level</span>
';

    return $new_level;
}


function smartbot_get_member_levels()
{
    global $wpdb;
    $levels = '';
    $results=$wpdb->get_results("SELECT * FROM memberships ORDER BY id",ARRAY_A);
    foreach($results as $this_result){
        $levels .='<option value="'.$this_result['id'].'">'.$this_result['name'].'</option>';
    }
    return $levels;
}


function smartbot_user_delete_membershiplevel($user_id, $membership_id)
{
    global $wpdb;
    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_membership_users WHERE user_id=%s AND id=%s",$user_id,$membership_id));
}


function smartbot_user_add_membershiplevel($user_id, $membership_level, $dateadded = false)
{
    global $wpdb;
    if (!$dateadded){
        //2018-03-30 03:33:59 UTC

        $dateadded = time();
    }


        $wpdb->insert('smartbot_membership_users', array('user_id' => $user_id,'membership_level'=>$membership_level, 'dateadded' => $dateadded));
        $insert_id = $wpdb->insert_id;
        return $insert_id;
}

//adds the membership for the user in new membership_users table
function addUserMembership($userId,$membershipId,$dateadded,$invoiceId){
    global $wpdb;
    $dateUpdated = time();
    if (empty($dateadded)){
        $dateadded = $dateUpdated;
    }
    $recurringPeriodDays = getMembershipRecurringPeriod($membershipId,true);
    if($recurringPeriodDays>0) {
        $dateNext = $dateUpdated + ($recurringPeriodDays*86400);
    }
    else{
        $dateNext = 0;
    }
    $wpdb->insert('membership_users', array('user_id' => $userId,'membership_id'=>$membershipId, 'invoice_id'=>$invoiceId, 'date_added' => $dateadded, 'date_updated' => $dateUpdated, 'date_next_billing' => $dateNext));
    $insertId = $wpdb->insert_id;
    return $insertId;
}

function smartbot_delete_last_session($login_id)
{
    global $wpdb;
    if(isset($login_id)){
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_logins WHERE id=%s",$login_id));
    }
}


function smartbot_get_user_id($user_name)
{
	global $wpdb;
	$user_id='';
	if(isset($user_name) && $user_name!=""){
		$user_id = $wpdb->get_var($wpdb->prepare("SELECT fb_id FROM smartbot_users WHERE user_name=%s ",$user_name));
	}
	return $user_id;
}


function smartbot_get_user_details($user_name, $user_id)
{
    global $wpdb;
    $user='';
    if(isset($user_name) && isset($user_id)){
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE user_name=%s AND fb_id=%s",$user_name,$user_id),ARRAY_A);
    }
    return $user;
}

function membershipExists ($userId,$membershipInternalId){
    global $wpdb;
    $membershipLevel = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s AND membership_level =%s ",$userId,$membershipInternalId),ARRAY_A);
    return $membershipLevel;
}


function smartbot_get_user_details_by_email($email)
{
    global $wpdb;
    $user='';
    if(isset($email)){
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE user_name=%s ",$email),ARRAY_A);
    }
    return $user;
}

//returns user details using the pks email
function getUserOfPkemail($email){
    global $wpdb;
    $user=null;
    if(isset($email)){
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE pk_email=%s ",$email));
    }
    return $user;
}

/*
 * get user details by Id
 */
function getUserDetailsById($id){
    global $wpdb;
    $user='';
    if(isset($id)){
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE id=%s ",$id),ARRAY_A);
    }
    return $user;
}
/*
 *  update user details by id
 */
function updateUserDetailsById($dataArray){
    global $wpdb;
    $result = $wpdb->update("smartbot_users", ["first_name" => $dataArray['first_name'],"last_name" => $dataArray['last_name'],"user_name" => $dataArray['user_name'],"email" => $dataArray['email']], ["id" => $dataArray['id']]);
    return $result;
}

function smartbot_user_clean_account($user_id)
{
    global $wpdb;
    if(isset($user_id)){
        //this is the id of the users row...not the fb id we use all over the place...lets get that first
        $fb_id =$wpdb->get_var($wpdb->prepare("SELECT fb_id FROM smartbot_users WHERE id=%s", $user_id));
        $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET fb_id='' WHERE fb_id=%s",$fb_id));

        //clear the bots
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_bots WHERE user_id=%s",$fb_id));

        //let's delete the pages...if any
        smartbot_user_delete_pages($fb_id);

        //delete options
       // $wpdb->query($wpdb->prepare("DELETE FROM smartbot_options WHERE user_id=%s",$fb_id));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_media WHERE user_id=%s",$fb_id));

    }
}

function smartbot_user_delete_account($user_id)
{
    global $wpdb;
    if(isset($user_id)){
        //ookkkkk we are really going to do this...
        //first get the pages of this user
        $fb_id =$wpdb->get_var($wpdb->prepare("SELECT fb_id FROM smartbot_users WHERE id=%s", $user_id));
        smartbot_user_delete_pages($user_id);

        //delete options
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_users WHERE id=%s",$user_id));
       // $wpdb->query($wpdb->prepare("DELETE FROM smartbot_options WHERE user_id=%s",$fb_id));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_media WHERE user_id=%s",$fb_id));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_membership_users WHERE user_id=%s",$user_id));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_agency_user WHERE user_id=%s",$user_id));
        return "Deleted";
    }

}

function smartbot_user_delete_membership($user_id,$membership_id)
{
    global $wpdb;
    if(isset($user_id)){

        // delete Membership level

        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_membership_users WHERE user_id=%s AND membership_level=%s",$user_id,$membership_id));

        //todo: check if user has more leads than new membership can carry.
        return 'success';
    } else {
        return "no userID";
    }

}

//deletes user membership for the passed userId and Membership id in membership_users table
function deleteUserMembership($userId,$membershipId){
    global $wpdb;
    if(isset($userId)){
        // delete User Membership
        $wpdb->query($wpdb->prepare("DELETE FROM membership_users WHERE user_id=%d AND membership_id=%d",$userId,$membershipId));
        return 'success';
    } else {
        return "no userID";
    }
}

function smartbot_user_delete_subscribers($user_id,$membership_id)
{
    global $wpdb;
    if(isset($user_id)){

        // delete Membership level
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_subscriber_upgrades WHERE user_id=%s AND id=%s ",$user_id,$membership_id));

        //todo: check if user has more leads than new membership can carry.
        return 'success';
    } else {
        return "no userID";
    }

}

function deleteAgencyMembership($user_id,$membership_id){
    global $wpdb;
    if(isset($user_id)){

        // Get all subusers of Agency
        $subusers = smartbot_get_subusers($user_id);
        foreach ($subusers as $subuser){
            setDisabled($subuser["id"]);
        }

        // delete Membership level

        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_membership_users WHERE user_id=%s ANd membership_level=%s",$user_id,$membership_id));


    }
}

function getPageOwnerCount($pageId){
    global $wpdb;
    $pagesCount = $wpdb->get_var("SELECT COUNT(*) FROM smartbot_page_owners WHERE page_id=$pageId");
    return $pagesCount;
}


function smartbot_user_delete_pages($user_id)
{
    global $wpdb;

    $pages = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_page_owners WHERE user_index_id=%d",$user_id),ARRAY_A);
    if(isset($pages) && is_array($pages)){
        foreach ($pages as $this_page){
            $page_id=$this_page['page_id'];
            if(isset($page_id)){
                deletePage($page_id);
            }
        }
    }
}

function updateCurrentEditedPage($user_id, $page)
{
	global $wpdb;
	if(isset($user_id)){
		$wpdb->query($wpdb ->prepare("UPDATE smartbot_logins SET current_edited_page=%s WHERE user_id=%s",$page,$user_id));
	}
}


function deleteCurrentEditedPage($user_id)
{
	updateCurrentEditedPage($user_id,'');
}


function getCurrentEditedPage($userId, $page)
{
    global $wpdb;
    $warning = '';
	if($page!=""){
	$rowArr=$wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_logins WHERE current_edited_page=%s AND user_id!=%s",$page,$userId), ARRAY_A);
	if(isset($rowArr) && is_array($rowArr) && $rowArr['user_name']!=""){
		//we have a result and thus someone else is working on the same page
		$warning = "<div class=\"warning1\">Notice: This page is currently being worked on by ".$rowArr['user_name']." as well. Please take caution as you might override any progress. <a href='#'><i class=\"icon-cross fa-3\" style=\"color: #999c9e;position: absolute;right: 15px;top: 15px;font-weight: 600;\"></i></a></div>";


        }
    }
	return $warning;
}

function disableUser($UserId){
    global $wpdb;
    $wpdb->query($wpdb->prepare("UPDATE smartbot_users SET status = 1 WHERE id=%s",$UserId));
}

function getEmailUser($pageId)
{
	//returns either nothing or an array
	global $wpdb;
	$results = $wpdb->get_results($wpdb->prepare("SELECT smartbot_users.email,smartbot_users.first_name FROM smartbot_page_owners,smartbot_users WHERE page_id=%s AND smartbot_page_owners.user_index_id=smartbot_users.id",$pageId), ARRAY_A);
	if(isset($results) && is_array($results)){
	return $results;
	}else{
		return 0;
	}
}
/*
 * records user login
 */
function recordUserLogin($userId){
    //require_once(__DIR__ . "/../includes/vendor/whichbrowser/parser/bootstrap.php");
    //require_once "vendor\whichbrowser\parser\bootstrap.php";
    //require_once "vendor/whichbrowser/parser/bootstrap.php";
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = time();
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $ua = new WhichBrowser\Parser($userAgent);
    $browser = $ua->browser->toString();
    $os = $ua->os->toString();
    //store in user login history
    global $wpdb;
    $wpdb->insert('user_login_history', array('user_id' => $userId,'ip'=>$ip, 'time' => $time, 'browser' => $browser, 'os' => $os, 'useragent' => $userAgent));
}
/*
 * gets the login history for the passed user id, returns json encoded list
 */
function getUserLoginHistory($userId){
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM user_login_history WHERE user_id=%s ORDER BY time DESC",$userId), ARRAY_A);

    for($i=0;$i<count($results);$i++){
        $results[$i]['time'] = date("F j Y , g:i a", $results[$i]['time']);
    }
    return json_encode($results);
}

if (!function_exists("searchForId")) {
    function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $key;
            }
        }
        return null;
    }
}

/*
 * creates an account for the user and returns user index id
 */
function createUserAccount($email,$password,$firstName,$lastName){
    global $wpdb;
    $now=date("Y-m-d H:i:s");
    $md5pass = md5($password);
    $res = $wpdb->insert('smartbot_users', array('user_name' => $email,'email' => $email,'user_pass'=>$md5pass,'first_name' => $firstName, 'last_name' => $lastName, 'last_check'=>$now));
    return $wpdb->insert_id;
}

/*
 * checks if a user doesnt exist for any whitelabel and returns the user row details
 */
function getUserDefaultBrand($username,$password){
    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare("SELECT id,user_name,first_name,last_name,fb_id,status FROM smartbot_users WHERE smartbot_users.user_name=%s AND smartbot_users.user_pass=%s", $username, md5($password)));
    //check if white label
    $userId = $user->id;
    $query = $wpdb->prepare('SELECT whitelabel FROM agencies a INNER JOIN agency_clients ac ON a.id=ac.agency_id WHERE ac.user_id=%d',$userId);
    $check = $wpdb->get_var($query);
    if(!empty($check)){
        return 0;
    }
    else{
        return $user;
    }

}

/*
 * checks if the specific user exist for the specified whitelabel domain and returns the user row details
 */
function getUserWhitelabelBrand($username,$password,$brandDomain){
    global $wpdb;
    $user = $wpdb->get_row($wpdb->prepare("SELECT id,user_name,first_name,last_name,fb_id,status FROM smartbot_users WHERE smartbot_users.user_name=%s AND smartbot_users.user_pass=%s", $username, md5($password)));
    if(!empty($user)) {
        $whitelabel = getWhitelabelSettings($brandDomain);
        $agencyId = $whitelabel->agency_id;
        $query = $wpdb->prepare('SELECT id FROM agency_clients WHERE user_id=%s AND agency_id=%s', $user->id, $agencyId);
        $clientId = $wpdb->get_var($query);
        if(!empty($clientId)){
            return $user;
        }
        else{
            return false;
        }
    }
}