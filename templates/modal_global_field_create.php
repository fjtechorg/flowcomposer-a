<div id="modal_global_field_create" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title cf-title">Create Global Field</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="global_field_name">Name</label>
                    <input placeholder="Global Field Name" class="form-control" id="global_field_name" type="text" maxlength="200">
                </div>
                <div class="form-group">
                    <label for="global_field_type">Type</label>
                    <select class="form-control" id="global_field_type" name="global_field_type">
                        <option value="">Select Type</option>
                        <option value="text">Text</option>
                        <option value="numeric">Numeric</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone Number</option>
                        <option value="url">URL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="global_field_value">Value</label>
                    <input class="form-control" id="global_field_value" type="text" placeholder="value(s)">
                </div>
                <div class="form-group">
                    <label for="global_field_desc">Description</label>
                    <textarea placeholder="Describe the Global Field in few words" class="form-control" id="global_field_desc" type="text" maxlength="200" rows="3"></textarea>
                </div>


            </div>
            <div class="modal-footer">
                <button class="btn btn-primary save_global_field_yes" data-type="create/update" data-id="">Save</button>
            </div>
        </div>

    </div>
</div>