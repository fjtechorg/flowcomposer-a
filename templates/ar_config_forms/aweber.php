<?php

global $user_id;

require_once(SB_PATH."/includes/function.php");

$consumerKey    = "AkUl7RsnEdUm1ath1F36NQCB";
$consumerSecret = "PcVSstRKLy1RVizPh6NQkryXmZ5RA6ShIfJ1YdNx";
if (!class_exists('AWeberAPI')) {
    require_once(SB_PATH."/includes/ar/aweber/aweber_api.php");
}

$aweber = new AWeberAPI($consumerKey, $consumerSecret);

if (!empty($_GET['oauth_token'])) {

    $aweber->user->tokenSecret = $_SESSION['requestTokenSecret'];
    $aweber->user->requestToken = $_GET['oauth_token'];
    $aweber->user->verifier = $_GET['oauth_verifier'];
    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
    smartbot_save_keys($user_id, "", "", "aweber-token", $accessToken);
    smartbot_save_keys($user_id, "", "", "aweber-tokenSecret", $accessTokenSecret);
    header('Location: ' . $_SESSION['callbackUrl']);
}


?><form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="aweber" />

          <input type="hidden" id="aweber" name="aweber" class="formatted-form" value="aweber">
     <?php
     $awebertoken = smartbot_get_options($user_id,'','','aweber-token');

     if (strlen($awebertoken)<1) {
         $callbackUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
         list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
         $_SESSION["requestTokenSecret"] = $requestTokenSecret;
         $_SESSION["callbackUrl"] = $callbackUrl;
         echo ' <input class="btn btn-primary" onclick="location=\''. $aweber->getAuthorizeUrl().'\'" type="button" value="Authorize your account">';
     }
         else {
         echo "<label>Your Aweber account is successfully connected to our platform.</label>";
             echo ' <input class="btn btn-danger" onclick="location=\'disconnect_provider.php?provider=aweber\'" type="button" value="Disconnect your Account">';
         }

     ?>

</form>