<div id="select_reference_type_container">
    <label>Select message type</label>
    <select class="form-control" id="select_reference_type" data-target="#flow_card_settings #flow-preview-container" name="widget_flow_type" >
        <option value="flow">Flow</option>
        <option value="flowcard">Single message</option>
    </select>
</div>

<div id="select_reference_flow_container" style="display: none">
    <label>Select flow</label>
    <select class="form-control" id="select_reference_flow"  data-target="#flow_card_settings #flow-preview-container" name="widget_flow_id" >
        <?php $flows = smartbot_get_page_flowids($_SESSION["page_id"],true) ;
        foreach ($flows as $flow)
            echo "<option value='".$flow->id."'>$flow->name</option>";

        ?>
    </select>
</div>


<div id="select_reference_flow_card_container" style="display: none">
    <label>Select single message</label>
    <select class="form-control" id="select_reference_flow_card" data-target="#flow_card_settings #flow-preview-container"  name="widget_flow_msg_id">

    </select>
</div>
