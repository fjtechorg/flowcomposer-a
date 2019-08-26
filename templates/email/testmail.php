<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18/10/17
 * Time: 07:39 م
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "/home/clever/clever-app/saas/includes/mail_functions.php";
require_once "/home/clever/clever-app/saas/includes/function.php";


echo sendMailTokenRefresh($_GET["email"],"testusername","testpass");

