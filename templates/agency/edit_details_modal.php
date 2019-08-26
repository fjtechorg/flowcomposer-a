<div id="modal_agency_details" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Agency Details</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post" action="">

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Name:</label>
                        <div class="col-lg-9">
                            <input class="form-control" id="agency_name" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Address:</label>
                        <div class="col-lg-9">
                            <input class="form-control" id="agency_address" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Logo: <br><a target="_blank" href="https://app.clevermessenger.com/images/logo.svg">example</a></label>
                        <div class="col-md-9">
                            <input type="file" class="filepond" name="filepond" accept="image/png, image/jpeg, image/jpg, image/svg+xml"/>
                            <br>
                            <img class="filepond_preview" style="max-width: 100%;" src=""/>
                        </div>
                    </div>

                    <?php if($_SESSION['membership']['whitelabel']==1){?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Square Logo: <br><a target="_blank" href="https://app.clevermessenger.com/images/logo-mini-grey.svg">example</a></label>
                            <div class="col-md-9">
                                <input type="file" class="filepond_square" name="filepond" accept="image/png, image/jpeg, image/jpg, image/svg+xml"/>
                                <br>
                                <img class="filepond_square_preview" style="max-width: 100%;" src=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Favicon: <br><a target="_blank" href="https://app.clevermessenger.com/favicon.ico">example</a></label>
                            <div class="col-md-9">
                                <input type="file" class="filepond_favi" name="filepond" accept="image/x-icon"/>
                                <br>
                                <img class="filepond_favi_preview" style="max-width: 100%;" src=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Header: <br><a target="_blank" href="https://app.clevermessenger.com/templates/email/email-header-cm.png">example</a></label>
                            <div class="col-md-9">
                                <input type="file" class="filepond_email" name="filepond" accept="image/png, image/jpeg, image/jpg, image/svg+xml"/>
                                <br>
                                <img class="filepond_email_preview" style="max-width: 100%;" src=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Domain:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_domain" type="text" placeholder="Contact support to setup domain" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Website:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_website" type="text" placeholder="Enter website">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Support link:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_link_support" type="text" placeholder="Enter your supportdesk link">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label class="col-lg-12 text-center">SMTP Settings</label>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Email:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_email" type="text" placeholder="Enter email to communicate">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Host:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_smtp_host" type="text" placeholder="Enter host">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Port:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_smtp_port" type="text" placeholder="Enter port">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Username:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_smtp_username" type="text" placeholder="Enter Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Password:</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="agency_smtp_password" type="text" placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Encryption Type:</label>
                            <div class="col-lg-9">
                                <select class="form-control" id="agency_enc_type">
                                    <option value="">None</option>
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                        </div>

                    <?php }?>

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <button id="saveAgencyDetails" class="btn btn-primary" type="button" style="float:right;width: 100%;">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>