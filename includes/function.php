<?php
/*

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/wp-db.php");
require_once(__DIR__ . "/constants.php");

require_once(__DIR__ . "/vendor/autoload.php");



//function file
if (CM_ENVIRONEMENT === "locals" || CM_ENVIRONEMENT === "dev"  ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function var_dump2($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}
@session_start();


/**
 * User related functions moved to user_functions.php
 */

require_once(__DIR__ . "/user_functions.php");


/**
 * Main FB Page related functions moved to fbpage_functions.php
 */

require_once(__DIR__ . "/fbpage_functions.php");


/**
 * Main Bot related functions moved to bot_functions.php
 *
 */

require_once(__DIR__ . "/bot_functions.php");


/**
 * Youzign related functions moved to youzign_functions.php
 */



/**
 * Main Message related functions moved to message_functions.php
 */

require_once(__DIR__ . "/flowFunctions.php");
require_once(__DIR__ . "/integrationFunctions.php");
require_once(__DIR__ . "/WordpressDatabaseCollector.php");
require_once(__DIR__ . "/previewFunctions.php");









use DebugBar\StandardDebugBar;

if (CM_ENVIRONEMENT == "dev" || CM_ENVIRONEMENT == "local"  ) {

    $debugbar = new StandardDebugBar();
    $collector = new WordpressDatabaseCollector($wpdb);
    //$debugbar->stackData();
    //$debugbar->sendDataInHeaders();

    $debugbar->addCollector($collector);
    $debugbarRenderer = $debugbar->getJavascriptRenderer("https://cleverstorage.b-cdn.net/Resources/");
}

/**
 * Ran into too many times the same preview for the phone...made it centralized for easy editing in the future
 *

 */
//require_once ("../templates/phone_preview.php");

// Takes input type of all basic message styles by bootstrap *(success/warning/info/danger)*

function ErrorSuccesMessage($content, $type)
{

    $msg = "<div class=\"alert alert-$type\">$content</div>";

    echo $msg;

}


/**
 * Function set Level
 * Used to see if we are at user, page or bot level and thus show the image of the profile or the page
 * And what to show on the sidebar
 */



function smartbot_set_level()
{

    global $wpdb;

    $this_page = basename($_SERVER['PHP_SELF'], '.php');

    if (isset($_SESSION['username'])) {
        $user_name = $_SESSION['username'];

//see if we have the user_id session..if not we make it

        if (!isset($_SESSION["user_id"])) {

//somehow we lost the user_id session

            $user_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_users WHERE user_name=%s", $user_name), ARRAY_A);

            $user_id = $user_details['fb_id'];

            $_SESSION["user_id"] = $user_id;

            $_SESSION['user_profile_pic'] = $user_details['profile_pic'];

            $_SESSION['first_name'] = $user_details['first_name'];

            $_SESSION['last_name'] = $user_details['last_name'];

        }


//which page are we...if it's dashboard or wizard we need to destroy the page/bot sessions

        if ($this_page == 'profile' || $this_page == 'template_blank' || $this_page == 'bot_amazon' ||$this_page == 'bot_shopify' || $this_page == 'bot_blank' || $this_page == 'index') {

            $_SESSION['level'] = 'user';

            unset($_SESSION["page_name"]);

            unset($_SESSION["page_id"]);

            unset($_SESSION["page_cat"]);

            unset($_SESSION["page_desc"]);

            unset($_SESSION["page_image"]);

            unset($_SESSION["page_token"]);

            unset($_SESSION["page_alias"]);

            unset($_SESSION["page_scan_code"]);

            unset($_SESSION["bot_id"]);

            unset($_SESSION["bot_name"]);

            unset($_SESSION["bot_image"]);

            unset($_SESSION["bot_type"]);

            unset($_SESSION["page_profile_image"]);

            unset($_SESSION["auto_responses_status"]);


        } else {

//first use of this or session retrieval. If we have a post or get with the page_id or bot_id then we need to set new sessions..else use what's there

            $bot_id = filter_input(INPUT_POST, 'bot_id');
            if (!isset($bot_id)) {
                $bot_id = filter_input(INPUT_GET, 'bot_id');
            }

            $page_id = filter_input(INPUT_POST, 'page_id');
            if (!isset($page_id)) {
                $page_id = filter_input(INPUT_GET, 'page_id');
            }


            if ($bot_id != "" || $page_id != "") {

                if (isset($page_id) && $page_id != "") {
                    $_SESSION['page_id'] = $page_id;
                    $_SESSION['level'] = 'page';
                }

                if (isset($bot_id) && $bot_id != "") {
                    $_SESSION['bot_id'] = $bot_id;
                    $_SESSION['level'] = 'bot';
                }


                if (!isset($_SESSION['page_name'])) {
                    if (isset($_POST['page_name'])) {
                        $_SESSION['page_name'] = filter_input(INPUT_POST, 'page_name');
                    }
                }

                if (!isset($_SESSION['bot_name'])) {
                    $_SESSION['bot_name'] = filter_input(INPUT_POST, 'bot_name');
                }


//let's see if we have either...if not it means we came here through the wizard and we need to fetch the data and set it


                if ($_SESSION['page_name'] == "" && $_SESSION['bot_name'] == "") {

//we have neither so we need to fetch the data

                    if ($page_id != "") {

                        $page_details = smartbot_get_page_details($page_id);

                        $_SESSION['page_name'] = $page_details['page_title'];

                        $_SESSION['page_cat'] = $page_details['page_category'];

                        $_SESSION['page_desc'] = $page_details['page_desc'];

                        $_SESSION['page_alias'] = $page_details['page_alias'];

                        $_SESSION['page_image'] = $page_details['page_image'];

                        $_SESSION['page_scan_code'] = $page_details['page_scan_code'];

                        $_SESSION['page_token'] = $page_details['page_token'];

                    }


                    if ($bot_id != "") {

                        $bot_details = getBotName($bot_id);

                        $_SESSION['bot_name'] = $bot_details['bot_name'];

                        $_SESSION['bot_page'] = $bot_details['bot_page'];

                        $_SESSION['bot_type'] = $bot_details['bot_type'];

                        $_SESSION['bot_image'] = 'images/card_icons/' . $bot_details["bot_type"] . '.png';

                    }


                } else {


                    if (!isset($_SESSION['bot_page'])) {
                        $_SESSION['bot_page'] = filter_input(INPUT_POST, 'bot_page');
                    }

                    if (!isset($_SESSION['page_cat'])) {
                        $_SESSION['page_cat'] = filter_input(INPUT_POST, 'page_cat');
                    }

                    if (!isset($_SESSION['bot_type'])) {
                        $_SESSION['bot_type'] = filter_input(INPUT_POST, 'bot_type');
                    }

                    if (!isset($_SESSION['page_image'])) {
                        $_SESSION['page_image'] = filter_input(INPUT_POST, 'page_image');
                    }

                    if (!isset($_SESSION['page_token'])) {
                        $_SESSION['page_token'] = filter_input(INPUT_POST, 'page_token');
                    }

                    if (!isset($_SESSION['bot_image'])) {
                        $_SESSION['bot_image'] = filter_input(INPUT_POST, 'bot_image');
                    }

                    if (!isset($_SESSION['page_alias'])) {
                        $_SESSION['page_alias'] = filter_input(INPUT_POST, 'page_alias');
                    }

                    if (!isset($_SESSION['page_scan_code'])) {
                        $_SESSION['page_scan_code'] = filter_input(INPUT_POST, 'page_scan_code');
                    }

                }


                if (isset($_SESSION['page_scan_code']) && $_SESSION['page_scan_code'] != "") {
                    $_SESSION['page_profile_image'] = $_SESSION['page_scan_code'];
                }

                if (empty($_SESSION['page_profile_image'])) {
                    $_SESSION['page_profile_image'] = $_SESSION['page_image'];
                }

                if (empty($_SESSION['page_profile_image'])) {
                    $_SESSION['page_profile_image'] = $_SESSION['bot_image'];
                }

                if (!isset($_SESSION['page_name'])) {
                    $_SESSION['page_name'] = $_SESSION['bot_name'];
                }

                if (!isset($_SESSION['page_cat'])) {
                    $_SESSION['page_cat'] = $_SESSION['bot_type'];
                }


            }
            if (isset($_SESSION['page_cat']) && $_SESSION['page_cat'] != "") {
                $page_cat = $_SESSION['page_cat'];
            } else {
                $page_cat = '';
            }
            if (isset($_SESSION['page_name']) && $_SESSION['page_name'] != "") {
                $page_name = $_SESSION['page_name'];
            } else {
                $page_name = '';
            }
            $profile['profile_name'] = '<strong class="font-bold">' . $page_name . '</strong><br>' . $page_cat;

            if (isset($_SESSION['page_profile_image']) && $_SESSION['page_profile_image'] != "") {
                $profile['profile_image'] = $_SESSION['page_profile_image'];
            }

        }


        if ($this_page == 'profile' || $this_page == 'integrations' || $this_page == 'template_blank' || $this_page == 'bot_amazon' ||$this_page == 'bot_shopify' || $this_page == 'bot_blank' || $this_page == 'faq' || $this_page == 'tips' || $this_page == 'support_desk') {

            $profile['profile_image'] = $_SESSION['user_profile_pic'];

            $profile['profile_name'] = '<strong class="font-bold">' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</strong>';

        }

    }

    if (isset($profile)) {
        return $profile;
    }

}


