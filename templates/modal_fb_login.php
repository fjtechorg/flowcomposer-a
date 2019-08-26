<div id="fb_settings" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Facebook Login</h4>
      </div>
      <div class="modal-body">
	  <p>Please click the button below to login into your Facebook account and approve the Cleverapp</p>
	  <?php 
	  
	  $fb = new Facebook\Facebook([
      'app_id' => SB_FB_APP,
  	  'app_secret' => SB_FB_SECRET,
  	  'default_graph_version' => 'v2.8',
  	  ]);
      $this_url = 'https://'. $_SERVER["SERVER_NAME"]. '/index.php';
      $helper = $fb->getRedirectLoginHelper();
      if(isset($_GET['state'])&& $_GET['state']!=""){$_SESSION['FBRLH_state']=$_GET['state'];}
      $loginUrl = getFacebookLoginURL($this_url);
	  ?>
	  <a href="<?php echo $loginUrl ?>" class="btn btn-primary" >Connect To Facebook</a>
	  </div>
      <div class="modal-footer">
      </div>
    </div>

  </div>
</div>