<div id="persistent_menu_weblink_modal" class="modal fade in" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Configure - URL</h4>
      </div>
      <div class="modal-body">
			<input type="text" name="button_link" id="button_link" class="form-control input-lg m-b" placeholder="Enter URL here" size="45">
              <label>Webview Size</label>
              <select style="margin-bottom: 15px" class="form-control" id="webview_height_ratio" name="webview_height_ratio">
                  <option value="full">Full</option>
                  <option value="tall">Tall</option>
                  <option value="compact">Compact</option>
              </select>

          <span>Output of webview sizes (compact, tall, full)</span>
          <img src="https://cleverstorage.b-cdn.net/assets/webview.png" width="100%">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn btn-primary save_weblink">Save</button>
      </div>
    </div>

  </div>
</div>