/**
 * Bunch of Curl related functions. In the end all Curl calls should go through the function smartbot_curl
 */

function smartbot_post_json($url, $post)
{

    $data = smartbot_curl($url, $post, 'post_json_adv');

    return $data;

}

function convertTimeToUserLocalTime($user_id, $timestamp)
{
    global $wpdb;
    $rij = $wpdb->get_row($wpdb->prepare("SELECT timezone FROM smartbot_users WHERE fb_id = %s ", $user_id), ARRAY_A);
    $timezone = $rij['timezone'];
    $daylight = date('I');
    if ($daylight)
        $timestamp -= 7200;
    else
        $timestamp -= 3600;


    $timestamp += ($timezone * 3600);

    return $timestamp;
}


function checkFirstLogin($user_name)
{
    global $wpdb;

    $user_details = $wpdb->get_row($wpdb->prepare("SELECT fb_id FROM smartbot_users WHERE user_name=%s", $user_name), ARRAY_A);
    if (strlen($user_details["fb_id"]) > 0) return 1;
    else return 0;
}

function writeToFile($filename,$data,$mode){
    $fh = fopen($filename, $mode);
    if (!$fh) return 0;
    fwrite($fh, $data);
    fclose($fh);
    return 1;
}


function getStringType($string)
{

    return is_numeric($string) ? 'numeric' : 'string';
}

function smartbot_get_base_url()
{
    $protocol = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') !== false) ? 'https' : 'http';
    $rootPathHtml = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__ . '/'));
    $baseUrl = 'https://' . $_SERVER['HTTP_HOST'] . $rootPathHtml . '/';

    return $baseUrl;
}


function smartbot_post_to_url($url, $post)
{

    $data = smartbot_curl($url, $post, 'post_json');

    return $data;

}


function smartbot_delete_curl($url, $post)
{

    $data = smartbot_curl($url, '', 'delete');

    return $data;

}


function smartbot_curl_get_userpass($url, $username, $password)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURL_HTTP_VERSION_1_1, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    $result = curl_exec($ch);
    return $result;
}

function smartbot_curl_post_userpass_json($url, $data, $user, $pass)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $pass);
    $result = curl_exec($ch);
    return $result;
}

function smartbot_get_xml($url)
{

//error_reporting(0);

    $data = smartbot_get_url($url);

    $xml = @simplexml_load_string($data);

    return $xml;

}


/**
 * Option Functions
 */


/**
 * Get Option Functions
 * Expects the user_id and option_name  the page_id and bot_id are optional
 * outputs the value of an option name for this user
 */

function oauthHandler()
{
    if (!isset($_GET["fbsuccess"])) {
        $_SESSION["after_auth_redirect_url"] = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        header("Location: oauthHandler.php");
    }

}

function smartbot_get_page_owner($page_id)
{

    global $wpdb;

    return $wpdb->get_var($wpdb->prepare("SELECT user_id from smartbot_page_owners where page_id=%s", $page_id));


}

function camelize($input, $separator = '_')
{
    return str_replace($separator, ' ', ucwords(strtolower($input), $separator));
}

function smartbot_get_options($user_id, $page_id, $bot_id, $option)
{
    global $wpdb;
    $this_option = '';

//build the query...do we have an page or bot id filled in or is it a global option
//first..check if we want global page options...so without the user id

    if ($user_id == "" && $page_id != "") {
        $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND page_id=%s", $option, $page_id));
    } else {

        //check if we have an option that has a user in it or not
        if ($user_id == "") {

            if ($page_id != "") {
                $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND  page_id=%s", $option, $page_id));
            }
            if ($bot_id != "") {
                $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND  bot_id=%s", $option, $bot_id));
            }

        } else {

            if ($page_id != "") {
                $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND user_id=%s AND page_id=%s", $option, $user_id, $page_id));
            }
            if ($bot_id != "") {
                $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND user_id=%s AND bot_id=%s", $option, $user_id, $bot_id));
            }
            if ($page_id == "" && $bot_id == "") {
                $this_option = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM smartbot_options WHERE option_name=%s AND user_id=%s", $option, $user_id));
            }

        }
    }

    return $this_option;

}


/**
 * Save Option Functions
 * Expects the user_id, option_name and option_value the page_id and bot_id are optional
 * Checks if this option is already there and thus must be an update instead of an insert
 * No output
 */

