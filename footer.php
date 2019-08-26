</div>
</div>
<?php
include('templates/modal_alert.php');
include('templates/modal_prompt.php');
include('templates/modal_confirm.php');
?>

<script src="js/jquery.charactercounter.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/jquery.slimscroll/jquery.slimscroll.js"></script>
<script src="js/plugins/lodash/lodash.min.js"></script>


<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>
<script src="js/plugins/idle-timer/idle-timer.min.js"></script>
<script src="js/plugins/toastr/toastr.min.js"></script>
<script src="js/plugins/spectrum/spectrum.js"></script>
<!-- common bot level functions-->
<script src="js/common-blocks.js"></script>
<script src="js/plugins/emoji-picker/js/config.js"></script>
<script src="js/plugins/emoji-picker/js/util.js"></script>

<script src="js/jquery.emojiarea.js"></script>
<script src="js/emoji-picker.js"></script>
<script src="js/tagsFunctions.js?<?php echo time() ?>"></script>
<script src="js/previewFunctions.js?<?php echo time() ?>"></script>
<script src="js/flowPreviewFunctions.js?<?php echo time() ?>"></script>
<script src="js/emojiFunctions.js?<?php echo time() ?>"></script>
<script src="js/customFieldsFunctions.js?<?php echo time() ?>"></script>
<script src="js/globalFieldFunctions.js?<?php echo time() ?>"></script>
<script src="js/personalizationFunctions.js?<?php echo time() ?>"></script>
<script src="js/genericFunctions.js?<?php echo time() ?>"></script>
<script src="js/plugins/jquery.waituntilexists/jquery.waituntilexists.min.js"></script>
<script src="js/plugins/plyr/plyr.js"></script>
<script src="js/flowHelpers.js"></script>
<script src="js/tourFunctions.js"></script>


<script>
    paceOptions = {
        elements: true
    };
</script>
<script src="js/plugins/pace/pace.min.js"></script>


<?php
if (isset($footer_additionals)) {
    echo $footer_additionals;
}
?>
<script type="text/javascript">
    // body tooltip enabler
    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });


    // auto logout script

    $(document).ready(function () {
        // Set idle time
        $(document).idleTimer(120000);
    });

    $(document).on("idle.idleTimer", function (event, elem, obj) {

    });



    function redirectToLogin() {
        //here we redirect the user to the login.php file
        window.location = "login.php";
    }

    function redirectToDashboard() {
        //here we redirect the user to the login.php file
        window.location = "index.php";
    }

    function currentPage() {

        <?php
        $thisPage = basename($_SERVER['PHP_SELF'], '.php');
        $pageLevel = pageSpecificPages($thisPage);
        ?>
        return <?php echo $pageLevel;?>;
    }

</script>

<?php
if (isset($_GET['fb_refresh'])) {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            jQuery('#fb_settings').modal('toggle');
        });
    </script>
    <?php
}

if (isset($_SESSION["page_name"])) {
    echo "<script>window.pageName = '" . addslashes($_SESSION['page_name']) . "';window.pageId = '" . $_SESSION['page_id'] . "';window.userId = '".$_SESSION["user_id"]."';window.userIndex ='".$_SESSION["user"]["id"]."'</script>";
}
?>
<script async="" defer="" src="https://connect.facebook.net/en_US/sdk.js"></script>
</body>
</html>