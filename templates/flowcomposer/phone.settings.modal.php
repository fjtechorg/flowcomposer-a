<div id="phone_card_settings" class="modal fade in flowcomposer-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <?php echo getModalClose("phone") ?>
                <h4 class="modal-title">Call Phone Number</h4>
            </div>
            <div class="modal-body">
                <label>Phone number</label>

                <input type="text" data-action="phone-input" class="pickers form-control" placeholder="Enter the phone number to be dialed, eg.+16505551234" size="45">
                <?php echo '<div data-target="air-picker"></div>' ?>


                <p class="m-b">Format must have "+" prefix followed by the country code, area code and local number. For example, +16505551234.</p>

            </div>
                <?php echo getModalFooter(); ?>
        </div>

    </div>
</div>