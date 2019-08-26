<div id="operator_products_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search Products and Add them</h4>
      </div>
      <div class="modal-body">										
	  <div class="col-lg-12">
                    <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_products-1"><i class="icon-magnifier"></i></a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_products-2"><i class="icon-menu"></i>Message</a></li>
									<li class=""><a data-toggle="tab" href="#tab_products-3"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_products-4"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_products-5"><i class="fa icon-tags"></i>Tags</a></li>
                                	<li class=""><a data-toggle="tab" href="#tab_products-6"><i class="icon-code"></i>JSON Code</a></li>
                                     
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_products-1" class="tab-pane active">
									<div class="col-lg-9">
                                    <p><table class="table-noborder">	
										<tr><td width="120px">Product Type</td><td>
                                         <select  id="products_type" name="products_type">
										 	  <option value="">Select Type</option>
                                              <option value="amazon">Amazon</option>
                                              <option value="shopify">Shopify</option>
                                      	</select></td></tr>
										</table>	
										<div id="product_detail"></div>			
										<div id="product_message_detail"></div>
										<div id="product_category"></div>	
										<div id="product_product"></div>	</p>							
									</div>
									<div class="col-lg-3">
        									<form id="filter_products" style="background-color:transparent;">
        									<input type="text" placeholder="Search Products" name="search" id="keywords">
        									</form>
									</div>
									
									</div>

                                	<div id="tab_products-2" class="tab-pane">
                                    <strong>Results</strong>
									<br />
									<div id="product_message_result">
									
									</div>
                                    <script type="text/javascript">
									jQuery(document).ready(function($){	
									  $('#product_message_result table').sortable({
									  containment: '#smartmessenger_filter_modules',		
									  cursor: 'crosshair',
									  forcePlaceHolderSize: true,
									  handle: '.smartmessenger-move',
									  items: 'tr',		axis: 'y'	});	
									  });	
									</script>
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
        <button type="button" class="btn btn-primary save_products_msg" data-dismiss="modal">Save Settings</button>
      </div>
    </div>

  </div>
</div>