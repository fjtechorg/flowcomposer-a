<?php
function smartbot_show_msg_keywords_input($msg_id,$page_id){
	$msg_keywords_input='
	<div id="msg_keywords_alert"></div>
<div id="msg_keywords_'.$msg_id.'" class="tag-container">'.smartbot_get_msg_keywords($msg_id,$page_id).'</div><div style="clear:both;"></div>
				 <div id="msg_keywords_button" class="input-group">
						 <input id="operator_keywords_'.$msg_id.'" value="" class="form-control add_pos_keyword_field" placeholder=" Enter positive keyword(s) - this will trigger your message." size="30">
						 	  <span class="input-group-btn" id="add_keyword" data-msg_id="'.$msg_id.'"><button type="button" class="btn btn-primary styling_model_tags_fieldbutton">Add Keyword</button></span>
				 </div>
<br>
<div id="msg_neg_keywords_'.$msg_id.'" class="tag-container">'.smartbot_get_msg_neg_keywords($msg_id,$page_id).'</div><div style="clear:both;"></div>
				 <div id="msg_keywords_button" class="input-group">
						 <input id="operator_neg_keywords_'.$msg_id.'" value="" class="form-control add_neg_keyword_field" placeholder="Enter negative keyword(s) - this will STOP the positive keyword." size="30">
						 	  <span class="input-group-btn" id="add_neg_keyword" data-msg_id="'.$msg_id.'"><button type="button" class="btn btn-primary styling_model_tags_fieldbutton">Add Keyword</button></span>
				 </div><br />  
';
return $msg_keywords_input;							 
}							  