<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  <input type="hidden" name="ar_type" value="sendy" />
          <input type="hidden" id="sendy" name="sendy" class="formatted-form" value="sendy">
          <input type="text" id="sendy-api-url" name="sendy-api-url" placeholder='Installation Url' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','sendy-api-url');?>"><br>
          <input type="text" id="sendy-api-key" name="sendy-api-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','sendy-api-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">        
</form>