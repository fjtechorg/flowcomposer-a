<?php
/**
 * All main Facebook Page related functions like gettng the details as name, token, page likes, page posts etc are here
 * The specific functions for building the messages are not here but in their own function files
 * Todo: move returned html from the function smartbot_manage_campaigns to templates folder
 */

/**
 * Get Subscribers Function
 * Used on the Dashboard to show the total and active subscribers
 * Output is active subs | total subs as a string
 */
function smartbot_get_subscribers($pageId)
{
    $these_subs = '';

    if (isset($pageId)) {
        global $wpdb;
        $now = time() - 86400;
        $active_subs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(distinct profile_id) FROM smartbot_profiles WHERE page_id=%s AND profile_id!='' AND last_contact >=$now  AND subscribe!='0'", $pageId));
        $total_subs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(distinct profile_id) FROM smartbot_profiles WHERE page_id=%s AND profile_id!=''", $pageId));
        $last_q = $wpdb->last_query;
        $unsubs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(distinct profile_id) FROM smartbot_profiles WHERE page_id=%s AND profile_id!='' AND subscribe='0'", $pageId));
        $total_subs = $total_subs - $unsubs;
        $these_subs = $active_subs . '|' . $total_subs . '|' . $unsubs . '|' . $last_q;
    }
    return $these_subs;
}


function deletePage($pageID)
{
    global $wpdb;
    $ownerCount = getPageOwnerCount($pageID);
    if ($ownerCount === 1) {
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_pages WHERE page_id=%s", $pageID));
      //  $wpdb->query($wpdb->prepare("DELETE FROM smartbot_options WHERE page_id=%s AND user_id=%s", $pageID, $userID));
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_tags WHERE page_id=%s", $pageID));


    }

}



function addPageAdmin($pageId,$profileId, $userId)
{
    global $wpdb;

    $array = array("page_id"=>$pageId,"profile_id"=>$profileId,"user_id"=>$userId);
    $result = $wpdb->insert("page_admins",$array);
    return $result;

}

/**
 * Facebook Page Image Function
 * expects variables  $pageId and $pageToken
 * fetches the profile image of the Facebook page
 * returns a variable with the url of the image
 */
function smartbot_get_page_image($pageId, $pageToken)
{
    $url = 'https://graph.facebook.com/v2.8/' . $pageId . '/picture?fields=url&access_token=' . $pageToken . '&redirect=false';
    $data = smartbot_get_url($url);
    $fbResponse = json_decode($data, true);
    if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
        errorHandling($fbResponse, $pageId);
        return '';
    } else {
        $imgdata = json_decode($data, true);
        $file_url = SB_HOME_URL . 'media/' . $pageId . '_profile.png';
        $targetPath = SB_PATH . 'media/' . $pageId . '_profile.png';
        //saving the image locally
        uploadImage($imgdata['data']['url'], $targetPath);
    }
    return $file_url;
}

function checkAccessToken($redirect)
{

    $fb = new Facebook\Facebook([
        'app_id' => SB_FB_APP,
        'app_secret' => SB_FB_SECRET,
        'default_graph_version' => 'v2.8'
    ]);

    $helper = $fb->getRedirectLoginHelper();
    try {
        $accessToken = $helper->getAccessToken($redirect);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // There was an error communicating with Graph
        return $e->getMessage();
    }

    if (isset($accessToken)) {
        // User authenticated your app!
        // Save the access token to a session and redirect
        $_SESSION['facebook_access_token'] = (string)$accessToken;
        // Log them into your web framework here . . .
        return $accessToken;
    } elseif ($helper->getError()) {
        // The user denied the request
        return 0;
    }

// If they've gotten this far, they shouldn't be here
    return 0;

}

function getFacebookLoginURL($redirect)
{
    $fb = new Facebook\Facebook(array(
        'app_id' => SB_FB_APP,
        'app_secret' => SB_FB_SECRET,
        'default_graph_version' => 'v2.8',
    ));

    $helper = $fb->getRedirectLoginHelper();

    if (CM_ENVIRONEMENT === "local" || CM_ENVIRONEMENT=== "dev") {
        $permissions = array('email,manage_pages,pages_messaging,pages_messaging_subscriptions,business_management,read_page_mailboxes,read_insights,publish_pages'); // Optional permissions
    } else
        $permissions = array('email,manage_pages,pages_messaging,pages_messaging_subscriptions,read_page_mailboxes,read_insights'); // Optional permissions
    $loginUrl = $helper->getLoginUrl($redirect, $permissions);
    return $loginUrl;
}


function smartbot_get_page_cover($pageId, $pageToken = false)
{
    global $wpdb;
    if (!$pageToken) {
        $pageToken = $wpdb->get_var($wpdb->prepare("SELECT page_token FROM smartbot_pages WHERE page_id=%s", $pageId));
    }

    if ($pageToken != "" && $pageId != "") {
        $url = 'https://graph.facebook.com/v2.8/' . $pageId . '?fields=cover&access_token=' . $pageToken;
        $data = smartbot_get_url($url);
        if (isset($data)) {
            $fbResponse = json_decode($data, true);
            if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
                errorHandling($fbResponse, $pageId);
                return 0;
            } else {
                //$imgdata = json_decode($data, true);
                $imgUrl = $fbResponse['cover']['source'];

                if ($imgUrl != "") {
                    $fileUrl = SB_HOME_URL . 'media/' . $pageId . '_cover.png';
                    $targetPath = SB_PATH . 'media/' . $pageId . '_cover.png';
                    //saving the image locally
                    uploadImage($imgUrl, $targetPath);
                    $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET page_cover=%s WHERE page_id=%s", $fileUrl, $pageId));
                    return $fileUrl;
                }
                return 0;
            }
        }
    }
    return 0;
}

/**
 * Facebook Page Likes Function
 * expects variables  $pageId and $pageToken
 * fetches the number of likes of the Facebook page
 * returns a variable with that number
 */
function smartbot_get_page_likes($pageId, $pageToken)
{
    $fanCount = '';
    $url = 'https://graph.facebook.com/v2.8/' . $pageId . '?fields=fan_count&access_token=' . $pageToken;
    $data = smartbot_get_url($url);
    $fbResponse = json_decode($data, true);
    if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
        errorHandling($fbResponse, $pageId);
    } else {
        //$fanData   = json_decode( $data, true );
        $fanCount = $fbResponse['fan_count'];
    }
    return $fanCount;
}

/**
 * Facebook Page Posts Function
 * expects variables  $pageId and $pageToken
 * fetches the number of posts of the Facebook page
 * returns a variable with that number
 */
function smartbot_get_fb_posts($pageId, $userId, $excludedPosts, $after = false, $limit = 9)
{
//getting the token first

    $page_details = smartbot_get_page_details($pageId);
    $pageToken = $page_details['page_token'];
    if ($after) {
        $loadmore = 1;
        $url = json_decode($after);
    } else {
        $loadmore = 0;
        $url = 'https://graph.facebook.com/' . $pageId . '/feed?fields=message,created_time,attachments{subattachments,media},backdated_time,description&limit=' . $limit . '&access_token=' . $pageToken;
    }

    file_get_contents("https://webhook.site/1555fe63-c91f-4e03-9c49-a75666ba4b05&url=$url");

    $show_posts = $url;
    $data = smartbot_get_url($url);
    $fbResponse = json_decode($data, true);
    if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
        errorHandling($fbResponse, $pageId);
        return 0;
    } else {


        if (isset($fbResponse) && is_array($fbResponse)) {

            $show_posts .= 'Success, retrieved the posts from this page, please click <strong>Next</strong> below|';
            $show_posts .= postTemplateFormatter($fbResponse, $excludedPosts,$after);

            if (isset($fbResponse['paging']) && isset($fbResponse['paging']['next'])) {
                $show_posts .= "|" . json_encode($fbResponse['paging']['next']);
            } else {
                $show_posts .= "|";
            }

            return $show_posts;
        } else {
            return 'Error, no posts retrieved from this page';
        }

    }
}


function smartbot_get_page_posts($pageId, $pageToken)
{
    $url = 'https://graph.facebook.com/' . $pageId . '/feed?limit=100&access_token=' . $pageToken;
    $data = smartbot_get_url($url);
    $fbResponse = json_decode($data, true);
    if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
        errorHandling($fbResponse, $pageId);
        return 0;
    }
//$pagedata = json_decode($data , true);
    $posts = $fbResponse['data'];
    $post_count = count($posts);
    return $post_count;
}


/**
 * Facebook Check Pages Function
 * expects variable $userId and token and checks if this user has pages and loops trhough them
 * checks if this page is already in our db table smartbot_pages
 * returns an array with the select query of this page on our table after the insert
 */
function smartbot_check_fb_pages($userId, $userToken, $url)
{
    $newurl = '';
    $data = smartbot_get_url($url);
    $result = json_decode($data, true);
    if (isset($result)) {
        foreach ($result["data"] as $thisPage) {
            smartbot_check_page($thisPage, $userId);
        }
        //do we maybe have more then 100 results? lets see if Next is there
        if ($result["paging"]["next"] != "") {
            $newurl = $result["paging"]["next"];
            if ($newurl != "") {
                smartbot_check_fb_pages($userId, $userToken, $newurl);//we are going into the loop again...with this new url
            }
        }
    }
}

/**
 * Facebook Check Page Function
 * expects array $page and variable $userId
 * checks if this page is already in our db table smartbot_pages
 * returns an array with the select query of this page on our table after the insert
 */
