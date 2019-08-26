<div id="flow_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <?php echo getModalClose("flow") ?>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs("flow","limited"); ?>


                    </div>

                </div>
            </div>

            <div class="modal-body">

                <div class="panel blank-panel">




                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="flow_settings" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">
                                    <?php echo getCardTitleInput() ?>

                                    <?php
                                    include __DIR__ . "/../flowcomposer/flow_selector.php";
                                    ?>

                                </div>

                                <div class="col-lg-5 styling_norightpadding">

                                    <?php include "phone_preview.modal.php" ?>

                                </div>


                            </div>

                            <div id="flow_triggers" class="tab-pane">


                                <?php echo getTriggersInputs("flow"); ?>

                            </div>


                        </div>

                    </div>


                </div>

            </div>

            <?php

            echo getModalFooter();

            ?>

        </div>

    </div>

</div>