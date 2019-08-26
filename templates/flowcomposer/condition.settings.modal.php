<?php $cardType = "condition"; ?>
<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



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

                                <div class="col-lg-12 styling_noleftpadding">
                                    <?php echo getCardTitleInput(); ?>


                                    <label>Select a segment</label>
                                    <select data-action="input-segment" id="segment_id" name="segment_id" class="form-control">
                                    <?php

                                    $segments = getSegmentsAll($_SESSION["page_id"]) ;
                                    foreach ($segments as $segment)
                                        echo "<option value='".$segment->id."'>$segment->name</option>";

                                    ?>
                                    </select>

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