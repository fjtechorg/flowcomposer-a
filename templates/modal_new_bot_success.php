<div id="newbot_modal" class="modal fade" role="dialog" style="height: 100%;overflow: hidden;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;padding: 0;min-height:auto;">
            </div>
            <div class="modal-body" style="text-align:  center;">
                <h2 style="font-size:  40px;font-weight: bold;"><img src="/assets/images/party-popper.png"
                                                                     style="height: 40px;margin-top: -15px;">
                    Congratulations!</h2>
                <h3 style="">Your new chatbot for <?php echo $_SESSION['page_name']; ?> has been created!</h3>
                <img src="<?php echo $page_scan_code ?>" style="max-width: 300px;display: inherit;margin: auto;">
                <span class="btn btn-primary" data-dismiss="modal" style="padding: 8px 16px;margin-top: 20px;">Start Building</span>
            </div>
            <div class="modal-footer" style="border-top: none;padding: 0;">
                <div id="confetti-wrapper"></div>
            </div>
        </div>
    </div>
</div>