<div id="modal_user_edit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit User</h4>
		<input type="hidden" name="edit_user_id" id="edit_user_id" value="">
      </div>
      <div class="modal-body">
	  	   					 <div id="edit_profile"></div>
							 
							 <div style="clear:both;"></div>
							  <hr />
							 
							 <div id="edit_password">
							 <input id="password_value" value="" class="form-control" placeholder="Enter a new password">
							 	  <span class="btn btn-primary" id="change_password">Change Password</span>
							 </div> 
							 <hr />

          <div>
            <p>Button below is to remove the Facebook id and pages. This will force a new connect and pages check</p>
              <span class="btn btn-primary" id="clear_account">Clean Account</span>
          </div>
          <hr />

	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>