<?php $cardType = "wowing"; ?>

<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs($cardType,"limited",true); ?>


                    </div>

                </div>
            </div>

            <div class="modal-body">

                <div class="panel blank-panel">




                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="<?php echo $cardType ?>_settings" class="tab-pane active">

                                <div class="col-lg-12 styling_noleftpadding">
                                    <?php echo getCardTitleInput() ?>

                                    <?php

                                    // This will load up the account selection
                                    echo getAccountSelectorDiv($cardType);
                                    ?>


                                    <div class="integration-settings" style="display: none">
                                        <?php
                                        // this will load up the actions
                                        echo getServiceActions($cardType);
                                        ?>

                                        <div id="" class="action-container create_list_contact_action_settings_container" style="display: none">

                                            <?php

                                            echo getServiceLists("Automation");
                                            ?>



                                        </div>




                                        <div id="" class="action-container delete_list_contact_action_settings_container" style="display: none">


                                        </div>



                                    </div>
                                </div>



                            </div>

                            <div id="<?php echo $cardType ?>_triggers" class="tab-pane">


                                <?php echo getTriggersInputs($cardType); ?>

                            </div>


                            <div id="<?php echo $cardType ?>_air_variables" class="tab-pane">


                                <p>The following card returns the following air variables that can be used throughout your flow.</p>
                                <p><b>Id</b> : Contact Reach internal ID for the added subscriber</p>
                                <p><b>Redeem Link</b> : Coupon redeem link</p>

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