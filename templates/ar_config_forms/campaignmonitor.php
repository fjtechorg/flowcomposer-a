<?php
global $user_id;
?>
<form method="POST">
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="campaignmonitor" />
          <input type="hidden" id="campaignmonitor" name="campaignmonitor" class="formatted-form" value="campaignmonitor">
          <input type="text" id="campaignmonitor-key" name="campaignmonitor-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','campaignmonitor-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">     
</form>