<?php
echo "<div class=\"row\">";

$P_Count = count($fb_pages) ;
$R_Count = ceil($P_Count / 3);
$Cur_Row = 1;
$Cur_Count = 1; 

if($P_Count==0){
   $first_time = checkFirstLogin($_SESSION['username']);
   if($first_time !=1 ) {
       echo '<div class="col-lg-4">
     <div class="ibox float-e-margins">
        <div class="ibox-title">
		The home of your first bot  
		 <div class="ibox-tools" style="display:inline-block;">
         <div style="float:left; margin-right: 5px;"> </div> 
		 </div>
		 </div>
			   <div class="ibox-content" style="
    height: 135px;
">
                <img src="'.$_SESSION['brand']['nav_logo_left'].'" style="float: left; margin-right:10px;width: 40px;height: 50px;">
                <span style="float: left;">Your page name</span><br>
                <small style="float: left;">Category: Your page category</small>
                <br style="clear:both;margin-bottom:30px">
                    <div class="total_subs_container">
                    <span>Total Subscribers</span>                                   
				    <div id="total_subscribers" class="total_subscribers">0</div>
                </div>
                <div class="active_subs_container">
                    <span>Active Subscribers</span>                                
				    <div id="active_subscribers" class="active_subscribers">0</div>
                </div>      

               </div>          		  
         </div>
</div>';
   }
}


if(!empty($fb_pages)){
foreach($fb_pages as $page){
$page_scan_code='';
$this_page_id='';

	 if(isset($page['page_id'])){
               $this_page_id = $page['page_id'];    
            }
            if(isset($page["page_title"])){
               $page_name = $page["page_title"];    
            }
            if(isset($page['page_category'])){
               $page_cat = $page['page_category'];   
            }
            if(isset($page['page_image'])){
                if(true){
                    //load page image from FB
                    $page_image = 'https://graph.facebook.com/' . $this_page_id . '/picture?type=normal';
                }
                else{
                    //load page image from our CDN
                    $page_image = $page['page_image'];
                }
            }
            if(isset($page['page_token'])){
               $page_token= $page['page_token'];    
            }
            if(isset($page['page_alias'])){
               $page_alias = $page['page_alias'];    
            } else {
                $page_alias = "";    
            }
            if(isset($page['page_scan_code'])){
               $page_scan_code = $page['page_scan_code'];   
            }
            if(isset($page['page_desc'])){
                $page_desc = $page['page_desc'];
            }
            if(isset($page['need_refresh'])){
                $_SESSION['need_refresh'][$this_page_id] = $needRefresh = $page['need_refresh'];
            }

    $backgroundColor = "";
    if (isset($needRefresh) && $needRefresh) $backgroundColor = 'background-color: #ffebeb;';


    echo "<div class=\"col-lg-4 bot_card\" data-bot_id=\"".$this_page_id."\" id=\"".$this_page_id."_card\">
                <div class=\"ibox float-e-margins\">
                    <div class=\"ibox-title\" style='$backgroundColor'>
        ";
        echo "<div style=\"white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 90%;float: left;\">".$page["page_title"]."</div>";
        echo "  <div class=\"ibox-tools\" style=\"display:inline-block;\">";


               echo'
         <div style="float:left; margin-right: 5px;"><li style="list-style: none;" class="">                        
        <a href="#" id="'.$this_page_id.'_dropdown" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="icon-cog" style="color: #565a6d;"></i> </a>           
          <ul  class="dropdown-menu" style=" /* width: 80px; */
    min-width: auto;">
          <li><form method="post" action="dashboard.php">
         <input type="hidden" name="action2" value="settings">
         <input type="hidden" name="page_cat" value="'.$page_cat.'">
         <input type="hidden" name="page_image" value="'.$page_image.'">
         <input type="hidden" name="page_name" value="'.$page_name.'">
         <input type="hidden" name="page_token" value="'.$page_token.'">
         <input type="hidden" name="page_id" value="'.$this_page_id.'">
         <input type="hidden" name="page_alias" value="'.$page_alias.'">
         <input type="hidden" name="page_scan_code" value="'.$page_scan_code.'">
         <input type="hidden" name="page_desc" value="'.$page_desc.'">
         
         <input id="male_'.$this_page_id.'" type="image" name="myclicker" src="images/cogs.png" value="view" alt="view" width="16" style="display: none; ">
         <label class="bot_hover_settings" for="male_'.$this_page_id.'" style="
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 3px;
    line-height: 25px;
    margin: 4px;
    text-align: left;
    font-weight: normal;
    display: block;
    clear: both;
    white-space: nowrap;
">Dashboard</label>
         </form></li>               
          
                    <li><a href="#" class="sidebar_link delete_bot" data-page_id="'.$this_page_id.'" data-bot_id="">Delete</a></li>                                         
          </ul>                       
      </li>
			   </div> '; 
        echo "</div>";
        echo "</div>";

        echo "<div class=\"ibox-content\" style='$backgroundColor'>
                <img src=\"".$page_image."\" style=\"float: left; margin-right:10px;border-radius: 50%;height:50px\" />
                <img src=\"images/facebook-512.svg\" style=\"float: right;\" width=\"25\" />
                <span style=\"white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 75%;float: left;\">".$page["page_title"]."</span><br />
                <small style=\"float: left;\">Category: ".$page["page_category"]."</small>
                <br style=\"clear:both;\" />
                <div class=\"total_subs_container\">
                    <span>Total Subscribers</span>                                   
				    <div id=\"".$this_page_id."_total_subscribers\" class=\"total_subscribers\"></div>
                </div>
                <div class=\"active_subs_container\">
                    <span>Active Subscribers</span>                                
				    <div id=\"".$this_page_id."_active_subscribers\" class=\"active_subscribers\"></div>
                </div>                    
                <br style=\"clear: both;\" />
               </div>";
                  ?>
          		  <script type="text/javascript">
          		  var Sub = PageSubscribersCount('<?php echo $this_page_id;?>');
          		  </script>
          		  <?php    
                  echo "</div></div>";     
              $Cur_Count++;

}

}
?>               
