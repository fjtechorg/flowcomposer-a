<div id="active-campaign_card_settings" class="modal fade flowcomposer-modal" role="dialog">

    <div class="modal-dialog modal-lg">



        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <div class="panel-heading">

                    <div class="panel-options">

                        <?php echo getNavigationTabs("action","limited"); ?>


                    </div>

                </div>
            </div>

            <div class="modal-body">

                <div class="panel blank-panel">




                    <div class="panel-body">

                        <div class="tab-content">

                            <div id="action_settings" class="tab-pane active">

                                <div class="col-lg-12 styling_noleftpadding">
                                    <?php echo getCardTitleInput() ?>

                                    <div id="action_type_container">
                                        <label>Select action type</label>
                                        <select class="form-control" data-action="action-type-select" >
                                            <option value="select">Select an action</option>
                                            <option value="create_contact">Create Contact</option>
                                            <option value="create_contact_list">Create Contact in List</option>
                                            <option value="delete_contact">Delete Contact</option>

                                        </select>
                                    </div>


                                    <div id="add_tag_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode !== 13;">
                                            <label>Tag to Add</label>
                                            <input data-action="tag-to-add" name="tag" value="" class="form-control" placeholder="Specify tag to add" size="30">
                                        </form>
                                    </div>

                                    <div id="remove_tag_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode !== 13;">
                                            <label>Tag to Remove</label>
                                            <select class="form-control" name="tag" data-action="tag-to-remove" id="tag_remove_select" name="tag_remove_select" >

                                            </select>
                                        </form>
                                    </div>

                                    <div id="set_custom_field_value_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode != 13;">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label>Custom Field</label>
                                                    <select class="form-control" name="custom_field" data-action="custom-field-to-set"  >

                                                    </select>
                                                </div>
                                                <div class="col-lg-8">
                                                    <label>New Value</label>
                                                    <input data-action="custom-field-value" name="custom_field_value" value="" class="form-control pickers" placeholder="Specify new custom field value">
                                                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>

                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <div id="set_global_field_value_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode != 13;">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label>Global Field</label>
                                                    <select class="form-control" name="global_field" data-action="global-field-to-set"  >

                                                    </select>
                                                </div>
                                                <div class="col-lg-8">
                                                    <label>New Value</label>
                                                    <input data-action="global-field-value" name="global_field_value" value="" class="form-control pickers" placeholder="Specify new global field value">
                                                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>

                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="clear_global_field_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode != 13;">
                                            <label>Global Field to Clear</label>
                                            <select class="form-control" name="global_field" data-action="global-field-to-clear"  >

                                            </select>
                                        </form>
                                    </div>

                                    <div id="clear_custom_field_action_settings_container" class="action-container" style="display: none">
                                        <form onkeypress="return event.keyCode != 13;">
                                            <label>Custom Field to Clear</label>
                                            <select class="form-control" name="custom_field" data-action="custom-field-to-clear"  >

                                            </select>
                                        </form>
                                    </div>
                                    <div id="notify_admin_action_settings_container" class="action-container" style="display: none">
                                        <form onsubmit="return false;">
                                            <input  type="hidden" name="notification_type" value="messenger">

                                            <label for="select-to">People to Notify</label>
                                            <select name="notification_recipient_ids" class="contacts to_notify_action" placeholder="Start typing to search...">
                                            </select>
                                            <div>
                                                <label for="select-to">Notification Content</label>

                                                <p class="lead emoji-picker-container"  style="min-width:350px; margin-bottom: 0px;">
                                                    <textarea class="msg_text form-control input-lg text-card-text" name="notification_message" data-emojiable="true" placeholder="Message text" data-action="text-message"  maxlength="640" ></textarea>
                                                    <?php echo '<div data-target="text-personalization"></div><div data-target="air-picker"></div>' ?>
                                                </p>
                                            </div>

                                        </form>
                                    </div>



                                </div>



                            </div>

                            <div id="action_triggers" class="tab-pane">


                                <?php echo getTriggersInputs("action"); ?>

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