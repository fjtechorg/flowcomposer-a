<?php
$WEB_arr = array(
    'GoToWebinar' => array(
        'image' => "gotowebinar.png",
        'settings' => 'gotowebinar.php'
        ),
    'Webinar Jam' => array(
        'image' => "webinarjam.png",
        'settings' => 'webinarjam.php'
        ),    
	'Webinar Jeo' => array(
        'image' => "webinarjeo.png",
        'settings' => 'webinarjeo.php'
        ),
    'Demio' => array(
        'image' => "demio.png",
        'settings' => 'demio.php'
        )    
);
      
$i = 1;   
echo "<div class=\"panel-body\">
                                <div class=\"panel-group styling_integrations_panel\" id=\"accordion2\">";
    foreach($WEB_arr as $name => $data){
        echo "<div class=\"panel panel-default\">
                                        <div class=\"panel-heading\" style=\"padding:5px 10px;\">
                                            <h5 class=\"panel-title\">";
        echo "<a data-toggle=\"collapse\" data-parent=\"#accordion2\" href=\"#2collapse".$i."\" data-target=\"#2collapse".$i."\" style=\"display:block;\">"."<img src=\"images/webinar-icons/".$data["image"]."\" width=\"60\" style=\"margin-right:20px;\"/>".$name."</a>";
        echo "                          </h5>
                                        </div>";
        echo "<div id=\"2collapse".$i."\" class=\"panel-collapse collapse\">";
        echo "<div class=\"panel-body\">";
        include('templates/webinar_config_forms/' . $data["settings"]);
        echo "                      </div>
                                        </div>";
        echo "</div>";
        $i++;
        
    }
echo "</div></div>";
?>
