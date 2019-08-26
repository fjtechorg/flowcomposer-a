<?php
if (!session_id()) {
    session_start();
}

$_SESSION["page_id"] = "123456";
$_SESSION["user_id"] = "1";
$_SESSION['user']['id'] = "1";
include_once('includes/config.php');
include_once('includes/function.php');
include_once('includes/membership_functions.php');
include_once('includes/wp-db.php');





global $wpdb, $conn;
//check to make sure the session variable is registered


?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo $_SESSION['brand']['name']; ?></title>
    <link rel="shortcut icon" href="<?php echo $_SESSION['brand']['favicon'];?>" type="image/x-icon">
    <link rel="icon" href="<?php echo $_SESSION['brand']['favicon'];?>" type="image/x-icon">


    <?php
    $pageName = basename($_SERVER['PHP_SELF'], '.php');
    if ($pageName == "widget_builder" || $pageName == "checkbox_builder" || $pageName == "chatwidget_builder" || $pageName == "customerchat_builder") {
        echo '<link href="css/shared/admin.min.css" rel="stylesheet">';
        echo '<link href="css/widget-overwrite.css" rel="stylesheet">';
    }
    ?>

    <?php if (isset($header_before)) {
        echo $header_before;
    } ?>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="icomoons/style.css" rel="stylesheet">
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="css/style.css?<?php echo time(); ?>" rel="stylesheet">
    <link href="css/phonepreview.css" rel="stylesheet">
    <link href="css/plugins/spectrum/spectrum.css" rel="stylesheet">
    <link href="js/plugins/plyr/plyr2.css?<?php echo time(); ?>" rel="stylesheet">


    <?php

    $currentDomain = preg_replace('/www\./i', '', $_SERVER['SERVER_NAME']);


    ?>

    <!-- Mainly scripts -->

    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/plugins/jquery-ui/jquery-ui.js"></script>


    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-M7QWDTM');</script>
    <!-- End Google Tag Manager -->


    ?>
    <?php if (isset($header_additionals)) {
        echo $header_additionals;
    } ?>

</head>
<body class="mini-navbar" <?php if (!empty($limitArray) and $limitArray["usage"] >= 90) {
    echo "style='padding-top:61px;'";
} ?>>

<?php
if (isset($_GET["hidewarning"]) and $_GET["hidewarning"] == 1) {
    $_SESSION["hidewarning"] = 1;
}
if (isset($_SESSION["page_id"])) {

    if (!isset($_SESSION["hidewarning"]) or $_SESSION["hidewarning"] != 1) {
        $pageID = $_SESSION["page_id"];
        if (isset($_SESSION['need_refresh'][$pageID]) && $_SESSION['need_refresh'][$pageID]) {
            echo "<div class=\"warning3\" id='page_token_warning' >Warning: Your page access token is not valid anymore, please refresh it on the configuration page.</div>";
        }

        //adding the warning for multipal people working on the page here
        if (isset($_SESSION['page_id'])) {
            $printCurrentEditedPage = getCurrentEditedPage($_SESSION['user']['id'], $_SESSION['page_id']);
            echo $printCurrentEditedPage;
        }

    }
    if ((isset($limitArray) && ($limitArray["usage"] < 90)) or empty($limitArray)) {
        if (empty($printCurrentEditedPage) && empty($_SESSION['need_refresh'][$_SESSION['page_id']])) {
            echo "<div id=\"wrapper\">";
        } else {
            echo "<div id=\"wrapper\" style='margin-top: 61px;'>";
        }
    } else {
        echo "<div id=\"wrapper\" style='margin-top: 61px;'>";
    }
}


?>

<?php
$side_bar_exclusion = 0;
//if ($user_id == "") {
//first time here...we need to popup the modal and ask for a FB login
    /*include_once('templates/modal_fb_login.php');
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
    jQuery('#fb_settings').modal();
    });
    </script>*/
//}

$toastr_options = ' toastr.options = {
    "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };';
?>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M7QWDTM"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
