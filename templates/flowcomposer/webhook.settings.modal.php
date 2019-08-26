<?php $cardType = "webhook"; ?>
<div id="<?php echo $cardType ?>_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <?php echo getModalClose($cardType) ?>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs($cardType,"snippet"); ?>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">



                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="<?php echo $cardType ?>_settings" class="tab-pane active">

                                <?php echo getCardTitleInput(); ?>


                                <div class="row">
                                    <div class="form-group col-xs-2">
                                        <label>Request Method</label>

                                        <select style="margin-bottom: 15px" class="form-control" data-action="webhook-request-method" >
                                            <option value="get">GET</option>
                                            <option value="post">POST</option>>
                                            <option value="put">PUT</option>>
                                            <option value="delete">DELETE</option>>
                                        </select>

                                    </div>
                                    <div class="form-group col-xs-10">
                                        <label>Request URL</label>

                                        <input type="text" data-action="webhook-url" class="pickers form-control" placeholder="Enter URL here" size="45">
                                        <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>

                                    </div>
                                </div>




                                <div class="styling_modal_fieldbackground" style="margin-top:0px;text-align: center;">

                                <?php echo getHeadersInput(); ?>
                                </div>

                                <div id="data_container" style="display: none">

                                    <label>Body type</label>



                                    <select style="margin-bottom: 15px" class="form-control" data-action="webhook-body-type" >
                                        <option value="raw">Raw</option>
                                        <option value="form-data">Form-data</option>>
                                    </select>

                                <?php echo getFormDataInput(); ?>
                                <div id="raw_container">
                                    <textarea  data-action="webhook-raw-body" id="webhook-raw-body" class="pickers form-control" placeholder="Request Body" rows="13"></textarea>
                                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                </div>
                                </div>


                                <div class="col-lg-6 styling_noleftpadding">

                                    <div class="" style="text-align: center;">
                                        

                                    </div>


                                </div>
                                <div class="col-lg-6 styling_norightpadding">



                                </div>

                            </div>



                            <div id="<?php echo $cardType ?>_triggers" class="tab-pane">


                                <?php echo getTriggersInputs($cardType); ?>

                            </div>

                            <div id="<?php echo $cardType ?>_clever_snippet" class="tab-pane">


                                <?php echo getCleverSnippetContainer($cardType); ?>

                            </div>

                            <div id="<?php echo $cardType ?>_documentation" class="tab-pane">


                                <p>Webhook cards allow a two way communication between your bot and external services across the internet.</p>
                                <p>It is possible to send any of your bot or subscribers data to your desired service either from the URL, headers or form data by using the personalization pickers in their corresponding input fields.</p>
                                <p>You can also pass data back from your desired external web service to your bot and use this data in any card that supports air variables. Moreover actions can be performed against the current subscriber using actions passed back to your bot.</p>
                                <p>For a full documentation of supported actions and responses, read our  <a target="_blank" href="https://clevermessengerwebhooks.docs.apiary.io/">Webhook's documentation</a></p>.


                            </div>



                        </div>

                    </div>


                </div>

            </div>

            <?php echo getModalFooter(); ?>

        </div>

    </div>

</div>
