<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="icontact" />
          <input type="hidden" id="icontact" name="icontact" class="formatted-form" value="icontact">
          <input type="text" id="icontact-api-key" name="icontact-api-key" placeholder='App Id' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','icontact-api-key');?>"> <br>
          <input type="text" id="icontact-username" name="icontact-username" placeholder='User Name' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','icontact-username');?>"> <br>
          <input type="text" id="icontact-password" name="icontact-password" placeholder='App Password'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','icontact-password');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">         
</form>