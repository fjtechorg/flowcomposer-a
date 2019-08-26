<?php
global $user_id;
?>
<form method="POST">
    <input type="hidden" name="actie" value="save_ar" />
    <input type="hidden" name="ar_type" value="markethero" />
    <input type="hidden" id="markethero" name="markethero" class="formatted-form" value="markethero">
    <input type="text" id="markethero-key" name="markethero-key" placeholder='API Key'  style="width: 96%;" class="form-control" value="<?php echo smartbot_get_options($user_id,'','','markethero-key');?>"><br>
    <input class="btn btn-primary" type="submit" value="Submit">
</form>