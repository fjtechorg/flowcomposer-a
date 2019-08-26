<div id="modal_create_integration" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Integration</h4>
            </div>
            <div class="modal-body">
                <label>Select integration</label>
                <select data-target="integration-selector" class="demo-default">
                    <?php
                    $services = getIntegrationsListWithGroups();
                    $sortedServices = [];
                    foreach($services as $service){
                        $sortedServices[$service['type_name']][] = $service;
                    }

                    foreach($sortedServices as $key => $type){
                        echo '<optgroup label="'.$key.'">';
                        foreach($type as $service){
                            echo '<option value="'.$service['id'].'">'.$service['name'].'</option>';
                        }
                        echo '</optgroup>';
                    }
                    /*
                     * generating json structure
                     */
                    /*
                    $x = new stdClass();
                    $x->type = 'direct';

                    $a = new stdClass();
                    $a->name='activecampaign-url';
                    $a->placeholder='enter activecampaign url';
                    $b = new stdClass();
                    $b->name='activecampaign-key';
                    $b->placeholder='enter activecampaign key';

                    $x->fields = [$a,$b];

                    echo json_encode($x);
                    */
                    ?>
                </select>
                <div class="panel-body">
                    <form id="integration-form">
                    </form>
                    <input class="form-control" type="text" id="integration-name" placeholder="Enter name for integration">
                    <div id="integration-error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-adismiss="modal" data-int-id="" id="save-integration" style="display:none;">Save</button>
            </div>
        </div>
    </div>
</div>