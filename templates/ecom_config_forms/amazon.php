<form method="POST">                                	
<input type="hidden" name="actie" value="amazon">	
<input type="text" class="form-control" size="60" placeholder="Amazon Affiliate ID" name="amazon_id" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_id');?>"><br>			
<input type="text" class="form-control" size="60" placeholder="Amazon Public Key" name="amazon_public" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_public');?>"><br>	
<input type="text" class="form-control" size="60" placeholder="Amazon Secret Key" name="amazon_secret" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_secret');?>"><br>	
<input type="submit" value="Submit" class="btn btn-primary"/></form>