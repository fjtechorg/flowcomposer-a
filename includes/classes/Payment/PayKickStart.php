<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20/03/19
 * Time: 09:05 م
 */

class PayKickStart
{

    public static function isValidIpn($data, $secretKey) {

        $paramStrArr = array();
        $paramStr = NULL;

        // Loop thru all POST vars
        foreach($data as $key=>$value)
        {
            // Ignore the encrypted key variable
            if($key == "verification_code") continue;
            //Ignore any empty variables
            if(!$key OR !$value) continue;
            //Add IPN values to validate to a new array
            $paramStrArr[] = (string) $value;
        }

        // Alphabetically sort IPN parameters by their key. This ensures
        // the params are in the same order as when Paykickstart
        // generated the verification code, in order to prevent
        // hash key invalidation due to POST parameter order.
        ksort( $paramStrArr, SORT_STRING );

        // Implode all the values into a string, delimited by "|"
        $paramStr = implode("|", $paramStrArr);

        // Generate the hash usingthe imploded string and secret key
        $encKey = hash_hmac( 'sha1', $paramStr, $secretKey );

        return $encKey == $data["verification_code"] ;
    }

    public static function getTransaction($transactionId) {
        //Set up API path and method
        $base_url = "https://app.paykickstart.com/api/";
        $route = "transaction/get";
        $url = $base_url . $route;
        $post = false;

        //Create request data string
        $data = http_build_query([
            'auth_token' => PKS_API_KEY,
            'id' => $transactionId
        ]);

        //Execute cURL request
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            $url = $url . "?" . $data;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        curl_close($ch);

        //Output Response
        return json_decode($output);
    }

    //Retrieves the purchase for the provided purchase id
    public static function getPurchase($purchaseId) {
        //Set up API path and method
        $base_url = "https://app.paykickstart.com/api/";
        $route = "purchase/get";
        $url = $base_url . $route;
        $post = false;

        //Create request data string
        $data = http_build_query([
            'auth_token' => PKS_API_KEY,
            'id' => $purchaseId
        ]);

        //Execute cURL request
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            $url = $url . "?" . $data;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        curl_close($ch);

        //Output Response
        return json_decode($output);
    }

    //Cancels the subscription for the provided Invoice Id
    public static function cancelSubscription($invoiceId) {
        //Set up API path and method
        $base_url = "https://app.paykickstart.com/api/";
        $route = "subscriptions/cancel";
        $url = $base_url . $route;
        $post = true;

        //Create request data string
        $data = http_build_query([
            'auth_token' => PKS_API_KEY,
            'invoice_id' => $invoiceId
        ]);

        //Execute cURL request
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            $url = $url . "?" . $data;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        curl_close($ch);

        /*
         * Sample output response in json
         {
           "success": 1,
           "message": "Subscription was cancelled"
          }
         */
        //Output Response
        return json_decode($output);
    }

    //Reactivates a cancelled subscription for the provided Invoice Id and required next billing unix timestamp(INT type)
    public static function reactivateSubscription($invoiceId,$nextBillingTimestamp){
        //Set up API path and method
        $base_url = "https://app.paykickstart.com/api/";
        $route = "subscriptions/re-activate";
        $url = $base_url . $route;
        $post = true;

        //Create request data string
        $data = http_build_query([
            'auth_token' => PKS_API_KEY,
            'invoice_id' => $invoiceId,
            'date'  => $nextBillingTimestamp
        ]);

        //Execute cURL request
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            $url = $url . "?" . $data;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output = curl_exec($ch);
        curl_close($ch);

        /* Sample JSON response
            {
              "code": 200,
              "status": true,
              "message": "Subscription was re-activated"
            }
         */

        //Output Response
        return json_decode($output);
    }
}