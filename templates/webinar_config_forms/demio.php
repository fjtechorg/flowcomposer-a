<form method="POST"  accept-charset="UTF-8" class="">
	      <input type="hidden" name="actie" value="save_webinar" />
	  	  <input type="hidden" name="webinar_type" value="demio" />
    <input type="text" id="demio-key" name="demio-key" placeholder='API key' class="form-control" style="width: 96%;" value="<?php echo smartbot_get_options($user_id,'','','demio-key');?>"><br>
    <input type="text" id="demio-key" name="demio-secret" placeholder='Secret key' class="form-control" style="width: 96%;" value="<?php echo smartbot_get_options($user_id,'','','demio-secret');?>"><br>
    <input class="btn btn-primary" type="submit" value="Submit">
</form>