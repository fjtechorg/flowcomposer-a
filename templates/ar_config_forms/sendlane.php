<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="sendlane" />
          <input type="hidden" id="sendlane" name="sendlane" class="formatted-form" value="sendlane">
          <input type="text" id="sendlane-api-url" name="sendlane-api-url" placeholder='http://username.sendlane.com' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','sendlane-api-url');?>"> <br>
          <input type="text" id="sendlane-api-key" name="sendlane-api-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','sendlane-api-key');?>"><br>
          <input type="text" id="sendlane-api-hash-key" name="sendlane-api-hash-key" placeholder='Hash Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','sendlane-api-hash-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">         
</form>