<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="mailerlite" />
          <input type="hidden" id="mailerlite" name="mailerlite" class="formatted-form" value="mailerlite">
          <input type="text" id="mailerlite-key" name="mailerlite-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','mailerlite-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">        
</form>