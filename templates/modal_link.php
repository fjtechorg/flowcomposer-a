<div id="operator_link" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Link settings</h4>
      </div>
      <div class="modal-body">
          <div id="modal_link_typing" >
          <label style="float:left; width: 50%;">Typing indicator (in seconds)</label>
              <!--<select id="typing_view" name="typing_view" class="form-control input-lg" style="float:left;width:65%">
                  <option value="0">How Long Should the Typing indicator show?</option>
                  <?php	  for($i=1;$i<20;$i++){	  echo '<option value="'.$i.'">'.$i.' Second';	  if($i>1){echo 's';}
                      echo '</option>';	  }	  ?>
              </select>-->
              <div style="width: 50%; float: left;">
                <input id="typing_view" name="typing_view"  type="text" value="" >
              </div>
              <br style="clear: both">
          </div>

          <div id="modal_link_color" style="clear: both;" class="m-t">

            <label for="link_color2" style="float: left; width: 50%">Link color</label> <input type="text" id="link_color2" class="form-control" style="float: left; width:50%;" />
              <br style="clear: both">
          </div>

      </div>
      <div class="modal-footer">
          <div id="modal_link_delete" class="pull-left">
              <span class="btn btn-danger delete_selected_link" id="">Delete link</span>
          </div>
          <div class="pull-right"><button type="button" class="btn btn-primary" data-dismiss="modal">Save</button></div>
      </div>
    </div>

  </div>
</div>
