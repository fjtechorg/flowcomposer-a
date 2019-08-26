<?php $cardType = "free-input"; ?>
<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <?php echo getModalClose($cardType) ?>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs($cardType); ?>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">



                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="<?php echo $cardType ?>_settings" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">
                                    <?php echo getCardTitleInput(); ?>


                                    <label>Save reply to custom field</label>
                                    <?php

                                    $customfields = getAllCustomfieldsData($_SESSION['page_id']);
                                    if(is_array($customfields)){
                                        echo '<select data-action="input-customfield"  class="form-control">
                                                <option value="select">Select a custom field</option>';
                                        foreach($customfields as $customfield){
                                            echo '<option value="'.$customfield->id.'">'.$customfield->customfield_name.'</option>';
                                        }
                                        echo '</select>';
                                    }
                                    ?>

                                    <div class="styling_modal_fieldbackground" style="text-align: center;">

                                        <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">
                                            <textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="Message text" data-action="text-message"  maxlength="640" ></textarea>
                                            <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                        </p>

                                        <?php echo getButtonsInput(); ?>

                                    </div>


                                    <?php echo getQuickRepliesInput(); ?>




                                </div>
                                <div class="col-lg-5 styling_norightpadding">

                                    <?php include "phone_preview.modal.php" ?>

                                </div>

                            </div>



                            <div id="<?php echo $cardType ?>_triggers" class="tab-pane">


                                <?php echo getTriggersInputs($cardType); ?>

                            </div>

                            <div id="<?php echo $cardType ?>_ads_json" class="tab-pane">


                                <?php echo getJsonPreviewContainer($cardType); ?>

                            </div>



                        </div>

                    </div>


                </div>

            </div>

            <?php echo getModalFooter(); ?>

        </div>

    </div>

</div>