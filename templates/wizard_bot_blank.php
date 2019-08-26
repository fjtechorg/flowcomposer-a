<?php include_once('templates/page_top_section.php'); ?>
<div class="row m-t">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
                <?php echo $bot_title;?>
            </div>
            <div class="ibox-content">
                <p>
                    <?php echo $bot_subtitle;?>
					<div id="submit_result_bot"></div>
                </p>
                <form id="wizard_form" action="#" class="wizard-big" method="post">
				<input type="hidden" id="bot_id" />
				<input type="hidden" id="bot_page" />
				<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
                    <!--//not showing this
				<?php
              //  print_r($_SESSION);
                ?>
                -->
                    <h1><?php echo $bot_step1_title;?></h1>
                    <fieldset>
                        <h2>Bot name</h2>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <input id="bot_name" name="bot_name" type="text" class="form-control required input-lg" placeholder="Enter name here - i.e. HAL 9000">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <div style="margin-top: 20px">
                                        <i class="fa icon-enter-right" style="font-size: 180px;color: #e5e5e5 "></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
					<h1><?php echo $bot_step2_title;?></h1>
                    <fieldset>
                        <h2 style="display: none;">Connect to Facebook</h2>
                        <div class="row">
						   <div id="wizard_connect_fb_heading" style="margin: 6px; padding: 10px;display: none;">Connect your bot to your Facebook page by clicking on "Connect to Page"</div>
                           <div id="wizard_connect_fb">
						   </div>					
                        </div>
                    </fieldset>
					
                    

            </div>
        </div>
    </div>
</div>
