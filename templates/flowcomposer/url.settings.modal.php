<div id="url_card_settings" class="modal fade in flowcomposer-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <?php echo getModalClose("url") ?>

                <h4 class="modal-title">Go to URL</h4>
            </div>
            <div class="modal-body">
                <label>URL </label> - <a target="_blank" href="manage.php?action2=settings"> Must be whitelisted if Messenger Extensions SDK is enabled</a>
                <input type="text" data-action="url-input" id="url_input" class="pickers form-control" placeholder="Enter URL here" size="45">
                <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>

                <label>Webview Size</label>
                <select style="margin-bottom: 15px" class="form-control" id="webview_height_ratio_input" data-action="webview-height-ratio-input" >
                    <option value="full">Full</option>
                    <option value="tall">Tall</option>
                    <option value="compact">Compact</option>
                </select>
                <label>Enable Messenger Extensions SDK <em>(Advanced users only)</em></label> - <a target="_blank" href="https://developers.facebook.com/docs/messenger-platform/reference/messenger-extensions-sdk/">How does it work?</a> </label>
                <select style="margin-bottom: 15px" class="form-control" id="messenger_extensions" data-action="messenger-extensions" >
                    <option value="false">False</option>
                    <option value="true">True</option>
                </select>
                <span>Output of webview sizes (compact, tall, full)</span>
                <img src="https://cleverstorage.b-cdn.net/assets/webview.png" width="100%">

            </div>
                <?php echo getModalFooter(); ?>
        </div>

    </div>
</div>