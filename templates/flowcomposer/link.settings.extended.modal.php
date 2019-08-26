<div id="links_settings_extended" class="modal fade link-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delay settings</h4>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="col-md-4">
                        <label>Send after </label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" style="margin-bottom: 15px" data-action="link-delay-type">
                            <option value="immediately">Immediately</option>
                            <option value="seconds">Seconds</option>
                            <option value="minutes">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                        </select>

                        <input type="number" style="display: none" data-action="link-delay-value" class="form-control m-b" placeholder="Specify value, eg. 10" size="">
                    </div>

                </div>


                <div class="row" >
                    <div class="col-md-4">
                        <label>Typing Indicator </label>
                    </div>
                    <div class="col-md-8" >
                        <input type="checkbox" class="js-switch" data-action="typing-indicator" />
                        <p class="m-b">Typing indicator will automatically be disabled if the selected delay is great than 60 seconds.</p>

                    </div>
                    <br style="clear: both">
                </div>


            </div>
            <div class="modal-footer">
                <button class="btn btn-danger pull-left" data-action="delete-link" type="button" data-dismiss="modal"><i class="icon-trash2"></i> Delete</button>
                <div class="pull-right"><button data-action="save-link-settings" type="button" data-dismiss="modal" class="btn btn-primary" >Save</button></div>
            </div>
        </div>

    </div>
</div>

