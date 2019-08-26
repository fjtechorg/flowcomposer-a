<div id="operator_reference" class="modal fade" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Flow Selector</h4>

            </div>

            <div class="modal-body">

                <div class="panel blank-panel">

                    <div class="panel-heading">

                        <div class="panel-options">

                            <ul class="nav nav-tabs">

                                <li class="active"><a data-toggle="tab" href="#tab_ref-1">Reference Selector</a></li>

                                <?php if ($_SESSION["flow_main"]) : ?>
                                    <li class=""><a data-toggle="tab" href="#tab_ref-2">Triggers</a></li>
                                <?php endif;?>

                                <li class=""><a data-toggle="tab" href="#tab_ref-3">Messenger link</a></li>

                                <li class=""><a data-toggle="tab" href="#tab_ref-4">Tags</a></li>

                            </ul>

                        </div>

                    </div>


                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="tab_ref-1" class="tab-pane active">

                                <div class="col-lg-7 styling_noleftpadding">

                                    <input placeholder="Message name" class="form-control input-lg" id="reference_title" type="hidden"  onchange="ChangeThisItem('#reference_title','', '_msg_name', '');" >

                                    <div class="" style="">

                                        <div class="" style="">



                                            <div class="ibox-content" style="padding: 15px 15px 15px 0px;">

                                                <div class="row">

                                                    <div class="form-group" style="margin-left: 15px">
                                                        <div id="select_reference_type_container">
                                                            <label>Select message type</label>
                                                            <select class="form-control" id="select_reference_type" name="widget_flow_type" >
                                                                <option value="select">Select</option>
                                                                <option value="flow">Flow</option>
                                                                <option value="flowcard">Single message</option>
                                                            </select>
                                                        </div>

                                                        <div id="select_reference_flow_container" style="display: none">
                                                            <label>Select flow</label>
                                                            <select class="form-control" id="select_reference_flow" name="widget_flow_id" >
                                                                <?php $flows = smartbot_get_page_flowids($_SESSION["page_id"],true) ;
                                                                foreach ($flows as $flow)
                                                                    echo "<option value='".$flow->id."'>$flow->name</option>";

                                                                ?>
                                                            </select>
                                                        </div>


                                                        <div id="select_reference_flow_card_container" style="display: none">
                                                            <label>Select single message</label>
                                                            <select class="form-control" id="select_reference_flow_card" name="widget_flow_msg_id">

                                                            </select>
                                                        </div>

                                                    </div>

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
                                    <div class="boxlayout_big" id="reference_msg_preview">
                                    </div>

                                    <?php
                                    echo phone_preview_bottom();
                                    ?>

                                </div>

                            </div>

                            <div id="tab_ref-2" class="tab-pane">

                                <div id="triggers_keywords_reference"></div>

                            </div>

                            <div id="tab_ref-3" class="tab-pane">

                                <div id="triggers_url_reference"></div>

                            </div>

                            <div id="tab_ref-4" class="tab-pane">

                                <div id="triggers_tags_reference"></div>

                            </div>

                            <div id="tab_ref-5" class="tab-pane" style="display: none;">

                                <div id="triggers_json_reference"></div>

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

                <button type="button" class="btn btn-primary save_reference" data-dismiss="modal">Save Settings</button>

            </div>

        </div>

    </div>

</div>
