<?php
global $user_id;
?>
<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  <input type="hidden" name="ar_type" value="getresponse" />
          <input type="hidden" id="getresponse" name="getresponse" class="formatted-form" value="getresponse">
		  <input type="text" id="getresponse-key" name="getresponse-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','getresponse-key');?>"><br>
           <input class="btn btn-primary" type="submit" value="Submit">        
</form>