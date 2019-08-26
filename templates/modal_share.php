<div id="modal_share" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Share Page as Template</h4>
      </div>
        <div id="page_share_content">
              <div class="modal-body">
               <p>With this option you can share all your page settings & flows/messages as a template</p>

                  <p>
                      <input type="hidden" name="template_id" value="" id="template_id">
                      <input placeholder="Enter the Template Name. What is displayed on the index" id="template_name" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_name" value="" maxlength="160">
                      <input placeholder="Enter the Template Alias. No qoutes, spaces etc. use underscores if needed" id="template_alias" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_alias" value="" maxlength="160">
                      <input placeholder="Enter the Template Desription. Shown next to the icon" id="template_descr" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_descr" value="" maxlength="160">
                      <input placeholder="Enter the Template Icon Url" id="template_icon" class="form-control input-lg m-b" type="text" style="margin-bottom: 10px" name="template_icon" value="" maxlength="160">
                      <button class="btn btn-primary upload_icon">Upload Icon</button>
                      <select id="template_type" class="form-control input-lg m-b"><option value="">Select the Template Type</option><option value="public">Public - Show to Everyone</option><option value="private">Private- Invited Users Only</option></select>
                      <br><span id="details_result"></span>
                  </p>

              </div>
              <div class="modal-footer">
                  <button class="btn btn-primary action_share_page_yes" id="">Share This Page</button>
              </div>
        </div>
    </div>

  </div>
</div>
