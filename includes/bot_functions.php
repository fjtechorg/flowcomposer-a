<?php

     /**
   * Create Bot Function
   * expecting a user_id, name & type
   * Outputs a variable holding the bot_id
   */ 
 function createBot($userId, $botName, $botType){
 global $wpdb;
 $now=date("Y-m-d H:i:s");
 $botId = smartbot_create_unique_id();
 $wpdb->insert('smartbot_bots', array('id' => $botId,'user_id' => $userId,'bot_name' => $botName,'bot_type' => $botType, 'last_update' => $now));

 return $botId;
 }  
 
     /**
   * Connect Bot Function
   * expecting a user_id, page_id, bot_id and bot_page
   * Bot page checks to see if it's a new connect, a disconnect from this page or a disconnect from an other page
   */  
function connectBotToPage($pageId, $botId, $botPage, $userId, $showOutput=true, $fetchAnalytics=false){
global $wpdb;
//see if we need to disconnect and if it's a disconnect which page we need to disconnect. We can have 3 possible options
//total new connect and no previous connections..this case the bot_page should be empty, a disconnect from this page then bot_page and page_id are the same
// and last but not least a switch between connections which means the bot_page is filled but not the same as this page id and we need to connect to the page and disconnect to the bot_page

  if($botId!="" && $pageId!="" && $botPage==""){
  //total new connection
      if ($showOutput)
  echo 'Success, connected your bot to this page';
  $connectionType='new';
  }
  
  if($botId!="" && $pageId!="" && $botPage==$pageId){
      if ($showOutput)
          echo 'DisconnectThis';
  $connectionType='disconnect_this';
  DisconnectBotFromPage($pageId,$botId,$userId);
  }
  
  if($botId!="" && $pageId!="" && $botPage!="" && $botPage!=$pageId){
      if ($showOutput)
          echo 'DisconnectBotPage';
  $connectionType='disconnect_other';
  DisconnectBotFromPage($botPage,$botId,$userId);
  }
  
  if($connectionType=='new' || $connectionType=='disconnect_other'){


      //update the db set bot id at the pages table
	  $wpdb->query($wpdb->prepare("UPDATE smartbot_page_owners SET active_page=1 WHERE page_id=%s AND user_index_id=%s",$pageId,$_SESSION['user']['id']));
      //update the db tables msg, elements, buttons, triggers 

      //create the webhook for this page and update the page table
      $rij = $wpdb->get_row($wpdb->prepare("SELECT page_token FROM smartbot_pages WHERE page_id=%s",$pageId),ARRAY_A);
      $pageToken = $rij['page_token'];
      smartbot_subscribe_app_page($pageToken,$pageId);

      /*
       * removed initial analytics fetching from bot creation process
      if ($fetchAnalytics) {
          //check if this page exists in analytics data table, if it does not exist then add historic data for this page
          $checkPageExistsinAnalytics = $wpdb->get_var($wpdb->prepare("SELECT Count(*) FROM analytics_data WHERE page_id=%s", $page_id));
          if (empty($checkPageExistsinAnalytics)) {
              setInitialFbAnalyticData($page_id, $page_token);
          }
          else{
              updateAnalyticsData($page_id, $page_token);
          }
      }
      */
      $pageScanCode = smartbot_check_messenger_code($pageId,$pageToken);
      $_SESSION['new_bot_scan_code'] = $pageScanCode;

      // Set welcome and default messages if already set

      //insert the menu
   //   updateFacebookPersistentMenu($page_id,$user_id);
   }
   
   
   
}

function DisconnectBotFromPage($pageId, $botId, $userId){
global $wpdb;
  //update the db tables msg, elements, buttons, triggers 
  $wpdb->query($wpdb->prepare("UPDATE smartbot_bots SET bot_page='' WHERE id=%s",$botId));
  $numRows =$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) from smartbot_bots where bot_page=%s AND user_id=%s",$pageId,$userId));
    		if($numRows<1){
  			//we need to put the webhook at blank, else all is ok
			$wpdb->query($wpdb->prepare("UPDATE smartbot_pages SET webhook_url='' WHERE page_id=%s",$pageId));
  			}
}


    /**
   * Update Bot Function
   * expecting a bot_id, bot_active_users & bot_subscribers
   * Outputs an array with all Bot details
   */ 
function updateBot($botId, $botActiveUsers, $botSubscribers){
 global $wpdb;
 if($botId!="" && $botActiveUsers!="" &&$botSubscribers!="" ){
 $wpdb->query($wpdb->prepare("UPDATE smartbot_bots SET bot_active_users=%s,bot_subscribers=%s WHERE id=%s",$botActiveUsers,$botSubscribers,$botId));
 }
}

/*
 *
 */
function getNumBots($userId){
    global $wpdb;
    $numRows =$wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) from smartbot_page_owners where user_id=%s AND webhook_url='subscribed'",$userId));
    return $numRows;
}
 
   /**
   * Get Bot Name Function
   * expecting a bot_id
   * Outputs an array with all Bot details
   */ 
 function getBotName($botId){
  global $wpdb;
  $userId = $_SESSION['user_id'];
  $botDetails = $wpdb->get_row($wpdb->prepare("SELECT * FROM smartbot_bots WHERE id=%s AND user_id=%s",$botId,$userId),ARRAY_A);
  return $botDetails;
 }
 
   /**
   * Delete Bot Function
   * expecting a user_id and a bot_id
   * deletes the bot with all data...double asked if they are sure
   */
function deleteBot($pageId, $userId){
      global $wpdb;

    if(isset($pageId) && isset($userId)) {
        	    //very first thing...lets make the page inactive for this user from page owners
        	    $wpdb->query( $wpdb->prepare( "DELETE FROM smartbot_page_owners WHERE page_id=%s AND user_index_id=%s", $pageId, $userId ) );
        	    //lets check if we are the last of the mohikans or are there any other admins left? if there are more then we just remove the page for this user which is done above
        	    $numAdmins = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM smartbot_page_owners WHERE page_id=%s AND active_page=1", $pageId ) );

        	   if ( $numAdmins < 1 ) {

        	       //get the connected page if any
                   $wpdb->query( $wpdb->prepare( "UPDATE smartbot_pages SET webhook_url='' WHERE page_id=%s", $pageId ) );
                   $wpdb->query( $wpdb->prepare( "DELETE FROM smartbot_integration WHERE page_id=%s", $pageId ) );
                   $wpdb->query( $wpdb->prepare( "DELETE FROM smartbot_tags WHERE page_id=%s ", $pageId ) );

	    }
    }
}