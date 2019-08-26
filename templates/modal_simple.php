<div id="operator_simple" class="modal fade" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Text Message</h4>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">

                    <div class="panel-heading">

                        <div class="panel-options">

                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab_text-1">Text message</a></li>

                              <?php if ($_SESSION["flow_main"]) : ?>
                                <li class=""><a data-toggle="tab" href="#tab_text-2">Triggers</a></li>
                                <?php endif;?>

                                <li class=""><a data-toggle="tab" href="#tab_text-3"> Messenger link</a></li>

                                <li class=""><a data-toggle="tab" href="#tab_text-4">Tags</a></li>

                                <li class=""><a data-toggle="tab" href="#tab_text-5">JSON code</a></li>

                            </ul>

                        </div>

                    </div>


                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="tab_text-1" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">

                                    <input placeholder="Message name" class="form-control input-lg" id="simple_operator_title" type="text"  onchange="ChangeThisItem('#simple_operator_title','', '_msg_name', '');" >

                                    <div class="styling_modal_fieldbackground" style="text-align: center;">

                                    <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">
                                        <span style="float:left; margin-bottom:10px;" class="badge badge-primary">Text message</span>
                                        <textarea placeholder="Message text" name="msg_text" id="msg_text" class="msg_text form-control input-lg" oninput="ChangeThisItem('#msg_text','_msg_content', '_msg_text','20');" maxlength="640"></textarea>
                                        <?php echo PersonalizationHTML(); ?>
                                    </p>
                                    <span id="chars_left"></span>

                                    </div>
                                </div>
                                <div class="col-lg-5 styling_norightpadding">

                                    <?php
                                    echo phone_preview_top();
                                    ?>
                                    <div class="boxlayout_big" id="broadcast_msg_preview">
                                        <div id="simple_msg_preview" class="broadcast_preview_text message-left"></div>
                                    </div>

                                    <?php
                                    echo phone_preview_bottom();
                                    ?>

                                </div>

                            </div>

                            <div id="tab_text-2" class="tab-pane">

                                <div id="triggers_keywords_text"></div>

                            </div>

                            <div id="tab_text-3" class="tab-pane">

                                <div id="triggers_url_text"></div>

                            </div>

                            <div id="tab_text-4" class="tab-pane">

                                <div id="triggers_tags_text"></div>

                            </div>

                            <div id="tab_text-5" class="tab-pane">

                                <div id="triggers_json_text"></div>

                            </div>

                        </div>

                    </div>


                    <script type="text/javascript">

                        var SimpleTextmaxLength = 500;

                        $('body').on('keyup', '.emoji-wysiwyg-editor',function() {

                            var SimpleTextlength = $(this).val().length;

                            var SimpleTextlength2 = SimpleTextmaxLength-SimpleTextlength;

                            //$('#chars_left').text(SimpleTextlength2);

                        });

                    </script>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary save_simple_msg" data-dismiss="modal">Save</button>

            </div>

        </div>

    </div>

</div>