
<?php

global $user_id;

require_once(SB_PATH."/includes/function.php");

$consumerKey    = "J1AmaWFGVnOKW4BROHfy2GB064mxXjo2";
$consumerSecret = "hHCjHopSjPGe7S8V";
if (!class_exists('Citrix')) {
    require_once(SB_PATH."/includes/webinars/gotowebinar/citrix.php");
}

$goto = new Citrix($consumerKey, $consumerSecret);

if (!empty($_GET['code'])) {

    $responsecode = $_GET['code'];
$data  = $goto->getKeys($responsecode);
    smartbot_save_keys($user_id, "", "", "gotowebinar-accessToken", $data->access_token);
    smartbot_save_keys($user_id, "", "", "gotowebinar-refreshToken", $data->refresh_token);
    smartbot_save_keys($user_id, "", "", "gotowebinar-organizerKey", $data->organizer_key);
    smartbot_save_keys($user_id, "", "", "gotowebinar-accountKey", $data->account_key);
}


?><form method="POST" >
    <input type="hidden" name="actie" value="save_webinar" />
    <input type="hidden" name="webinar_type" value="gotowebinar" />

    <?php
    $gotowebinar_organizer_key = smartbot_get_options($user_id,'','','gotowebinar-organizerKey');

    if (strlen($gotowebinar_organizer_key)<1) {

        echo ' <input class="btn btn-primary" onclick="location=\''. $goto->getAuthorizationURL().'\'" type="button" value="Authorize your account">';
    }
    else {
        echo "<label>Your GotoWebinar account is successfully connected to our platform.</label>";
        echo ' <input class="btn btn-danger" onclick="location=\'disconnect_provider.php?provider=gotowebinar\'" type="button" value="Disconnect your Account">';
    }

    ?>

</form>