<?php

$header_before  = '
<link rel="stylesheet" href="css/plugins/semantic-ui/semantic.min.css" data-theme="default">';
$header_additionals = '	
	<!-- Begin emoji-picker Stylesheets -->

    <link href="js/plugins/emoji-picker/css/nanoscroller.css" rel="stylesheet">

    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- End emoji-picker Stylesheets -->

	

    <link href="css/plugins/mockit/style.css" rel="stylesheet">

	<link href="css/emoji2.css" rel="stylesheet">

	<link href="css/emoji.css" rel="stylesheet">
	       
    <link href="css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
    
    <link href="css/plugins/touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/jquery.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
 <link href="css/plugins/filepond/filepond-plugin-image-preview.min.css" rel="stylesheet">
 <link href="css/plugins/filepond/filepond.min.css" rel="stylesheet">


    <link rel="stylesheet" href="css/custom.css">

        <link rel="stylesheet" href="css/flowcomposer.css">
              	<link rel="stylesheet" href="css/plugins/selectize/selectize.default.css" data-theme="default">
		
    <style>
    
    .filepond--root {
    min-height: 75px;
    font-size: 16px;
}

.filepond--file-info-sub{
visibility: hidden;
}
.filepond--file-info-main{
    margin-top: 6px;
    height:15px;
   
}


</style>
    <link rel="stylesheet" href="css/plugins/fineuploader/fine-uploader-new.css">
    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">

        <script src="js/plugins/flowchart/jquery.flowchart.js?'.time().'"></script>
       
        <script src="js/plugins/selectize/selectize.js"></script>


    ';
?>

<?php
include('header.php');

include('sidebar.php');

require_once __DIR__."/templates/flowcomposer/components.php";