function smartbot_save_keys($user_id, $page_id, $bot_id, $option_name, $option_value)
{

    global $wpdb;

    if ($option_name != "" && $option_value != "") {
        if ($page_id == "") {

            $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_options where option_name=%s AND user_id=%s", $option_name, $user_id));
        } else {
            //do we have a global page option maybe...so without the user_id
            if (empty($user_id)) {
                $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_options where option_name=%s AND page_id=%s", $option_name, $page_id));
            } else {
                $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_options where option_name=%s AND user_id=%s AND page_id=%s", $option_name, $user_id, $page_id));
            }
        }

        if ($num_rows > 0) {

            if (empty($user_id) && !empty($page_id)) {
                $wpdb->query($wpdb->prepare("UPDATE smartbot_options SET option_value=%s WHERE option_name=%s AND page_id=%s ", $option_value, $option_name, $page_id));
            } else
                $wpdb->query($wpdb->prepare("UPDATE smartbot_options SET option_value=%s WHERE option_name=%s AND user_id=%s AND page_id=%s ", $option_value, $option_name, $user_id, $page_id));

        } else {

            $wpdb->insert('smartbot_options', array('page_id' => $page_id, 'bot_id' => $bot_id, 'user_id' => $user_id, 'option_name' => $option_name, 'option_value' => $option_value));

        }

    }

}


function smartbot_delete_option($user_id, $option_name)
{
    global $wpdb;
    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_options WHERE option_name=%s AND user_id=%s ", $option_name, $user_id));

}

/**
 * Get Key Values Functions
 * We have an array $item_data_arr and in it we want the values of some keys
 * Builds the key by the msg id, item id and item name and then checks if we have this key in the array
 * Outputs the value of the item_data_arr key if it finds it
 */

function smartbot_get_keyvalues($msg_id, $this_id, $item_name, $item_data_arr)
{
    $this_value = '';
    if (isset($item_data_arr) && is_array($item_data_arr)) {

        $arr_key = $msg_id . '_items[' . $this_id . '][' . $item_name . ']';

        $key = array_search($arr_key, array_column($item_data_arr, 'name'));
        if (isset($item_data_arr[$key]['value'])) {
            $this_value = $item_data_arr[$key]['value'];
        }

    }
    return $this_value;
}


function smartbot_update_read_receipt($pageid, $recipientid, $timestamp)
{
    global $wpdb;

    // $qr = "UPDATE smartbot_ar_msgs_tasks SET read_receipt='1'  WHERE page_id = '$pageid' AND recipient_id='$recipientid' AND read_receipt='0' AND send_time*100<=$timestamp ";
    //sendMail("f.jouti@gmail.com","update receipt",$qr);
    // $wpdb->query($wpdb->prepare("UPDATE smartbot_ar_msgs_tasks SET opened='1',open_time=%d  WHERE page_id = '%s' AND recipient_id='%s' AND opened='0' AND send_time <= %d ",time(),$pageid,$recipientid,$timestamp));
    $wpdb->query($wpdb->prepare("UPDATE sent_elements SET opened=1, open_time = %d  WHERE page_id = '%s' AND profile_id='%s' AND opened=0 AND send_time<=%d ", time(), $pageid, $recipientid, $timestamp));

}


function markOpenedMessage($pageId, $profileId)
{
    global $wpdb;
    require_once __DIR__.'/vendor/autoload.php';

    $bulk = new MongoDB\Driver\BulkWrite(['ordered' => false]);
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    $timestamp = time();
    $results = $wpdb->get_results($wpdb->prepare("SELECT id,flow_id,card_id from sent_messages WHERE page_id = '%s' AND profile_id='%s' AND opened=0 AND send_time<=%d", $pageId, $profileId, $timestamp));

    if (is_array($results)) {
        $idsToUpdate  = implode(',', array_map(function($x) { return $x->id; }, $results));
        if (!empty($idsToUpdate))
            $updateResult = $wpdb->query($wpdb->prepare("UPDATE sent_messages SET opened=1, open_time = %d WHERE id IN ($idsToUpdate) ", time() ));
        else
            $updateResult = false;
    }

    if ($updateResult) {
        foreach ($results as $result) {
            $bulk->update(["flow" => $result->flow_id], ['$inc' => ['data._cards.' . $result->card_id . '._analytics._opens' => 1]], ['multi' => false, 'upsert' => false]);

        }
        try{

            $result = $manager->executeBulkWrite('clevermessenger.flows', $bulk);
            return $result;
        }
        catch (Exception $ex)
        {
            return 0;
        }

    }
    return 0;
}

function markClickedMessage($pageId, $profileId, $cardId,$flowId)
{
    global $wpdb;

    $mongoClient = new MongoDB\Client;
    $collection = $mongoClient->clevermessenger->flows;

    $time = time();

    $results = $wpdb->get_results("SELECT id,flow_id,card_id from sent_messages WHERE (page_id = '$pageId' AND profile_id='$profileId' AND clicked=0 AND card_id='$cardId' AND send_time<=$time) ORDER BY id DESC LIMIT 1");

    if (is_array($results)) {
        $idsToUpdate  = implode(',', array_map(function($x) { return $x->id; }, $results));
        if (!empty($idsToUpdate))
            $wpdb->query("UPDATE sent_messages SET clicked=1, click_time = $time  WHERE  id IN ($idsToUpdate) ");
    }

    foreach ($results as $result){
        $result = $collection->updateOne(["flow" => $flowId],['$inc' =>['data._cards.'.$result->card_id.'._analytics._clicks' =>1]], ['multi' => false, 'upsert' => false]);

    }

    return $result;
}

function registerClickedElement($pageID, $profileID, $msgID, $buttonID)
{
    global $wpdb;

    $wpdb->query($wpdb->prepare("UPDATE sent_elements SET clicked=1, click_time = %d,clicked_element = %d  WHERE page_id = '%s' AND profile_id='%s' AND clicked=0 AND msg_id=%d AND send_time<=%d ", time(), $buttonID, $pageID, $profileID, $msgID, time()));

}


function handleAuhorizedUser()
{
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["page_id"])) {
        die();
    }
}

/**
 * Get time ago from a timestamp Value Functions
 * Expects $datetime input either as date or timestamp
 * outputs the string showing time ago elapsed
 */

function getTimeAgo($date)
{

    if (empty($date)) {
        return "No date provided";
    }
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
    $now = time();
    $unix_date = ($date);
// check validity of date
    if (empty($unix_date)) {
        return "Bad date";
    }
// is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if ($difference != 1) {
        $periods[$j] .= "s";
    }
    if ($tense == "from now")
        return "In $difference $periods[$j]";

    else
        return "$difference $periods[$j] {$tense}";

}


/**
 * Upload File Function
 * Checks for the correct file types and also size. Max at this moment is 10mb
 * Name is prefixed by the user_id
 * Output is echood ...either success or error
 */