function smartbot_check_page($pageId, $userId, $userIndexId=false)
{
    if($userIndexId===false){
        $userIndexId = $_SESSION['user']['id'];
    }
    if(isset($_SESSION["temp_page_tokens"][$pageId])){
        $pageToken = $_SESSION["temp_page_tokens"][$pageId];
        $pageDetails = requestFbPageDetails($pageId, $pageToken);
        $page = (array)$pageDetails;
        $page['access_token'] = $pageToken;
        unset($_SESSION["temp_page_tokens"]);
    }
    else{
        return false;
    }
    global $wpdb;
    $pageAlias = '';
    $requestLog = SB_PATH . "logs/request.log";
    $pageId = $page['id'];
    $rij = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_pages WHERE page_id=%s ", $pageId), ARRAY_A);
    $lastQ = $wpdb->last_query;
    $id = $rij['id'];
    $_SESSION['page_token'] = $pageToken;
    smartbot_subscribe_app_page($pageToken,$pageId);
    $pagePicUrl = 'https://graph.facebook.com/' . $page['id'] . '/picture?type=normal';
    //store image in cdn and get cdn url
    $pagePicUrl = smartbot_profile_pic($pagePicUrl, $userId, $page['id']);

    if(isset($page['cover']->source)) {
        $pageCoverUrl = $page['cover']->source;
        $imgUrl = $pageCoverUrl;
        if ($imgUrl != "") {
            $fileUrl = SB_HOME_URL . 'media/' . $pageId . '_cover.png';
            $targetPath = SB_PATH . 'media/' . $pageId . '_cover.png';
            //saving the image locally
            uploadImage($imgUrl, $targetPath);
            $pageCoverUrl = $fileUrl;
        }
    }
    else{
        $pageCoverUrl = '';
    }


    if ($id == "") {
        //$page_image=smartbot_get_page_image($pageId,$pageToken);
        if (!isset($page['username'])) {
            $page['username'] = '';
        } else {
            $pageAlias = $page['username'];
        }
        if (!isset($page['about'])) {
            $page['about'] = '';
        }
        if (!isset($page['category'])) {
            $page['category'] = '';
        }
        $wpdb->insert('smartbot_pages', array('id' => '', 'user_id' => $userId, 'page_image' => $pagePicUrl, 'page_cover' => $pageCoverUrl, 'page_id' => $pageId, 'page_title' => $page['name'], 'page_token' => $page['access_token'], 'page_category' => $page['category'], 'page_desc' => $page['about'], 'page_alias' => $page['username']));
        $wpdb->insert('smartbot_page_owners', array('id' => '', 'user_id' => $userId, 'user_index_id' => $userIndexId, 'page_id' => $pageId, 'active_page' => '0'));
    } else {
        //$image=smartbot_get_page_image($pageId,$pageToken);
        //update the table and add the image & alias

        $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET page_token=%s,need_refresh='',page_image=%s WHERE id=%d", $page['access_token'],$pagePicUrl, $id));
        //$wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET need_refresh='' WHERE id=%d", $id));
        $wpdb->query($wpdb->prepare("DELETE FROM error_codes WHERE page_id=%s AND error_code='190'", $pageId));
        //page -> owner
        $numRows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_page_owners WHERE page_id=%s AND user_index_id=%s", $pageId, $userIndexId));
        if ($numRows < 1) {
            $wpdb->insert('smartbot_page_owners', array('id' => '', 'user_id' => $userId, 'user_index_id' => $userIndexId, 'page_id' => $pageId, 'active_page' => '0'));
        }

        if (isset($page['username'])) {
            $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET page_alias=%s WHERE id=%d", $page['username'], $id));
            $pageAlias = $page['username'];
        }

        if (isset($page['about'])) {
            $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET page_desc=%s WHERE id=%d", $page['about'], $id));
        }

    }


    $pageScanCode = $rij['page_scan_code'];
    if ($pageScanCode === NULL) {
        smartbot_check_messenger_code($pageId, $page['access_token']);
    }


    return $pageAlias;
}


function smartbot_get_alias($pageId)
{
    global $wpdb;
    $alias = '';
    if (isset($pageId) && $pageId != "") {
        $alias = $wpdb->get_var($wpdb->prepare("SELECT page_alias FROM smartbot_pages WHERE page_id=%s", $pageId));
    }
    return $alias;
}


/**
 * Facebook Get Page Details Function
 * expects variable $pageId and variable $userId
 * checks if this page is already in our db table smartbot_pages
 * returns an array with the select query of this page
 */
function smartbot_get_page_details($pageId)
{
    global $wpdb;
    $thisPage = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_pages WHERE page_id=%d ", $pageId), ARRAY_A);
    return $thisPage;
}



function updatePageNeedRefresh($pageID, $needRefresh)
{
    global $wpdb;
    $result = $wpdb->update("smartbot_pages", ["need_refresh" => $needRefresh], ["page_id" => $pageID]);
    return $result;

}

function getPageName($pageId)
{
    global $wpdb;
    $pageName = $wpdb->get_var($wpdb->prepare("SELECT page_title FROM smartbot_pages WHERE page_id=%s", $pageId));
    return $pageName;
}

/**
 * Facebook Check Page APP Connection Function
 * expects array with page details
 * checks if this page has a webhook in our db table smartbot_pages
 * if not it will subscribe this page to our app on Facebook
 * returns nothing for now. Does put the return of FB in our requestlog
 */
function smartbot_check_fb_connection($thisPage)
{
    $pageId = $thisPage['page_id'];
    $pageToken = $thisPage['page_token'];
    if ($pageId != "" && $pageToken != "") {
        smartbot_subscribe_app_page($pageToken, $pageId);
    }
}

/**
 * Subscribe a page to our APP Function
 * expects a page_id and token
 * Sets webhook as subscribed in our db table smartbot_pages
 * returns nothing for now. Does put the return of FB in our requestlog
 */
function smartbot_subscribe_app_page($pageToken, $pageId)
{
    global $wpdb;
    $url = "https://graph.facebook.com/v2.11/$pageId/subscribed_apps";
    $data['access_token'] = $pageToken;
    $json = smartbot_post_url($url, $data);

    $fbResponse = json_decode($json, true);
    if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
        errorHandling($fbResponse, $pageId);
        return 0;
    } else {
        $wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET webhook_url='subscribed' WHERE page_id=%s", $pageId));
        //whitelist clevermessenger to this page
        updateWhitelistedDomains($pageId,  'https://clevermessenger.com');
        $url2 = facebookUrl($pageId, $pageToken);
        getStartedInsert($url2);
        return 1;
    }
}

function getPageKey($pageId){
    global $wpdb;
    $pageId = $wpdb->get_var($wpdb->prepare("SELECT api_key from smartbot_pages WHERE page_id = %s",$pageId));
    return $pageId;
}
/**
 * Facebook Page Template Function
 * expects array with page details and the variable user_id
 * returns HTML -> should be moved to templates in the future
 */
