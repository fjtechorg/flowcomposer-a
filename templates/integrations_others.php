<?php
$AR_arr = array(
    'Youzign' => array(
        'image' => "youzign.png",
        'settings' => 'youzign.php'
        )
    );
      
$i = 1;   
echo "<div class=\"panel-body\">
                                <div class=\"panel-group styling_integrations_panel\" id=\"accordion4\">";
    foreach($AR_arr as $name => $data){
        echo "<div class=\"panel panel-default\">
                                        <div class=\"panel-heading\" style=\"padding:5px 10px;\">
                                            <h5 class=\"panel-title\">";
        echo "<a data-toggle=\"collapse\" data-parent=\"#accordion4\" href=\"#4collapse".$i."\" style=\"display:block;\">"."<img src=\"images/others-icons/".$data["image"]."\" width=\"60\" style=\"margin-right:20px;\"/>".$name."</a>";
        echo "                          </h5>
                                        </div>";
        echo "<div id=\"4collapse".$i."\" class=\"panel-collapse collapse\">";

        echo "<div class=\"panel-body\">";
        include('templates/others_config_forms/' . $data["settings"]);
        echo "                      </div>
                                        </div>";
        echo "</div>";
        $i++;
        
    }
echo "</div></div>";
?>
