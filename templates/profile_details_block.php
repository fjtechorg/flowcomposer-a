<?php
/**
 * Created by PhpStorm.
 * User: ABHISHEK
 * Date: 06-02-2018
 * Time: 15:10
 */

?>
<div class="profile-container styling_profile-container">
    <div id="edit_profile"></div>
    <div id="edit_pause_status" style="margin-top:20px;margin-bottom:  20px;"></div>

    <span id="edit_email_title" style="padding-bottom: 10px;"><strong style="color: #8f939b;">Email</strong></span>
    <hr class="datatable-hr" style="margin: 5px 0px 5px 0px;">
    <div id="edit_email"></div>
    <div id="edit_email_button" class="input-group" style="padding-top: 10px;padding-bottom: 20px;">
        <input id="email_value" value="" class="form-control" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" placeholder="Enter subscriber email here...">
        <span class="input-group-btn" id="add_email"><button type="button" class="btn btn-primary">Save Email</button></span>
    </div>

    <span id="edit_tags_title"><strong style="color: #8f939b;">Tags</strong></span>
    <hr class="datatable-hr" style="margin: 5px 0px 5px 0px;">
    <div id="edit_tags" style="padding-top: 10px;padding-bottom: 10px;"></div>
    <div class="input-group" id="edit_tags_button" style="padding-bottom: 20px;">
        <input id="tag_value" value="" class="form-control" style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;" placeholder="Enter tag(s) here">
        <span class="input-group-btn" id="add_tag"><button type="button" class="btn btn-primary">Add Tag</button></span>
    </div>


    <span id="edit_customfields_title"><strong style="color: #8f939b;">Custom Fields</strong></span>
    <hr class="datatable-hr" style="margin: 5px 0px 5px 0px;">
    <div id="edit_customfields" style="padding-top: 10px;padding-bottom: 10px;"></div>
    <div class="input-group" id="edit_customfields_button" style="padding-bottom:  10px;">
        <input id="customfield_key" value="" class="form-control" style="border-bottom-color:#F8F8F8;" placeholder="Enter custom field ID here - i.e phonenumber">
        <input id="customfield_value" value="" class="form-control" placeholder="Enter custom field value here - i.e 555-5555">
        <span class="" id="add_customfield"><button type="button" class="btn btn-primary" style="border-radius: 0px 0px 5px 5px;width: 100%;margin: 0px 0px 10px 0px;">Add Custom Field</button></span>
    </div>


    <span id="" style="display:none;">
        <strong style="color: #8f939b;">Sequences</strong>
        <hr class="datatable-hr" style="margin: 5px 0px 15px 0px;">
    </span>
    <div class="col-lg-12" style="padding: 0px 0px 20px 0px;display:none;">
        <span data-profile_id="" class="btn btn-primary" style="width: 100%;"> Subscribe to Sequence</span>
    </div>


    <span id=""><strong style="color: #8f939b;">Entry Point</strong><hr class="datatable-hr" style="margin: 5px 0px 5px 0px;"></span>
    <div id="" style="padding-top: 10px;padding-bottom: 10px;">
        <span class="chat_tags styling_span_tags" >
            <strong id="origin" class="profile_origin_type"></strong>
            <span class="profile_origin_name" id="origin_id"></span>
            <span class="" data-tag_id="34"></span>
        </span>
    </div>


</div>