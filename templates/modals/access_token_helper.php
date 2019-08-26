<div id="modal_access_token_helper" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Facebook Access Token Helper</h4>
            </div>
            <div id="page_share_content">
                <div class="modal-body">

                    <div id="chrome_extension_checker">
                        <p>In order to be able to generate your access token, you will need to install our Chrome extension.</p>
                        <a href="https://chrome.google.com/webstore/detail/clever-messenger-helper-e/lkhmcocjnikhimapcikjaphdnfchjgnj" target="_blank" data-action="install-chrome-extension">Install Clever Messenger Helper Extension</a>
                    </div>

                    <div id="access_token_helper_content" style="display: none">
                    <p>
                        <div id="credentials_container">
                        <label>Facebook Username</label>
                        <input placeholder="Facebook Username or Email" data-target="fb-username" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_name" value="<?php echo $profileInformation["email"] ?>" maxlength="160">
                        <label>Facebook Password</label>
                        <input placeholder="Facebook password" data-target="fb-password" class="form-control input-lg m-b" type="password" style="margin-bottom: 10px" name="template_alias" value="" maxlength="160">
                        </div>
                        <span id="fb_error_message" class="error-message" style="display: none;"></span>
                        <div style="clear: both;margin: 15px"></div>
                        <div id="access_token_phone_verification" style="display: none">
                        <input placeholder="Verification code" id="template_icon" data-target="fb-verification-code" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_icon" value="" maxlength="160">
                        </div>
                        <button data-action="generate-fb-access-token" class="btn btn-primary upload_icon">Generate Access Token</button>
                        <div id="access_token_container" style="display: none">
                            <label>Your access token</label>
                            <input placeholder="Access Token" readonly id="fb_access_token" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" >
                            <button data-action="generate-new-fb-access-token" class="btn btn-primary upload_icon">Generate new token</button>

                        </div>
                        <br><span id="details_result"></span>
                    </p>

                </div>

                </div>
            </div>
        </div>

    </div>
</div>
<div id="access_token_result"></div>