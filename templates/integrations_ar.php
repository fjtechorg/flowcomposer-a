<?php
ob_start();

$AR_arr = array(
    'ActiveCampaign' => array(
        'image' => "ar-activeCampaign.png",
        'settings' => 'activecampaign.php'
        ),
    'Aweber' => array(
        'image' => "ar-aweber.png",
        'settings' => 'aweber.php'
        ),
    'Campaign Monitor' => array(
        'image' => "ar-campaignmonitor.png",
        'settings' => 'campaignmonitor.php'
        ),
    'Market Hero' => array(
        'image' => "ar-markethero.png",
        'settings' => 'markethero.php'
        ),
    'ConstantContact' => array(
        'image' => "ar-constant-contact.png",
        'settings' => 'constantcontact.php'
        ),
    'ConvertKit' => array(
        'image' => "ar-convertkit.png",
        'settings' => 'convertkit.php'
        ),
    'GetResponse' => array(
        'image' => "ar-getresponse.png",
        'settings' => 'getresponse.php'
        ),
    'iContact' => array(
        'image' => "ar-icontact.png",
        'settings' => 'icontact.php'
        ),
    'MadMimi' => array(
        'image' => "ar-madmimi.png",
        'settings' => 'madmimi.php'
        ),
    'MailChimp' => array(
        'image' => "ar-mailchimp.png",
        'settings' => 'mailchimp.php'
        ),
    'Mailerlite' => array(
        'image' => "ar-mailerlite.png",
        'settings' => 'mailerlite.php'
        ),
    'Sendlane' => array(
        'image' => "ar-sendlane.png",
        'settings' => 'sendlane.php'
        ),
    'Sendy' => array(
        'image' => "ar-sendy.png",
        'settings' => 'sendy.php'
        )
    );
      
$i = 1;   
echo "<div class=\"panel-body\">
                                <div class=\"panel-group styling_integrations_panel\" id=\"accordion1\">";
    foreach($AR_arr as $name => $data){
        echo "<div class=\"panel panel-default\">
                                        <div class=\"panel-heading\" style=\"padding:5px 10px;\">
                                            <h5 class=\"panel-title\">";
        echo "<a data-toggle=\"collapse\" data-parent=\"#accordion1\" href=\"#1collapse".$i."\" style=\"display:block;\">"."<img src=\"images/ar-icons/".$data["image"]."\" width=\"60\" style=\"margin-right:20px;\"/>".$name."</a>";
        echo "                          </h5>
                                        </div>";
        echo "<div id=\"1collapse".$i."\" class=\"panel-collapse collapse\">";
        echo "<div class=\"panel-body\">";
        include('templates/ar_config_forms/' . $data["settings"]);
        echo "                      </div>
                                        </div>";
        echo "</div>";
        $i++;
        
    }
echo "</div></div>";
?>
