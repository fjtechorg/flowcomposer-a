<form method="POST"  accept-charset="UTF-8" class="">
	      <input type="hidden" name="actie" value="save_webinar" />
	  	  <input type="hidden" name="webinar_type" value="webinarjeo" />
          <input type="hidden" id="webinareo" name="webinarjeo" class="formatted-form" value="webinarjeo">
          <input type="text" id="webinarjeo-user" name="webinarjeo-user" placeholder='Email' class="form-control" style="width: 96%;" value="<?php echo smartbot_get_options($user_id,'','','webinarjeo-user');?>"><br>
          <input type="text" id="webinarjeo-pass" name="webinarjeo-pass" placeholder='Password' class="form-control" style="width: 96%;" value="<?php echo smartbot_get_options($user_id,'','','webinarjeo-pass');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">       
</form>