<div id="modal_profile_bulk_customfield" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Custom field for the selected user(s)</h4>
            </div>
            <div class="modal-body">
                <div id="edit_bulk_customfield"></div>
                <div id="edit_bulk_customfield_button">
                    <?php

                    $cfieldsKeys = getAllCustomfieldsData($_SESSION['page_id']);
                    if(is_array($cfieldsKeys)){
	                    echo '<select id="bulk_customfield_key"  class="form-control"><option value="">Select Custom Field</option>';
	                    foreach($cfieldsKeys as $thisKey){
		                    echo '<option value="'.$thisKey->id.'">'.$thisKey->customfield_name.'</option>';
	                    }
	                    echo '</select>';
                    }
                    ?>
                    <br>
                    <input id="bulk_customfield_value" value="" class="form-control" placeholder="Enter Your Custom Field Value Here">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="float:left;">Close</button>
                <span class="btn btn-primary" id="add_bulk_customfield">Save</span>
            </div>
        </div>

    </div>
</div>