function smartbot_upload_file($user_id, $this_file, $msg_type)
{

    global $wpdb;

    if ($this_file["file"]["size"] > 25000000) {
        echo "<span id='invalid'>Invalid file Size<span>";
    } else {

        if (isset($this_file["file"]["type"])) {

            $validextensions = array("jpg", "jpeg", "png", "gif", "pdf", "doc", "docx", "key", "ppt", "pptx", "pps", "ppsx", "odt", "xls", "xlsx", "zip", "mp3", "m4a", "ogg", "wav", "mp4", "m4v", "mov", "wmv", "avi", "mpg", "ogv", "3gp", "3g2");

            //let's check if we do not have ths file already as I don't want to upload the same file over and over again.

            // Name, size and file type if those 3 match identically I assume it's the same file

            //ok we are almost there ...now let see if we are not uploading a file in the wrong format..audio - video is ok but we can not have an image or file in there
            $goodtogo = "yeah";

            $temporary = explode(".", $this_file["file"]["name"]);

            $file_extension = strtolower(end($temporary));

            $validmediaextensions = array("mp3", "m4a", "ogg", "wav", "mp4", "m4v", "mov", "wmv", "avi", "mpg", "ogv", "3gp", "3g2");
            if (($msg_type == "audio" || $msg_type == "video") && !in_array($file_extension, $validmediaextensions)) {

                $goodtogo = "nope";
            }

            $validimageextensions = array("jpg", "jpeg", "png", "gif");
            if (($msg_type == "image" || $msg_type == "simple_image" || $msg_type == "quick" || $msg_type == "carousel" || $msg_type == "structured" || $msg_type == "products" || $msg_type == "list" || $msg_type == "template" || $msg_type == "widget") && !in_array($file_extension, $validimageextensions)) {

                $goodtogo = "nope";
            }

            if ($goodtogo == "yeah") {

                $file_url = $wpdb->get_var($wpdb->prepare("SELECT file_url from smartbot_media where user_id=%s AND file_name=%s AND file_type=%s AND file_size=%s", $user_id, $this_file['file']['name'], $this_file['file']['type'], $this_file['file']['size']));

                if (strlen($file_url) < 1) {


                    if (($this_file["file"]["size"] < 25000000)//Approx. 25mb files can be uploaded.

                        && in_array($file_extension, $validextensions)) {

                        if ($this_file["file"]["error"] > 0) {

                            echo "Return Code: " . $this_file["file"]["error"] . "<br/><br/>|";

                        } else {


                            $sourcePath = $this_file['file']['tmp_name']; // Storing source path of the file in a variable

                            //we seem to have issues with files that have spaces in the file_name..going to replace that with a _
                            $file_name = $this_file["file"]["name"];
                            $file_name = str_replace(' ', '_', $file_name);

                            $targetPath = SB_PATH . 'media/' . $msg_type . '/' . $user_id . '_' . $file_name; // Target path where file is to be stored

                            $file_url = SB_HOME_URL . 'media/' . $msg_type . '/' . $user_id . '_' . $file_name;

                            move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file

                            echo "<span id='success'>File Uploaded Successfully</span><br/>|";

                            echo $file_url . '|' . $msg_type . '|' . $file_name;

                            $now = date("Y-m-d H:i:s");

                            $wpdb->insert('smartbot_media', array('id' => '', 'user_id' => $user_id, 'file_name' => $this_file["file"]["name"], 'file_type' => $this_file["file"]["type"], 'file_size' => $this_file["file"]["size"], 'file_url' => $file_url, 'file_org_name' => $this_file["file"]["name"], 'uploaded_time' => $now));


                        }//end if error

                    } else {

                        echo "<span id='invalid'>Invalid file Size or Type<span>|";

                    }


                } else {

                    //we have this file already...lets just say upload successfully and return the url

                    //$file_url = SB_HOME_URL .'media/'.$msg_type.'/'.$user_id.'_'.$this_file["file"]["name"];


                    echo "<span id='success'>File Uploaded Successfully</span><br/>|";

                    echo $file_url . '|' . $msg_type . '|' . $this_file["file"]["name"];
                    //echo $file_url; not sure who comment out that line above but we need it for the file name

                }
            } else {
                echo "<span id='invalid'>Invalid file Type<span>|";
            }

        }
    }//size is ok
}

