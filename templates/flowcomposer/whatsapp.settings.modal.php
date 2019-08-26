<div id="whatsapp_card_settings" class="modal fade in flowcomposer-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <?php echo getModalClose("whatsapp") ?>
                <h4 class="modal-title">Initiate WhatsApp Conversation With Your Business</h4>
            </div>
            <div class="modal-body">
                <label>Your Business Phone number</label>

                <input type="text" data-action="wt-phone-number" class="pickers form-control" placeholder="Enter the phone number to be dialed, eg.+16505551234" size="45">
                <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>

                <label>Message text</label>

                <div>
                <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">

                    <textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="Message text" data-action="wt-text-message"  maxlength="640" ></textarea>
                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                </p>
                </div>
                <p class="m-b">This card allows your subscriber to start a conversation with your business via WhatsApp using your business phone number.</p>

                <p class="m-b">Format must have "+" prefix followed by the country code, area code and local number. For example, +16505551234.</p>

            </div>
            <?php echo getModalFooter(); ?>
        </div>

    </div>
</div>