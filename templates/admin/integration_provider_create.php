<div id="modal_service_provider_create" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Create Service provider</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="catForm" class="form-horizontal" role="form" method="post" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Name:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="provider_name" placeholder="ActiveCampaign" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Image url:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="provider_img" placeholder="https://dev.clevermessenger.com/images/ar-icons/ar-activeCampaign.png" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Category:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="provider_cat">
                                    <?php
                                    $types = getProviderTypes();
                                    foreach($types as $type){
                                        echo '<option value="'.$type->id.'">'.$type->name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <b>JSON Structure Instructions</b>
                                <p><b>"type"</b>: value must be either <br> a. "direct" - In direct no authentication is required <br> b. "post" - In post user would be redirected to service provider to authenticate</p>
                                <p><b>"fields"</b>: it is an array of objects with name and placeholder as object properties, note the name must match the key for the specified provider, incase of post/authenticate type, fields array can be left empty.</p>
                                <p><b>"verify_url"</b>: the verification url path on which the key pairs are verified for the specific provider.</p>
                                <p><b>"raw"</b>: json object , which will be used in the body/raw data for verification request, <br><b>note*</b>: The values for the keys in the raw object should have % enclosed variable names which should match the key pair name from the service provider eg: %aweber-token%</p>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">JSON:</label>
                            <div class="col-md-8">
                                <!--<textarea class="form-control" id="provider_raw" type="text"></textarea>-->
                                <div id="jsoneditor" style="width: 400px; height: 400px;"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-primary" id="save_provider" data-type="create" data-dismiss="modal">Save</button>
            </div>
        </div>

    </div>
</div>