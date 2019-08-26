<div id="modal_leadcatchers" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Lead Catchers Code</h4>
      </div>
      <div class="modal-body">
	  <h3>Please Copy and Paste these codes in your site</h3>
	  <label>SCRIPT code:</label>					
<div id="leadcatcher_script_result">
<textarea rows="8" cols="50">
<script>

    window.fbAsyncInit = function() {
      FB.init({
        appId: "370910519945402",
        xfbml: true,
        version: "v2.6"
      });

    };

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) { return; }
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

  </script>
</textarea>
</div>
			
<label>HTML code:</label>
<div id="leadcatcher_html_result">
<textarea rows="8" cols="50" >
<div class="fb-send-to-messenger" 
messenger_app_id="370910519945402" 
page_id="<?php echo $_SESSION['page_id'];?>" 
data-ref="messenger_link" 
color="blue" 
size="standard">
</div>  
</textarea></div>	
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>