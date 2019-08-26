<div id="text_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

               <?php echo getModalClose("text") ?>
                <div class="panel-heading">

                    <div class="panel-options">

                         <?php echo getNavigationTabs("text"); ?>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">



                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="text_settings" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">
                                    <?php echo getCardTitleInput(); ?>
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



                            <div id="text_triggers" class="tab-pane">


                                <?php echo getTriggersInputs("text"); ?>

                            </div>

                            <div id="text_ads_json" class="tab-pane">


                                <?php echo getJsonPreviewContainer("text"); ?>

                            </div>



                        </div>

                    </div>


                </div>

            </div>

         <?php echo getModalFooter(); ?>

        </div>

    </div>

</div>

