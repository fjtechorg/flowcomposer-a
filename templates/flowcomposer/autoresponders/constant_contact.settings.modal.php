<?php $cardType = "constant-contact"; ?>

<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

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
                                    <?php echo getCardTitleInput() ?>

                                    <div id="<?php echo $cardType ?>_account_container">
                                        <label>Select Constant Contact account</label>
                                        <select class="demo-default integration_accounts" data-action="integration-account-select" >
                                            <option value="select">Select an account</option>
                                            <?php

                                            $accounts = getIntegrationsByServiceProviderName($_SESSION["page_id"],"constant contact");
                                                foreach ($accounts as $account){
                                                    echo "<option value='$account->id'>$account->name</option>";
                                                }
                                            ?>

                                        </select>
                                    </div>

                                    <div id="<?php echo $cardType ?>_type_container">
                                        <label>Select an action</label>
                                        <select class="demo-default integration_accounts" style="" data-action="action-type-select" >
                                            <option value="select">Select an Action</option>
                                            <option value="create_contact">Create Contact</option>
                                            <option value="update_contact">Update Contact</option>
                                            <option value="delete_contact">Delete Contact</option>
                                            <option value="create_list_contact">Create Contact in List</option>
                                            <option value="delete_list_contact">Delete Contact from List</option>

                                        </select>
                                    </div>


                                    <div id="create_contact_action_settings_container" class="action-container" style="display: none">

                                                    <label>Subscriber's email</label>
                                                    <input data-action="constant-contact-email-value" name="email_value" value="" class="form-control pickers" placeholder="Specify the email value">
                                                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                                    <?php echo getFormDataInput("block","Custom Fields"); ?>

                                    </div>



                                    <div id="create_list_contact_action_settings_container" class="action-container" style="display: none">

                                        <label>Subscriber's email</label>
                                        <input data-action="constant-contact-email-value" name="email_value" value="" class="form-control pickers" placeholder="Specify the email value">
                                        <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                        <?php echo getFormDataInput("block","Custom Fields"); ?>

                                    </div>

                                    <div id="delete_contact_action_settings_container" class="action-container" style="display: none">

                                        <label>Subscriber's email</label>
                                        <input data-action="constant-contact-email-value" name="email_value" value="" class="form-control pickers" placeholder="Specify the email value">
                                        <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                        <?php echo getFormDataInput("block","Custom Fields"); ?>

                                    </div>

                                    <div id="delete_list_contact_action_settings_container" class="action-container" style="display: none">

                                        <label>Subscriber's email</label>
                                        <input data-action="constant-contact-email-value" name="email_value" value="" class="form-control pickers" placeholder="Specify the email value">

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

            <?php

            echo getModalFooter();

            ?>

        </div>

    </div>

</div>