function smartbot_manage_campaigns($result, $userId, $pageId, $bot_id, $bot_page, $type)
{
    global $wpdb;
    ?>
    <div id="forum-list">
        <div class="forum-title" style="display:none;"><h3>Pages</h3></div>
        <?php

        if (count($result) <= 0) {
            echo '<b class="row">It seems you do not have any page to connect, please <a target="_blank" href="https://www.facebook.com/pages/creation/">click here</a> to create a new page.</b>';
        }
        $i = 1;
        if ($type == 'manage') {
            $col = '6';
        } else {
            $col = '8';
        }
        foreach ($result as $page) {
            $page_name = '';
            $page_cat = '';
            $page_desc = '';
            $page_alias = '';
            if (isset($page['name'])) {
                $page_name = $page['name'];
            }
            if (isset($page['category'])) {
                $page_cat = $page['category'];
            }
            if (isset($page['about'])) {
                $page_desc = $page['about'];
            }
            if (isset($page['username'])) {
                $page_alias = $page['username'];
            }

            if ($i % 2 == 0) {
                $css_active = '';
            } else {
                $css_active = 'active';
            }

            $profile_img_temp = 'https://graph.facebook.com/' . $page['id'] . '/picture?type=normal';
            ?>
            <div class="forum-item <?php echo $css_active; ?>" data-page-name="<?php echo $page_name; ?>">
                <div class="row">
                    <div class="col-md-<?php echo $col; ?>">
                        <div class="forum-icon"><img
                                    src="<?php echo $profile_img_temp; ?>"
                                    class="img-circle" style="width:50px;"></div>
                        <span class="forum-item-title"
                              style="font-size: 18px;font-weight: 500;"><?php echo $page_name; ?></span>
                        <div class="forum-sub-title">Category: <?php echo $page_cat; ?> </div>
                        <!--<div class="forum-sub-title">Description: <?php echo $page_desc; ?> </div>
										<div class="forum-sub-title me-link"><?php if (isset($page_alias)) {
                            echo '<a href="https://m.me/' . $page_alias . '" target="_blank">https://m.me/' . $page_alias . '</a>';
                        } ?> </div>-->
                    </div>
                    <?php
                    if ($type == 'manage') {
                        ?>
                        <div class="col-md-4 forum-info">

                            <form method="post">
                                <input type="hidden" name="actie" value="settings">
                                <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                                <input type="hidden" name="page_name" value="<?php echo $page_name; ?>">
                                <input type="hidden" name="page_cat" value="<?php echo $page_cat; ?>">
                                <input type="hidden" name="page_image" value="<?php echo $profile_img_temp; ?>">
                                <button class="btn btn-primary" style="color:#fff" data-page=""><strong><i
                                                class="fa icon-cog" style="color:#fff"></i></strong> Edit Settings
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 forum-info">
                            <form method="post" action="visual.php?page=visual">
                                <input type="hidden" name="actie" value="visual_bot">
                                <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                                <button class="btn btn-success" style="color:#fff"><strong><i class="fa icon-desktop"
                                                                                              style="color:#fff"></i></strong>
                                    Goto Flow Composer
                                </button>
                            </form>
                        </div>
                        <?php
                    } else {
                        $thisPage_id = $page['id'];
                        ?>
                        <div class="col-md-4 forum-info">
                            <form method="post" action="dashboard.php" style="display:none;">
                                <input type="hidden" name="action2" value="settings">
                                <input id="newbot_page_cat" type="hidden" name="page_cat"
                                       value="<?php echo $page['category']; ?>">
                                <input id="newbot_page_image" type="hidden" name="page_image" value="<?php echo $profile_img_temp; ?>">
                                <input id="newbot_page_name" type="hidden" name="page_name"
                                       value="<?php echo $page_name; ?>">
                                <input id="newbot_page_token" type="hidden" name="page_token"
                                       value="<?php echo $page['access_token']; ?>">
                                <input id="newbot_page_id" type="hidden" name="page_id"
                                       value="<?php echo $page['id']; ?>">
                                <input id="newbot_page_alias" type="hidden" name="page_alias"
                                       value="<?php echo $page_alias; ?>">
                                <input id="newbot_page_scan_code" type="hidden" name="page_scan_code" value="">
                                <input id="newbot_page_desc" type="hidden" name="page_desc"
                                       value="<?php echo $page_desc; ?>">
                                <input id="page_is_new" type="hidden" name="page_is_new" value="true">
                                <input type="submit" name="submit" id="newbot_form_submit_<?php echo $page['id']; ?>">
                            </form>
                            <form method="post" id="fb_connection_form">
                                <input type="hidden" name="actie" value="connect_bot">
                                <input type="hidden" name="page_id" value="<?php echo $page['id']; ?>">
                                <?php
                                if ($bot_page == $thisPage_id) {
                                    echo '<button type="button" id="bot_button" data-page_id="' . $thisPage_id . '" data-bot_id="' . $bot_id . '" class="btn btn-success fb_connection">Connected</button>';
                                } else {
                                    echo '<button type="button" id="bot_button" data-page_id="' . $thisPage_id . '" data-bot_id="' . $bot_id . '" class="btn btn-w-m btn-primary fb_connection">Connect</button>';
                                }
                                ?>

                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php $i++;
        } ?>
    </div>
    <?php
}

/**
 * Facebook Settings Page Function
 * expects a post with the page id, name, image and category
 * displays the settings for the sticky menu, greeting text and Welcome message
 * returns HTML -> should be moved to templates in the future
 */
function smartbot_settings_page()
{
    global $wpdb, $userId;
    $bot_id = '';
    if (isset($_SESSION['bot_id'])) {
        $bot_id = $_SESSION['bot_id'];
    }
    $pageId = $_SESSION['page_id'];
    smartbot_manage_page_top($pageId, $bot_id);
//smartbot_layout_blank_row();
//if($bot_type!="instant_reply"){smartbot_sticky_menu($pageId,$bot_id);}
    smartbot_greeting_text($pageId, $bot_id);
    smartbot_settings_visual_builder($pageId, $bot_id);
//if($pageId==""){smartbot_settings_connect_to_fb($pageId,$bot_id,$bot_page,$userId);}
//smartbot_shopify_settings($pageId,$bot_id,'');
//smartbot_amazon_settings($pageId,$bot_id,'',$userId);

//smartbot_layout_blank_row();
//smartbot_settings_js($pageId,$bot_id,'');
}

function smartbot_settings_js($pageId, $bot_id, $bot_page)
{
    ?>
    <script type="text/javascript">

    </script>
    <?php
}

/**
 * Facebook Manage Page Top Function
 * Displayed at the top of the manage Page.
 * Output gives the html
 */

function smartbot_manage_page_top($pageId, $bot_id)
{
//we are already in an ibox. After this just close it for the modules to appear on a grey background
    if (empty($_SESSION['page_name'])) {
        $_SESSION['page_name'] = $_SESSION['bot_name'];
    }
    if (empty($_SESSION['page_cat'])) {
        $_SESSION['page_cat'] = $_SESSION['bot_type'];
    }
    if (isset($_SESSION['page_scan_code']) && $_SESSION['page_scan_code'] != "") {
        $_SESSION['page_image'] = $_SESSION['page_scan_code'];
    }
    $page_top = '<div class="manage_image"><img src="' . $_SESSION['page_image'] . '" class="img-circle" width="95"></div><h2> ' . $_SESSION['page_name'] . '</h2>
		  	<div class="forum-sub-title">Category: ' . $_SESSION['page_cat'] . ' </div>
			<div class="forum-sub-title me-link">';
    if (isset($_SESSION['page_alias']) && $_SESSION['page_alias'] != "") {
        $page_top .= '<a href="https://m.me/' . $_SESSION['page_alias'] . '" target="_blank">https://m.me/' . $_SESSION['page_alias'] . '</a>';
    }
    $page_top .= '</div><div id="fb_connection_result"></div>';
    $page_top .= '</div>';
    echo $page_top;
}


/**
 * Facebook Welcome Message Function
 *
 */
function getWelcomeMessage($pageId)
{
    //let's see if we have a welcome message in the db
    $welcome_msg = smartbot_get_options("", $pageId, "", "welcome_message_flow");

    return $welcome_msg;
}

function setWelcomeMessage($pageId,$flowId)
{
    //let's see if we have a welcome message in the db
    smartbot_save_keys('',$pageId,'',"welcome_message_flow",$flowId);

    return $flowId;
}


function getWelcomeMessageStatus($pageId)
{
    //let's see if we have a welcome message in the db
    $welcome_msg = smartbot_get_options("", $pageId, "", "welcome_on");

    return $welcome_msg;
}

function getDefaultReplyStatus($pageId)
{
    //let's see if we have a welcome message in the db
    $welcome_msg = smartbot_get_options("", $pageId, "", "default_on");

    return $welcome_msg;
}

function enableWelcomeMessage($pageId)
{
    smartbot_save_keys("", $pageId, '', 'welcome_on', 'yeah');
}

function disableWelcomeMessage($pageId)
{
    smartbot_save_keys("", $pageId, '', 'welcome_on', 'off');
}


function setWelcomeMessageStatus($pageId,$status)
{
    smartbot_save_keys("", $pageId, '', 'welcome_on', $status);
}

function getDefaultReply($pageId)
{
    //let's see if we have a default message in the db
    $thismsg = smartbot_get_options('', $pageId, '', 'default_reply_flow');
    return ($thismsg);
}

function setDefaultReply($pageId,$flowId)
{
    smartbot_save_keys('',$pageId,'',"default_reply_flow",$flowId);

}


function enableDefaultReply($pageId)
{
    smartbot_save_keys("", $pageId, '', 'default_on', 'yeah');
}

function disableDefaultReply($pageId)
{
    smartbot_save_keys("", $pageId, '', 'default_on', 'off');
}


function setDefaultReplyStatus($pageId,$status)
{
    smartbot_save_keys("", $pageId, '', 'default_on', $status);
}

/**
 * Facebook Greeting Text Function
 * This part outputs the actual HTML of the greeting text
 * it also looks if there is any previous set text already
 */
function smartbot_greeting_text($pageId, $bot_id)
{
    global $wpdb;
    $userId = $_SESSION['user_id'];
    if ($pageId != '') {
        $greeting_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_elements WHERE element_name='greeting' AND page_id=%s", $pageId), ARRAY_A);
        if (isset($greeting_row) && is_array($greeting_row)) {
            $greeting_text = $greeting_row['element_text'];
        } else {
            $greeting_text = '';
        }
    } else {
//we don't have a page....maybe it's "just" a bot till now
        $greeting_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_elements WHERE element_name='greeting' AND bot_id=%s", $bot_id), ARRAY_A);
        if (isset($greeting_row) && is_array($greeting_row)) {
            $greeting_text = $greeting_row['element_text'];
        } else {
            $greeting_text = '';
        }
    }
    $sticky_menu = '<div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"> <i class="fa icon-enter-right"></i>  Greeting Text <i class="fa icon-notification-circle greetingmodal fr" aria-hidden="true"></i></div>
                    <div class="ibox-content" style="display: block;">
					 <div id="greeting_result"></div>
					 <!--<form method="post" id="greeting_text_form">-->
					 <input type="hidden" name="action" value="greeting_text" >
					 <input type="hidden" name="user_id" value="' . $userId . '" id="greeting_user_id">
					 <input type="hidden" name="page_id" value="' . $pageId . '" id="greeting_page_id"></input>
					 <input type="hidden" name="bot_id" value="' . $bot_id . '" id="greeting_bot_id"></input>
					 <input placeholder="Enter your Greeting Text...max 160 Characters" id="greeting_text" class="form-control input-lg m-b" type="text" name="greeting_text" value="' . $greeting_text . '">
					<span class="btn btn-primary save_greeting">Save Greeting</span>
					<!--</form>-->
					<br /><br />
					</div>
				</div>
				</div>';
    echo $sticky_menu;
}


/**
 * Facebook Layout Blank Row Function
 * This part outputs HTML with a div with a blank row. Used in the manage page
 */
function smartbot_layout_blank_row()
{
    echo '<div class="row"><br /><br /></div>';
}

/**
 * Facebook Settings Flow Composer Function
 * This part outputs the actual HTML of the link to the flow composer on the manage page
 */
function smartbot_settings_visual_builder($pageId, $bot_id)
{
    echo ' 
<div class="col-lg-6">
    <div class="ibox float-e-margins">
        <div class="ibox-title"> <i class="fa icon-bubbles"></i>  Create Messages</div>
        <div class="ibox-content">
        <p style="font-size:15px">You can use the Flow Composer to create new messages or the option below</p>
    		<div align="center">
                <form method="post" action="visual.php?page=visual">
    				<input name="actie" value="visual_bot" type="hidden">
    				<input name="bot_id" value="' . $bot_id . '" id="visual_bot_id" type="hidden">
    				<input name="page_id" value="' . $pageId . '" id="visual_bot_id" type="hidden">
    				<button class="btn btn-success" style="color:#fff"><strong><i class="fa icon-desktop" style="color:#fff"></i></strong> Goto Flow Composer</button>
    			</form>
            </div>
        </div>
    </div>
</div>';
}

function smartbot_settings_connect_to_fb($pageId, $bot_id, $bot_page, $userId, $userIndexId, $template = "blank")
{
    global $wpdb;
    echo '
<div class="col-lg-12">
    <div class="">
        <div class=""><input id="pageSearch" class="filter form-control live-chat-search-sub-form" style="font-size:20px;" type="text" placeholder="Search page..." title="Type in a name"></div> 
        <div class="">
        <div class="scroll_content">
        ';
    if ($userId != "") {
        $fb_pages = $_SESSION['temp_pages_list'];
        /*if($template=="blank"){
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_pages WHERE page_id IN(SELECT page_id FROM smartbot_page_owners WHERE user_id=%s AND active_page!=1)  GROUP BY page_id ORDER BY page_title",$userId),ARRAY_A);

            }else{
                $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_pages WHERE page_id IN(SELECT page_id FROM smartbot_page_owners WHERE user_id=%s)  GROUP BY page_id  ORDER BY page_title",$userId),ARRAY_A);

            }*/

        $active_pages_array = $wpdb->get_col($wpdb->prepare("SELECT page_id FROM smartbot_pages WHERE page_id IN(SELECT page_id FROM smartbot_page_owners WHERE user_index_id=%s AND active_page=1)  GROUP BY page_id", $userIndexId));

        $count = count($fb_pages);
        $result = array();
        for ($counter = 0; $counter < $count; $counter++) {
            if (!in_array($fb_pages[$counter]['id'], $active_pages_array)) {
                array_push($result, $fb_pages[$counter]);
            }
        }
        smartbot_manage_campaigns($result, $userId, $pageId, $bot_id, $bot_page, 'connection');
        unset($_SESSION['temp_pages_list']);
    }

    echo '
        </div>
        </div>
    </div>
</div>';

}

/*
 * gets the status of default greeting, $status is 0(disabled) or 1(enabled)
 */
function getDefaultGreetingStatus($pageId){
    if ($pageId != '') {
        global $wpdb;
        $status = $wpdb->get_var($wpdb->prepare("SELECT status FROM smartbot_greeting WHERE greeting_type='default' AND page_id=%s ", $pageId));
        return $status;
    }
}

/*
 * Sets the status of default greeting, $status is 0(disabled) or 1(enabled)
 */
function setDefaultGreetingStatus($pageId,$status){
    if ($pageId != '' && $status===1 || $status===0) {
        global $wpdb;
        $wpdb->update('smartbot_greeting',['status'=>$status],['page_id'=>$pageId]);
        setPageGreeting($pageId);
    }
}
/**
 * Function Get Greting Text
 * Used to prefill the greeting on the wizard after the selection of the page
 */
function smartbot_get_greeting($pageId)
{
    global $wpdb;
    if ($pageId != '') {
        $greeting_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_greeting WHERE greeting_type='default' AND page_id=%s ", $pageId), ARRAY_A);
        if (isset($greeting_row) && is_array($greeting_row)) {
            $greeting_text = $greeting_row['greeting_text'];
        } else {
            $greeting_text = '';
        }
        return stripslashes($greeting_text);
    }
}

function insertGreetingText($pageId, $greeting)
{
    global $wpdb;


    $greeting = stripslashes($greeting);

    if (!empty($greeting)) {

        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_greeting WHERE greeting_type='default' AND page_id=%s", $pageId));
        $wpdb->insert('smartbot_greeting', array('id' => '', 'page_id' => $pageId, 'greeting_type' => 'default', 'greeting_text' => $greeting));
        $msg_id = $wpdb->insert_id;
        setPageGreeting($pageId);
    }

    if ($msg_id > 0) {
        $msg = 'Success, the greeting is saved';
    } else {
        $msg = 'Error, the greeting is not saved. Please try again!';
    }

    return $msg;
}

function smartbot_insert_greeting_local($bot_id, $pageId, $userId, $greeting, $langCode, $langName)
{
    global $wpdb;
    $msg = '';
    $greeting = stripslashes($greeting);

    if ($greeting != "") {
        //lets first delete the message. Officially I can delete it from FB as well but we have a new message so for now it's like this as FB overwrites anything we add later
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_greeting WHERE greeting_type='local' AND greeting_lang_code=%s AND page_id=%s ", $langCode, $pageId));
        $wpdb->insert('smartbot_greeting', array('id' => '', 'page_id' => $pageId, 'greeting_type' => 'local', 'greeting_lang_code' => $langCode, 'greeting_lang_name' => $langName, 'greeting_text' => $greeting));
        $greetingId = $wpdb->insert_id;

        setPageGreeting($pageId);


        if ($greetingId > 0) {
            $prsnlHtml = PersonalizationHTML('local');
            $msg = '<div class="row" id="' . $greetingId . '_local_greeting" style="margin: 0px;border-radius:  10px;">
    <div class="col-lg-3" style="padding: 0px;">
	<span class="input-group-addon input-lg greeting_lang"  style="background-color: #0084FF;color: #FFFFFF;font-size: 14px;width: inherit;">' . $langName . '</span> 
	</div>
	<div class="col-lg-7" style="padding: 0px;">
	<input class="form-control input-lg local_greeting_input_txt styling_local_greeting_txt" type="text" value="' . htmlentities($greeting) . '" id="' . $greetingId . '_local_greeting_txt" maxlength="160" data-emojiable="true" data-charcounter="true">' . $prsnlHtml . ' 
	</div>
	<div class="col-lg-1" style="padding: 0px;border-right: 1px solid #f5f6f9;">
	<span class="input-group-addon input-lg edit_greeting_span"><i data-greeting_id="' . $greetingId . '" data-lang_name="' . $langName . '"  data-lang_code="' . $langCode . '" class="edit_greeting fa icon-sync"></i></span>
	</div>
	<div class="col-lg-1" style="padding: 0px;border-right: 1px solid #f5f6f9;">
	<span class="input-group-addon input-lg delete_greeting_span"><i data-greeting_id="' . $greetingId . '" data-lang_name="' . $langName . '"  data-lang_code="' . $langCode . '" class="delete_greeting fa icon-cross"></i></span>
    </div>
</div>
<div style="clear:both"></div>';
        } else {
            $msg = 0;
        }


    }
    return $msg;
}

function smartbot_get_greeting_local($pageId)
{
    global $wpdb;
    $msg = '';
    if ($pageId != "") {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_greeting WHERE page_id=%s AND greeting_type='local' ORDER BY greeting_lang_name", $pageId), ARRAY_A);
        if (is_array($results)) {
            $prsnlHtml = PersonalizationHTML('local');
            foreach ($results as $this_row) {

                $msg .= '<div class="row" id="' . $this_row['id'] . '_local_greeting" style="margin: 0px;border-radius:  10px;">
    <div class="col-lg-3" style="padding: 0px;">
	<span class="greeting_lang input-group-addon input-lg " style="background-color: #F9FAFC;font-size: 14px;width: inherit;">' . $this_row['greeting_lang_name'] . '</span> 
	</div>
	<div class="col-lg-7" style="padding: 0px;">
	<input class="form-control input-lg local_greeting_input_txt styling_local_greeting_txt" type="text" value="' . htmlentities($this_row['greeting_text']) . '" id="' . $this_row['id'] . '_local_greeting_txt" maxlength="160" data-emojiable="true" data-charcounter="true">' . $prsnlHtml . '
	</div>
	<div class="col-lg-1" style="padding: 0px;border-right: 1px solid #f5f6f9;">
	<span class="input-group-addon input-lg edit_greeting_span styling_local_greeting_txt_edit_greeting_span"><i data-greeting_id="' . $this_row['id'] . '" data-lang_name="' . $this_row['greeting_lang_name'] . '"  data-lang_code="' . $this_row['greeting_lang_code'] . '" class="edit_greeting fa icon-sync"></i></span>
	</div>
	<div class="col-lg-1" style="padding: 0px;border-right: 1px solid #f5f6f9;">
	<span class="input-group-addon input-lg delete_greeting_span" ><i data-greeting_id="' . $this_row['id'] . '" data-lang_name="' . $this_row['greeting_lang_name'] . '"  data-lang_code="' . $this_row['greeting_lang_code'] . '"class="delete_greeting fa icon-cross"></i></span>
    </div>
</div>
<div style="clear:both"></div>';

            }

        }
    }
    return $msg;
}

function smartbot_delete_greeting_local($pageId, $userId, $greetingId, $langCode)
{
    global $wpdb;
    if (isset($pageId) && isset($greetingId)) {
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_greeting WHERE greeting_type='local' AND greeting_lang_code=%s AND page_id=%s AND id=%s", $langCode, $pageId, $greetingId));

        $pageDetails = smartbot_get_page_details($pageId);
        $pageToken = $pageDetails['page_token'];

        if ($pageToken != "") {
            $url = "https://graph.facebook.com/v2.8/" . $pageId . "/messenger_profile?fields=%5B%22greeting%22%5D&access_token=" . $pageToken;
            $jsonResponse = smartbot_curl($url, '', 'delete_json');
            $fbResponse = json_decode($jsonResponse, true);
            if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
                errorHandling($fbResponse, $pageId);
            } else {
                setPageGreeting($pageId);
            }
        }
    }
}

