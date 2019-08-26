<?php $cardType = "split-test"; ?>
<div id="<?php echo $cardType ?>_card_settings"  class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg" style="max-width: 800px">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <?php echo getModalClose($cardType) ?>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs($cardType,"limited"); ?>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">



                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="<?php echo $cardType ?>_settings" class="tab-pane active">

                                <?php echo getCardTitleInput(); ?>



                                <div id="variants_outer_container" style="">



                                    <?php echo getVariantsInput(); ?>

                                </div>


                            </div>



                            <div id="<?php echo $cardType ?>_triggers" class="tab-pane">


                                <?php echo getTriggersInputs($cardType); ?>

                            </div>



                        </div>

                    </div>


                </div>

            </div>

            <?php echo getModalFooter(); ?>

        </div>

    </div>

</div>
