<?php include_once('phone_preview.php');?>
<div class="row m-t">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <?php echo $bot_title;?>
                        </div>
                        <div class="ibox-content">
                            
                            <p>
                                <?php echo $bot_subtitle;?>
                            </p>

                            <form id="form2" class="wizard-big" method="post">

                                <h1><?php echo $bot_step1_title;?></h1>
                                <fieldset>
                                    <h2>Bot Basic Information</h2>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <input id="bot_name" name="bot_name" type="text" class="form-control required input-lg" placeholder="Enter name here - i.e. HAL 9000">
											</div>
											
                                        </div>
                                    </div>

                                </fieldset>								
								<h1><?php echo $bot_step2_title;?></h1>
                                <fieldset>
                                    <h2>Amazon Details</h2>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <input id="amazon_id" name="amazon_id"  type="text" class="form-control required input-lg" placeholder="enter your Amazon associate or tracking id"  value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_id');?>">
                                                <input id="amazon_public" name="amazon_public" type="text" class="form-control required input-lg" placeholder="enter your Amazon Public Key"  value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_public');?>">
                                                <input id="amazon_secret" name="amazon_secret" type="text" class="form-control required input-lg" placeholder="enter your Amazon Secret Key"  value="<?php echo smartbot_get_options($_SESSION['user_id'],'','','amazon_secret');?>">
                                            <br/>
                                            <span class="btn btn-primary check_amazon">Check Connection</span>
                                            </div>
                                            
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="text-center">
                                                <div style="margin-top: 20px">
                                                    <img src="images/card_icons/amazon.png" />
                                                    FAQ Here<br />
                                                    <div id="amazon_check_result"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <h1><?php echo $bot_step3_title;?></h1>
                                <fieldset>
                                    <h2></h2>
                                    <div class="row">
                                       <div id="wizard_connect_fb_heading"></div>
                                       <div id="wizard_connect_fb"></div>                   
                                    </div>
                                </fieldset>
                                

                                <h1><?php echo $bot_step4_title;?></h1>
                                <fieldset>
                                    <h2>Amazon Products</h2>
                                    <div class="row">
									<div class="col-lg-12">
    									  <div class="panel blank-panel">
										  	<div class="panel-heading">
                            					<div class="panel-options">
                            						<ul class="nav nav-tabs">
                            							<li class="active"><a data-toggle="tab" href="#tab-1"><i class="icon-magnifier"></i></a></li>
                            							<li class=""><a data-toggle="tab" href="#tab-2"><i class="fa icon-desktop"></i></a></li>
                            						</ul>
                            					</div>
                            				</div>
                            </form>
    											<div class="panel-body">
												<div class="tab-content">
    												<div id="tab-1" class="tab-pane active">
    													<div class="col-lg-9">
                                                            <form id="form" action="<?php echo SB_HOME_URL;?>bot_amazon.php?page=wizard" method="post">
                                                                <input type="hidden" id="bot_page" />
                                                                <input type="hidden" name="actie" value="save_amazon" />
                                                                <input type="hidden" id="bot_id" name="bot_id"/>
                                                                <input type="hidden" id="page_id" name="page_id"/>
                                                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
                                                                <input type="hidden" name="edit_msgid" value="" id="edit_msgid">
                                                                <input type="hidden" id="amazon_valid" value="" >

                        									  <div id="amazon_search_form">


                                                              </div>
                    										  
                        									  <div id="amazon_search_result"></div>
                                                            </form>
    													</div>
														<!--<div class="col-lg-3"><h2>Filters</h2>
                    									<input type="text" placeholder="Search Products" class="form-control input-lg amazon_search" name="search" id="amazon_keywordfilter">
                    									</div>	-->
                                                    </div>

												  	  <div id="tab-2" class="tab-pane">

                                                          <div class="row wrapper wrapper-content white-bg">
                                                          <div class="col-lg-6">
                                                              <div id="modal_structured_content"></div>
                                                              <div id="product_message_result"></div>
                                                          </div>

                                                          <div class="col-lg-4">

                                                              <?php
                                                              echo phone_preview_top();
                                                              ?>
                                                              <div class="boxlayout_big" id="broadcast_msg_preview">
                                                                  <div id="preview_carousel"><div class="broadcast_preview_carousel">
                                                                          <div id="carousel_items" class="preview_slider">
                                                                              <div id="slides" class="productslides"></div>
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


                                                      </div>

													  
												  </div>
        									   </div>
    									  </div>
									</div> 


                                    </div>
                                </fieldset>

                        </div>
                    </div>
                    </div>

                </div>
