<div id="template_preview" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content template-modal-content" style="border-radius: 20px;margin-top: 50px;">
            <div class="modal-header" style="border-color: transparent;">
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body" style="min-height:480px;">
                <div class="row" style="">
                    <div class="col-lg-12">
                        <div class="ibox-content" style="text-align: center;">
                            <div class="template-explore-image">
                                <i class="" style="font-size: 80px;padding: 25px;color: white;border-radius: 100%;box-shadow: 0px 4px 21px #eef0f5;background-position: center;background-size: cover;margin: 20px auto;background-repeat: no-repeat;background: #e48c82;"></i>
                            </div>
                            <h2 class="manage_image_pagename template-explore-title" style="font-size:20px;margin-top: 10%;"></h2>
                            <div class="forum-sub-title dashboard-page-desc">
                                by <a class="template-explore-author" href="#"></a>
                            </div>
                            <hr style="border-bottom: 1px solid #efefef;width: 100%;margin: 20px auto -10px;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <p class="template-explore-full-desc" style="text-align: center;padding:0 20px;"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h4>This template comes with</h4>
                        <p class="template-explore-contains"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-color: transparent;">
                <a id="template-explore-me-link" href="" target="_blank" style="padding: 0;margin: 0;min-height: 0;float: left;">
                    <span class="btn btn-primary"><img style="margin-right: 4px;margin-left: -7px;margin-top: -4px;width: 20px;height: 20px;" src="/images/cm-messengericon.svg"> Preview</span>
                </a>

                <?php if($templateType==='private'){ ?>
                <span id="template_confirm" class="btn btn-primary" style="background-color: white;border-color: #e5e5e5;color: #0a82fb;"> Install</span>
                <?php }
                    else if($templateType==='public') {
                ?>
                <span id="install_template" class="btn btn-primary" data-id="1" style="background-color: white;border-color: #e5e5e5;color: #0a82fb;"> Install</span>
                <?php
                    }
                ?>

            </div>
        </div>
    </div>
</div>