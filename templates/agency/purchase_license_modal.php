<div id="modal_agency_purchase_licenses" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Purchase Client License</h4>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="">
                    <div style="width: 100%; float: left;">
                        <input id="sublicensecount" type="number" min="1" value="1" id="sublicensecount" value="" name="sublicensecount" required>
                        <input id="stripeEmail" name="stripeEmail" type="hidden" value="<?php echo $_SESSION["user"]["user_name"]; ?>"  required>
                    </div>
                    <div style="width: 100%;float: left; text-align: center; line-height: 34px;">
                        <span id="sublicenseprice">$39</span><span> per license per month</span>
                    </div>
                    <br style="clear: both">
                    <a id="purchaseLicenses" class="btn btn-primary btn-block" href="">Buy now</a>
                </form>
            </div>
        </div>

    </div>
</div>