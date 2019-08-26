<?php
/**
 * Created by PhpStorm.
 * User: Dennis
 * Date: 21-3-2018
 * Time: 17:06
 */
require_once('config.php');
require_once('function.php');
require_once('wp-db.php');
require_once('facebook/autoload.php');
/*
 * Return a html formatted table row for admin index page on the user memberships table
 */
function getMembershipRowForAdmin($userId){
    global $wpdb;
    $query = $wpdb->prepare('SELECT m.name,mu.date_updated,mu.date_next_billing FROM membership_users mu INNER JOIN memberships m ON m.id=mu.membership_id WHERE mu.user_id=%d',$userId);
    $mems = $wpdb->get_results($query);
    $membership = '';
    foreach($mems as $mem) {
        if ($mem->name == 'Lifetime') {
            $pack = 'Unlimited';
        } else {
            $pack = $mem->name;
        }
        $membership = $membership . '<tr><td>' . $pack . '</td><td>' . date("F j Y , g:i a", $mem->date_updated) . '</td>';
        if (!empty($mem->date_next_billing)) {
            $membership = $membership . '<td>' . date("F j Y , g:i a", $mem->date_next_billing) . '</td>';
        } else {
            $membership = $membership . '<td>N/A</td>';
        }
        $membership = $membership . '</tr>';
    }
    return $membership;
}

function getMemberships($userId,$divideSubsByOrder = false){
    //Grab all associated levels
    global $wpdb;
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s", $userId), ARRAY_A);
    if (isset($R)) {
        //Indicate baselevel account
        foreach ($R as $level) {
            //Indicating which baselevel membership is active
            if ($level["membership_level"] > 0 and $level["membership_level"] < 5) {
                $membership["base"]["membership_id"] = $level["membership_level"];
                switch ($membership["base"]["membership_id"]) {
                    case 1:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 2:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 3:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "lifetime";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 4:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                }
            }
            //Indicating if baselevel is Pro
            if ($level["membership_level"] > 9 and $level["membership_level"] < 18) {
                $membership["base"]["membership_id"] = $level["membership_level"];
                switch ($membership["base"]["membership_id"]) {
                    case 10:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 11:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 12:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 13:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 14:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 15:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 16:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                    case 17:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["dateadded"];
                        break;
                }
            }

            //Indicating if Agency is active
            if ($level["membership_level"] == 5 or $level["membership_level"] == 6 or $level["membership_level"] == 7) {
                $membership["agency"]["id"] = $level["id"];
                $membership["agency"]["membership_id"] = $level["membership_level"];
                $membership["agency"]["length"] = "yearly";
                $membership["agency"]["dateadded"] = $level["dateadded"];
            }

            //Indicating if Affiliate is active
            if ($level["membership_level"] == 8) {
                $membership["affiliate"] = true;
            }

            //Indicating if admin is active
            if ($level["membership_level"] == 9) {
                $membership["admin"] = true;
            }


        }
        if(!isset($membership["base"])){
            // if no membership set user is from beta stage -> so legacy user
            $membership["base"]["membership_id"] = 18;
            $membership["base"]["length"] = "lifetime";
        }
        //echo "<pre>";
        //var_dump($membership);
        //echo "</pre>";

        if($membership["base"]["length"] == "monthly"){
            $dateadded = $membership["base"]["dateadded"];
            $timeNow = time();
            $timeBack = $timeNow - (35 * 86400 /*days*/);
            if( $dateadded > $timeBack ){
                $membership["base"]["status"] = "active";
            } else {
                $membership["base"]["status"] = "expired";
            }
            if($membership["base"]["membership_id"] == 4 AND $membership["base"]["status"] == "expired"){
                //not applicable anymore for Paykickstart
            }


        } elseif ($membership["base"]["length"] == "yearly"){
            $dateadded = $membership["base"]["dateadded"];
            $timeNow = time();
            $timeBack = $timeNow - (370 * 86400 ); /*days*/
            if( $dateadded > $timeBack ){
                $membership["base"]["status"] = "active";
            } else {
                $membership["base"]["status"] = "expired";
            }

        } elseif ($membership["base"]["length"] == "lifetime"){
            $membership["base"]["status"] = "active";
        }


        if(!empty($membership["agency"])){
            $membership["agency"]["status"] = "active";
        }


    }


    //Calculate amount of upgrade subs
    if($divideSubsByOrder == false){
        $membership["addon_subs"] = getSubscriberAddOn($userId, 35);
        //var_dump2($membership["addon_subs"]);
    } elseif ($divideSubsByOrder == true){
        $membership["addon_subs"] = getSubscriberAddOnOrders($userId);
        //var_dump2($membership["addon_subs"]);
    }


    if (empty($membership["addon_subs"])) {
        $membership["addon_subs"] = 0;
    }


    return $membership;
}