?>


    <div class="wrapper wrapper-content" style="padding: 40px 0 0px;">



        <div class="row">

            <div class="col-lg-12" style="padding:20px 0px 0px;background-color: #F9FAFC;">



                <?php if (isset($_GET["wizard"]) && $_GET["wizard"] == "new"): ?>
                <div id="newflow_modal" class="modal fade" role="dialog" style="height: 100%;overflow: hidden;">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header" style="border-bottom: none;padding: 0;min-height:auto;">
                                <!--<button type="button" class="close" data-dismiss="modal" style="position: absolute;right: -360px;top: -20px;color: white;font-size: 40px;text-shadow: none;">×</button>-->
                            </div>
                            <div class="modal-body" style="text-align:  center;">
                                <h2 style="font-size:  40px;font-weight: bold;">
                                    Ready to create a new flow!</h2>
                                <h3 style="">How do you want to build your new flow? </h3>
                                <span class="btn btn-primary" data-dismiss="modal" style="padding: 8px 16px;margin-top: 20px;">Build From Scratch</span>
                                <span class="btn btn-primary" data-action="import-flow-invoke" data-dismiss="modal" style="padding: 8px 16px;margin-top: 20px;">Import a flow</span>
                            </div>
                            <div class="modal-footer" style="border-top: none;padding: 0;">
                                <div id="confetti-wrapper"></div>


                            </div>
                        </div>
                    </div>
                </div>
                    <?php endif; ?>



                    <div class="col-lg-3 flowcardscolumn">
                    <div class="">

                        <div class="" style="padding: 20px 20px 5px 20px;">

                            <div class="form-group">
                                <!--<label>Select Flow to edit</label>-->


                            </div>
                        </div>
                    </div>

                    <div class="">

                        <div style="padding: 0px 20px 0px 20px;">
                            <span class="table-col-title">Message Types</span>
                            <a href="#" class="tour-trigger" data-target="message-types"><i class="userpilot-breadcrumb-i icon-menu-square pull-right small"></i></a>

                            <hr class="datatable-hr">
                        </div>

                        <div class="" style="padding-bottom:5px !important;">


                                <div class="draggable_operators" style="padding:10px 30px;">

                                    <div class="draggable_operators_divs row" id="boundbox">

                                        <div class="row">

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                    data-title="Text Card" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="text"><i class="icon-text-format"></i><br/>Text
                                                </div>
                                                <br>

                                            </div>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Image Card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="image">
                                                    <i class="icon-file-image"></i><br/>Image
                                                </div>
                                                <br>

                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-nb-inputs="1" data-nb-outputs="1" data-title="Audio Card" data-card-type="audio">
                                                    <i class="icon-file-audio"></i><br/>Audio
                                                </div>
                                                <br>

                                            </div>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-nb-inputs="1" data-nb-outputs="1" data-title="Video Card" data-card-type="video">
                                                    <i class="icon-file-video"></i><br/>Video
                                                </div>
                                                <br>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-nb-inputs="1" data-nb-outputs="1" data-title="File Card" data-card-type="file">
                                                    <i class="icon-file-empty"></i><br/>File
                                                </div>
                                                <br>

                                            </div>

                                            <span title='Carousel cards allow you to send a structured message that includes an image, text and buttons. A Carousel card with multiple elements will send a horizontally scrollable carousel of items, each composed of an image, text and buttons.'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-nb-inputs="1" data-nb-outputs="1" data-title="Carousel Card" data-card-type="carousel">
                                                    <i class="icon-menu-square"></i><br/>Carousel
                                                </div>
                                                <br>

                                            </div>


                                        </div>
                                        <div class="row">
                                            <span title='List card allows you to send a structured message with a set of items rendered vertically'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-nb-inputs="1" data-nb-outputs="1" data-title="List Card" data-card-type="list">
                                                    <i class="icon-menu"></i><br/>List
                                                </div>
                                                <br>

                                            </div>


                                        </div>


                                        <div>
                                            <div style="padding: 15px 5px 10px 5px;">
                                                <span class="table-col-title">Button Actions</span>
                                                <a href="#" class="tour-trigger" data-target="button-actions"><i class="userpilot-breadcrumb-i icon-menu-square pull-right small"></i></a>

                                                <hr class="datatable-hr">
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Visit URL" data-card-class="url-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="url"><i
                                                            class="icon-link2"></i><br/>Visit URL
                                                </div>
                                                <br>

                                            </div>


                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Call Phone Number" data-card-class="phone-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="phone"><i
                                                            class="icon-phone"></i><br/>Call Phone
                                                </div>
                                                <br>

                                            </div>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Share card" data-card-class="phone-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="share"><i
                                                            class="icon-share2"></i><br/>Share Card
                                                </div>
                                                <br>

                                            </div>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="WhatsApp Card" data-card-class="url-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="whatsapp"><i
                                                            class="icon-whatsapp"></i><br/>WhatsApp
                                                </div>
                                                <br>

                                            </div>


                                        </div>


                                        <div>
                                            <div style="padding: 15px 5px 10px 5px;">
                                                <span class="table-col-title">Ask for</span>
                                                <a href="#" class="tour-trigger" data-target="ask-for"><i class="userpilot-breadcrumb-i icon-menu-square pull-right small"></i></a>

                                                <hr class="datatable-hr">
                                            </div>
                                        </div>

                                        <div class="row">

                                          <span title='Ask for email address'>


                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Ask for email" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="email-input"><i
                                                            class="icon-envelope"></i><br/>Email Address
                                                </div>


                                                <br>

                                            </div>

                                            <span title='Ask for phone number'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Ask for phone number" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="phone-input"><i
                                                            class="icon-phone"></i><br/>Phone Number
                                                </div>


                                                <br>

                                            </div>

                                        </div>
                                        <div class="row">

                                            <span title='Ask for location'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Ask for location" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="location-input"><i
                                                            class="icon-location"></i><br/>Location
                                                </div>


                                                <br>

                                            </div>

                                            <span title='Ask multipe choice question'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Ask multiple choice question" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="multiple-input"><i
                                                            class="icon-register"></i><br/>Multiple choice
                                                </div>


                                                <br>

                                            </div>

                                        </div>
                                        <div class="row">

                                            <span title='Ask for other information'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Ask for other information" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="free-input"><i
                                                            class="icon-database-add"></i><br/>Other information
                                                </div>


                                                <br>

                                            </div>



                                        </div>


                                        <div>
                                            <div style="padding: 15px 5px 10px 5px;">
                                                <span class="table-col-title">Actions</span>
                                                <a href="#" class="tour-trigger card-trigger" data-target="actions"><i class="userpilot-breadcrumb-i icon-menu-square pull-right small"></i></a>

                                                <hr class="datatable-hr">
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Go to flow" data-card-class="flow-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="flow"><i
                                                            class="icon-return2"></i><br/>Go to flow
                                                </div>


                                                <br>

                                            </div>



                                            <div class="flow_operator_card">


                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Perform Action"  data-card-class="action-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="action"><i
                                                            class="icon-select2"></i><br/>Perform action
                                                </div>


                                                <br>

                                            </div>

                                        </div>
                                        <div class="row">

                                            <span title='Webhooks allow you to communicate with remote apps'>

                                            <div class="flow_operator_card">

                                                <div  class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Webhook" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="webhook"><i
                                                            class="icon-network"></i><br/>Webhook
                                                </div>


                                                <br>

                                            </div>

                                                     <span title='Split test cards allow you to contact split tests inside your flow.'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="A/B Split" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="split-test"><i
                                                            class="icon-percent"></i><br/>A/B Split
                                                </div>


                                                <br>

                                            </div>

                                        </div>
                                        <div class="row">

                                            <span title='Conditions allow you to segment your flows'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Add a condition" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="condition"><i
                                                            class="icon-arrows-split"></i><br/>Condition
                                                </div>


                                                <br>

                                            </div>


                                                <span title='Random Path cards allow you to randomize messages inside your flow.'>

                                            <div class="flow_operator_card">

                                                <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                     data-title="Random Path" data-card-class="text-card" data-nb-inputs="1" data-nb-outputs="0" data-card-type="randomizer"><i
                                                            class="icon-shuffle"></i><br/>Random Path
                                                </div>


                                                <br>

                                            </div>


                                        </div>



                                        </div>

                                    <div>
                                        <div style="padding: 15px 5px 10px 5px;">
                                            <span class="table-col-title">Autoresponders</span>
                                            <hr class="datatable-hr">
                                        </div>
                                    </div>

                                    <div class="row">


                                        <div class="flow_operator_card">

                                            <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                 data-title="Constant Contact" data-card-class="action-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="constant-contact"><i
                                                        class="ico-constant-contact"></i><br/>Constant Contact
                                            </div>


                                            <br>

                                        </div>



                                        <div class="flow_operator_card">



                                            <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                 data-title="Contact Reach" data-card-class="action-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="contact-reach"><i
                                                        class="ico-contact-reach"></i><br/>Contact Reach
                                            </div>

                                            <br>

                                        </div>

                                    </div>

                                    <div class="row">


                                        <div class="flow_operator_card">

                                            <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                 data-title="Wowing" data-card-class="action-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="wowing"><i
                                                        class="ico-wowing"></i><br/>Wowing
                                            </div>


                                            <br>

                                        </div>


                                        <div class="flow_operator_card">

                                            <div class="draggable_operator ui-draggable ui-draggable-handle"
                                                 data-title="Active Campaign" data-card-class="action-card" data-nb-inputs="1" data-nb-outputs="1" data-card-type="active-campaign"><i
                                                        class="ico-wowing"></i><br/>Active Campaign
                                            </div>


                                            <br>

                                        </div>




                                    </div>

                                </div>


                        </div>

                    </div>


                </div>

                <div class="col-lg-9 flowcanvascolumn" style="padding:0px;">

                    <div class="">


                        <div class=""
                             style="display: inline;position: absolute;z-index:  10;text-align:  center;right: 10px;top: 10px;">
                            <div class="zoombuttons"
                                 style="border: 1px solid #edf0f2;border-radius: 50px;background:white;margin-top:10px;box-shadow:0px 10px 10px -10px #eaeaea;">
                                <button data-action="zoom-in" class="zoom-button">
                                    <i class="icon-plus zoom-icon"></i>
                                </button>
                                <button data-action="zoom-reset" class="zoom-button">
                                    <i class="icon-contract zoom-icon"></i>
                                </button>

                                <button data-action="zoom-out" class="zoom-button">
                                    <i class="icon-minus zoom-icon"></i>
                                </button>
                                <div id="flowchart_zoom_value zoom-value"></div>

                            </div>
                        </div>

                        <div class="">

                            <div>

                                <div style="overflow: hidden; position: relative; border:none" id="chart_container"
                                     class="flowchart-example unselectable">

                                    <div class="flowchart-example-container flowchart-container unselectable"
                                         id="visualbuilder"></div>

                                </div>


                            </div>


                            </form>




                            <?php

                            include_once('templates/phone_preview.php');
                            include_once('templates/flowcomposer/text.settings.modal.php');

                            include_once('templates/flowcomposer/action.settings.modal.php');
                            include_once('templates/flowcomposer/link.settings.modal.php');
                            include_once('templates/flowcomposer/link.settings.extended.modal.php');

                            // Integration templates
                            include_once('templates/flowcomposer/autoresponders/constant_contact.settings.modal.php');
                            include_once('templates/flowcomposer/autoresponders/contact_reach.settings.modal.php');
                            include_once('templates/flowcomposer/autoresponders/wowing.settings.modal.php');
                            include_once('templates/flowcomposer/autoresponders/active_campaign.settings.modal.php');

                            ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script src="js/plugins/uiblock/jquery.blockUI.js"></script>





