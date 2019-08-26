<div id="modal_customfield_create" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Custom Field</h4>
            </div>
            <div class="modal-body">
                <label for="global_field_type">Name</label>
                <input placeholder="Custom Field name" class="form-control" id="customfield_name" type="text"><br>

                <label for="global_field_type">Type</label>
                <select class="form-control" id="customfield_type" name="customfield_type">
                    <option value="">Select Type</option>
                    <option value="text">Text</option>
                    <option value="numeric">Numeric</option>
                    <option value="email">Email</option>
                    <option value="phone">Phone number</option>
                    <option value="location">Location</option>
                    <option value="url">URL</option>
                </select>


            </div>
            <div class="modal-footer">
                <button class="btn btn-primary create_customfield_yes">Create</button>
            </div>
        </div>

    </div>
</div>