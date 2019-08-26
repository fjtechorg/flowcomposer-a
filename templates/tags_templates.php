<?php
function smartbot_show_msg_tags_input($msg_id,$page_id){
$msg_tags_input='<div id="msg_tags_'.$msg_id.'">'.getMsgTags($msg_id,$page_id).'</div>
				<div style="clear:both;"></div>
				 <div id="msg_tags_button" class="input-group">
						 <input id="tag_value_'.$msg_id.'" value="" class="form-control" placeholder="Enter your tag(s)" size="30">
						 	  <span class="input-group-btn" id="add_tag" data-msg_id="'.$msg_id.'"><button type="button" class="btn btn-primary styling_model_tags_fieldbutton">Add Tag</button></span>
				 </div>';
return $msg_tags_input;							 
}							  