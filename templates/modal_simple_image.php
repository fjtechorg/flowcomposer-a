<div id="operator_simple_image" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Image Message</h4>
      </div>
      <div class="modal-body">
	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_simple_image-1">Select image</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_simple_image-2">Triggers</a></li>
                                	<?php endif; ?>
                                    <li class=""><a data-toggle="tab" href="#tab_simple_image-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_simple_image-4">Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_simple_image-5">JSON code</a></li>
                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
							
                                <div id="tab_simple_image-1" class="tab-pane active">
								<div class="col-lg-7 styling_noleftpadding">
	  	   <table class="table-noborder">	
		   		  <tbody> <tr>
	   						  <td><input placeholder="Message name" class="form-control input-lg" id="simple_image_operator_title" type="text"  onchange="ChangeThisItem('#simple_image_operator_title','', '_msg_name', '');">
							  </td></tr> 
										
						  <tr>
			       <td style="padding-top: 10px;"><input placeholder="Image url" class="form-control input-lg m-b" type="text" value="" name="simple_image_url" id="simple_image_url" class="simple_image_url" onchange="ChangeThisItem('.simple_image_url','_msg_content', '_simple_image_url','');" placeholder="Enter the url of your image or use the button below to upload one">
									<span href="#myModalImg" data-itemid=""  data-msgid="" data-msgtype="simple_image" class="OpenImgModal upload_btn styling_modal_fieldbackground" style="text-align: center;margin: auto;margin-top: 10px;" >
									<i class="fa icon-picture2 fa-2x" style="padding-top: 10px;"></i>
                                    <input type="button" class="btn btn-primary  form-control" value="Select Image"></span>
										<br>
										
					</td>
				</tr>
				  </tbody>
			</table>
			</div>

<div class="col-lg-5 styling_norightpadding">
									
									<?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="broadcast_msg_preview">
    												<div class="broadcast_preview_img">
    												<img id="phone_preview_image" class="broadcast_preview_image img-responsive" src="">
    												</div>
												</div>

									<?php 
									echo phone_preview_bottom();
									?>
									
									</div>										
    								</div>
								<div id="tab_simple_image-2" class="tab-pane">
									<div id="triggers_keywords_simple_image"></div>
								</div>
								<div id="tab_simple_image-3" class="tab-pane">
									<div id="triggers_url_simple_image"></div>
								</div>
								<div id="tab_simple_image-4" class="tab-pane">
									<div id="triggers_tags_simple_image"></div>
								</div>
								<div id="tab_simple_image-5" class="tab-pane">
									<div id="triggers_json_simple_image"></div>
								</div>
							</div>	
						</div>		
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_img_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>