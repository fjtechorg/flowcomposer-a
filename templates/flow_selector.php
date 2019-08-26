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

<a class="action_open_preview pull-right" data-feature="flowselect" style="color: #408fff;padding: 0px 0px;float: right;margin-top: 5px;">Open Preview</a>
