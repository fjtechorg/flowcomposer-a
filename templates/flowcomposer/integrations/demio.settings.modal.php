<?php $cardType = "demio"; ?>
<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">×</button>

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

                                <div class="col-lg-7 styling_noleftpadding">
                                    <?php echo getCardTitleInput(); ?>


                                    <label>API Key </label>
                                    <input type="text" data-action="api-key-input" class="pickers form-control" placeholder="API Key" size="45">
                                    <label>API secret </label>
                                    <input type="text" data-action="api-secret-input" class="pickers form-control" placeholder="API secret" size="45">



                                    <div class="styling_modal_fieldbackground">

                                        <label>Webinar</label>
                                        <span style="color: #0a82fb"></span>
                                        <select data-action="demio-events-input"  class="form-control">
                                        </select>

                                        <label>Email </label>
                                        <input type="text" data-action="email-key-input" class="pickers form-control" placeholder="Secret Key" size="20">
                                        <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>


                                    </div>






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