function setPageGreeting($pageId)
{
    global $wpdb;
    $pageDetails = smartbot_get_page_details($pageId);
    $pageToken = $pageDetails['page_token'];
    $dRow = $wpdb->get_row($wpdb->prepare("SELECT greeting_text,status FROM smartbot_greeting WHERE greeting_type='default' AND page_id=%s ", $pageId));
    if(!empty($dRow->status)) {
        // greeting is enabled i.e status=1
        $defaultGreeting = $dRow->greeting_text;
        $defaultGreeting = addslashes(smartbot_check_personalisation($defaultGreeting, $pageDetails, 'greeting', ''));
        $jsonMsg = '{"greeting":[{ "locale":"default","text":"' . $defaultGreeting . '"}';

        //getting the local greetings if any
        $results = $wpdb->get_results($wpdb->prepare("SELECT greeting_text,greeting_lang_code FROM smartbot_greeting WHERE page_id=%s AND greeting_type='local'", $pageId), ARRAY_A);
        if (is_array($results)) {

            foreach ($results as $this_row) {
                $greeting = addslashes(smartbot_check_personalisation($this_row['greeting_text'], $pageDetails, 'greeting', ''));
                $jsonMsg .= ',{"locale":"' . $this_row['greeting_lang_code'] . '","text":"' . $greeting . '"}';
            }//end for each
        }
        $jsonMsg .= ']}';
    }
    else{
        //greeting is disabled i.e status=0
        $jsonMsg = '{"greeting":[{ "locale":"default","text":""}]}';
    }
    if ($pageToken != "") {
        $url = "https://graph.facebook.com/v2.8/" . $pageId . "/messenger_profile?access_token=" . $pageToken;
        $data = smartbot_post_json($url, $jsonMsg);
        getStartedInsert($url);
    }
}


function smartbot_create_sticky_menu($pageId, $userId, $item_id, $main_id, $menu_title, $menu_type, $menu_url, $menu_msg, $item_order, $item_type)
{
    global $wpdb;
    $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_menus where element_name=%s AND item_id=%s ", $menu_type, $item_id));
    if ($num_rows < 1) {
        $wpdb->insert('smartbot_menus', array('id' => '', 'page_id' => $pageId, 'main_id' => $main_id, 'element_name' => $menu_type, 'item_id' => $item_id, 'element_title' => $menu_title, 'element_type' => $menu_type, 'item_url' => $menu_url, 'element_text' => $menu_msg, 'element_order' => $item_order));
    } else {
        //we need to run an update
        $wpdb->query($wpdb->prepare("UPDATE smartbot_menus SET element_title=%s ,element_type=%s,item_url=%s,element_text=%s,element_order=%s WHERE item_id=%s AND element_name=%s", $menu_title, $item_type, $menu_url, $menu_msg, $item_order, $item_id, $menu_type));
        //if this is an update and it's not a submenu we need to delete any subelement id's in case those are there
        if ($item_type != 'submenu') {
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE element_name='submenu' AND main_id=%s", $item_id));
        }
    }

//let's run the creation of the menu...but only if we have a page_id as else it makes no sense
    if ($pageId != "") {
        smartbot_sticky_menu_creation('', $userId, $pageId);
    }
}

