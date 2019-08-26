<?php
function smartbot_ar_settings($page_id,$bot_id,$bot_page,$user_id){
echo '

    
        
        <div class="styling_configuration-right-container" style="padding-bottom: 0;">
        Select your integrated service and choose a list/campaign.
        <div class="configuration-sub-title" style="border: none;margin: 0;padding: 5px;"></div>
            <div class="form-group configuration-sub-content"">
                     <!--<label>AutoResponder/Webinar Provider</label>-->
					 <div id="active_integrations"></div>
                     <div id="ar_settings">';
    				 $ar_list = smartbot_get_active_ar($user_id,$page_id,$bot_id);
                      if(isset($ar_list['active'])){
    				  $active_lists = $ar_list['active'];
    				  
    				  echo '<select id="select_ar" class="form-control">';
    				  	   echo '<option value="">Select Integration</option>';
    				  	   foreach($active_lists as $active_list){
                            if($active_list=="raw_html"){$active_list_name="Custom HTML Form";}else{$active_list_name=$active_list;}
							echo '<option value="'.$active_list.'">'.$active_list_name.'</option>';
                            }
    					   
    				  echo '</select>';
                      }
    				 echo'
                     </div>
                     <div id="ar_settings_lists"></div>
            </div>
        </div>
    
';
?>
<?php
}	