function uploadImage($url, $targetPath)
{
    $ch = curl_init($url);
    $fp = fopen($targetPath, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

function smartbot_show_image_library($user_id)
{

    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_media WHERE user_id=%s AND (file_type='image/jpeg' OR file_type='image/png' OR file_type='image/gif')", $user_id), ARRAY_A);

    if (is_array($results)) {

        foreach ($results as $this_result) {

            if ($this_result['file_url'] != "") {

                echo '<div class="image_library_box uploaded_image" data-img_url="' . $this_result['file_url'] . '"><img src="' . $this_result['file_url'] . '" class="img-responsive" /></div>';

            }

        }

    }

}


function smartbot_show_file_library($user_id, $msg_type)
{

    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_media WHERE user_id=%s AND (file_type!='image/jpeg' OR file_type!='image/png' OR file_type!='image/gif')", $user_id), ARRAY_A);

    if (is_array($results)) {

        foreach ($results as $this_result) {

            if ($this_result['file_url'] != "") {

                if (strpos($this_result['file_type'], 'image') === FALSE) {

                    //what type do we want

                    $showthis = 'yeah';

                    if ($msg_type == 'video') {
                        if (strpos($this_result['file_type'], 'video') === FALSE) {
                            $showthis = 'nope';
                        }
                    }

                    if ($msg_type == 'audio') {
                        if (strpos($this_result['file_type'], 'audio') === FALSE) {
                            $showthis = 'nope';
                        }
                    }

                    if ($msg_type == 'file') {

                        if (strpos($this_result['file_type'], 'audio') !== FALSE) {
                            $showthis = 'nope';
                        }

                        if (strpos($this_result['file_type'], 'video') !== FALSE) {
                            $showthis = 'nope';
                        }

                    }

                    if ($showthis == 'yeah') {

                        echo '<div class="file_library_box uploaded_file" data-file_url="' . $this_result['file_url'] . '" data-file_name="' . $this_result['file_name'] . '"><i class="fa icon-file-empty"></i>&nbsp; ' . $this_result['file_name'] . '</div>';

                    }

                }

            }

        }

    }

}

/**
 * Create Unique id Function
 * Used a lot...for bot_id, msg_id or item id. 2 random letters, the date in numbers including the milisecond and a random 3 chyper number
 * Still a small chance there is a duplicate id but almost all is user in combo with user_id which is also unique so the chance is slim to none
 */

function smartbot_create_unique_id()
{

    $dat_num = date("U");

    $rand_let = chr(97 + mt_rand(0, 25));

    $rand_let2 = chr(97 + mt_rand(0, 25));

    $rand_num = rand(100, 999);

    $unique_id = $rand_let . $rand_let2 . $dat_num . $rand_num;

    return $unique_id;

}


function smartbot_update_msg_unique_id($msg_id, $uniqid)
{

    global $wpdb;

    $wpdb->query($wpdb->prepare("UPDATE smartbot_msgs SET msg_uniqid=%s WHERE id=%d", $uniqid, $msg_id));

}


function smartbot_get_msg_unique_id($msg_id)
{

    global $wpdb;

    $msg_uniqid = $wpdb->get_var($wpdb->prepare("SELECT msg_uniqid FROM smartbot_msgs WHERE id=%d", $msg_id));

    return $msg_uniqid;

}


function smartbot_check_email($userdetails, $msg)
{

    global $wpdb;
    $this_email = '';


//reg ex on the message to get emails out of it

    $matches = array();

    $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";

    preg_match_all($pattern, $msg, $matches);

    if (is_array($matches)) {

        $this_email = $matches[0][0];

        if ($this_email != "") {

            $user_id = $userdetails['user_id'];

            $wpdb->query($wpdb->prepare("UPDATE smartbot_profiles SET email=%s WHERE profile_id=%s", $this_email, $user_id));

            //see if we need to add this email to an ar list

            $userdetails['email'] = $this_email;

            smartbot_check_page_ar($userdetails);

        }

    }

    return $this_email;
}


function smartbot_save_media($mediaurl, $type)
{
    $webPath = '';
//according to the latest API docs FB allows profile pic field on FB App based id's...until a few weeks ago it was not so, so making this a seperate function to make sure that all other data is gotten and it will not hang on this profile pic part

    if ($mediaurl != "") {

        $parts = parse_url($mediaurl);

        $str = explode("/", $parts['path']);

        $str = $str[count($str) - 1];

        $rnd = bin2hex(random_bytes(15));


        if (!is_dir(SB_PATH . "media/$type")) {

            // dir doesn't exist, make it

            mkdir(SB_PATH . "media/$type");

        }

        $targetPath = SB_PATH . "media/" . $type . "/" . $rnd . "_" . $str; // Target path where file is to be stored

        $webPath = SB_HOME_URL . "media/" . $type . "/" . $rnd . "_" . $str; // Target path where file is to be stored

        file_put_contents($targetPath, fopen($mediaurl, 'r'));

    }

    return $webPath;
}

function smartbot_check_personalisation_from_db($this_msg, $page_id, $profile_id)
{

    $results = smartbot_get_profile_and_page_details($page_id, $profile_id);

    $this_msg = str_ireplace('[FIRST_NAME]', $results["first_name"], $this_msg);

    $this_msg = str_ireplace('[LAST_NAME]', $results["last_name"], $this_msg);

    $this_msg = str_ireplace('[FULL_NAME]', $results["first_name"] . " " . $results["last_name"], $this_msg);

    $this_msg = str_ireplace('[FIRST]', $results["first_name"], $this_msg);

    $this_msg = str_ireplace('[LAST]', $results["first_name"], $this_msg);

    $this_msg = str_ireplace('[FULL]', $results["first_name"] . " " . $results["last_name"], $this_msg);
    $this_msg = str_ireplace('[PAGE_TITLE]', $results["page_title"], $this_msg);
    $this_msg = str_ireplace('[PAGE_NAME]', $results["page_title"], $this_msg);

    return $this_msg;

}



function smartbot_check_personalisation($this_msg, $page_details, $type, $profile_id)
{

    $this_msg = str_ireplace('[FIRST_NAME]', '{{user_first_name}}', $this_msg);

    $this_msg = str_ireplace('[LAST_NAME]', '{{user_last_name}}', $this_msg);

    $this_msg = str_ireplace('[FULL_NAME]', '{{user_full_name}}', $this_msg);

    $this_msg = str_ireplace('[FIRST]', '{{user_first_name}}', $this_msg);

    $this_msg = str_ireplace('[LAST]', '{{user_last_name}}', $this_msg);

    $this_msg = str_ireplace('[FULL]', '{{user_full_name}}', $this_msg);

    return $this_msg;

}




function smartbot_get_active_campaigns_by_user_from_page($page_id)
{

    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_integration WHERE page_id = %s", $page_id), ARRAY_A);

    if (is_array($results)) {
        return $results;
    }
}


function smartbot_get_active_campaigns_by_user($user_id)
{

    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_integration WHERE user_id = '%s'", $user_id), ARRAY_A);

    if (is_array($results)) {
        return $results;
    }
}


function smartbot_get_active_campaigns_by_page($page_id)
{

    global $wpdb;

    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_integration WHERE page_id = '%s'", $page_id), ARRAY_A);

    if (is_array($results)) {
        return $results;
    }
}

function smartbot_buttons_callback($page_id, $msg_id)
{

    global $wpdb;
    $trigger_button = '';
    $y = '';
    $results = $wpdb->get_results($wpdb->prepare("SELECT smartbot_buttons.id,smartbot_buttons.button_title,smartbot_msgs.msg_name,smartbot_elements.element_name FROM smartbot_buttons,smartbot_msgs,smartbot_elements WHERE button_type='postback' AND smartbot_buttons.msg_id=smartbot_msgs.id AND smartbot_buttons.element_id=smartbot_elements.id AND smartbot_msgs.page_id=%d", $page_id), ARRAY_A);

    if (is_array($results)) {

        $y = 0;

        foreach ($results as $rij) {

            $id = $rij['id'];
            $button_title = $rij['msg_name'] . "-" . $rij['element_name'] . "-" . $rij['button_title'];
            if ($msg_id != $id) {
                if ($y == 0) {
                    $trigger_button = '<select multiple name="trigger_button[]" id="select_trigger_buttonsm">

		                            <option value="' . $id . '"';

                    $trigger_button .= '>' . $button_title . ' &nbsp; &nbsp;</option>';

                } else {

                    $trigger_button .= '<option value="' . $id . '"';

                    $trigger_button .= '>' . $button_title . ' &nbsp; &nbsp; </option>';

                }
            }
            $y++;

        }

        $trigger_button .= '</select><span onclick="clearSelectedButtons();" class="clearButtons">clear</span>';

    }


    if ($y == 0) {
        $trigger_button = 'No buttons with postback actions just yet. You can add them in the structured message section';
    }

    return $trigger_button;
}


function smartbot_get_messages_callback($user_id, $bot_id, $page_id, $msg_id, $msg_type, $button1_msg, $flowid)
{

    global $wpdb;
    $item_id = '';
    $button1 = '';
    $button2 = '';
    $button3 = '';
    $button1_msg = str_replace('direct_', '', $button1_msg);

    if ($page_id != "" || $bot_id != "") {

        //lets get the messages for this page

        if ($msg_type == "live_chat") {

            $flows = smartbot_get_page_flowids($_SESSION["page_id"], true);
            $button1 = '<select id="' . $item_id . 'button1_msg_select" name="' . $item_id . 'button1_msg_select" class="form-control input-lg m-b">';
            foreach ($flows as $flow) {
                $flow_id = $flow->id;
                $flow_name = $flow->name;
                $button1 .= '<optgroup label="' . $flow_name . '">';
                $results = $wpdb->get_results($wpdb->prepare("SELECT id,msg_name,msg_uniqid,msg_type FROM smartbot_msgs WHERE page_id=%d AND flow_id=%d AND msg_type!='typing'", $page_id, $flow_id), ARRAY_A);
                if (is_array($results)) {

                    //we got an array so we can create the select box with the options...but just to be sure lets see if we actually got a message first

                    $x = 0;

                    foreach ($results as $rij) {

                        $this_msg_id = $rij['id'];

                        $this_uniq = $rij['msg_uniqid'];

                        $msg_name = trim($rij['msg_name']);
                        if ($msg_name == "") {
                            $msg_name = smartbot_get_msgname($rij['msg_type']);
                        }

                        if ($this_msg_id > 0 && $msg_id != $this_uniq) {

                            $button1 .= '<option value="' . $this_msg_id . '"';
                            if ($this_msg_id == $button1_msg) {
                                $button1 .= ' selected="selected"';
                            }
                            $button1 .= '>' . $msg_name . '</option>';

                            $x++;

                        }

                    }//end of for each...lets check if $x > 0 so we know we have results

                }//end array is results


                $button1 .= '</optgroup>';
            }

        } else {

            $results = $wpdb->get_results($wpdb->prepare("SELECT id,msg_name,msg_uniqid,msg_type FROM smartbot_msgs WHERE page_id=%d AND flow_id=%d AND msg_type!='typing'", $page_id, $flowid), ARRAY_A);

            $button1 = '<select id="' . $item_id . 'button1_msg_select" name="' . $item_id . 'button1_msg_select" class="form-control input-lg m-b">';

            $button2 = '<select id="' . $item_id . 'button2_msg_select" name="' . $item_id . 'button2_msg_select" class="form-control input-lg m-b">';

            $button3 = '<select id="' . $item_id . 'button3_msg_select" name="' . $item_id . 'button3_msg_select" class="form-control input-lg m-b">';

            if ($msg_type != "direct") {

                $button1 .= '<option value="">Select a Message</option>';

                //$button2.='<option value="">Select a Message</option>';

                //$button3.='<option value="">Select a Message</option>';

            }

            if (is_array($results)) {

                //we got an array so we can create the select box with the options...but just to be sure lets see if we actually got a message first

                $x = 0;

                foreach ($results as $rij) {

                    $this_msg_id = $rij['id'];

                    $this_uniq = $rij['msg_uniqid'];

                    $msg_name = trim($rij['msg_name']);
                    if ($msg_name == "") {
                        $msg_name = smartbot_get_msgname($rij['msg_type']);
                    }

                    if ($this_msg_id > 0 && $msg_id != $this_uniq) {

                        $button1 .= '<option value="' . $this_msg_id . '"';
                        if ($this_msg_id == $button1_msg) {
                            $button1 .= ' selected="selected"';
                        }
                        $button1 .= '>' . $msg_name . '</option>';

                        //$button2.='<option value="'.$this_msg_id.'"'; if($this_msg_id==$button1_msg){$button2.=' selected="selected"';} $button2.='>'.$msg_name.'</option>';

                        //$button3.='<option value="'.$this_msg_id.'"'; if($this_msg_id==$button1_msg){$button3.=' selected="selected"';} $button3.='>'.$msg_name.'</option>';

                        $x++;

                    }

                }//end of for each...lets check if $x > 0 so we know we have results

            }//end array is results


            if ($msg_type != "live_chat") {

                //for a moment disabling this...code is 90% there though
                // $button1.='<option></option><option value="new">New Message</option><option></option>';

                // $button2.='<option></option><option value="new">New Message</option><option></option>';

                // $button3.='<option></option><option value="new">New Message</option><option></option>';


                $button1 .= '<option value="">----Actions below for Subscribing or Unsubscribing---</option>';

                // $button2.='<option value="">----Actions below for Subscribing or Unsubscribing---</option>';

                // $button3.='<option value="">----Actions below for Subscribing or Unsubscribing---</option>';

                $button1 .= '<option value="subscribe">Subscribe Action</option>';

                // $button2.='<option value="subscribe">Subscribe Action</option>';

                // $button3.='<option value="subscribe">Subscribe Action</option>';

                $button1 .= '<option value="unsubscribe">Unsubscribe Action</option>';

                // $button2.='<option value="unsubscribe">Unsubscribe Action</option>';

                // $button3.='<option value="unsubscribe">Unsubscribe Action</option>';

            }

            $button1 .= '</select>|';

            $button2 .= '</select>|';

            $button3 .= '</select>';
        }
        echo $button1 . $button2 . $button3; //just to be nice..I could have echod them directly before this

    }

    die();

}

function smartbot_select_msg($page_id, $msg_id)
{
    global $wpdb;
    $options = "";
    $results = $wpdb->get_results($wpdb->prepare("SELECT id,msg_name,msg_uniqid,msg_type FROM smartbot_msgs WHERE page_id=%d AND msg_type!='typing' ORDER BY msg_name", $page_id), ARRAY_A);
    if (is_array($results)) {

        foreach ($results as $rij) {

            $this_msg_id = $rij['id'];

            $this_uniq = $rij['msg_uniqid'];
            if ($this_uniq == $msg_id) {
                $this_uniq .= '"  selected="selected"';
            }

            $msg_name = trim($rij['msg_name']);
            if ($msg_name == "") {
                $msg_name = smartbot_get_msgname($rij['msg_type']);
            }

            if ($this_msg_id > 0) {

                $options .= '<option value="' . $this_uniq . '">' . $msg_name . '</option>';

            }

        }

    }//end array is results
    return $options;
}




function smartbot_clone_msgs($results, $current_page, $user_id, $new_flow_id)
{
    global $wpdb;
    $newresults = array();
    $msgs_arr = array();
    if (isset($results) && is_array($results)) {
        //lets create an array to store the original msg_id's and the new ones

        foreach ($results as $this_msg) {

            $msg_id = $this_msg['id'];
            $msg_name = $this_msg['msg_name'];
            $msg_type = $this_msg['msg_type'];
            $flow_id = $this_msg['flow_id'];

            $msg = $this_msg['msg'];
            $last_check = $this_msg['last_check'];
            $msg_uniqid = smartbot_create_unique_id();
            $builder_top = $this_msg['builder_top'];
            $builder_left = $this_msg['builder_left'];

            $wpdb->insert('smartbot_msgs', array('page_id' => $current_page,
                'user_id' => $user_id,
                'msg_type' => $msg_type,
                'msg_name' => $msg_name,
                'msg' => $msg,
                'builder_top' => $builder_top,
                'builder_left' => $builder_left,
                'msg_uniqid' => $msg_uniqid,
                'flow_id' => $new_flow_id
            ));

            $this_msg_id = $wpdb->insert_id;

            if ($this_msg_id == "") {
                $rij = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_msgs WHERE page_id=%d AND msg_uniqid=%s", $current_page, $msg_name), ARRAY_A);
                $this_msg_id = $rij['id'];
            }
            $msgs_arr[$msg_id] = $this_msg_id;
            $newresults[] = $this_msg_id;
            //now lets add any elements, triggers and buttons belonging to the message

            $elem_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_elements WHERE msg_id=%s", $msg_id), ARRAY_A);

            if (is_array($elem_results)) {

                foreach ($elem_results as $this_element) {

                    $item_id = $this_element['item_id'];
                    $element_name = $this_element['element_name'];
                    $element_title = $this_element['element_title'];
                    $element_subtitle = $this_element['element_subtitle'];
                    $item_url = $this_element['item_url'];
                    $img_url = $this_element['img_url'];
                    $element_order = $this_element['element_order'];
                    $last_check = $this_element['last_check'];

                    $wpdb->insert('smartbot_elements', array('msg_id' => $this_msg_id,
                        'item_id' => $item_id,
                        'element_name' => $element_name,
                        'element_title' => $element_title,
                        'element_subtitle' => $element_subtitle,
                        'item_url' => $item_url,
                        'img_url' => $img_url,
                        'element_order' => $element_order,
                        'page_id' => $current_page
                    ));

                }

            }

            $button_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_buttons WHERE msg_id=%s", $msg_id), ARRAY_A);

            if (is_array($button_results)) {

                foreach ($button_results as $this_button) {

                    $element_id = $this_button['element_id'];
                    $button_type = $this_button['button_type'];
                    $button_payload = $this_button['button_payload'];
                    $button_name = $this_button['button_name'];
                    $button_title = $this_button['button_title'];
                    $button_order = $this_button['button_order'];

                    $wpdb->insert('smartbot_buttons', array('id' => '', 'msg_id' => $this_msg_id, 'page_id' => $current_page, 'element_id' => $element_id, 'item_id' => $element_id, 'button_type' => $button_type, 'button_payload' => $button_payload, 'button_name' => $button_name, 'button_title' => $button_title, 'button_order' => $button_order));
                }

            }

            $trigger_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_triggers WHERE msg_id=%s", $msg_id), ARRAY_A);

            if (is_array($trigger_results)) {

                foreach ($trigger_results as $this_trigger) {

                    $trigger_type = $this_trigger['trigger_type'];
                    $this_trigger_keyword = $this_trigger['trigger_keyword'];
                    $trigger_neg_keyword = $this_trigger['trigger_neg_keyword'];
                    $link_color = $this_trigger['link_color'];
                    $builder_top = $this_trigger['builder_top'];
                    $builder_left = $this_trigger['builder_left'];

                    $wpdb->insert('smartbot_triggers', array('id' => '', 'msg_id' => $this_msg_id, 'page_id' => $current_page, 'trigger_type' => $trigger_type, 'trigger_keyword' => $this_trigger_keyword, 'trigger_neg_keyword' => $trigger_neg_keyword, 'link_color' => $link_color, 'builder_top' => $builder_top, 'builder_left' => $builder_left));

                }

            }

        }
        //now we have had all the msgs and we have the id's in the array msgs_arr ...means now we can create links if needed

        $link_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_msg_block_items WHERE flow_id=%s", $flow_id), ARRAY_A);
        if (isset($link_results) && is_array($link_results)) {
            //ok so we have links in the db for this flow...now lets do some magic here
            foreach ($link_results as $this_link) {
                $org_link_from = $this_link['msg_from'];
                $org_link_to = $this_link['msg_to'];
                $link_color = $this_link['link_color'];
                $new_from = $msgs_arr[$org_link_from];
                $new_to = $msgs_arr[$org_link_to];
                if ($new_from != "" && $new_to != "") {
                    $wpdb->insert('smartbot_msg_block_items', array('id' => '', 'msg_from' => $new_from, 'msg_to' => $new_to, 'link_color' => $link_color, 'page_id' => $current_page, 'flow_id' => $new_flow_id));
                }
            }
        }

    }//end if is set results
    //ok lets clean up this mess for a moment. The buttons are having as payload in the case of a direct link the old message. So we need to run over all the new_msg's and fix this

    if (is_array($newresults)) {
        foreach ($newresults as $this_msg) {
            $button_results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_buttons WHERE msg_id=%s", $this_msg), ARRAY_A);

            if (is_array($button_results)) {
                foreach ($button_results as $this_button) {
                    $button_id = $this_button['id'];
                    $button_payload = $this_button['button_payload'];
                    $button_type = $this_button['button_type'];

                    if ($button_type == "postback") {
                        $button_payload = str_replace('direct_', '', $button_payload);
                        $new_payload = $msgs_arr[$button_payload];
                        if ($new_payload != "") {
                            $button_payload = 'direct_' . $new_payload;
                            $wpdb->query($wpdb->prepare("UPDATE smartbot_buttons SET button_payload=%s WHERE id=%s", $button_payload, $button_id));
                        }
                    }
                }
            }
        }
    }

    return $msgs_arr;
}

/**
 * Giphy Integration
 * Search Giphy for gif's. Expecting a variable $keyword
 */

function smartbot_search_giphy($keyword, $offset)
{
    $giphy_keyword = urlencode(trim($keyword));
    $image_result = '';
    $url = 'http://api.giphy.com/v1/gifs/search?q=' . $giphy_keyword . '&api_key=dc6zaTOxFJmzC&offset=' . $offset;
    $x = 0;
    $result = smartbot_get_url($url);

    if ($result != "") {

        $data_raw = json_decode($result, TRUE);

        $data = $data_raw['data'];
        $pagination = $data_raw['pagination'];
        // I rather read an ARRAY_A then Json :)

        foreach ($data as $this_image) {

            $image_url = $this_image['images']['fixed_height']['url'];

            $image_id = $this_image['id'];

            $image_result .= '<img src="' . $image_url . '" class="giphy_image" id="' . $image_id . '">';
            $x++;
        }
        if ($pagination['total_count'] == 0) {
            $image_result = 'No results for ' . $giphy_keyword;
        }
        if ($pagination['total_count'] > $offset) {
            $image_result .= '<div style="clear: both"></div><div id="giphy_pagination">';
            if ($offset > 0) {
                $previous_giphy = $offset - 25;
                $image_result .= '<span class="search_giphy btn btn-primary" data-offset="' . $previous_giphy . '">Previous</span>';
            }
            $next_giphy = $offset + 25;
            $image_result .= '<span class="search_giphy btn btn-primary" data-offset="' . $next_giphy . '">Next</span>';
            $image_result .= '</div>';
        }
    }

    return $image_result;

}


//cron




function detectLoop($data, $linkData)
{
    $data = json_decode($data);
    $linkData = json_decode($linkData);

    $size = count(get_object_vars($data->links));

    if ($size > 0) {
        $operators = [];
        $graphs = [];
        foreach ($data->operators as $operator) {
            $operators[$operator->properties->uniqid] = new stdClass();
            $tmp = new stdClass();
            $tmp->name = $operator->properties->uniqid;
            $tmp->type = $operator->properties->msgtype;
            $operators[$operator->properties->uniqid] = $tmp;
        }

        foreach ($operators as $element) {
            if ($element->type == "quick" || $element->type == "buttons" || $element->type == "list" || $element->type == "products" || $element->type == "structured" || $element->type == "welcome")
                continue;
            $currentRabit = $element->name;
            $result = findConnection($data->links, $currentRabit);
            while ($result) {
                $currentRabit = $result;

                if (!isset($graphs[$element->name])) $graphs[$element->name] = [];
                $graphs[$element->name][] = $result;
                $result = findConnection($data->links, $currentRabit);
                if ($result == false) break;
                $ntype = $operators[$result]->type;
                if ($ntype == "quick" || $ntype == "buttons" || $ntype == "list" || $ntype == "products" || $ntype == "structured" || $ntype == "welcome")
                    break;
            }

        }

        if (isset($graphs[$linkData->toOperator])) {
            $arrlen = count($graphs[$linkData->toOperator]);

            if ($arrlen) {
                if ($graphs[$linkData->toOperator][$arrlen - 1] == $linkData->fromOperator) {

                    return 1;
                } else
                    return 0;
            }
        }
    }
    return 0;
}

function findConnection($links, $from)
{

    foreach ($links as $link) {


        if ($link->fromOperator == $from) {
            $toOperator = $link->toOperator;
            return $toOperator;
        }


    }
    return false;

}


function smartbot_insert_broadcast($page_id, $msg_title, $msg_text, $time_travel, $tags, $type, $time, $pageid)
{
    global $wpdb;

    $tags = json_decode($tags);
    $gender = array();
    $language = array();
    $custom_tags = array();
    foreach ($tags as $tag) {
        if ($tag->type == "gender") $gender[] = $tag;
        else if ($tag->type == "custom") $custom_tags[] = $tag;
        else if ($tag->type == "language") $language[] = $tag;
    }

    $i = 0;
    foreach ($gender as $g) {
        if ($i++ == 0)
            $genderWhere = "gender = '$g->value' ";
        else
            $genderWhere .= " or gender = '$g->value' ";

    }

    $i = 0;
    foreach ($language as $g) {
        if ($i++ == 0)
            $languageWhere = "locale = '$g->value' ";
        else
            $languageWhere .= " or locale = '$g->value' ";

    }

    $i = 0;
    foreach ($custom_tags as $g) {
        if ($i++ == 0)
            $customWhere = "tag_id = '$g->value' ";
        else
            $customWhere .= " or tag_id = '$g->value' ";

    }

    $basicQuery = "SELECT profile_id from smartbot_profiles WHERE page_id = '$pageid'";
    $now = $time - 86400;
    $now2 = time() - 86400;
    if ($type == "promotional") $basicQuery = "$basicQuery AND (last_contact >= $now ) ";
    else if ($type == "followup") $basicQuery = "$basicQuery AND (last_contact>=$now2 AND followup = 2 ) ";
    if (isset($genderWhere) && isset($languageWhere)) $basicQuery .= " AND ($genderWhere) AND ($languageWhere)";
    else if (isset($genderWhere)) $basicQuery .= " AND ($genderWhere) ";
    else if (isset($languageWhere)) $basicQuery .= " AND ($languageWhere) ";

    if (isset($customWhere)) $basicQuery = "SELECT profile_id FROM  smartbot_tag_profiles WHERE profile_id in ($basicQuery) AND ($customWhere)  ";


    $finalquery = "SELECT profile_id,first_name,last_name,profile_pic FROM smartbot_profiles WHERE profile_id in ($basicQuery)";
    $results = $wpdb->get_results($finalquery, ARRAY_A);
    return json_encode($results);
}


function checkNeedRefresh($pageDetails)
{
    global $wpdb;
    $pageId = $pageDetails['id'];
    $needRefresh = $wpdb->get_var($wpdb->prepare("SELECT need_refresh FROM smartbot_pages WHERE page_id=%s", $pageId));
    if ($needRefresh == 1) {
        $pageToken = $pageDetails['access_token'];
        $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET need_refresh='',page_token=%s WHERE page_id=%s", $pageToken, $pageId));
    }
}

function checkActiveOrPaused($pageId)
{
    global $wpdb;
    $state = '';
    if ($pageId != "") {
        $state = $wpdb->get_var($wpdb->prepare("SELECT active_page FROM smartbot_pages WHERE page_id=%s", $pageId));
    }
    return $state;
}

function pauseResponses($pageId)
{
    global $wpdb;
    $result = 0;
    if ($pageId != "") {
        $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET active_page=2 WHERE page_id=%s", $pageId));
        $result = 1;
    }
    return $result;
}

function activatePausedResponses($pageId)
{
    global $wpdb;
    $result = 0;
    if ($pageId != "") {
        $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET active_page=1 WHERE page_id=%s", $pageId));
        $result = 1;
    }
    return $result;
}

function getFileSize($url)
{
    $headers = get_headers($url, TRUE);
    $fileSize = $headers['Content-Length'];
    return $fileSize;
}

function pageSpecificPages($thisPage)
{
    $pageLevel = 0;
    if (
        $thisPage == "ai_triggers" ||
        $thisPage == "autoposting" ||
        $thisPage == "bot_instant_reply" ||
        $thisPage == "audience" ||
        $thisPage == "segmentation" ||
        $thisPage == "dashboard" ||
        $thisPage == "default_reply" ||
        $thisPage == "edit_keyword" ||
        $thisPage == "lead_catchers" ||
        $thisPage == "widget_builder" ||
        $thisPage == "checkbox_builder" ||
        $thisPage == "widgets" ||
        $thisPage == "live_chat" ||
        $thisPage == "livechat" ||
        $thisPage == "manage" ||
        $thisPage == "main_menu" ||
        $thisPage == "main_menu_list" ||
        $thisPage == "messenger_button" ||
        $thisPage == "messenger_chat" ||
        $thisPage == "chatwidget_builder" ||
        $thisPage == "messenger_checkbox" ||
        $thisPage == "schedule_broadcast" ||
        $thisPage == "sent" ||
        $thisPage == "sent_message" ||
        $thisPage == "view_broadcast" ||
        $thisPage == "sequence" ||
        $thisPage == "scheduled" ||
        $thisPage == "ref_library" ||
        $thisPage == "messenger_code" ||
        $thisPage == "messenger_code_builder" ||
        $thisPage == "json_library" ||
        $thisPage == "visual" ||
        $thisPage == "composer" ||
        $thisPage == "view_flow" ||
        $thisPage == "flows" ||
        $thisPage == "welcome" ||
        $thisPage == "analytics" ||
        $thisPage == "stats" ||
        $thisPage == "customfields" ||
        $thisPage == "page_agency" ||
        $thisPage == "post_engagement_builder" ||
        $thisPage == "post_engagement" ||
        $thisPage == "segment_builder" ||
        $thisPage == "customerchat_builder" ||
        $thisPage == "templates" ||
        $thisPage == "share_template" ||
        $thisPage == "manage_template_urls" ||
        $thisPage == "template_installed_overview" ||
        $thisPage == "installed_templates" ||
        $thisPage == "tags_manager" ||
        $thisPage == "global_fields" ||
        $thisPage == "integrations_manager") {
        $pageLevel = 1;
    }
    return $pageLevel;
}