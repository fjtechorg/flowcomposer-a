<div id="operator_structured" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Carousel Message</h4>
      </div>
      <div class="modal-body">
	  	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_structured-1">Carousel Message</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_structured-2">Triggers</a></li>
                               		<?php endif;?>
                                    <li class=""><a data-toggle="tab" href="#tab_structured-3">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_structured-4">Tags</a></li>
									<li class=""><a data-toggle="tab" href="#tab_structured-5">JSON Code</a></li>
                                                                
								</ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_structured-1" class="tab-pane active">
				


				<div class="col-lg-7 styling_noleftpadding">
                    <label for="operator_title" style="display: none;">Message Name: </label>
                    <input class="form-control" id="structured_operator_title" type="text" placeholder="Message name" onchange="ChangeThisItem('#structured_operator_title','', '_msg_name', '');">
	  							<div id="modal_structured_content"></div>		
				</div>
				
				<div class="col-lg-5 styling_norightpadding">
						
									  <?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="broadcast_msg_preview">
    												<div id="preview_carousel"><div class="broadcast_preview_carousel">
    													<div id="carousel_items" class="preview_slider">
    														 <div id="slides" class="carouselslides"></div>
    														 <span class="controls carousel_previous" data-slide_id=""><</span>
    														 <span class="controls carousel_next" data-slide_id="">></span>
    													</div>
													</div></div>
													
													<div style="clear:both;"></div>

												</div>
									<?php 
									echo phone_preview_bottom();
									?>
									
									</div>	
					

	 			 </div>
								<div id="tab_structured-2" class="tab-pane">
									<div id="triggers_keywords_structured"></div>
								</div>
								<div id="tab_structured-3" class="tab-pane">
									<div id="triggers_url_structured"></div>
								</div>
								<div id="tab_structured-4" class="tab-pane">
									<div id="triggers_tags_structured"></div>
								</div>
								<div id="tab_structured-5" class="tab-pane">
									<div id="triggers_json_structured"></div>
								</div>
							</div>	
						</div>				
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_structured_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>