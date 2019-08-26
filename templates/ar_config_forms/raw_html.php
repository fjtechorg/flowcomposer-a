<form method="POST" >
		  <input type="hidden" name="actie" value="save_ar" />
		  <input type="hidden" name="ar_type" value="raw_html" />
          <textarea rows="4" cols="70" placeholder="Enter your HTML form here" name="raw_html" class="form-control"><?php echo smartbot_get_options($user_id,'','','raw_html');?></textarea><br>
		  <input class="btn btn-primary" type="submit" value="Submit">        
</form>