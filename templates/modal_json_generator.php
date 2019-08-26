<div id="display_modal_json_generator" class="modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Reference Message</h4>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">


                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="tab_ref-1" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">


                                    <div class="" style="">

                                        <div class="ibox" style="">

                                            <div class="ibox-title">

                                                Generated JSON code

                                                <a class="action_json_code_copy" id="7143" data-msgid="7143"><i class="fa icon-copy pull-right" aria-hidden="true"></i></a>

                                            </div>

                                            <div class="ibox-content" style="padding: 15px 15px 15px 0px;">

                                                <div class="row">
                                                    <input type="hidden" class="display_json_codes_raw">

                                                    <pre><code class="display_json_codes">
                                                        </code>


                                                    </pre>

                                                </div>

                                            </div>

                                        </div>



                                        <span id="chars_left"></span>

                                    </div>
                                </div>
                                <div class="col-lg-5 styling_norightpadding">

                                    <?php
                                    echo phone_preview_top();
                                    ?>
                                    <div class="boxlayout_big" id="broadcast_msg_preview_json">
                                    </div>

                                    <?php
                                    echo phone_preview_bottom();
                                    ?>

                                </div>

                            </div>


                        </div>

                    </div>


                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>