function smartbot_sticky_menu_creation($bot_id, $userId, $pageId)
{
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE page_id=%s AND element_name='menu'", $pageId), ARRAY_A);
    $last_q = $wpdb->last_query;
    $x = 0;
    if (is_array($results)) {
        $menu = array();
        foreach ($results as $menu_item) {
            if (isset($menu_item['element_title']) && $menu_item['element_title'] != "") {
                $menu[$x]['menu_title'] = $menu_item['element_title'];
                $menu[$x]['menu_type'] = $menu_item['element_type'];
                $menu[$x]['menu_payload'] = $menu_item['element_text'];
                $menu[$x]['menu_url'] = $menu_item['item_url'];
                $menu[$x]['menu_id'] = $menu_item['item_id'];
                $x++;
            }
        }
    }
    if ($x == 0) {
        $menu = 'delete';
    }

    $main_menu_onoff = smartbot_get_options('', $_SESSION['page_id'], '', 'main_menu_on');

    if ($main_menu_onoff == 'off') {
        $menu = 'delete';
    }
    if ($main_menu_onoff == '') {
        /* menu is saved for the first time*/
        $option_name = 'main_menu_on';
        $option_value = 'off';
        smartbot_save_keys('', $pageId, $bot_id, $option_name, $option_value);
    }


    return smartbot_insert_persistent_menu($bot_id, $pageId, $userId, $menu);
}

function smartbot_get_type_icon($item_type)
{
    $icon = '';
    switch ($item_type) {
        case "web_url":
            $icon = '<i class="fa icon-link2" aria-hidden="true"></i>';
            break;
        case "postback":
            $icon = '<i class="fa icon-bubble" aria-hidden="true"></i>';
            break;
        case ("submenu") || ("subsubmenu"):
            $icon = '<i class="fa icon-indent-increase" aria-hidden="true"></i>';
            break;
    }
    if ($icon == '') {
        $icon = '<i class="fa icon-indent-increase" aria-hidden="true"  style="color:#fff !important;"></i>';
    }

    return $icon;
}

//delete menu item...kind of as we set the menu title and type at '' which causes we will not take it with us but it also saves previous sub menu's etc in case the user changes his mind and does want this item to use etc
function smartbot_delete_menu_item($pageId, $item_id)
{
    global $wpdb;
    $delete_items = "";
    if (isset($item_id) && isset($pageId)) {

        //first lets see if this item has sub items etc. Lets start by findingout what type of menu item this is
        $menutype = $wpdb->get_var($wpdb->prepare("SELECT element_name FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $item_id, $pageId));
        if ($menutype == "subsubmenu") {
            //we have an end of the line...only delete this one and we are done
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s ", $item_id, $pageId));
            $delete_items .= $item_id . '|';
        }

        if ($menutype == "submenu") {
            $delete_items .= $item_id . '|';
            //we have a submenu so there could be 1 level below this.
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $item_id, $pageId), ARRAY_A);
            if (isset($results) && is_array($results)) {
                foreach ($results as $row) {
                    $delete_items .= $row['item_id'] . '|';
                }
            }
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s ", $item_id, $pageId));
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s ", $item_id, $pageId));
        }

        if ($menutype == "menu") {
            //for the main menu I do not want to delete anything...we start and stop with 3 main menu items
            $wpdb->query($wpdb->prepare("UPDATE smartbot_menus SET element_title='',element_type='' WHERE page_id=%s AND item_id=%s ", $pageId, $item_id));

            //we have a first level...so there can be 2 levels below this
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $item_id, $pageId), ARRAY_A);
            if (isset($results) && is_array($results)) {
                foreach ($results as $row) {
                    $this_id = $row['item_id'];
                    $delete_items .= $this_id . '|';

                    $results2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $this_id, $pageId), ARRAY_A);
                    if (isset($results2) && is_array($results2)) {
                        foreach ($results2 as $row2) {
                            $delete_items .= $row2['item_id'] . '|';
                        }
                    }

                    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $this_id, $pageId));
                    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $this_id, $pageId));
                }
            }
            //sublevels done if any...lets clear the rest
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $item_id, $pageId));
        }

        return $delete_items;
    }
}

//delete menu item...old version
function smartbot_delete_menu_item_old($pageId, $userId, $item_id)
{
    global $wpdb;
    if (isset($item_id) && isset($userId)) {

        //first lets see if this item has sub items etc. Lets start by findingout what type of menu item this is
        $menutype = $wpdb->get_var($wpdb->prepare("SELECT element_name FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $item_id, $pageId));
        if ($menutype == "subsubmenu") {
            //we have an end of the line...only delete this one and we are done
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s ", $item_id, $pageId));
        }

        if ($menutype == "submenu") {
            //we have a submenu so there could be 1 level below this.
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s ", $item_id, $pageId));
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s ", $item_id, $pageId));
        }

        if ($menutype == "menu") {
            //we have a first level...so there can be 2 levels below this
            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $item_id, $pageId), ARRAY_A);
            if (isset($results) && is_array($results)) {
                foreach ($results as $row) {
                    $this_id = $row['item_id'];
                    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $this_id, $pageId));
                    $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $this_id, $pageId));
                }
            }
            //sublevels done if any...lets clear the rest
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $item_id, $pageId));
            $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $item_id, $pageId));
        }

    }
    smartbot_sticky_menu_creation('', $userId, $pageId);//we need to create a new menu or delete all if there was just one. The function will take care of it
}

/**
 * Sticky SubMenu Delete item
 * This function is called in the sticky submenu part when someone deletes a submenu item
 * Expects the page_id, user_id, item_id and menu_id. After the delete the whole sticky menu is build again and send to FB. Just incase someone does not
 * click the save settings on the submenu modal which triggers also the creation of the new menu
 * Outputs success on delete and fail on not being able to delete
 */

function smartbot_sticky_delete_submenu_item($pageId, $userId, $menuId, $item_id)
{
    global $wpdb;

    if (isset($item_id) && isset($userId)) {
        $wpdb->query($wpdb->prepare("DELETE FROM smartbot_menus WHERE element_name='submenu' AND item_id=%s AND main_id=%s AND page_id=%s", $item_id, $menuId, $pageId));

        smartbot_sticky_menu_creation('', $userId, $pageId);//we need to create a new menu or delete all if there was just one. The function will take care of it. Not relying on the save settings of the submenu
        echo '<span class="msg_success">Success, deleted submenu item</span>';
    } else {
        echo '<span class="msg_error">Failed to delete item</span>';
    }
}

function smartbot_create_menu_items($menu)
{
//we have an array and we want to spit out json in the format
// {
//      "type":"postback",
//      "title":"Start a New Order",
//      "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_START_ORDER"
// }
    $x = 0;
    $menu_json = '';
    foreach ($menu as $menu_item) {
        $menu_title = $menu_item['menu_title'];
        $menu_type = $menu_item['menu_type'];
        if ($menu_type == "submenu") {
            $menu_type = "nested";
        }
        $menu_payload = $menu_item['menu_payload'];
        $menu_url = $menu_item['menu_url'];
        //we need to check that if the type is X it should have a value
        $check_pass = 'nope';
        if ($menu_type == "web_url" && $menu_url != "") {
            $check_pass = "yeah";
        }
        if ($menu_type == "postback" && $menu_payload != "") {
            $check_pass = "yeah";
            $menu_payload = "direct_" . $menu_payload;
        }
        if ($menu_type == "nested") {
            $check_pass = "yeah";
        }

        if ($menu_title != "" && $check_pass == "yeah") {
            if ($x == 0) {
                $menu_json .= '{';
            } else {
                $menu_json .= ',{';
            }
            //type of menu item
            $menu_json .= '"title":"' . $menu_title . '",';
            $menu_json .= '"type":"' . $menu_type . '",';
            if ($menu_type == "web_url") {
                $menu_json .= '"url":"' . $menu_url . '"';
            }
            if ($menu_type == "postback") {
                $menu_json .= '"payload":"' . $menu_payload . '"';
            }
            if ($menu_type == "nested") {
                $menuId = $menu_item['menu_id'];
                $menu_json .= smartbot_create_submenu_items($menuId);
            }
            $menu_json .= '}';
            $x++;
        }
    }
    return $menu_json;
}

function smartbot_create_submenu_items($menuId)
{
// we need to return something like this
//"type":"nested",
//          "call_to_actions":[
//            {
//              "title":"Pay Bill",
//              "type":"postback",
//              "payload":"PAYBILL_PAYLOAD"
//            },
//            {
//              "title":"History",
//              "type":"postback",
//              "payload":"HISTORY_PAYLOAD"
//            }
//          ]

    $submenu_items = '"call_to_actions":[';
    global $wpdb;

    if ($menuId != "") {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE main_id=%s", $menuId), ARRAY_A);
        if (is_array($results) && isset($results)) {
            $x = 0;
            foreach ($results as $this_result) {

                $menuId = $this_result['item_id'];
                $menu_type = $this_result['element_type'];
                if ($menu_type == "submenu") {
                    $menu_type = "nested";
                }
                $menu_title = $this_result['element_title'];
                $menu_payload = $this_result['element_text'];
                $menu_url = $this_result['item_url'];

                $check_pass = 'nope';
                if ($menu_type == "web_url" && $menu_url != "") {
                    $check_pass = "yeah";
                }
                if ($menu_type == "postback" && $menu_payload != "") {
                    $check_pass = "yeah";
                    $menu_payload = "direct_" . $menu_payload;
                }
                if ($menu_type == "nested") {
                    $check_pass = "yeah";
                }

                if ($menu_title != "" && $check_pass == "yeah") {
                    if ($x > 0) {
                        $submenu_items .= ',';
                    }
                    $submenu_items .= '{"title":"' . $menu_title . '",
        				             "type":"' . $menu_type . '",';
                    if ($menu_type == "web_url") {
                        $submenu_items .= '"url":"' . $menu_url . '"';
                    }
                    if ($menu_type == "postback") {
                        $submenu_items .= '"payload":"' . $menu_payload . '"';
                    }
                    if ($menu_type == "nested") {
                        $submenu_items .= smartbot_create_submenu_items($menuId);
                    }
                    $submenu_items .= '}';
                    $x++;
                }
            }
        }
    }
    $submenu_items .= ']';
    return $submenu_items;
}