function getMembershipsNew($userId,$divideSubsByOrder = false){
    //Grab all associated levels
    global $wpdb;
    //$R = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_membership_users WHERE user_id=%s", $userId), ARRAY_A);
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM membership_users WHERE user_id=%s", $userId), ARRAY_A);
    if (isset($R)) {
        //Indicate baselevel account
        foreach ($R as $level) {
            //Indicating which baselevel membership is active
            if ($level["membership_id"] > 0 and $level["membership_id"] < 5) {
                $membership["base"]["membership_id"] = $level["membership_id"];
                switch ($membership["base"]["membership_id"]) {
                    case 1:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 2:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 3:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "lifetime";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 4:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                }
            }
            //Indicating if baselevel is Pro
            if ($level["membership_id"] > 9 and $level["membership_id"] < 18) {
                $membership["base"]["membership_id"] = $level["membership_id"];
                switch ($membership["base"]["membership_id"]) {
                    case 10:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 11:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 12:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 13:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 14:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 15:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 16:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "monthly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                    case 17:
                        $membership["base"]["id"] = $level["id"];
                        $membership["base"]["length"] = "yearly";
                        $membership["base"]["dateadded"] = $level["date_added"];
                        break;
                }
            }

            //Indicating if Agency is active
            if ($level["membership_id"] == 5 or $level["membership_id"] == 6 or $level["membership_id"] == 7) {
                $membership["agency"]["id"] = $level["id"];
                $membership["agency"]["membership_id"] = $level["membership_id"];
                $membership["agency"]["length"] = "yearly";
                $membership["agency"]["dateadded"] = $level["date_added"];
            }

            //Indicating if Affiliate is active
            if ($level["membership_id"] == 8) {
                $membership["affiliate"] = true;
            }

            //Indicating if admin is active
            if ($level["membership_id"] == 9) {
                $membership["admin"] = true;
            }


        }
        if(!isset($membership["base"])){
            // if no membership set user is from beta stage -> so legacy user
            $membership["base"]["membership_id"] = 18;
            $membership["base"]["length"] = "lifetime";
        }
        //echo "<pre>";
        //var_dump($membership);
        //echo "</pre>";

        if($membership["base"]["length"] == "monthly"){
            $dateadded = $membership["base"]["dateadded"];
            $timeNow = time();
            $timeBack = $timeNow - (35 * 86400 /*days*/);
            if( $dateadded > $timeBack ){
                $membership["base"]["status"] = "active";
            } else {
                $membership["base"]["status"] = "expired";
            }
            if($membership["base"]["membership_id"] == 4 AND $membership["base"]["status"] == "expired"){
                //not applicable anymore for Paykickstart
            }


        } elseif ($membership["base"]["length"] == "yearly"){
            $dateadded = $membership["base"]["dateadded"];
            $timeNow = time();
            $timeBack = $timeNow - (370 * 86400 ); /*days*/
            if( $dateadded > $timeBack ){
                $membership["base"]["status"] = "active";
            } else {
                $membership["base"]["status"] = "expired";
            }

        } elseif ($membership["base"]["length"] == "lifetime"){
            $membership["base"]["status"] = "active";
        }


        if(!empty($membership["agency"])){
            $membership["agency"]["status"] = "active";
        }


    }


    //Calculate amount of upgrade subs
    if($divideSubsByOrder == false){
        $membership["addon_subs"] = getSubscriberAddOn($userId, 35);
        //var_dump2($membership["addon_subs"]);
    } elseif ($divideSubsByOrder == true){
        $membership["addon_subs"] = getSubscriberAddOnOrders($userId);
        //var_dump2($membership["addon_subs"]);
    }


    if (empty($membership["addon_subs"])) {
        $membership["addon_subs"] = 0;
    }


    return $membership;
}

