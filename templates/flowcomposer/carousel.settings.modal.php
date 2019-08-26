<?php $cardType = "carousel"; ?>
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

                                    <?php echo getTemplateElement(); ?>

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