function getStartedInsert($url)
{
    $get_started = '{"get_started":{"payload":"get_started"}}';
    smartbot_post_json($url, $get_started);
}

function facebookUrl($pageId, $pageToken)
{
    //will make this a switch later ...main thing is to be able to change the version easily in the near future
    $url = "https://graph.facebook.com/v2.8/" . $pageId . "/messenger_profile?access_token=" . $pageToken;
    return $url;
}

function smartbot_insert_persistent_menu($bot_id, $pageId, $userId, $menu)
{
    $fbResponse = '';
    $request_log = SB_PATH . "logs/menu.log";

    if ($bot_id == "") {
        $bot_id = $pageId;
    }
    if ($pageId != "") {
        $page_details = smartbot_get_page_details($pageId);
        $pageToken = $page_details['page_token'];
        $url = facebookUrl($pageId, $pageToken);


        if (is_array($menu)) {
            $menu_arr_result = smartbot_create_menu_items($menu);
            //making sure there is a get started button....
            getStartedInsert($url);

            $json_msg = '{"persistent_menu":[
  {
    				  "locale":"default",
					  "composer_input_disabled": false,
					  "call_to_actions":[' . $menu_arr_result . ']
  				}
	]}';


            $jsonResponse = smartbot_post_json($url, $json_msg);
            $fbResponse = json_decode($jsonResponse, true);
            if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
                errorHandling($fbResponse, $pageId);
            }

        }
        if ($menu == 'delete') {
            //we need to delete the menu
            $url = "https://graph.facebook.com/v2.8/" . $pageId . "/messenger_profile?fields=%5B%22persistent_menu%22%5D&access_token=" . $pageToken;
            $jsonResponse = smartbot_curl($url, '', 'delete');
            $fbResponse = json_decode($jsonResponse, true);
            if (isset($fbResponse['error']) && isset($fbResponse['error']['code'])) {
                errorHandling($fbResponse, $pageId);
            }
        }

    }
    return $fbResponse;
}

/**
 * Sticky Menu Rows Function
 * Creates table rows for the sticky menu item from previous entries
 * Outputs the formatted menu rows
 */
function smartbot_sticky_menu_rows($pageId, $results, $main_id, $type, $x)
{
    $x = 0;
    $sticky_rows = '';
    if (isset($results)) {
        foreach ($results as $row) {
            $last_empty = 'empty';
            $item_id = $row['item_id'];
            $menu_type = $row['element_name'];
            $item_title = $row['element_title'];
            $item_url = $row['item_url'];
            $msg_id = $row['element_text'];
            $item_type = $row['element_type'];
            $item_type_icon = smartbot_get_type_icon($item_type);
            if ($item_title != "" && $item_type != "") {
                $last_empty = 'nope';
            }
            $sticky_rows .= smartbot_menu_rows($pageId, $x, $main_id, $item_id, $menu_type, $item_title, $item_url, $msg_id, $item_type, $item_type_icon, $last_empty);

            //lets see if we have a submenu
            if ($item_type == "submenu" || $item_type == "subsubmenu") {
                $sticky_rows .= smartbot_sticky_submenu_prefill($pageId, $item_id, $item_type, $menu_type);
            }
            $x++;
        }
    }

    if ($menu_type == "menu" && $x < 3) {

        $sticky_rows .= smartbot_menu_rows($pageId, $x, $main_id, '', $menu_type, '', '', '', 'empty', '', $last_empty);
    }

    if (($menu_type == "submenu" && $x < 5 || $menu_type == "subsubmenu" && $x < 5) && $last_empty == "nope") {
        $sticky_rows .= smartbot_menu_rows($pageId, $x, $main_id, '', $menu_type, '', '', '', 'empty', '', $last_empty);
    }
    return $sticky_rows;
}


function smartbot_menu_rows($pageId, $x, $main_id, $item_id, $menu_type, $item_title, $item_url, $msg_id, $item_type, $item_type_icon, $last_empty)
{
    global $wpdb;
    $menu_rows = '';
    $li_class = '';
    $li_style = '';
    if ($item_type == "empty") {
        $li_class = 'empty';
        $item_type = '';
        $li_style = 'background-color: #0084FF; color: #fff';
        $item_id = smartbot_create_unique_id();
        $item_type_icon = '<i class="fa icon-indent-increase" aria-hidden="true"  style="color:#fff !important;"></i>';
        $wpdb->insert('smartbot_menus', array('page_id' => $pageId, 'element_name' => $menu_type, 'item_id' => $item_id, 'main_id' => $main_id, 'element_title' => '', 'element_type' => '', 'item_url' => '', 'element_text' => '', 'element_order' => $x));
    }
    if ($item_type_icon == "") {
        $item_type_icon = '<i class="fa icon-indent-increase" aria-hidden="true"  style="color:#fff !important;"></i>';
    }
    if ($menu_type === "submenu") {
        $li_class = 'li-children ' . $main_id . '-child';
    }
    if ($menu_type === "subsubmenu") {
        $top_main = smartbot_get_top_main($main_id, $pageId);
        $li_class = 'li-children ' . $main_id . '-child ' . $top_main . '-main-child';
    }
    $menu_rows .= '<li class="dd-item dd3-item ' . $li_class . '" id="' . $item_id . '" data-id="' . $item_id . '"  style="height: 60px;margin-bottom: 10px;" data-empty="' . $last_empty . '">
				<div class="input-group m-b">
					<span id="' . $item_id . '_menu_handle" class="input-group-addon input-lg menu-handle" style="font-size: 14px;width:20px;height: 60px;' . $li_style . '"><i class="fa icon-menu" aria-hidden="true"></i></span> 
					<input class="form-control input-lg menu_txt_input input_dropdown ' . $li_class . '" type="text"placeholder="Enter Menu Text...max 30 Characters" size="30" maxlength="30" value="' . $item_title . '" id="' . $item_id . '_menu_txt_input" style="height: 60px;" data-id="' . $item_id . '" data-menu_type="main"> 
					<input type="hidden" name="' . $item_id . '_menu_msg" id="' . $item_id . '_menu_msg" value="' . $msg_id . '"/>
                    <input type="hidden" name="' . $item_id . '_menu_url" id="' . $item_id . '_menu_url" value="' . $item_url . '"/>
                    <input type="hidden" name="' . $item_id . '_menu_sub" id="' . $item_id . '_menu_sub" value=""/>                   
                    <input type="hidden" name="' . $item_id . '_item_type" id="' . $item_id . '_item_type" value="' . $item_type . '"/>   
                    <input type="hidden" name="' . $item_id . '_menu_type" id="' . $item_id . '_menu_type" value="' . $menu_type . '"/>                  
                    <input type="hidden" name="' . $item_id . '_menu_num" id="' . $item_id . '_menu_num" value="' . $x . '"/>';

    if ($menu_type === "submenu" || $menu_type === "subsubmenu") {
        $menu_rows .= '<input type="hidden" name="' . $item_id . '_main_id" id="' . $item_id . '_main_id" value="' . $main_id . '"/>';
    }
    if ($menu_type === "menu") {
        $menu_rows .= '<input type="hidden" name="' . $item_id . '_main_id" id="' . $item_id . '_main_id" value="main"/>';
    }

    $menu_rows .= '      <span id="' . $item_id . '_menu_type_span" class="input-group-addon input-lg menu_type_span" data-id="' . $item_id . '">' . $item_type_icon . '</span>
					<span class="input-group-addon input-lg"  data-id="' . $item_id . '" data-menu_type="' . $menu_type . '">
					    <div class="ibox-tools dropup myDropdown" style="display:inline-block;" data-id="' . $item_id . '" data-menu_type="' . $menu_type . '">
                        <a href="#" class="dropdown-toggle edit_menu_span" data-toggle="dropdown" aria-expanded="false" data-id="' . $item_id . '" data-menu_type="' . $menu_type . '">
					    <i class="fa icon-pencil"></i> </a>
                                                    <div id="' . $item_id . '_dropdown_menu" class="dropdown-menu" style="top: -100px; left: -250px;">
                                                      <div class="edit_menu_item">
                                                              <div class="edit_menu_txt">
                                                                  <span style="width:80%;float: left;"><input class="form-control input-lg menu_txt_input2 ' . $li_class . '" type="text" placeholder="Enter Menu Text...max 30 Characters" size="30"  maxlength="30" value="" id="' . $item_id . '_menu_txt" data-id="' . $item_id . '" style="height: 40px;"></span>
                                                                  <!--<span style="width:15%"><i class="fa fa-globe fa-2x" aria-hidden="true"></i></span>-->
                                                              </div>
                                                          <div class="edit_menu_select">
                                                              <div class="menu_select_msg"  id="' . $item_id . '_menu_select_msg" data-id="' . $item_id . '" ><i class="fa icon-bubble fa-lg" aria-hidden="true"></i>Message</div>
                                                              <div class="menu_select_url" id="' . $item_id . '_menu_select_url" data-id="' . $item_id . '" ><i class="fa icon-link2 fa-lg" aria-hidden="true"></i>Url</div>';
    if ($menu_type != "subsubmenu") {
        $menu_rows .= '<div class="menu_select_sub"  id="' . $item_id . '_menu_select_sub" data-id="' . $item_id . '" ><i class=" fa icon-indent-increase fa-lg" aria-hidden="true"></i>Submenu</div>';
    }

    $menu_rows .= '                                        </div>
                                                          <div style="clear: both;"></div>
                                                          <div class="edit_menu_result" id="' . $item_id . '_edit_menu_result"></div>
                                                          <div style="clear: both;"></div>
                                                          <div class="edit_menu_additional" id="' . $item_id . '_edit_menu_additional">
                                                          <label>Additional Actions</label>
                                                          <span>This option will follow asap</span>
                                                           </div>
                                                          <div style="clear: both;"></div>
                                                          <div class="edit_menu_save"><span class="btn btn-primary save_menu_item" id="' . $item_id . '_edit_menu_save" data-menu_id="' . $item_id . '">Save</span></div>
                                                      </div>
                                                    </div>
                                                </div>
					
                    </span>
					<span class="input-group-addon input-lg delete_menu_span" id="' . $item_id . '_trash" data-id="' . $item_id . '" data-menu_type="' . $menu_type . '" ><i class="delete_menu fa icon-cross"></i></span>
				</div>
			 </li>';
    return $menu_rows;
}