function getPurchases($username){
    global  $wpdb;
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM user_purchases WHERE email=%s ", $username), ARRAY_A);
    return $R;
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function getProductName($prodId){
    if($prodId > 0 and $prodId < 19){
        $prodName = getMembershipLevels($prodId);
    } elseif ($prodId == "subs"){
        $prodName = "Add-on Subscribers";
    } elseif ($prodId == "booster"){
        $prodName = "Subsriber Booster";
    } elseif ($prodId == "agencySubusers"){
        $prodName = "Agency Subuser Licenses";
    }
    return $prodName;
}

function getMembershipLevels($prodId){
    global  $wpdb;
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_membership WHERE id=%s ", $prodId), ARRAY_A);
    return $R[0]["level_name"];
}

function checkInvoiceOwnership($id,$userName){
    global  $wpdb;
    $R = $wpdb->get_row($wpdb->prepare("SELECT * FROM user_purchases WHERE id=%s AND email=%s",$id ,$userName), ARRAY_A);
    return $R;
}

function saveAgencySubPurchase($token,$amount,$amountWithoutTax,$taxPercentage,$quantity,$email,$product,$buyerid){
    $jvzoo_time =  time();

    try{

        global $wpdb;
        $wpdb->insert('user_purchases', array('trans_id'=>$token,'amount'=>$amount, 'amount_without_tax'=> $amountWithoutTax, 'tax_percentage' => $taxPercentage, 'quantity' => $quantity, 'email'=> $email, 'prod_id' => $product, 'buyer_id' => $buyerid, 'symbol' => "sale", 'status' => 1, 'dateadded' => $jvzoo_time));

        /*
        global $wpdb;

        $add_transaction = $wpdb->prepare("INSERT INTO user_purchases (trans_id, amount, email, symbol, prod_id, buyer_id, status, dateadded) VALUES (:trans_id, :amount, :email, :symbol, :prod_id, :buyer_id, :status, :dateadded)");

        $add_transaction->bindParam(':trans_id', $token);

        $add_transaction->bindParam(':amount', $amount);

        $add_transaction->bindParam(':email', $email);

        $add_transaction->bindParam(':prod_id', $product);

        $add_transaction->bindParam(':buyer_id', $buyerid);

        $add_transaction->bindParam(':symbol', "sale");

        $add_transaction->bindParam(':status', '1');

        $add_transaction->bindParam(':dateadded', $jvzoo_time );

        $add_transaction->execute();
        */


    } catch(PDOException $e) {

        echo $e->getMessage();

    }
}

function getSubscriberAddOn($userId,$daysback){
    //Grab all associated levels
    $timeNow = time();
    $timeBack = $timeNow - ($daysback * 86400);
    global $wpdb;
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_subscriber_upgrades WHERE user_id=%s AND dateadded BETWEEN $timeBack AND $timeNow", $userId, $timeBack, $timeNow), ARRAY_A);
    //var_dump($R);
    $upgradeTotal = 0;
    if (!empty($R)) {

        //Indicate baselevel account
        foreach ($R as $upgrade) {

            $upgradeTotal = $upgradeTotal + $upgrade["extra_subscriber"];

        }
    }
    return $upgradeTotal;
}
function getSubscriberAddOnOrders($userId){
    global $wpdb;
    $R = $wpdb->get_results($wpdb->prepare("SELECT * FROM smartbot_subscriber_upgrades WHERE user_id=%s ", $userId), ARRAY_A);
    return $R;

}

function getInternalMembershipIdforPKId($externalProductId){
    switch ($externalProductId) {
        case "12566":
            //Clever Messenger (with Pro trial)
            $internalProductId = 4;
            break;
        case "12777":
            //Clever Messenger (monthly)
            $internalProductId = 1;
            break;
        case "12778":
            //Clever Messenger (yearly)
            $internalProductId = 2;
            break;
        case "12779":
        case "16473":
            //Clever Messenger (without Pro trial)
            $internalProductId = 3;
            break;
        case "13066":
        case "16472":
            //Clever Messenger (with Pro trial - payment plan)
            $internalProductId = 4;
            break;
        case "13067":
            //Clever Messenger (without Pro trial - payment plan)
            $internalProductId = 3;
            break;
        case "12780":
            //Clever Messenger Pro Yearly (10,000 subscribers)
            $internalProductId = 11;
            break;
        case "12781":
            //Clever Messenger Pro Yearly (15,000 subscribers)
            $internalProductId = 13;
            break;
        case "12782":
            //Clever Messenger Pro Yearly(20,000 subscribers)
            $internalProductId = 15;
            break;
        case "12783":
            //Clever Messenger Agency (5 Sub-licences)
            $internalProductId = 5;
            break;
        case "12784":
            //Clever Messenger Agency (10 Sub-licences)
            $internalProductId = 6;
            break;
        case "12785":
            //Clever Messenger Agency (15 Sub-licences)
            $internalProductId = 7;
            break;
        case "12796":
            //Booster pack
            $internalProductId = "booster";
            break;
    }
    return $internalProductId;
}

//Takes in results from getMemberships($userId) and returns user limits
function getMembershipLimits($memberships){
    global $wpdb;
    $limitsArr = $wpdb->get_results("SELECT * FROM smartbot_membership", ARRAY_A);
    // Base level limit
    $totalLimit = 3000;
    $baseLimit = 3000;
    if(!empty($memberships["base"])){
        $baseMembershipId = $memberships["base"]["membership_id"] - 1 ; //zero indexed array
        // switchcase not needed anymore due to database restructure
        /*
        switch ($memberships["base"]) {
            case 1:
                // monthly fe
                $baseLimit = 3000;
                break;
            case 2;
                // yearly fe
                $baseLimit = 3000;
                break;
            case 3;
                // lifetime fe
                $baseLimit = 3000;
                break;
            case 4;
                // lifetime fe + Pro Trial
                $baseLimit = 10000;
                break;
            case 10;
                // Pro Monthly 10k
                $baseLimit = 10000;
                break;
            case 11;
                // Pro Yearly 10k + 1000 free
                $baseLimit = 11000;
                break;
            case 12;
                // Pro Monthly 15k
                $baseLimit = 15000;
                break;
            case 13;
                // Pro Yearly 15k + 1000 free
                $baseLimit = 16000;
                break;
            case 14;
                // Pro Monthly 20k
                $baseLimit = 20000;
                break;
            case 15;
                // Pro Yearly 20k + 1000 free
                $baseLimit = 21000;
                break;
            case 16;
                // Pro Monthly 25k
                $baseLimit = 25000;
                break;
            case 17;
                // Pro Yearly 25k + 1000 free
                $baseLimit = 26000;
                break;
            case 18;
                //Legacy account 10k free FE
                $baseLimit = 10000;
                break;
        }
        */
        $baseLimit = $limitsArr[$baseMembershipId]["membership_limit"];
    }

    if(!empty($memberships["agency"]) and $memberships["agency"] > 0){
        $agencyMembershipId = $memberships["agency"]["membership_id"];
        switch ($agencyMembershipId) {
            case 5:
                // Agency 1
                $agencyLimit = $limitsArr[$agencyMembershipId - 1]["membership_limit"]; //zero indexed array
                // 5 monthly pro 1 users
                $agencySubLimit = 5;
                break;
            case 6;
                // Agency 2
                $agencyLimit = $limitsArr[$agencyMembershipId - 1]["membership_limit"]; //zero indexed array
                // 10 monthly pro 1 users
                $agencySubLimit = 10;
                break;
            case 7;
                // Agency 3
                $agencyLimit = $limitsArr[$agencyMembershipId - 1]["membership_limit"]; //zero indexed array
                // 15 monthly pro 1 users
                $agencySubLimit = 10;
                break;
        }

    }

    if(isset($memberships["admin"]) and $memberships["admin"] == true){
        //add 1m subs (semi-unlimited)
        $adminLimit = 1000000;
    }


    if(isset($memberships["addon_subs"]) and $memberships["addon_subs"] > 0){
        //add 1m subs (semi-unlimited)
        $upgradeLimit = $memberships["addon_subs"];
    }

    // Calculate full amount of subs
    if(isset($baseLimit)){
        $totalLimit = $baseLimit;
        if(isset($agencyLimit)){
            $totalLimit = $totalLimit + $agencyLimit;
        }
        if(isset($adminLimit)){
            $totalLimit = $totalLimit + $adminLimit;
        }
        if(isset($upgradeLimit)){
            $totalLimit = $totalLimit + $upgradeLimit;
        }
    }
    return $totalLimit;

}
function addSubscribers($userId,$amount,$dateadded = false){
    global $wpdb;
    if (!$dateadded){
        //2018-03-30 03:33:59 UTC
        $dateadded = time();
    }
    $wpdb->insert('smartbot_subscriber_upgrades', array('user_id'=>$userId,'extra_subscriber'=>$amount,'dateadded' => $dateadded));
}

//returns the membership ids of the passed user id
function getUserMemberships($userId){
    global $wpdb;
    $query = $wpdb->prepare('SELECT DISTINCT membership_id FROM membership_users WHERE user_id=%d',$userId);
    $res = $wpdb->get_col($query);
    return $res;
}

//check if the passed userid has the specified membership id
function checkUserMembership($userId,$membershipId){
    global $wpdb;
    $query = $wpdb->prepare('SELECT count(id) FROM membership_users WHERE user_id=%d AND membership_id=%d',$userId,$membershipId);
    $count = $wpdb->get_var($query);
    return $count;
}

//returns an associative array whose key are PKS product ids and value is the membership id
function getMembershipProdIdsArray(){
    global $wpdb;
    $prods = $wpdb->get_results('SELECT id,product_ids FROM memberships WHERE product_ids!=""');
    $products = [];
    foreach($prods AS $prod){
        $id = $prod->id;
        $arr = explode(',',$prod->product_ids);
        foreach($arr as $ar){
            $products[$ar] = $id;
        }
    }
    return $products;
}

//returns reccuring period in no. of days for the passed membership id
function getMembershipRecurringPeriod($membershipId,$trial=false){
    global $wpdb;
    if ($trial)
        $query = $wpdb->prepare('SELECT trial_period FROM memberships WHERE id=%d',$membershipId);
    else
    $query = $wpdb->prepare('SELECT recurring_period FROM memberships WHERE id=%d',$membershipId);
    $days = $wpdb->get_var($query);
    return $days;
}

//check if user has admin membership, returns 0 if no membership found else returns the count
function countAdminMembership($userId){
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT count(id) from smartbot_membership_users WHERE user_id=%d AND membership_id=9",$userId));
    return $count;
}

//update billing date for recurring memberships
function updateMembershipDates($userId,$membershipId,$invoiceId,$dateUpdate){
    global $wpdb;
    $membershipsDays = getMembershipRecurringPeriod($membershipId);
    $membershipsSecs = $membershipsDays*86400;
    $dateNextBilling = $dateUpdate+$membershipsSecs;
    $wpdb->update('membership_users',['invoice_id'=>$invoiceId,'date_updated'=>$dateUpdate,'date_next_billing'=>$dateNextBilling],['user_id'=>$userId,'membership_id'=>$membershipId]);
}

//Extend Membership Billing Date
function extendMembershipBillingDate($userId,$membershipId,$dateNextBilling){
    global $wpdb;
    $wpdb->update('membership_users',['date_next_billing'=>$dateNextBilling],['user_id'=>$userId,'membership_id'=>$membershipId]);
}

// retrieve user pro memberships
function getUserProMemberships($userIndex){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM membership_users WHERE membership_id>=10 AND membership_id<=17 AND user_id=%d ",$userIndex);
    $res = $wpdb->get_results($query);
    return $res;
}

// retrieve user pro memberships
function getUserAgencyMemberships($userIndex){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM membership_users WHERE membership_id>=5 AND membership_id<=7 AND user_id=%d ",$userIndex);
    $res = $wpdb->get_results($query);
    return $res;
}

//check and cancel existing user pro memberships (except legacy lifetime) in paykickstart and delete them in memberships table
function upgradeUserProMemberships($userIndex,$newMembershipId){
    if(empty($userIndex)){
        return false;
    }
    $memberships = getUserProMemberships($userIndex);
    require_once __DIR__."/classes/Payment/PayKickStart.php";
    $secretKey = "Zbt1zR6LuAs4";
    if (!PayKickStart::isValidIpn($_POST,$secretKey)) die();

    $memArray = [];
    foreach($memberships as $mem){
        if(!empty($mem->invoice_id)) {
            PayKickStart::cancelSubscription($mem->invoice_id);
        }
        array_push($memArray,$mem->id);
    }
    $memIds = implode(",",$memArray);
    global $wpdb;
    $query = "DELETE FROM membership_users WHERE id IN (".$memIds.")";
    $wpdb->query($query);
}

//check and cancel existing user agency memberships in paykickstart and delete them in memberships table
function deleteUserAgencyMemberships($userIndex){
    if(empty($userIndex)){
        return false;
    }
    $memberships = getUserAgencyMemberships($userIndex);
    require_once __DIR__."/classes/Payment/PayKickStart.php";
    $secretKey = "Zbt1zR6LuAs4";
    if (!PayKickStart::isValidIpn($_POST,$secretKey)) die();

    $memArray = [];
    foreach($memberships as $mem){
        if(!empty($mem->invoice_id)) {
            PayKickStart::cancelSubscription($mem->invoice_id);
        }
        array_push($memArray,$mem->id);
    }
    $memIds = implode(",",$memArray);
    global $wpdb;
    $query = "DELETE FROM membership_users WHERE id IN (".$memIds.")";
    $wpdb->query($query);
}

// Checks if there is an existing pro membership for the user, if yes then it checks if it is an upgrade or duplicate purchase
// If Upgrade then it will cancel the old pro membership subscription in PKS and deletes it in membership_users table
// and add the new membership to the membership_users table
// If Duplicate purchase then it removes old membership and adds the new one similar to upgrade
function addOrUpgradeProMembership($userIndex,$newMembershipId,$currentTimestamp, $invoiceId){
    if(empty($userIndex)||empty($newMembershipId)){
        return false;
    }
    //get old memberships for pro
    $memberships = getUserProMemberships($userIndex);
    //check if any existing memberships is greater than new membership
    $check = true;
    foreach($memberships as $membership){
        if($membership->membership_id > $newMembershipId){
          $check = false;
        }
    }
    //if true then cancel old sub and add this new one
    //if duplicate then just remove old and add new
    if($check){
        require_once __DIR__."/classes/Payment/PayKickStart.php";
        $memArray = [];
        foreach($memberships as $mem){
            if(!empty($mem->invoice_id)) {
                PayKickStart::cancelSubscription($mem->invoice_id);
            }
            array_push($memArray,$mem->id);
        }
        $memIds = implode(",",$memArray);
        global $wpdb;
        $query = "DELETE FROM membership_users WHERE id IN (".$memIds.")";
        $wpdb->query($query);
        addUserMembership($userIndex, $newMembershipId, $currentTimestamp, $invoiceId);
    }
}

// Checks if there is an existing agency membership for the user, if yes then it checks if it is an upgrade or duplicate purchase
// If Upgrade then it will cancel the old agency membership subscription in PKS and deletes it in membership_users table
// and add the new membership to the membership_users table
// If Duplicate purchase then it removes old membership and adds the new one similar to upgrade
function addOrUpgradeAgencyMembership($userIndex,$newMembershipId,$currentTimestamp, $invoiceId){
    if(empty($userIndex)||empty($newMembershipId)){
        return false;
    }
    //get old memberships for agency
    $memberships = getUserAgencyMemberships($userIndex);
    //check if any existing memberships is greater than new membership
    $check = true;
    $licenseCnt = 0;
    $agencyLicCnt = getAgencyPackageArr();
    foreach($memberships as $membership){
        if($membership->membership_id > $newMembershipId){
            $check = false;
        }
        else{
            $licenseCnt = $licenseCnt + $agencyLicCnt[$membership->membership_id];
        }
    }
    $addNumLic = $agencyLicCnt[$newMembershipId]-$licenseCnt;
    //double validation to made sure we are only upgrading and replacing duplicated purchases
    if($addNumLic<0){
        return false;
    }
    //if true then cancel old sub and add this new one
    //if duplicate then just remove old and add new
    if($check){
        require_once __DIR__."/classes/Payment/PayKickStart.php";
        $memArray = [];
        foreach($memberships as $mem){
            if(!empty($mem->invoice_id)) {
                PayKickStart::cancelSubscription($mem->invoice_id);
            }
            array_push($memArray,$mem->id);
        }
        $memIds = implode(",",$memArray);
        global $wpdb;
        $query = "DELETE FROM membership_users WHERE id IN (".$memIds.")";
        $wpdb->query($query);
        addUserMembership($userIndex, $newMembershipId, $currentTimestamp, $invoiceId);
        //add licenses
        addAgencyMembershipLicenses($userIndex,$addNumLic);
    }
}