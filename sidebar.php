<?php
$livechat_navbar_background_color='';
$sticky_menu='';
$unread='';

if(isset($_SESSION['page_id'])){
    $unreadStatus  = $unread>0 ? 'true' : 'false';

}
if($side_bar_exclusion != 1){
?>    

<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu" style="min-height: 100vh;">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <?php echo $profile['profile_name'];?>
                             </span></span> </a>
                    </div>
                    <div class="logo-element">
                     <a href="index.php"><img class="dashboard-logo-img" src="<?php echo $_SESSION['brand']['nav_logo_left'];?>"></a>
                    </div>
                </li>
				
                <?php
				//$this_page = filter_input(INPUT_GET,'page');
                $this_page = basename($_SERVER['PHP_SELF'], '.php');
                $pageLevel = pageSpecificPages($this_page);
				if($pageLevel ==1){

				    //ok we have a page specific page..this means we need a page_id...if not lets redirect back to the main dashboard
                    //let's see if this user should be able to see this page anyway...page_id is $_SESSION['page_id'] and user_id is in $_SESSION['user_id']
                    global $wpdb;
                    $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM smartbot_page_owners WHERE page_id=%s AND user_index_id=%s",$_SESSION['page_id'], $_SESSION['user']['id']));



                    if($num_rows < 1){unset($_SESSION['page_id']);}

                ?>

                <li id="page_dashboard" class="not_automate">
                    <a href="dashboard.php" class="sidebar_link" data-link="dashboard"
                       data-toggle="tooltip" title="Dashboard" data-placement="right"><i
                                class="icon-speed-fast"></i> <span class="nav-label">Dashboard</span></a>
                </li>

                <li id="page_analytics" class="not_automate" style="display:none;">
                        <a href="analytics.php" class="sidebar_link" data-link="analytics"
                           data-toggle="tooltip" title="Analytics" data-placement="right"><i
                                    class="icon-chart-growth"></i> <span class="nav-label">Analytics</span></a>
                </li>



                    <li id="subs_ctrl" class="not_automate page_icon_li">
                    <a href="#" class="sidebar_link" data-link="audience" data-toggle="tooltip" title="Audience" data-placement="right"><i class="icon-users2"></i> <span class="nav-label">Audience</span></a>
                    <ul class="nav nav-second-level collapse" id="ul_lead_catchers">
                        <li id="page_audience"><a href="audience.php" class="" data-link="audience">Audience</a></li>
                        <li id="page_segmentation"><a href="segmentation.php"  class="" data-link="segmentation">Segmentation</a></li>
                    </ul>

                </li>
				<li id="page_live_chat"  class="not_automate">
                    <a href="livechat.php"  class="sidebar_link" data-link="live_chat" data-toggle="tooltip" title="Live Chat <?php echo "($unread)" ?>" data-placement="right"><i class="icon-bubble" style="display: inline-flex;"><span class="label label-info pull-right unread-<?php echo $unreadStatus ?>" id="unreads" ></span></i> <span class="nav-label">Live Chat</span></a>

                </li>
                <li id="page_lead_catchers"   class="not_automate page_icon_li">
                    <a href="#" class="sidebar_link" data-link="lead_catchers" data-toggle="tooltip" title="Capture" data-placement="right"><i class="icon-magnet"></i> <span class="nav-label">Capture</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse" id="ul_lead_catchers">
                        <li id="page_widgets"><a href="widgets.php"  class="" data-link="">Send to Messenger</a></li>
                        <li id="page_messenger_checkbox"><a href="messenger_checkbox.php"  class="" data-link="messenger_checkbox">Checkbox Plugin</a></li>
                        <li id="page_messenger_chat"><a href="messenger_chat.php"  class="" data-link="messenger_chat">Customer Chat</a></li>
                        <li id="page_messenger_code"><a href="messenger_code.php"  class="" data-link="">Messenger Code</a></li>
                        <li id="page_post_engagement"><a href="post_engagement.php"  class="bot_instant_reply" data-link="instant_reply">Post Engagement</a></li>
                        <li id="page_ref_library" class=""><a href="ref_library.php"  class="" data-link="reflibrary">M.ME Link Library</a></li>
                        <li id="page_json_library" class=""><a href="json_library.php"  class="" data-link="json_library">JSON Generator</a></li>
                    </ul>
                </li>   
				<li id="page_sent">
                    <a href="sent.php" class="sidebar_link" data-link="sent" data-toggle="tooltip" title="Broadcasts" data-placement="right"><i class="icon-bullhorn"></i> <span class="nav-label">Broadcasts</span></a>
                </li>

				<li id="automate" class="page_icon_li">
                    <a href="#" data-toggle="tooltip" title="Automation" data-placement="right"><i class="icon-magic-wand"></i> <span class="nav-label">Automation</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse" id="ul_automate">

                        <li id="page_flows"><a href="flows.php"  class="sidebar_link" data-link="visual">Flows</a></li>
                        <li id="page_ai_triggers"><a href="ai_triggers.php" class="sidebar_link" data-link="ai_triggers">AI Triggers</a></li>
                        <li id="page_main_menu_list"><a href="main_menu_list.php" class="sidebar_link" data-link="main_menu">Main Menu</a></li>
                        <li id="page_customfields"><a href="customfields.php" class="sidebar_link" data-link="custom_fields">Custom Fields</a></li>
                        <li id="page_global_fields"><a href="global_fields.php" class="sidebar_link" data-link="custom_fields">Global Fields</a></li>
                        <li id="page_tags"><a href="tags_manager.php" class="sidebar_link" data-link="tags">Tags</a></li>
                        <!--<li id="page_integrations"><a href="integrations_manager.php" class="sidebar_link" data-link="integrations">Integrations</a></li>-->

                    </ul>
                </li>
                <li id="templates" class="page_icon_li">
                    <a href="#" data-toggle="tooltip" title="Templates" data-placement="right"><i class="icon-layers"></i> <span class="nav-label">Templates</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse" id="ul_automate">
                        <li id="page_templates"><a href="templates.php"  class="sidebar_link" data-link="visual">Marketplace</a></li>
                        <li id="page_installed_templates"><a href="installed_templates.php" class="sidebar_link" data-link="installed_templates">Installed templates</a></li>
                    </ul>
                </li>
                <li id="page_stats" class="not_automate page_icon_li" style="">
                    <a href="analytics.php" class="sidebar_link" data-link="stats"
                       data-toggle="tooltip" title="Analytics" data-placement="right"><i
                                class="icon-graph"></i> <span class="nav-label">Analytics</span></a>
                </li>
                <li id="page_manage"  class="not_automate">
                    <a href="manage.php?action2=settings" class="sidebar_link" data-link="settings" data-toggle="tooltip" title="Configure" data-placement="right"><i class="icon-cog"></i> <span class="nav-label">Configure</span></a>
                </li>
                <?php if($_SESSION['whitelabel']===0){ ?>
                    <li style="position: absolute;bottom: 50px;width: 100%;">
                    <a href="#" data-toggle="tooltip" title="Help" data-placement="right"><i class="icon-lifebuoy" >
                    </i> <span class="nav-label">Support</span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse" style="bottom: 0;top: auto;">
                                         <li><a href="https://docs.clevermessenger.com/" target="_blank">Knowledgebase</a></li>
                                         <li><a href="https://status.clevermessenger.com/" target="_blank">Status</a></li>
                                         <li><a href="https://www.facebook.com/groups/clevermessenger/" target="_blank">Community</a></li>
                                    </ul>
                    </li>
                <?php } else { ?>
                    <li style="position: absolute;bottom: 50px;width: 100%;">
                    <a href="<?php echo $_SESSION['brand']['link_support']; ?>" target="_blank" class="sidebar_link" data-toggle="tooltip" title="Help" data-placement="right"><i class="icon-lifebuoy"></i> <span class="nav-label">Support</span></a>
                    </li>
                <?php } ?>

                 <li style="position: absolute;bottom: 0;width: 100%;">
                    <a href="#" style="" data-toggle="tooltip" title="Account" data-placement="right"><i class="icon-user" >
                    </i> <span class="nav-label">Account</span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse" style="bottom: 0;top: auto;">
                                        <li><a href="index.php" class="sidebar_link">Back to overview</a></li>
                                        <li><a href="profile.php" class="sidebar_link">Account</a></li>
                                        <?php if($_SESSION['whitelabel']==1){?>
                                        <li><a href="billing.php" class="sidebar_link" data-link="billing">Billing</a></li>
                                        <?php }?>
                                        <li><a href="logout.php" class="sidebar_link">Log Out</a></li>
                                    </ul>
                 </li>
				<?php
				}
				?>
				
					
            </ul>
        <div style="width: 100%; text-align:center; margin-top: -7vh;" id="sidebar_logo_div">
            <a href="index.php"><img class="sb_logo" src="<?php echo $_SESSION['brand']['nav_logo_top'];?>" /></a>
            <!--<img class="sb_logo_mini" src="images/logo_mini_grey.png" style="display: none;" width="50" />-->
        </div>
<?php
    $sticky_menu = "";
//$pageLevel = pageSpecificPages($this_page);
if($pageLevel ==1){

         $sticky_menu = "navbar-bot-level";
    // if current page is at bot level and not live chat then navbar will be sticky
   /* if($this_page!="live_chat")
    {
    $sticky_menu = "navbar-bot-level";
    }*/
    // if current page is live chat then set this background color
    /* if($this_page=="live_chat")
     {
         $livechat_navbar_background_color = 'background:#F9FAFC';
     }*/

?>	        
<style>

.wrapper-content {
    padding: 80px 0px 0px;
}
.wrapper-tables {
    padding: 80px 0 0;
}
body.mini-navbar  img.sb_logo {
    display:none !important;
}
.navbar-index {
    display: none;
}
.navbar-static-top {
   /* padding-bottom: 4px; */
}
</style>

<script type="text/javascript">
// sidebar related js
jQuery(window).on("load", function(){
    $('.nav-second-level').removeClass('in');

});



//sidebar related js end

var ThisHeight = $(window).height();
if(ThisHeight < 770){

$(document).on('click', '#automate', function (){	
//if there is a click on the automate id we need to know if this is a collapse or the opposite. On collapse we need to set the 
//logo style to position relative. Else back to absolute...
var AutomateCollapsed = $(this).find('ul[aria-expanded]').attr('aria-expanded');
      if (AutomateCollapsed==='true') {
      $('#sidebar_logo_div').css('position', 'relative');
	  }else{
	  $('#sidebar_logo_div').css('position', 'absolute');
	  }
});

$(document).on('click', '#page_engage', function (){	
//if there is a click on the page_engage id we need to know if this is a collapse or the opposite. On collapse we need to set the 
//logo style to position relative. Else back to absolute...
var AutomateCollapsed = $(this).find('ul[aria-expanded]').attr('aria-expanded');
      if (AutomateCollapsed==='true') {
      $('#sidebar_logo_div').css('position', 'relative');
	  }else{
	  $('#sidebar_logo_div').css('position', 'absolute');
	  }  
});

//if we have a screen smaller and go from one of the automate parts to an other we need to set the logo at absolute again.
$(document).on('click', '.not_automate', function (){	
	$('#sidebar_logo_div').css('position', 'absolute');
});

}


$(document).ready(function(){
var ThisHeight = $(window).height();

var ThisPage = '<?php  echo basename($_SERVER['PHP_SELF'], '.php');  ?>';
jQuery('#side-menu').removeClass('activePage');
jQuery('#page_'+ThisPage).addClass('activePage');
jQuery('#page_'+ThisPage).closest('.page_icon_li').addClass('activePage');

    switch (ThisPage) {
        case 'segment_builder':
            jQuery('#subs_ctrl').addClass('activePage');
            break;
        case 'widget_builder':
        case 'checkbox_builder':
        case 'customerchat_builder':
        case 'messenger_code_builder':
        case 'post_engagement_builder':
            jQuery('#page_lead_catchers').addClass('activePage');
            break;
        case 'schedule_broadcast':
            jQuery('#page_sent').addClass('activePage');
            break;
        case 'visual':
        case 'main_menu':
            jQuery('#automate').addClass('activePage');
            break;
    }
//if we have a page inside an ul then we need to set the class of that ul to
if(ThisPage==='lead_catchers' || ThisPage==='bot_instant_reply' || ThisPage==='messenger_button' || ThisPage==="messenger_chat" || ThisPage=== "messenger_checkbox" || ThisPage==='widget_builder'|| ThisPage==='checkbox_builder'|| ThisPage==='widgets' || ThisPage==='messenger_code' || ThisPage==='messenger_code_builder' || ThisPage==='ref_library' || ThisPage==='json_library'){
jQuery('#side-menu').removeClass('in');
jQuery('#ul_lead_catchers').addClass('collapse');
jQuery('#ul_lead_catchers').addClass('in');
}

if(ThisPage==='schedule_broadcast' || ThisPage==='sent' ||  ThisPage==='scheduled' || ThisPage==='sequence' || ThisPage==='autoposting'){
jQuery('#side-menu').removeClass('in');
jQuery('#ul_new_message').addClass('collapse');
jQuery('#ul_new_message').addClass('in');
if(ThisHeight < 770){
$('#sidebar_logo_div').css('position', 'relative');
}
}

if(ThisPage==='configure'|| ThisPage==='manage'){
jQuery('#side-menu').removeClass('in');
jQuery('#ul_configure').addClass('collapse');
jQuery('#ul_configure').addClass('in');
}
<?php
if($this_page=="visual" ||$this_page=="composer" || $this_page=="flows" || $this_page=="welcome"|| $this_page=="ai_triggers" || $this_page=="edit_keyword" || $this_page=="default_reply" || $this_page=="main_menu" || $this_page=="main_menu_list"){
?>
jQuery('#side-menu').removeClass('in');
jQuery('#ul_automate').addClass('collapse');
jQuery('#ul_automate').addClass('in');
var the_page_id = '<?php echo $_SESSION["page_id"]; ?>'; jQuery('#visual_page_id').val(the_page_id);
var the_bot_id = jQuery('#form_bot_id').val(); jQuery('#visual_bot_id').val(the_bot_id);

if(ThisHeight < 770){
$('#sidebar_logo_div').css('position', 'relative');
}
<?php
}

?>
});



</script>
<?php
}}
?>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg" <?php if($side_bar_exclusion == 1){ echo "style=\"margin-left:0px !important;\"";} ?>>
        <div class="row border-bottom">
		
        <nav class="navbar navbar-static-top <?php echo $sticky_menu; ?>" role="navigation" style="margin-bottom: 0;<?php echo $livechat_navbar_background_color;?>">
         <?php if($side_bar_exclusion != 1){ ?>
        <div class="navbar-header" style="padding: 13px 15px;">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#" style="display: none;"><i class="fa icon-menu"></i></a>
                        <?php
                            echo "<ol class='breadcrumb'>";

                            if(isset($_SESSION['page_alias']) && $_SESSION['page_alias']!=""){
                                $first_crumb = '<a href="https://m.me/'.$_SESSION['page_alias'].'" target="_blank" style="color: #90949c;font-weight: bold;line-height: 18px;margin-top: 4px;word-wrap: break-word;" >@'.$_SESSION['page_alias'].'</a>';
                                $first_crumb = '<a href="https://m.me/'.$_SESSION['page_alias'].'" target="_blank" style="color: #90949c;font-weight: bold;line-height: 18px;margin-top: 4px;word-wrap: break-word;" >@'.$_SESSION['page_alias'].'</a>';
                            }
                            else
                            {
	                            if(isset($_SESSION['template_name']) && $_SESSION['template_name']!=""){$_SESSION['page_name']=$_SESSION['template_name'];}
                                if(isset($_SESSION['page_id'])){$first_crumb = '<a href="https://www.facebook.com/'.$_SESSION['page_id'].'" target="_blank" >'.$_SESSION['page_name'].'</a>';}
                            }

                            //unless of course we have a template on our hands here...lets do a final check
                            if(isset($_SESSION['template_name']) && $_SESSION['template_name']!=""){
                                //ok we do have a template here...so lets add the nav back to it
                                $template_id = $_SESSION['page_id'];
                                $first_crumb = '<a href="./admin/template_blank.php&page=wizardtemplate='.$template_id.'" >'.$_SESSION['template_name'].'</a>';
                            }


                            switch ($this_page) {
                                
                                case "dashboard":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Dashboard</strong>
                                        </li>';
                                    $userpilotTarget = 'dashboard';
                                    break;

                                case "analytics":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Analytics</strong>
                                        </li>';
                                    $userpilotTarget = 'analytics';
                                    break;
                                case "stats":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Analytics</strong>
                                        </li>';
                                    break;
                                case "manage":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Configure</strong>
                                        </li>';
                                    $userpilotTarget = 'configure';
                                    break;

                                case "audience":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Audience</strong>
                                        </li>';
                                    $userpilotTarget = 'audience';
                                    break;


                                    case "segmentation":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Segmentation</strong>
                                        </li>';
                                    $userpilotTarget = 'segmentation';
                                    break;

                                case "segment_builder":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="/audience.php">Audience</a>
                                        </li>
                                        <li>
                                            <a href="/segmentation.php">Segmentation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Builder</strong>
                                        </li>';
                                    $userpilotTarget = 'segment-builder';
                                    break;

                                case "livechat":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Live Chat</strong>
                                        </li>';
                                    $userpilotTarget = 'livechat';
                                    break;

                                case "bot_instant_reply":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Facebook Comments</strong>
                                        </li>';
                                    $userpilotTarget = 'bot-instant-reply';
                                    break;
                                case "post_engagement":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Post Engagement</strong>
                                        </li>';
                                    $userpilotTarget = 'post-engagement';
                                    break;
                                case "post_engagement_builder":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li>
                                            <a href="post_engagement.php">Post Engagement</a>
                                        </li>
                                        <li class="active">
                                            <strong>Builder</strong>
                                        </li>';
                                    $userpilotTarget = 'post-engagement-builder';
                                    break;
                                    case "widget_builder":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li>
                                            <a href="/widgets.php">Send to Messenger Library</a>
                                        </li>
                                        <li class="active">
                                            <strong>Builder</strong>
                                        </li>';
                                    $userpilotTarget = 'send-to-messenger-builder';
                                    break;

                                    case "checkbox_builder":
                                        echo '<li>
                                                '.$first_crumb.'
                                            </li>
                                            <li>
                                                <a href="#">Capture</a>
                                            </li>
                                            <li>
                                                <a href="messenger_checkbox.php">Checkbox Library</a>
                                            </li>
                                            <li class="active">
                                                <strong>Builder</strong>
                                            </li>';
                                        $userpilotTarget = 'checkbox-builder';
                                        break;

                                    case "widgets":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Send to Messenger Library</strong>
                                        </li>';
                                    $userpilotTarget = 'send-to-messenger-library';
                                    break;

                                case "sent":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Engage</a>
                                        </li>
                                        <li class="active">
                                            <strong>Broadcasts</strong>
                                        </li>';
                                    $userpilotTarget = 'broadcasts';
                                    break;
                                case "view_broadcast":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Engage</a>
                                        </li>
                                        <li >
                                            <a href="sent.php">Broadcasts</a> 
                                        </li>
                                        <li class="active">
                                        <strong>View Broadcast</strong>
                                        </li>';
                                    $userpilotTarget = 'view-broadcast';
                                    break;
                                case "ai_triggers":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>AI Triggers</strong>
                                        </li>';
                                    $userpilotTarget = 'ai-triggers';
                                    break;
                                    
                                    case "edit_keyword":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Edit Message & Keyword</strong>
                                        </li>';
                                    $userpilotTarget = 'edit-keyword';
                                    break;

	                            case "messenger_checkbox":
		                            echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Checkbox Library</strong>
                                        </li>';
		                            $userpilotTarget = 'checkbox-library';
		                            break;

	                            case "messenger_chat":
		                            echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Customer Chat Library</strong>
                                        </li>';
		                            $userpilotTarget = 'customer-chat-library';
		                            break;
	                            case "customerchat_builder":
		                            echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li>
                                            <a href="messenger_chat.php">Customer Chat Library</a>
                                        </li>
                                        <li class="active">
                                            <strong>Builder</strong>
                                        </li>';
		                            $userpilotTarget = 'customer-chat-builder';
		                            break;
		                            case "messenger_code":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>Messenger Code</strong>
                                        </li>';
                                    $userpilotTarget = 'messenger-code';
                                    break;
	                            case "messenger_code_builder":
		                            echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li>
                                            <a href="messenger_code.php">Messenger Code</a>
                                        </li>
                                        <li class="active">
                                            <strong>Builder</strong>
                                        </li>';
		                            $userpilotTarget = 'messenger-code-builder';
		                            break;
                                case "ref_library":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>M.ME Link Library</strong>
                                        </li>';
                                    $userpilotTarget = 'ref-library';
                                    break;
                                case "json_library":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Capture</a>
                                        </li>
                                        <li class="active">
                                            <strong>JSON Library</strong>
                                        </li>';
                                    $userpilotTarget = 'json-library';
                                    break;
                                case "scheduled":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Engage</a>
                                        </li>
                                        <li class="active">
                                            <strong>Scheduled Broadcasts</strong>
                                        </li>';
                                    $userpilotTarget = 'scheduled-broadcasts';
                                    break;
                                case "visual":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li>
                                            <a href="flows.php">Flows</a>
                                        </li>
                                        <li class="active">
                                            <strong>Composer</strong>
                                        </li>';
                                    break;
                                case "composer":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li>
                                            <a href="flows.php">Flows</a>
                                        </li>
                                        <li class="active">
                                            <strong>Composer</strong>
                                        </li>';
                                    $userpilotTarget = 'flow-composer';
                                    break;
                                case "flows":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Flows</strong>
                                        </li>';
                                    $userpilotTarget = 'flows';
                                    break;
                                case "agency":
                                    echo '<li>
                                            <a href="agency.php">Agency</a>
                                        </li>
                                        <li class="active">
                                            <strong>Overview</strong>
                                        </li>';
                                    break;
                                case "page_agency":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Page Management</a>
                                        </li>
                                        <li class="active">
                                            <strong>Agency</strong>
                                        </li>';
                                    break;
                                case "customfields":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Custom Fields</strong>
                                        </li>';
                                    $userpilotTarget = 'custom-fields';
                                    break;
                                case "main_menu":
                                    $mainMenuLanguage= '';
                                    if(isset($_GET['language'])){
                                    $mainMenuLanguage = str_replace("%20"," ",$_GET['language']);
                                    }
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li>
                                            <a href="main_menu_list.php">Menu List</a>
                                        </li>
                                        <li class="active">
                                            <strong>'.$mainMenuLanguage.'</strong>
                                        </li>';
                                    $userpilotTarget = 'main-menu';
                                    break;
                                case "main_menu_list":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Menu List</strong>
                                        </li>';
                                    $userpilotTarget = 'main-menu-list';
                                    break;
                                case "schedule_broadcast":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Engage</a>
                                        </li>
                                        <li class="active">
                                            <strong>New Broadcast</strong>
                                        </li>';
                                    $userpilotTarget = 'new-broadcast';
                                    break;
                                case "welcome":
                                echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Welcome Message</strong>
                                        </li>';
                                break;
                                case "default_reply":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Default Reply</strong>
                                        </li>';
                                    break;
                                case "templates":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Templates</strong>
                                        </li>';
                                    $userpilotTarget = 'templates';
                                    break;
                                case "share_template":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Share template</strong>
                                        </li>';
                                    $userpilotTarget = 'share-template';
                                    break;
                                case "manage_template_urls":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="share_template.php">Share Template</a>
                                        </li>
                                        <li class="active">
                                            <strong>Manage Template Links</strong>
                                        </li>';
                                    $userpilotTarget = 'manage-template-links';
                                    break;
                                case "template_installed_overview":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="share_template.php">Share Template</a>
                                        </li>
                                        <li class="active">
                                            <strong>Template Installed Details</strong>
                                        </li>';
                                    $userpilotTarget = 'template-installed-details';
                                    break;
                                case "installed_templates":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li class="active">
                                            <strong>Installed Templates</strong>
                                        </li>';
                                    $userpilotTarget = 'installed-templates';
                                    break;
                                case "tags_manager":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Tags Manager</strong>
                                        </li>';
                                    $userpilotTarget = 'tags-manager';
                                    break;
                                case "integrations_manager":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Integrations Manager</strong>
                                        </li>';
                                    $userpilotTarget = 'integrations-manager';
                                    break;
                                case "global_fields":
                                    echo '<li>
                                            '.$first_crumb.'
                                        </li>
                                        <li>
                                            <a href="#">Automation</a>
                                        </li>
                                        <li class="active">
                                            <strong>Global Fields</strong>
                                        </li>';
                                    $userpilotTarget = 'global-fields';
                                    break;

                                default:
                                    
                            }
                            if(isset($userpilotTarget))
                                $userpilotBreadcrumb = '<a href="#" class="tour-trigger" data-target="'.$userpilotTarget.'"><div class="userpilot-breadcrumb-div"><i class="userpilot-breadcrumb-i ico-info-tour"></i></div></a>';
                            else
                                $userpilotBreadcrumb = '';

                            echo "</ol>".$userpilotBreadcrumb;
                            ?>
        </div>

        <?php
        if(isset($_SESSION['page_alias']) && $_SESSION['page_alias']!=""){
            $page_messenger_preview_link = 'https://m.me/'.$_SESSION['page_alias'];
        }
        else{
            if(isset($_SESSION['page_id'])){
	            $page_messenger_preview_link = 'https://www.messenger.com/t/'.$_SESSION['page_id'];
            }
        }

        /*
         * Action buttons container, use following
         */
        $actionButtonContainerOpen = '<div class="styling-action-buttons-container"><ul class="nav navbar-top-links navbar-right">';
        $actionButtonContainerClose = '</ul></div>';
        switch ($this_page) {
                                
                                case "schedule_broadcast":
                                    echo $actionButtonContainerOpen.'<li id="send_broadcast_sticky" style=""><button type="button" class="btn btn-primary send_broadcast" style="padding: 8px 16px; margin-top: -3px; margin-bottom: -1px;" value="Send Broadcast">Send Broadcast</button>
                                         </li>'.$actionButtonContainerClose;
                                    break;
                                case "sent":
                                    echo $actionButtonContainerOpen. '<li><a href="schedule_broadcast.php"  style="padding: 0;margin: 0;min-height: 0;">
                                             <span class="btn btn-primary" style="padding: 8px 16px;margin-top:-3px"> Send New Broadcast</span>
                                          </a></li>' .$actionButtonContainerClose;
                                    break;
                                case "dashboard":
                                    echo $actionButtonContainerOpen.'<li><a href="'.$page_messenger_preview_link.'" target="_blank" style="padding: 0;margin: 0;min-height: 0;">
                                            <span class="btn btn-primary" style="padding: 8px 16px;margin-top:-3px"><img style="margin-right:2px;width: 20px;height: 20px;" src="/images/cm-messengericon.svg" > Preview on Messenger</span>
                                         </a></li>'.$actionButtonContainerClose;
                                    break;
                                case "analytics":
                                    echo $actionButtonContainerOpen.'<li><a href="'.$page_messenger_preview_link.'" target="_blank" style="padding: 0;margin: 0;min-height: 0;">
                                             <span class="btn btn-primary" style="padding: 8px 16px;margin-top:-3px"><img style="margin-right:2px;width: 20px;height: 20px;" src="/images/cm-messengericon.svg" > Preview on Messenger</span>
                                          </a></li>'.$actionButtonContainerClose;
                                    break;
                                case "stats":
                                    echo $actionButtonContainerOpen.'<li><a href="'.$page_messenger_preview_link.'" target="_blank" style="padding: 0;margin: 0;min-height: 0;">
                                                                 <span class="btn btn-primary" style="padding: 8px 16px;margin-top:-3px"><img style="margin-right:2px;width: 20px;height: 20px;" src="/images/cm-messengericon.svg" > Preview on Messenger</span>
                                                              </a></li>'.$actionButtonContainerClose;
                                    break;
                                case "main_menu":
                                    echo $actionButtonContainerOpen.'<li>
                                         <div class="btn-group" style="padding-right: 10px;">
                                            <div class="styling_required_mini_toggle">
                                                <input type="checkbox" id="main_menu_check">
                                                <label for="main_menu_check"></label>
                                            </div>
                                         </div>
                                         </li>
                                         <li>
                                         <span class="btn btn-primary save_the_menu">Save</span>
                                         </li>'.$actionButtonContainerClose;
                                    break;
                                    case "main_menu_list":
                                        echo $actionButtonContainerOpen.'
                                                                 <li>
                                                                 <span class="btn btn-primary create_new_menu">Create New</span>
                                                                 </li>'.$actionButtonContainerClose;
                                        break;
                                    case "manage":
                                        echo $actionButtonContainerOpen.'<li><a href="'.$page_messenger_preview_link.'" target="_blank" style="padding: 0;margin: 0;min-height: 0;">
                                            <span class="btn btn-primary" style="padding: 8px 16px;margin-top:-3px"><img style="margin-right:2px;width: 20px;height: 20px;" src="/images/cm-messengericon.svg" > Preview on Messenger</span>
                                         </a></li>'.$actionButtonContainerClose;
                                        break;
                                    case "messenger_code":
                                        echo $actionButtonContainerOpen.'<li>
                                             <div class="" style="vertical-align: middle;">
                                              <span class="btn btn-primary create_new_widget">Create New</span>
                                              </div>
                                            </li>'.$actionButtonContainerClose;
                                    break;
                                    case "messenger_code_builder":
                                        echo $actionButtonContainerOpen.'<li>
                                             <div class="" style="vertical-align: middle;">
                                              <span class="btn btn-primary action_create_code">Generate Code</span>
                                              </div>
                                            </li>'.$actionButtonContainerClose;
                                    break;
                                    case "post_engagement":
                                        echo $actionButtonContainerOpen.'<li>
                                             <div class="" style="vertical-align: middle;">
                                              <span class="btn btn-primary action_create_new_comment">Create New</span>
                                              </div>
                                            </li>'.$actionButtonContainerClose;
                                        break;

                                    case "post_engagement_builder":
                                        echo $actionButtonContainerOpen.'
                                        <li>
                                         <div class="btn-group" style="padding-right: 10px;">
                                            <div class="styling_required_mini_toggle">
                                                <input type="checkbox" id="actionReplyActive">
                                                <label for="actionReplyActive"></label>
                                            </div>
                                         </div>
                                         </li>
                                         <li>
                                             <div class="" style="vertical-align: middle;">
                                              <span class="btn btn-primary action_save_comment">Save</span>
                                              </div>
                                        </li>'.$actionButtonContainerClose;
                                        break;

                                case "visual":
                                    $visual_import_bot = '';
                                    echo $actionButtonContainerOpen.'<li><label class="btn btn-primary" style="margin:-2px 0px;" for="action_save_flowcomposer" data-action="save_flow">Save</label>
                                  <div class="" style="float: right;vertical-align: middle;margin-top: 4px;-webkit-transform: rotate(90deg);-moz-transform: rotate(90deg);-o-transform: rotate(90deg);-ms-transform: rotate(90deg);transform: rotate(90deg);"> 
                                  <span class="" style=" transform: rotate(90deg);">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="border: none; background: none !important;color: #666A6C;padding: inherit;min-height: 0px"> 
                                  <i class="icon-ellipsis" style=" font-size: 25px; font-weight: 100; -webkit-transform: rotate(90deg); -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -ms-transform: rotate(90deg); transform: rotate(90deg);"></i>
                                  </a>
                                  <ul  class="dropdown-menu" style="border:none;margin-right: -140px;-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);-ms-transform: rotate(-90deg);transform: rotate(-90deg);">						    
					<li><a class="duplicate_flow">Duplicate</a></li>
                    <li><a class="share_flow">Share</a></li>
                    <li><a class="import_flow">Import</a></li>                                         
					</ul> </span>
                                  </div>
                                   </li>'.$actionButtonContainerClose;
                                    break;
                                case "composer":
                                    $visual_import_bot = '';
                                    echo $actionButtonContainerOpen.'<li><label class="btn btn-primary" style="margin:-2px 0px;" for="action_save_flowcomposer" data-action="save_flow">Save</label>
                                                     <div class="" style="float: right;vertical-align: middle;margin-top: 4px;-webkit-transform: rotate(90deg);-moz-transform: rotate(90deg);-o-transform: rotate(90deg);-ms-transform: rotate(90deg);transform: rotate(90deg);"> 
                                  <span class="" style=" transform: rotate(90deg);">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="border: none; background: none !important;color: #666A6C;padding: inherit;min-height: 0px"> 
                                  <i class="icon-ellipsis" style=" font-size: 25px; font-weight: 100; -webkit-transform: rotate(90deg); -moz-transform: rotate(90deg); -o-transform: rotate(90deg); -ms-transform: rotate(90deg); transform: rotate(90deg);"></i>
                                  </a>
                                  <ul  class="dropdown-menu" style="border:none;margin-right: -140px;-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);-o-transform: rotate(-90deg);-ms-transform: rotate(-90deg);transform: rotate(-90deg);">						    
					                    <li><a data-action="import-flow-invoke" class="import_flow">Import</a></li>                                         
					<li><a data-action="duplicate-flow-invoke" class="duplicate_flow">Duplicate</a></li>
                    <li><a data-action="share-flow-invoke" class="share_flow">Share</a></li>
                    <li><a data-action="access-token-helper">Access Token Helper</a></li>
					</ul> </span>
                                  </div>
                                                       </li>'.$actionButtonContainerClose;
                                    break;
                                    case "view_flow":
                                        $visual_import_bot = '';
                                        echo $actionButtonContainerOpen.'<li><label class="btn btn-primary action_import_flow" style="margin:-2px 0px;" for="action_import_flow">Import</label>
                                                                        </li>'.$actionButtonContainerClose;
                                        break;
                                case "welcome":
                                    $welcome_class="";$welcome_class2="";$default_checked="";
                                    $welcome_on=smartbot_get_options($_SESSION['user_id'], $_SESSION['page_id'],'','welcome_on');
                                    if($welcome_on=="off"){$welcome_class="welcome_off_selected";}
                                    if($welcome_on=="yeah"){$welcome_class2="welcome_on_selected";$default_checked="checked";}
                                    
                                    echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                    <div class="btn-group" style="padding-right: 10px;">
                                                    <label class="switch" style="margin-bottom: 0;"><input type="checkbox" style="display:none;" id="welcome_check" '.$default_checked.'>  <div class="slider round"></div></label>
                                                        <!--<button id="button_welcome_on" class="btn btn-white welcome_on '.$welcome_class2.'" type="button">On</button>
                                                        <button id="button_welcome_off" class="btn btn-white welcome_off '. $welcome_class.'" type="button">Off</button>-->
                                                    </div>
                                                    <a data-toggle="tab" href="#tab_welcome-2" class="btn btn-primary save_message" style="min-height: 0;padding: 8px 16px;">Save</a>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                                    break;

               case "flows":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_flow">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;

	        case "customfields":


		        echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_customfield">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
		        break;
            case "global_fields":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_global_field">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;
                case "segmentation":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_segment">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;
                case "segment_builder":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary save_segment">Save</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;
                case "json_library":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary action_generate_json">Select at least one message</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;


            case "widget_builder":
            case "checkbox_builder":
            case "customerchat_builder":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary save_widget">Save</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;

                case "widgets":


                echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_widget">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
                break;
	        case "messenger_chat":


		        echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_widget">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
		        break;

	        case "messenger_checkbox":


		        echo $actionButtonContainerOpen.'<li>
                                                <div class="" style="vertical-align: middle;">
                                                  
                                         <span class="btn btn-primary create_new_widget">Create New</span>
                                                </div>
                                            </li>'.$actionButtonContainerClose;
		        break;
                                case "default_reply":
                                        $default_class="";$default_class2="";$default_checked="";
                                        $default_on=smartbot_get_options($_SESSION['user_id'], $_SESSION['page_id'],'','default_on');
                                        if($default_on=="off"){$default_class="default_off_selected";}
                                        if($default_on=="yeah"){$default_class2="default_on_selected";$default_checked="checked";}

                                        echo $actionButtonContainerOpen.'<li>
                                                                        <div class="" style="vertical-align: middle;">
                                                                            <div class="btn-group" style="padding-right: 10px;">
                                                                            <label class="switch" style="margin-bottom: 0;"><input type="checkbox" style="display:none;" id="default_check" '.$default_checked.'>  <div class="slider round"></div></label>
                                                                                <!--<button id="button_default_on" class="btn btn-white default_on '.$default_class2.'" type="button">On</button>
                                                                                <button id="button_default_off" class="btn btn-white default_off '. $default_class.'" type="button">Off</button>-->
                                                                            </div>
                                                                            <a data-toggle="tab" href="#tab_default-1" class="btn btn-primary save_message" style="min-height: 0;padding: 8px 16px;">Save</a>
                                                                        </div>
                                                                    </li>'.$actionButtonContainerClose;
                                        break;
                                    case "share_template":
                                        echo $actionButtonContainerOpen.'<li>
                                         <div class="btn-group" style="padding-right: 10px;">
                                            <div class="styling_required_mini_toggle">
                                                <input type="checkbox" id="share_template">
                                                <label for="share_template"></label>
                                            </div>
                                         </div>
                                         </li>
                                         <li>
                                         <span class="btn btn-primary save_template">Save</span>
                                         </li>'.$actionButtonContainerClose;
                                        break;
                                    case "manage_template_urls":
                                        echo $actionButtonContainerOpen.'
                                         <li>
                                         <span class="btn btn-primary create_template_url">Create</span>
                                         </li>'.$actionButtonContainerClose;
                                        break;
                                    case "tags_manager":
                                        echo $actionButtonContainerOpen.'
                                         <li>
                                         <span class="btn btn-primary create_tag">Create New</span>
                                         </li>'.$actionButtonContainerClose;
                                        break;
                                    case "integrations_manager":
                                        echo $actionButtonContainerOpen.'
                                         <li>
                                         <span class="btn btn-primary create_integration">Create New</span>
                                         </li>'.$actionButtonContainerClose;
                                        break;

                                default:
                                    
                            }
        ?>
		
        <?php }else{
		?>
		<div class="navbar-header" style="padding-left:25px;padding-top:10px">
            <a href="index.php"><img class="sb_logo" src="<?php echo $_SESSION['brand']['nav_logo_top'];?>" /></a>
		</div>
		<?php
		
		} ?>
          <ul class="nav navbar-top-links navbar-right navbar-index">

              <?php
              if($_SESSION['whitelabel'] === 0){
              ?>
              <li>
                  <a href="">
                      <i class=""></i><span  class="headway"></span>
                  </a>
              </li>
              <?php
              }
              ?>

			<?php 
                if(isset($_SESSION["takeover"]) && $_SESSION["takeover"] == 1){ 
                    echo "<li>
                            <a href=\"agency_revert.php\">
                                    <i class=\"icon-exit-right\"></i> Sign out (takeover session)
                           </a>         
                        </li>";
                }
            ?>
              <?php
              if(!isset($_SESSION["membership"]["agency"]) || !isset($_SESSION["membership"]["whitelabel"])) {
                  $agency = false;
                  if (!empty($agency)) {
                      $_SESSION["membership"]["agency"] = 1;
                      if (!empty($agency->whitelabel)) {
                          $_SESSION["membership"]["whitelabel"] = 1;
                      }
                      else{
                          $_SESSION["membership"]["whitelabel"] = 0;
                      }

                  }
                  else {
                      $_SESSION["membership"]["agency"] = 0;
                      $_SESSION["membership"]["whitelabel"] = 0;
                  }
              }
              if(isset($_SESSION["membership"]["agency"]) && $_SESSION["membership"]["agency"] == 1 and !isset($_SESSION["takeover"])){
              ?>
              <li>
                  <a href="agency.php">
                      <i class="icon-apartment"></i> Agency
                  </a>
              </li>
              <?php }?>
              <li>
                <a href="index.php">
                        <i class="icon-home2"></i> Home
                </a>
              </li>
            <li>                        
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i> <span class="nav-label" style="display: initial;">Account</span></a>
					<ul  class="dropdown-menu">						    
					<li><a href="profile.php">Profile</a></li>
					<?php if($_SESSION['whitelabel']==0){?>
                    <li><a href="billing.php">Billing</a></li>
                    <?php }?>
                    <li><a href="integrations.php"  class="sidebar_link" data-link="integrations">Integrations</a></li>
					</ul>                       
			</li>
			<?php if($_SESSION['whitelabel']===0){ ?>
			<li>
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lifebuoy"></i> <span class="nav-label" style="display: initial;">Support</span></a>
                    <ul class="dropdown-menu">
                            <li><a href="https://docs.clevermessenger.com/" target="_blank">Knowledgebase</a></li>
                            <li><a href="https://status.clevermessenger.com/" target="_blank">Status</a></li>
                            <li><a href="https://www.facebook.com/groups/clevermessenger/" target="_blank">Community</a></li>
                    </ul>
            </li>
            <?php } else { ?>
            <li>
               <a href="<?php echo $_SESSION['brand']['link_support'];?>" target="_blank">
                        <i class="icon-lifebuoy"></i> Support
               </a>
             </li>
            <?php } ?>
            <li>
               <a href="logout.php">
                        <i class="icon-exit-right"></i> Log out
               </a>
             </li>
          </ul>
		</nav>
        </div>

            <?php
            if($_SESSION['whitelabel'] === 0){
            ?>
            <script>
                // @see https://docs.headwayapp.co/widget for more configuration options.
                var HW_config = {
                    selector: ".headway", // CSS selector where to inject the badge
                    account:  "xY4Ply"
                }
            </script>
            <script async src="https://cdn.headwayapp.co/widget.js"></script>
            <?php
              }
            ?>