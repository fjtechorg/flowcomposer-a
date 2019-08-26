
<?php

global $user_id;

require_once(SB_PATH."/includes/function.php");

if (!class_exists('CtctOAuth2')) {
    require_once(SB_PATH."/includes/ar/constantcontact_official/src/Ctct/autoload.php");
    require_once(SB_PATH."/includes/ar/constantcontact_official/vendor/autoload.php");



}

use Ctct\Auth\CtctOAuth2;
use Ctct\Exceptions\OAuth2Exception;

define("APIKEY", "xgcnar4wvcutepw9zghjrdxv");
define("CONSUMER_SECRET", "qkvYxaPECRu55qwpGnPJBHvQ");
define("REDIRECT_URI", "https://app.clevermessenger.com/integrations.php?page=integrations");


$oauth = new CtctOAuth2(APIKEY, CONSUMER_SECRET, REDIRECT_URI);


if (!empty($_GET['code'])) {

    try {
        $accessToken = $oauth->getAccessToken($_GET['code']);
        smartbot_save_keys($user_id, "", "", "constantcontact-accesstoken", $accessToken['access_token']);

    } catch (OAuth2Exception $ex) {

        die("Authorization Error");
    }


}


?><form method="POST" >
    <input type="hidden" name="actie" value="save_ar" />
    <input type="hidden" name="ar_type" value="aweber" />

    <input type="hidden" id="constantcontact" name="constantcontact" class="formatted-form" value="constantcontact">
    <?php
    $constantcontact_accesstoken = smartbot_get_options($user_id,'','','constantcontact-accesstoken');

    if (strlen($constantcontact_accesstoken)<1) {

        echo ' <input class="btn btn-primary" onclick="location=\''. $oauth->getAuthorizationUrl().'\'" type="button" value="Authorize your account">';
    }
    else {
        echo "<label>Your ConstantContact account is successfully connected to our platform.</label>";
        echo ' <input class="btn btn-danger" onclick="location=\'disconnect_provider.php?provider=constantcontact\'" type="button" value="Disconnect your Account">';
    }

    ?>

</form>