function smartbot_get_top_main($main_id, $pageId)
{
    global $wpdb;
    $top_id = '';
    if (isset($main_id) && isset($pageId)) {
        $top_id = $wpdb->get_var($wpdb->prepare("SELECT main_id FROM smartbot_menus WHERE item_id=%s AND page_id=%s", $main_id, $pageId));
    }
    return $top_id;
}

function smartbot_new_menu_row($pageId, $userId, $menuId, $main_id, $menu_type)
{
    //smal check if we even can do this...menu type>menu max =3, submenu max is 5
    $num_items = smartbot_sticky_menu_prefill_num($pageId, $menuId, $menu_type);
    if (($menu_type == "menu" && $num_items < 3) || ($menu_type == "submenu" && $num_items < 5) || ($menu_type == "subsubmenu" && $num_items < 5)) {
        $x = $num_items + 1;
        $sticky_rows = smartbot_menu_rows($pageId, $x, $main_id, '', $menu_type, '', '', '', 'empty', '', '');
        return $sticky_rows;
    }
}

function smartbot_create_sticky_submenu($bot_id, $pageId, $userId, $menuId, $main_menu_id, $menu_title, $item_ids, $submenu_title, $submenu_type, $submenu_url, $submenu_msg, $item_order, $menu_type)
{
    global $wpdb;
    $x = 0;
    $y = 0;
    if (is_array($item_ids)) {

        foreach ($item_ids as $this_item) {
            $this_title = $submenu_title[$x];
            $this_type = $submenu_type[$x];
            $this_url = $submenu_url[$x];
            $this_msg = $submenu_msg[$x];
            if ($this_title != "") {
                //only if we have a title we can go further
                $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_menus where element_name=% AND item_id=%s ", $menu_type, $this_item));
                if ($num_rows < 1) {
                    $wpdb->insert('smartbot_menus', array('page_id' => $pageId, 'bot_id' => $bot_id, 'user_id' => $userId, 'element_name' => $menu_type, 'item_id' => $this_item, 'main_id' => $menuId, 'element_title' => $this_title, 'element_type' => $this_type, 'item_url' => $this_url, 'element_text' => $this_msg, 'element_order' => $x));
                } else {
                    //we need to run an update
                    $wpdb->query($wpdb->prepare("UPDATE smartbot_menus SET element_title=%s ,element_type=%s,item_url=%s,element_text=%s,element_order=%s WHERE item_id=%s AND user_id=%s AND element_name=%s", $this_title, $this_type, $this_url, $this_msg, $x, $this_item, $userId, $menu_type));
                }
                $y++;
            }
            $x++;
        }
        if ($y > 0) {
            //ok we have at least one submenu items and this means the main item is now a menu with a submenu...need to set it like that
            $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_menus where element_name='menu' AND item_id=%s", $menuId));
            if ($num_rows < 1) {
                $wpdb->insert('smartbot_menus', array('page_id' => $pageId, 'bot_id' => $bot_id, 'user_id' => $userId, 'element_name' => 'menu', 'item_id' => $menuId, 'element_title' => $menu_title, 'element_type' => 'submenu'));
            } else {
                $wpdb->query($wpdb->prepare("UPDATE smartbot_menus SET element_type='submenu' WHERE item_id=%s AND element_name='menu'", $menuId));
            }
        }
    }
}

/**
 * Facebook Prefill Sticky Menu Function
 * This part looks if there are any sticky menu items already in teh db and fetches it and displays the HTML for it
 */
function smartbot_sticky_menu_prefill($pageId, $bot_id)
{
    global $wpdb;
//check if we have menu items for this page/bot
    if ($pageId != "") {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE element_name='menu' AND page_id=%s", $pageId), ARRAY_A);
    }

    if (!empty($results) && is_array($results)) {
        $sticky_rows = smartbot_sticky_menu_rows($pageId, $results, '', 'menu', '');
        return $sticky_rows;
    } else {
        //we have a total empty menu here...lets fill the 3 main menu items
        $new_menu = '';
        for ($i = 0; $i < 3; $i++) {
            $new_menu .= smartbot_menu_rows($pageId, $i, '', '', 'menu', '', '', '', 'empty', '', '');
        }
        return $new_menu;
    }
}


/**
 * Facebook Prefill Sticky Menu Number Function
 * This part looks if there are any sticky menu items already in the db and fetches the number of results for entering in the form so we do not exceed the limit of 3 main and 5 submenu items
 */
function smartbot_sticky_menu_prefill_num($pageId, $menuId, $type)
{
    global $wpdb;
    $item_num = '';

//check if we have menu items for this page/bot
    if ($pageId != "") {
        if ($type == "submenu" || $type == "subsubmenu") {
            $menuId = smartbot_get_top_main($menuId, $pageId);
        } else {
            $menuId = "";
        }
        $item_num = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_menus WHERE element_name=%s AND page_id=%s AND main_id=%s", $type, $pageId, $menuId));
    }
    return $item_num;
}


/**
 * Facebook Prefill Sticky SubMenu Function
 * This part looks if there are any sticky menu items already in teh db and fetches it and displays the HTML for it
 */
function smartbot_sticky_submenu_prefill($pageId, $menuId, $type, $menu_type)
{
    global $wpdb;
    $sticky_rows = '';
//check if we have menu items for this page/bot/menu item
    if ($menu_type == "menu") {
        $menu_type = "submenu";
    } else {
        $menu_type = "subsubmenu";
    }
    if ($pageId != "") {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_menus WHERE element_name=%s AND page_id=%s AND main_id=%s ORDER BY element_order ASC", $menu_type, $pageId, $menuId), ARRAY_A);
    }

    if (!empty($results) && is_array($results)) {
        $sticky_rows = '<ol id="' . $menuId . '_menu_items">' . smartbot_sticky_menu_rows($pageId, $results, $menuId, $type, '') . '</ol>';
    }

    return $sticky_rows;
}


/*
 *
 */
function smartbot_update_menu_title($pageId, $item_id, $menu_title)
{
    global $wpdb;
    if (isset($item_id) && isset($pageId)) {
        $wpdb->query($wpdb->prepare("UPDATE smartbot_menus SET element_title=%s WHERE page_id=%s AND item_id=%s ", $menu_title, $pageId, $item_id));
    }
}

/**
 *  Get edit menu items...used to retrieve the menu item data and display it in the popup for the edit
 */

function smartbot_edit_menu_item($pageId, $userId, $menuId, $item_type)
{
    global $wpdb;
    $menu_item = '';
    if ($pageId != "" && $menuId != "") {
        //let's see if we have the item type filled already
        if ($item_type == "submenu") {
            $menu_item = '<span>Create a submenu for this menu item</span>';
        }


        if ($item_type == "web_url") {
            $this_var = $wpdb->get_var($wpdb->prepare("SELECT item_url FROM smartbot_menus WHERE page_id=%s AND item_id=%s", $pageId, $menuId));
            $menu_item = '<div>
                            <span style="float:left;margin-right: 5px;padding-top: 10px;">
                                <label>Url:</label>
                            </span>
                             <span style="float:left;width:70%">
                                <input type="text" name="' . $menuId . '_url_input" id="' . $menuId . '_url_input" class="form-control input-lg url_input" data-id="' . $menuId . '" value="' . $this_var . '">
                             </span>
                        </div>';
        }

        if ($item_type == "postback") {
            $this_var = $wpdb->get_var($wpdb->prepare("SELECT element_text FROM smartbot_menus WHERE page_id=%s AND item_id=%s", $pageId, $menuId));
            $menu_item = '<div>
                            <span style="float:left;margin-right: 5px;padding-top: 10px;">
                                <label>Message:</label>
                            </span>
                             <span style="float:left;width:70%">
                                <select name="' . $menuId . '_msg_input" id="' . $menuId . '_msg_input" class="form-control input-lg msg_input" data-id="' . $menuId . '">';

            if ($this_var == "") {
                $menu_item .= '<option value="">Select a Message</option>';
            }

            $results = $wpdb->get_results($wpdb->prepare("SELECT smartbot_msgs.id,smartbot_msgs.msg_name,smartbot_msgs.msg_uniqid,smartbot_msgs.msg_type,smartbot_flows.name as flow_name FROM smartbot_msgs,smartbot_flows WHERE smartbot_msgs.page_id=%d AND smartbot_msgs.msg_type!='welcome' AND smartbot_msgs.msg_type!='typing' AND smartbot_msgs.flow_id=smartbot_flows.id ORDER BY smartbot_flows.name,smartbot_msgs.msg_name ", $pageId), ARRAY_A);
            if (isset($results) && is_array($results)) {
                foreach ($results as $this_result) {
                    $this_msg_id = $this_result['id'];
                    $flow_name = $this_result['flow_name'];
                    $msg_name = trim($this_result['msg_name']);
                    if ($msg_name == "") {
                        $msg_name = smartbot_get_msgname($this_result['msg_type']);
                    }
                    $menu_item .= '<option value="' . $this_msg_id . '"';
                    if ($this_msg_id == $this_var) {
                        $menu_item .= ' selected="selected"';
                    }
                    $menu_item .= '>' . $flow_name . ' - ' . $msg_name . '</option>';
                }
            }

            $menu_item .= '</select>
                             </span>
                        </div>';

        }
    }
    return $menu_item;
}

