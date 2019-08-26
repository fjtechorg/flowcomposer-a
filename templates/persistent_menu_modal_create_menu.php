<div id="persistent_menu_modal_create_menu" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Select Language</h4>
            </div>
            <div class="modal-body">
                <div id="menu_item_msg_result">
                    <?php
                    $resultLanguages =  getPersistentMenuAvailableLocales($_SESSION['page_id']);
                    if(!empty($resultLanguages[0])) {
                        echo '<select id="selected_create_locale"  class="form-control input-lg m-b">';
                        echo $resultLanguages[0];
                        echo '</select>';
                    }
                    ?>
                </div>
                <?php
                echo $resultLanguages[1];
                ?>
                <!--<div style="margin-top: 50px;" class="row">
                    <b class="col-md-1">Note:</b>
                    <p class="col-md-11">Please create "All Languages" menu first before creating menu for any other language</p>
                </div>-->
            </div>
            <div class="modal-footer">
                <?php
                if($resultLanguages[2]) {
                echo '<button type="button" class="btn btn btn-primary save_create_new_menu" data-dismiss="modal">Create</button>';
                }
                ?>
            </div>
        </div>

    </div>
</div>