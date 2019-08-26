<div class="modal inmodal fade" id="modal_agency_upgrade_subscribers" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content modal-content-rounded">
            <div class="modal-body" id="subspaymentmodal">
                <h1 id="subscriberpaymentmodaltitle" class="modal-title">Upgrade subscriber limits</h1>
                <div class="periodtogglewrap">
                    <span class="monthly">Monthly</span>
                    <div class="styling_required_mini_toggle">
                        <input type="checkbox" id="subscriberperiod_toggle" >
                        <label for="subscriberperiod_toggle"></label>
                        <input id="period" type="hidden" value="">
                    </div>
                    <span class="yearly">Yearly</span>
                </div>
                <form action="" method="post">
                    <input id="subs" type="hidden" value="1000" name="subs" required>
                    <input id="user_id" type="hidden" value="" name="user_id" required>
                    <br>
                    <select id="upgrade-qty" class="form-control">
                        <option value="select">Select</option>
                        <option value="flow">1000 Subscribers $10/month</option>
                        <option value="flow">2500 Subscribers $25/month</option>
                        <option value="flow">5000 Subscribers $50/month</option>
                        <option value="flow">10000 Subscribers $100/month</option>
                        <option value="flow">15000 Subscribers $150/month</option>
                        <option value="flow">20000 Subscribers $200/month</option>
                    </select>
                    <br style="clear: both">
                    <a id="licensebutton" class="btn btn-primary" style="width: 100%;">Continue with purchase</a>
                </form>
            </div>
        </div>
    </div>
</div>