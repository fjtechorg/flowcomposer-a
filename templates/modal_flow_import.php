<div id="modal_flow_import" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import Flow Wizard</h4>
            </div>
            <div class="modal-body">
                        <label>Select a flow to import</label>
                        <select data-target="flow-import-selector" class="demo-default" placeholder="Select a flow...">
                            <option value="">Select a flow...</option>
                            <?php

                            $sharableFlows = getSharableFlows(1,$_SESSION["user"]["id"]);
                            foreach ($sharableFlows as $sharableFlow){
                                if ($sharableFlow->share_status == 1)
                                    echo "<option value='$sharableFlow->id'>$sharableFlow->name</option>";
                                else
                                    echo "<option value='$sharableFlow->id'>$sharableFlow->page_title - $sharableFlow->name</option>";
                            }


                            ?>
                        </select>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" data-action="import-flow" id="">Import</button>
            </div>
        </div>

    </div>
</div>