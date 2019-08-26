<div id="file_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <?php echo getModalClose("file") ?>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs("file"); ?>


                    </div>

                </div>
            </div>

            <div class="modal-body">

                <div class="panel blank-panel">




                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="file_settings" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">
                                    <?php echo getCardTitleInput() ?>
                                    <div class="styling_modal_fieldbackground" style="text-align: center;">

                                        <label class="direct-upload-label"><span class="orinput">Input file <a href="#" data-action="show-direct-link-container">direct URL</a> or <a href="#" data-action="show-upload-container">upload</a> from your device</label>
                                        <label class="upload-label" style="display: none"><span <a href="#" data-action="show-upload-container">Upload</a> from your device</label>

                                        <div class="container2 upload-container">


                                        </div>

                                        <div class="direct-link-container" style="display: none">

                                            <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">

                                                <textarea class="msg_text form-control input-lg text-card-text" data-emojiable="true"  data-action="media-url" placeholder="Input file direct URL, maximum file size is 20 MB." ></textarea>
                                                <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                            </p>
                                        </div>

                                    </div>


                                    <?php echo getQuickRepliesInput(); ?>
                                </div>

                                <div class="col-lg-5 styling_norightpadding">

                                    <?php include "phone_preview.modal.php" ?>

                                </div>

                            </div>

                            <div id="file_triggers" class="tab-pane">


                                <?php echo getTriggersInputs("file"); ?>

                            </div>


                            <div id="file_ads_json" class="tab-pane">


                                <?php echo getJsonPreviewContainer("file"); ?>

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