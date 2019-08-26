<div id="operator_products" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search products</h4>
      </div>
      <div class="modal-body">										
	  <div class="col-lg-12">
                    <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_products-1"></a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_products-2">Message</a></li>
                                    <?php if ($_SESSION["flow_main"]) : ?>

                                    <li class=""><a data-toggle="tab" href="#tab_products-3">Triggers</a></li>
                               		<?php endif;?>
                                    <li class=""><a data-toggle="tab" href="#tab_products-4">Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_products-5">Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_products-6">JSON Code</a></li>
                                     
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_products-1" class="tab-pane active">
									<div class="row">
									


                                        <div class="col-lg-6">

                                             <select  id="products_type" name="products_type" class="form-control">
                                                  <option value="">Select Type</option>
                                                  <option value="amazon">Amazon</option>
                                                  <option value="shopify">Shopify</option>
                                            </select>

                                        </div>
                                        <div class="col-lg-6">
                                            <form id="filter_products" style="background-color:transparent;padding:0;">
                                                <input type="text" placeholder="Search Products" name="search" id="keywords"  class="form-control">
                                            </form>
                                        </div>
                                        <div id="product_detail"></div>
                                        <div id="product_message_detail"></div>
                                        <div id="product_category"></div>
                                        <div id="product_product"></div>


									</div>

									
									</div>

                                	<div id="tab_products-2" class="tab-pane">
							
									
									<div class="col-lg-7 styling_noleftpadding">
                                    <input placeholder="Message name" type="text" name="product_msg_name" id="product_msg_name" class="form-control input-lg m-b" onchange="ChangeThisItem('#product_msg_name','', '_msg_name', '');">
								<div id="product_message_result">	
								
								</div>	
				</div>
				
				<div class="col-lg-5 styling_norightpadding">
									
									
									<?php 
    									echo phone_preview_top();
    									?>
												<div class="boxlayout_big" id="broadcast_msg_preview">
        												<div id="preview_products">
														<div class="broadcast_preview_products">
        													<div id="product_items" class="preview_slider">
        														 <div id="product_slides" class="productslides"></div>
        														 <span class="controls product_previous" data-slide_id=""><</span>
        														 <span class="controls product_next" data-slide_id="">></span>
        													</div>
    													</div>
														</div>
													<div style="clear:both;"></div>

												</div>

									<?php 
									echo phone_preview_bottom();
									?>
									
									</div>	
								</div>	
                                
								<div id="tab_products-3" class="tab-pane">
									<div id="triggers_keywords_products"></div>
									</div>
								<div id="tab_products-4" class="tab-pane">
									<div id="triggers_url_products"></div>
									</div>
								<div id="tab_products-5" class="tab-pane">
									<div id="triggers_tags_products"></div>
									</div>	
								<div id="tab_products-6" class="tab-pane">
									<div id="triggers_json_products"></div>
								</div>		
                            </div>

                        </div>

                    </div>
                </div>
	  
					<div style="clear:both;"></div>									
	 <br /><br /> 	
	 						
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_products_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>