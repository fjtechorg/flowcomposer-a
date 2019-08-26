<?php
global $user_id;
?>
<form method="POST">
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="convertkit" />
          <input type="hidden" id="convertkit" name="convertkit" class="formatted-form" value="convertkit">
          <input type="text" id="convertkit-key" name="convertkit-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','convertkit-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">         
</form>