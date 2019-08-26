<form method="POST"  accept-charset="UTF-8" class="">
	      <input type="hidden" name="actie" value="save_webinar" />
	  	  <input type="hidden" name="webinar_type" value="webinarjam" />
          <input type="text" id="webinarjam-key" name="webinarjam-key" placeholder='API key' class="form-control" style="width: 96%;" value="<?php echo smartbot_get_options($user_id,'','','webinarjam-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">       
</form>