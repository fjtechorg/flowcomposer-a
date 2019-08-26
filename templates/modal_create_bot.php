<div id="createbot_modal" class="modal fade" role="dialog">
    <input type="hidden" id="bot_id" />
    <input type="hidden" id="bot_page" />
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
    <form method="post" action="dashboard.php" style="display:none;">
        <input type="hidden" name="action2" value="settings">
        <input id="newbot_page_cat" type="hidden" name="page_cat" value="">
        <input id="newbot_page_image" type="hidden" name="page_image" value="">
        <input id="newbot_page_name" type="hidden" name="page_name" value="">
        <input id="newbot_page_token" type="hidden" name="page_token" value="">
        <input id="newbot_page_id" type="hidden" name="page_id" value="">
        <input id="newbot_page_alias" type="hidden" name="page_alias" value="">
        <input id="newbot_page_scan_code" type="hidden" name="page_scan_code" value="">
        <input id="newbot_page_desc" type="hidden" name="page_desc" value="">
        <input type="submit" name="submit" id="newbot_form_submit">
    </form>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Continue by connecting your page</h4>
            </div>
            <div class="modal-body">
                <fieldset>
                    <h2 style="display: none;">Connect to Facebook</h2>
                    <div class="row">
                        <div id="wizard_connect_fb_heading" style="margin: 6px; padding: 10px;display: none;">Connect your bot to your Facebook page by clicking on "Connect to Page"</div>
                        <div id="wizard_connect_fb">
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>

    </div>
</div>