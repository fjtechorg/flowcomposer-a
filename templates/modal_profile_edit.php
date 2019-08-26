<div id="modal_profile_edit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Profile</h4>
      </div>
      <div class="modal-body">
	  	   					 <div id="edit_profile"></div>
							 
							 <div style="clear:both;"></div>
							  <hr />
							 <span id="edit_email_title" style="padding-bottom: 10px;"><i class="icon-paper-plane"></i> <strong>Email</strong></span>
							 <div id="edit_email"></div>
							 <div id="edit_email_button" class="input-group" style="padding-top: 10px;">
							 <input id="email_value" value="" class="form-control" placeholder="Enter the users Email">
                                 <span class="input-group-btn" id="add_email"><button type="button" class="btn btn-primary">Add Email</button></span>
							 </div> 
							 <hr />
							 <span id="edit_tags_title"><i class="fa icon-tags"></i> <strong>Tags</strong></span>
							 <div id="edit_tags" style="padding-top: 10px;padding-bottom: 10px;"></div>
							 <div class="input-group" id="edit_tags_button">
							 <input id="tag_value" value="" class="form-control" placeholder="Enter Your Tags Here">
                                 <span class="input-group-btn" id="add_tag"><button type="button" class="btn btn-primary">Add Tag</button></span>
							 </div> 
							 
							 <hr />
							 <div id="edit_pause_auto">
							 <i class="fa icon-bubbles" id="pause_icon"></i> <strong>Pause Automated Replies</strong>
							 <div id="edit_pause_status"></div>
							 </div>	

	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>