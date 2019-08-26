<?php
$public_key=smartbot_get_options($_SESSION['user_id'],'','','youzign_public_key');
$token=smartbot_get_options($_SESSION['user_id'],'','','youzign_token');
?>
<form method="POST" >
        <input type="hidden" id="youzign" name="actie" class="formatted-form" value="youzign">
        
		<input type="text" class="form-control" size="60" name="public_key" value="<?php echo $public_key;?>" placeholder="Youzign Public Key" style="width: 96%;" ><br>
		
		<input type="text" class="form-control" size="60" name="token" value="<?php echo $token;?>" placeholder="Youzign Token" style="width: 96%;" ><br>
        <input class="btn btn-primary" type="submit" value="Submit"> 
</form>