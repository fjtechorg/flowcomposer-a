<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  <input type="hidden" name="ar_type" value="activecampaign" />
          <input type="hidden" id="activecampaign" name="activecampaign" class="formatted-form" value="activecampaign">
          <input type="text" id="activecampaign-url" name="activecampaign-url" placeholder='API Access URL' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','activecampaign-url');?>"><br>
          <input type="text" id="activecampaign-key" name="activecampaign-key" placeholder='API Access Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','activecampaign-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">         
</form>