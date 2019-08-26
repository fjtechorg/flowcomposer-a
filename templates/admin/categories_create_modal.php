<div id="modal_category_create" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Create Category</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="catForm" class="form-horizontal" role="form" method="post" action="">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Name:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="cat_name" placeholder="Education" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Icon:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="cat_icon" placeholder="icon icon-plus" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Font Color:</label>
                            <div class="col-md-8">
                                <input class="form-control" id="cat_color" placeholder="#fff" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Background Color:</label>
                            <div class="col-md-8">
                                <input class="form-control" id="cat_background" placeholder="green" type="text">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn btn-primary category_confirm" data-type="" data-dismiss="modal">Save</button>
            </div>
        </div>

    </div>
</div>