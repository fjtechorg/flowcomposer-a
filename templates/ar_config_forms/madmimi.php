<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  		  <input type="hidden" name="ar_type" value="madmimi" />
          <input type="hidden" id="madmimi" name="madmimi" class="formatted-form" value="madmimi">
          <input type="text" id="madmimi-username" name="madmimi-username" placeholder='User Name' style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','madmimi-username');?>"><br>
          <input type="text" id="madmimi-api-key" name="madmimi-api-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','madmimi-api-key');?>"><br>
          <input class="btn btn-primary" type="submit" value="Submit">    
</form>