function smartbot_preview_menu_page($userId, $pageId, $menuId, $menu_type, $main_title)
{
    global $wpdb;
    $menu_page = '';
    $short_menu = '';
    $y = 0;
    if ($menuId != "" && $pageId != "") {
        $results = $wpdb->get_results($wpdb->prepare("SELECT item_id,element_name,element_title,element_type FROM smartbot_menus WHERE main_id=%s AND page_id=%s", $menuId, $pageId), ARRAY_A);
        if ($menu_type == 'menu') {
            $short_menu = 'sub';
        }
        if ($menu_type == 'submenu') {
            $short_menu = 'subsub';
        }
        $menu_page = '<div id="' . $short_menu . '_back" class="menu-text preview_menu_' . $short_menu . '_back" data-menu_id="' . $short_menu . 'back">' . $main_title . '</div>';
        for ($x = 0; $x < 5; $x++) {
            $y = $x + 1;
            $thisclass = "";
            $this_title = "";
            if (isset($results[$x])) {
                $this_id = $results[$x]['item_id'];
                $this_title = $results[$x]['element_title'];
                $this_type = $results[$x]['element_type'];
                if ($this_type == "submenu" || $this_type == "subsubmenu") {
                    $thisclass = 'preview-has-children';
                }
            }
            if ($this_title != "") {
                $menu_page .= '<div id="' . $short_menu . '_menu' . $y . '" class="menu-text preview_menu_item_' . $short_menu . ' ' . $thisclass . '" data-menu_type="' . $menu_type . '" data-id="' . $this_id . '">' . $this_title . '</div>';
            }
        }
    }
    $menu_page = '<div class="message-handle"></div>' . $menu_page;
    return $menu_page;
}


function errorHandling($error, $pageId)
{
    //we got several types of errors. The main focus for now though is on error 190 ->Expired or invalid page token. Any other error we do store but we have no action attached to it (yet)
    //error is a json string. Lets log it for a moment for testing purposes
    $errorMsg = '';
    if ($error != "" && $pageId != "" && isset($error['error']['code'])) {
        $errorCode = $error['error']['code'];
        if (isset($error['error']['message'])) {
            $errorMsg = $error['error']['message'];
        }
        //check if we have this error already, if not we go further else we ignore it for now as we have this error already listed in the db
        global $wpdb;

        $numRows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM error_codes WHERE page_id=%s AND error_code=%s", $pageId, $errorCode));
        if ($numRows < 1) {
            $now = date("Y-m-d H:i:s");
            $wpdb->insert('error_codes', array('page_id' => $pageId, 'error_code' => $errorCode, 'error_msg' => $errorMsg, 'error_date' => $now));
            if ($errorCode == "190") {
                errorExpiredToken($pageId);
            }
        }
    }
}

function errorExpiredToken($pageId)
{
    //we have a code 190 on our hands. This means an expired or invalid page token. We need to send out an email and set the needs_refresh on the pages table to 1
    global $wpdb;
    $result = updatePageNeedRefresh($pageId, 1);
    //lets see if we have an email on file for this user, if so we need to send an email. Later we can extend this function by also sending a Messenger msg thrugh our Support bot
    $emailNames = getEmailUser($pageId);
    $pageName = getPageName($pageId);
    $_SESSION['need_refresh'][$pageId] = 1;

    if ($emailNames != "0") {
        //we got results so we can send an email
        foreach ($emailNames as $thisUser) {
            sendMailTokenRefresh($thisUser['email'], $pageName);
        }
    }
}

function getPageToken($page_id)
{
    global $wpdb;

    $page_data = $wpdb->get_row($wpdb->prepare("SELECT page_token FROM smartbot_pages WHERE page_id=%s", $page_id), ARRAY_A);
    return $page_data['page_token'];
}

function getPageTokenAndActive($page_id)
{
    global $wpdb;

    $page_data = $wpdb->get_row($wpdb->prepare("SELECT id,page_token,active_page FROM smartbot_pages WHERE page_id=%s", $page_id), ARRAY_A);
    return $page_data;
}

function getPageData($page_id)
{
    global $wpdb;

    $page_data = $wpdb->get_row($wpdb->prepare("SELECT id,page_id,user_id,page_title,page_token FROM smartbot_pages WHERE page_id=%s", $page_id));
    return $page_data;
}

function getPageDataFromIndex($id)
{
    global $wpdb;

    $page_data = $wpdb->get_row($wpdb->prepare("SELECT id,page_id,user_id,page_title,page_token FROM smartbot_pages WHERE id=%d", $id));
    return $page_data;
}

function requestFbPageDetails($pageId, $pageToken){
    // Doc: https://developers.facebook.com/docs/graph-api/reference/page/
    $parameters = 'username,name,category,about,cover';
    $url = 'https://graph.facebook.com/v2.8/' . $pageId . '/?fields=' . $parameters.'&access_token='.$pageToken;
    $res = smartbot_get_url($url);
    $res = json_decode($res);
    return $res;
}

function postToPage($pageId,$accessToken,$message){
    $url = "https://graph.facebook.com/$pageId/feed?&access_token=$accessToken";
   $data = "message=$message";
    echo(smartbot_curl($url,$data,"post"));
}

function getUserPagesData($userId){
    global $wpdb;

    $query = $wpdb->prepare("SELECT * from smartbot_pages WHERE page_id in (SELECT page_id FROM smartbot_page_owners WHERE user_index_id = %d)",$userId);
    $results = $wpdb->get_results($query);
    return $results;
}

function getUserPages($userId){
    global $wpdb;

    $query = $wpdb->prepare("SELECT DISTINCT(page_id) from smartbot_page_owners WHERE user_index_id = %d",$userId);
    $results = $wpdb->get_results($query);
    return $results;
}
/*
 * Validate page owner
 */

function validatePageOwner($userIndex,$fbPageId){
    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM smartbot_page_owners WHERE  user_index_id = %d AND page_id=%s LIMIT 1",$userIndex,$fbPageId));
    if(!empty($id)){
        return true;
    }
    else{
        return false;
    }
}
/*
 * returns the page list HTML structure for private template page selection modal on index.php
 */
function getPageListForTemplatesHtml(){
    global $wpdb;
    $fbPages = $wpdb->get_results($wpdb->prepare("SELECT smartbot_pages.* FROM smartbot_pages,smartbot_page_owners WHERE smartbot_page_owners.user_index_id=%s AND smartbot_page_owners.page_id=smartbot_pages.page_id AND smartbot_page_owners.active_page = '1' GROUP BY smartbot_pages.page_id ORDER BY page_title",$_SESSION["user"]["id"]),ARRAY_A);

    $html = '<div class="col-lg-12">
                <div class="">
                    <div class=""><input id="pageSearch" class="filter form-control live-chat-search-sub-form" style="font-size:20px;" type="text" placeholder="Search page..." title="Type in a name"></div>
                    <div class="">
                        <div class="scroll_content">
                            <div id="forum-list">
                                <div class="forum-title" style="display:none;"><h3>Pages</h3></div>';
    foreach($fbPages as $page){
        $pageScanCode='';
        $fbPageId='';
        $id = $page['id'];
        if(isset($page['page_id'])){
            $fbPageId = $page['page_id'];
        }
        if(isset($page["page_title"])){
            $pageName = $page["page_title"];
        }
        if(isset($page['page_category'])){
            $pageCat = $page['page_category'];
        }
        if(isset($page['page_image'])){
            if(true){
                //load page image from FB
                $pageImage = 'https://graph.facebook.com/' . $fbPageId . '/picture?type=normal';
            }
            else{
                //load page image from our CDN
                $pageImage = $page['page_image'];
            }
        }
        if(isset($page['page_token'])){
            $pageToken= $page['page_token'];
        }
        if(isset($page['page_alias'])){
            $pageAlias = $page['page_alias'];
        } else {
            $pageAlias = "";
        }
        if(isset($page['page_scan_code'])){
            $pageScanCode = $page['page_scan_code'];
        }
        if(isset($page['page_desc'])){
            $pageDesc = $page['page_desc'];
        }
        $html = $html.'<div class="forum-item active" data-page-name="'.$pageName.'">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="forum-icon"><img src="https://graph.facebook.com/'.$fbPageId.'/picture?type=normal" class="img-circle" style="width:50px;"></div>
                                    <span class="forum-item-title" style="font-size: 18px;font-weight: 500;">'.$pageName.'</span>
                                    <div class="forum-sub-title">Category: '.$pageCat.'</div>
                                </div>
                                <div class="col-md-4 forum-info">
                                    <form method="post" action="dashboard.php?template_installed=true" style="display:none;">
                                         <input type="hidden" name="action2" value="settings">
                                         <input type="hidden" name="page_cat" value="'.$pageCat.'">
                                         <input type="hidden" name="page_image" value="'.$pageImage.'">
                                         <input type="hidden" name="page_name" value="'.$pageName.'">
                                         <input type="hidden" name="page_token" value="'.$pageToken.'">
                                         <input type="hidden" name="page_id" value="'.$fbPageId.'">
                                         <input type="hidden" name="page_alias" value="'.$pageAlias.'">
                                         <input type="hidden" name="page_scan_code" value="'.$pageScanCode.'">
                                         <input type="hidden" name="page_desc" value="'.$pageDesc.'">
                                         <input type="submit" name="submit" id="template_install_'.$fbPageId.'">
                                     </form>
                                    <form method="post">
                                        <button type="button" data-page_id="'.$fbPageId.'" data-page_index_id="'.$id.'" class="btn btn-w-m btn-primary install_page_template">Install</button>
                                    </form>
                                </div>
                            </div>
                        </div>';
    }

    $html=$html.          '</div>
                        </div>
                    </div>
                </div>
            </div>';
    return $html;
}

function importGreetingText($sourcePageId,$destinationPageId){
    global $wpdb;

    $query = "DELETE FROM smartbot_greeting WHERE page_id = '$destinationPageId';
              ";
    $wpdb->query($query);

    $query = "INSERT INTO smartbot_greeting (page_id, greeting_type, greeting_lang_code, greeting_lang_name, greeting_text)   
              SELECT  '$destinationPageId', greeting_type, greeting_lang_code, greeting_lang_name, greeting_text
              FROM smartbot_greeting
              WHERE page_id = '$sourcePageId';
              ";

    $result = $wpdb->query($query);


    if ($result) {
        setPageGreeting($destinationPageId);
        return $result;
    }
    else
        return 0;
}

function importWelcomeMessage($sourcePageId,$destinationPageId,$flowsMap){

    $flowId = $flowsMap[getWelcomeMessage($sourcePageId)];
    setWelcomeMessage($destinationPageId,$flowId);
    $welcomeMessageStatus = getWelcomeMessageStatus($sourcePageId);
    setWelcomeMessageStatus($destinationPageId,$welcomeMessageStatus);

}

function importDefaultReply($sourcePageId,$destinationPageId,$flowsMap){

    $flowId = $flowsMap[getDefaultReply($sourcePageId)];
    setDefaultReply($destinationPageId,$flowId);
    $defaultReplyStatus = getDefaultReplyStatus($sourcePageId);
    setDefaultReplyStatus($destinationPageId,$defaultReplyStatus);

}