<script>
    $(document).ready(function(){




    });

</script>

<?php



$footer_additionals = '
    <!-- Color picker -->
    
    <script src="js/jquery.panzoom.js"></script>
    

	<script src="js/jquery.sticky-kit.min.js"></script>
    <script src="js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    
    <!-- End emoji-picker JavaScript -->

<script src="js/plugins/fineuploader/fineuploader.js"></script>


    <script src="js/plugins/emoji-picker/js/nanoscroller.min.js"></script>
    <script src="js/plugins/emoji-picker/js/tether.min.js"></script>
    <script src="js/plugins/emoji-picker/js/config.js"></script>
    <script src="js/plugins/emoji-picker/js/util.js"></script>
    <script src="js/jquery.emojiarea.js"></script>
    <script src="js/emoji-picker.js"></script>
    <script src="js/plugins/clipboard/clipboard.min.js"></script>
    <script src="js/library.json.js"></script>
    <script src="js/flowcomposerFunctions.js?'.time().'"></script>
    <script src="js/classes/flowcomposer.js?'.time().'"></script>
    <script src="js/classes/autoresponders/integrationCard.js?'.time().'"></script>
    <script src="js/classes/autoresponders/autoResponder.js?'.time().'"></script>
    <script src="js/classes/autoresponders/constantContact.js?'.time().'"></script>
    <script src="js/classes/autoresponders/activeCampaign.js?'.time().'"></script>
    <script src="js/integrationFunctions.js?'.time().'"></script>
    <script src="js/fileUploadFunctions.js?'.time().'"></script>
    <script src="js/globalFieldFunctions.js?'.time().'"></script>
    
    <script src="js/plugins/SimpleAjaxUploader/SimpleAjaxUploader.min.js"></script>
    <script type="text/javascript" src="js/plugins/tooltipster/jquery.tooltipster.min.js"></script>
    <script src="js/plugins/filepond/filepond.js"></script> 
    <script src="js/plugins/filepond/filepond-plugin-file-validate-size.min.js"></script>
    <script src="js/plugins/filepond/filepond-plugin-file-validate-type.min.js"></script>
    <script src="js/emojiFunctions.js"></script>
    <script src="js/plugins/semantic-ui/dropdown.min.js"></script>
    <script src="js/plugins/semantic-ui/transition.min.js"></script>    
  <!-- Switchery -->
   <script src="js/plugins/switchery/switchery.js"></script>
    
    <script src="js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- TouchSpin -->
    <script src="js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="js/plugins/serialijse/serialijse.bundle.min.js"></script>
  

    
    <script>


        $(document).ready(function () {
            
       
        
            
    var elem = document.querySelector(\'.js-switch\');
    var switchery = new Switchery(elem, { color: \'#1AB394\' });


            
            
            // init touchspin
            jQuery(\'#typing_view\').TouchSpin();
            $("rect").attr("mask","");
            // Add slimscroll to element
            $(\'.scroll_content\').slimscroll({
                height: \'400px\'
            })
            
        });
jQuery.unblockUI();
    </script>
	';


include('footer.php');
?>
