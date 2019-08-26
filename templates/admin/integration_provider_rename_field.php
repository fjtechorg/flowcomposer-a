<div id="modal_service_provider_rename_field" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Rename Integration Key Name</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="fieldRenameForm" class="form-horizontal" role="form" method="post" action="">
                        <div class="form-group">
                            <label class="col-lg-12" style="text-align: center;">
                                <img class="int_img" src="https://dev.clevermessenger.com/images/ar-icons/ar-activeCampaign.png" width="50px"><i class="int_name"> ActiveCampaign</i>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Old name:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="field_old_name" placeholder="activecampaign-token" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">New name:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="field_new_name" placeholder="token" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <p><b class="text-danger">Warning:</b>
                                    <br>This functionality does not check for duplicate field names, please check manually if there are any duplicates for the new name in the same integration.
                                    <br>This does not change the name in the JSON structure.
                                    <br>For 'post' type, values are returned from service provider hence they need to be renamed in code.
                                    <br>
                                    <br>Use this functionality for only <b class="text-warning">'post'</b> type, for <b class="text-warning">'direct'</b> type edit from JSON structure which has duplicate checks along with replacement functionality.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-primary" id="save_field_name">Save</button>
            </div>
        </div>

    </div>
</div>