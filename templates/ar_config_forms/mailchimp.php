<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="mailchimp" />
          <input type="hidden" id="mailchimp" name="mailchimp" class="formatted-form" value="mailchimp">
          <input type="text" id="mailchimp-key" name="mailchimp-key" placeholder='mailchimp-key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','mailchimp-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">       
</form>