<form method="POST" >
        <input type="hidden" id="shopify" name="actie" class="formatted-form" value="shopify">
        
        <input id="shopify_url" name="shopify_url" type="text" class="form-control" style="width: 96%;" placeholder="http://YOURSHOPNAME.myshopify.com" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','shopify_url');?>"><br>
        
        <input id="shopify_api_key" name="shopify_api_key" type="text" class="form-control" style="width: 96%;" placeholder="API Key" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','shopify_api_key');?>"><br>
        
        <input id="shopify_api_pass" name="shopify_api_pass" type="text" class="form-control" style="width: 96%;" placeholder="App Password" value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','shopify_api_pass');?>"><br>
        <input class="btn btn-primary" type="submit" value="Submit"> 
</form>