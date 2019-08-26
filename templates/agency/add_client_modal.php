<div id="modal_agency_add_client" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Client</h4>
            </div>
            <div class="modal-body">
                <form id="addClientForm" class="form-horizontal" role="form" method="post" action="">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">First Name:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="sub_user_first_name" placeholder="John" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Last Name:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="sub_user_last_name" placeholder="Doe" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Email Address:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="sub_user_email" placeholder="test@mail.com" type="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Password:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="sub_user_pass" value="" type="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Confirm Password:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="sub_user_pass_confirm" value="" type="password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="margin-top:18px;">Subscribers</label>
                        <div class="col-md-8">
                            <div style="width: 100%;float: left; text-align: center; line-height: 34px; margin-bottom: 25px;margin-top: 5px">
                                <div id="ionrange_1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-8">
                            <button class="btn btn-primary" id="create_agency_user" type="button" style="float:right;width: 100%;">Save</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>