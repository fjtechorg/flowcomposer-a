<?php
$ECOM_arr = array(
    'Amazon' => array(
        'image' => "amazon.png",
        'settings' => 'amazon.php'
        ),
    'Shopify' => array(
        'image' => "shopify.png",
        'settings' => 'shopify.php'
        ),
	 'Instant Ecom Store' => array(
     'image' => "instantecom.png",
     'settings' => 'instantecom.php'
        )	
    );
      
$i = 1;   
echo "<div class=\"panel-body\">
                                <div class=\"panel-group styling_integrations_panel\" id=\"accordion3\">";
    foreach($ECOM_arr as $name => $data){
        echo "<div class=\"panel panel-default\">
                                        <div class=\"panel-heading\" style=\"padding:5px 10px;\">
                                            <h5 class=\"panel-title\">";
        echo "<a data-toggle=\"collapse\" data-parent=\"#accordion3\" href=\"#3collapse".$i."\" data-target=\"#3collapse".$i."\" style=\"display:block;\">"."<img src=\"images/ecom-icons/".$data["image"]."\" width=\"60\" style=\"margin-right:20px;\"/>".$name."</a>";
        echo "                          </h5>
                                        </div>";
        echo "<div id=\"3collapse".$i."\" class=\"panel-collapse collapse\">";
        echo "<div class=\"panel-body\">";
        include('templates/ecom_config_forms/' . $data["settings"]);
        echo "                      </div>
                                        </div>";
        echo "</div>";
        $i++;
        
    }
echo "</div></div>";