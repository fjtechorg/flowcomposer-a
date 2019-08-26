<?php
$success_new_msg='Your new message is saved';
$success_new_title='Success!';
?>
<div id="operator_new_message" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog" style="width: 95%;">

      <!--container for the new msg id returned by the save -->
      <input type="hidden" id="new_msg_id">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New Message</h4>
      </div>
      <div class="modal-body">
	  	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_new_msg-1"><i class="icon-ellipsis"></i>New Message</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_new_msg-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_new_msg-3"><i class="fa icon-tags"></i>Tags</a></li>
                                
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_new_msg-1" class="tab-pane active">

                                    <div class="col-lg-8">
                                        <form method="POST" action="modal_new_message.php" id="new_msg_form">
                                        <div class="ibox" style="">

                                            <div class="ibox-title">

                                                <h3>Create New Message</h3>
                                                <label>Message Name</label><input type="text" name="msg_name" class="form-control" placeholder="Enter the name for this new message">
                                            </div>

                                            <div class="ibox-content" style="padding: 0px 15px 15px 0px;">

                                                <div class="row">

                                                    <div class="draggable_operators_divs" id="boundbox">

                                                        <div class="col-lg-4" style="width:30%;">


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="text"><i class="icon-text-format"></i><br/>Text</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="image"><i class="icon-picture"></i><br/>Image</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="audio"><i class="icon-file-audio"></i><br/>Audio</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="video"><i class="icon-file-video"></i><br/>Video</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="file"><i class="icon-file-empty"></i><br/>File</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="buttons"><i class="icon-pointer-up"></i><br/>Buttons</div><br>

                                                            </div>


                                                            <!-- <div class="col-lg-6 msg-card">

                                                                 <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="products"><i class="icon-cart"></i><br/>Products</div><br>

                                                             </div>-->

                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="carousel"><i class="icon-map2"></i><br/>Carousel</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="list"><i class="icon-menu"></i><br/>List</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="quick"><i class="icon-ellipsis"></i><br/>Quick reply</div><br>

                                                            </div>


                                                            <div class="col-lg-6 msg-card">

                                                                <div class="draggable_operator_new ui-draggable ui-draggable-handle" data-msgtype="typing"><i class="icon-ellipsis"></i><br/>Typing...</div><br>

                                                            </div>

                                                        </div>


                                                    </div>

                                                    <div class="col-lg-8" style="width:70%;">



                                                            <input type="hidden" name="action" value="save_new_message">

                                                            <input type="hidden" name="tr_order" id="tr_order_new">

                                                        <input type="hidden" value="" id="current_msg">
                                                        <input type="hidden" value="<?php echo $_SESSION['page_id'];?>" id="page_id">
                                                        <input type="hidden" value="<?php echo $_SESSION['user_id'];?>" id="user_id">

                                                            <input type="hidden" value="" id="current_item">

                                                            <input type="hidden" value="" id="current_button">

                                                            <div id="broadcast_msgs_canvas" class="scroll_content" style="margin-bottom:20px;">

                                                                <div id="broadcast_msgs_table_new">

                                                                    <div id="vertical-timeline_new" class="vertical-container dark-timeline" style="width: 100%; margin-top: 0px;"></div>

                                                                </div>

                                                            </div>

                                                            <div class="broadcast_alert_msg_new"></div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                        </form>
                                    </div>



                                    <div class="col-lg-4">

                                        <?php
                                        echo phone_preview_top();
                                        ?>

                                        <div class="boxlayout_big" id="broadcast_msg_preview_new"></div>

                                        <?php
                                        echo phone_preview_bottom();
                                        ?>

                                    </div>

                                </div>
								<div id="tab_new_msg-2" class="tab-pane">
									<div id="triggers_keywords_new_msg"></div>
								</div>
								<div id="tab_new_msg-3" class="tab-pane">
									<div id="triggers_tags_new_msg"></div>
								</div>

                            </div>
							</div>	
						</div>				
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_new_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>

<script>

$(document).on('click', '.draggable_operator_new', function (e) {

if($(this).hasClass('operators_overlay')){
e.preventDefault();
}else{
$('.return-msg').html('');

var ThisType = $(this).data('msgtype');

var uniqid = CreateUniqID();
var icon=GetTypeIcon(ThisType);

$('#vertical-timeline_new').append('<div id="'+uniqid+'" data-id="'+uniqid+'" class="vertical-timeline-block"><div class="smartmessenger-delete"><i class="icon-cross"></i></div><div class="broadcast_handlers vertical-timeline-icon navy-bg"><div class="smartmessenger-move ui-sortable-handle"><i class="'+icon+'"></i></div></div><div class="msg_block vertical-timeline-content" data-msgtype="'+ThisType+'" data-msg_id="'+uniqid+'">'+ShowMsgInput(ThisType,uniqid,'')+'</div></div>');

$('#broadcast_msg_preview_new').append(ShowMsgPreview(ThisType,uniqid,''));
if(ThisType==="quick"){jQuery('#qr_alert').html('<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><?php echo BROADCAST_QR_WARNING;?>');}
setTimeout(function(){

window.emojiPicker = new EmojiPicker({

emojiable_selector: '[data-emojiable=true]',

assetsPath: 'img/',

popupButtonClasses: 'icon-smile'

});

// Finds all elements with `emojiable_selector` and converts them to rich emoji input fields

// You may want to delay this step if you have dynamically created input fields that appear later in the loading process

// It can be called as many times as necessary; previously converted input fields will not be converted again

window.emojiPicker.discover();

}



, 100);



$('#broadcast_msgs_table_new .vertical-timeline-block').sortable({


cursor: 'crosshair',

handle: '.smartmessenger-move',

items: '.vertical-timeline-block',

tolerance: 'pointer',

axis: 'y'

});

$('#broadcast_msgs_table_new .vertical-timeline-block').sortable('refresh');

jQuery('<input type="hidden" name="msg_id[]" value="'+uniqid+'" id="msg_id_'+uniqid+'">').prependTo('#new_msg_form');
sendOrderToInput('new');
}
});

$(document).on('click', '.save_new_msg', function (e) {
    e.preventDefault();
    <?php echo $toastr_options; ;?>
    toastr.success("<?php echo $success_new_msg;?>", "<?php echo $success_new_title;?>");
    var form=$("#new_msg_form");
    var data = form.serialize();
    var ajax_url='includes/admin-ajax.php';
    $.ajax({

        type:"POST",

        url:ajax_url,

        data:$("#new_msg_form").serialize(),

        success: function(response){


        jQuery('#new_msg_id').val(response);
        }

    });
});

</script>