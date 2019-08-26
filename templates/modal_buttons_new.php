<div id="operator_buttons_new" class="modal fade" role="dialog" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Buttons</h4>
      </div>
      <div class="modal-body">	
	  
	  <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab_buttons-1"><i class="icon-list"></i>Button messages</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_buttons-2"><i class="icon-arrow-right-circle"></i>Triggers</a></li>
                               		<li class=""><a data-toggle="tab" href="#tab_buttons-3"><i class="icon-launch"></i>Messenger link</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab_buttons-4"><i class="fa icon-tags"></i>Tags</a></li>
                                <li class=""><a data-toggle="tab" href="#tab_buttons-5"><i class="icon-code"></i>Json Ad Code</a></li>
                                
							    </ul>
                            </div>
                        </div>
						 <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab_buttons-1" class="tab-pane active">
	  									

<table class="table-noborder"> 
										<tr><td width="120px"></td><td><input class="form-control input-lg" id="buttons_operator_title" type="text"  onchange="ChangeThisItem('#buttons_operator_title','', '_msg_name', '');"></td></tr>
										<tr><td>Message Text</td><td>
										<p class="lead emoji-picker-container"  style="min-width:350px;"><textarea rows="2" cols="55" name="msg_buttontext" class="msg_buttontext form-control input-lg" data-emojiable="true" data-emoji-input="unicode" onchange="ChangeThisItem('.msg_buttontext','_msg_content', '_msg_buttontext', '60');" maxlength="640"></textarea></p><br></td></tr>
										<tr><td valign="top"><strong># Buttons</strong></td><td><div class="form-container">
       <select class="form-control input-lg" id="buttons-filter" name="buttons-filter">
	   <option value="">Select Number of Buttons&nbsp; &nbsp; &nbsp; </option>
            <option value="1">1 Button&nbsp; &nbsp; &nbsp; </option>
            <option value="2">2 Buttons</option>
            <option value="3">3 Buttons</option>
    </select> Maximum # buttons is 3 
</div></td></tr>
</table><br />

<?php
for($x=1;$x<4;$x++){
?>
<div class="products-item buttons<?php echo $x;?>_item">
    <div class="box-item"></div>
	<table class="table-noborder">
	<tr><td>Button <?php echo $x;?> Title</td><td><input type="text" name="button<?php echo $x;?>_title" class="buttons_button<?php echo $x;?>_title form-control input-lg" size="35" onchange="ChangeThisItem('.buttons_button<?php echo $x;?>_title','_con_label_output_<?php echo $x -1;?>', '_button<?php echo $x;?>_title','');"  maxlength="20"/>  (20 Characters maximum)<br/></td></tr>
	<tr><td>Button <?php echo $x;?> Type</td><td><select  id="<?php echo $x;?>buttons<?php echo $x;?>-type-filter" name="<?php echo $x;?>buttons<?php echo $x;?>-type-filter" class="form-control input-lg">
	        <option value="">Select Type</option>
            <option value="<?php echo $x;?>buttons<?php echo $x;?>url" >Url</option>
            <option value="<?php echo $x;?>buttons<?php echo $x;?>onclick">Onclick Action</option>
            <option value="<?php echo $x;?>buttons<?php echo $x;?>phone">Phone Number</option>
    </select> Select the Type of Action after click. See the manual</td></tr>
	</table>
</div>

	<div class="buttons-item<?php echo $x;?>" buttons="<?php echo $x;?>buttons<?php echo $x;?>url" id="<?php echo $x;?>buttons<?php echo $x;?>url"><table class="table-noborder"><tr><td width="120px">Button <?php echo $x;?> Url&nbsp; &nbsp; </td><td><input type="text" name="button<?php echo $x;?>_url" class="buttons_button<?php echo $x;?>_url form-control input-lg" onchange="ChangeThisItem('.buttons_button<?php echo $x;?>_url','', '_button<?php echo $x;?>_url','');" size="55"/><br></td></tr></table></div>	
	<div class="buttons-item<?php echo $x;?>" buttons="<?php echo $x;?>buttons<?php echo $x;?>onclick" id="<?php echo $x;?>buttons<?php echo $x;?>onclick"><table class="table-noborder"><tr><td colspan="2">Button <?php echo $x;?> will create an Onclick Action calling an other message.<div id="trigger_buttons<?php echo $x;?>"></div></td></tr></table></div>
	<div class="buttons-item<?php echo $x;?>" buttons="<?php echo $x;?>buttons<?php echo $x;?>share" id="<?php echo $x;?>buttons<?php echo $x;?>share"><table class="table-noborder"><tr><td width="120px">Button <?php echo $x;?> Share &nbsp; &nbsp; </td><td>This Button when clicked will popup a Share function</td></tr></table></div>	
	<div class="buttons-item<?php echo $x;?>" buttons="<?php echo $x;?>buttons<?php echo $x;?>phone" id="<?php echo $x;?>buttons<?php echo $x;?>phone"><table class="table-noborder"><tr><td width="120px">Button <?php echo $x;?> Phone &nbsp; &nbsp; </td><td><input type="text" name="button<?php echo $x;?>_phone" class="buttons_button<?php echo $x;?>_phone form-control input-lg" onchange="ChangeThisItem('.buttons_button<?php echo $x;?>_phone','', '_button<?php echo $x;?>_phone','');" size="55"/><br></td></tr></table></div>	
	
<br />
<?php
}
?>											
	 <br /><br /> 
	 </div>
								<div id="tab_buttons-2" class="tab-pane">
									<div id="triggers_keywords_buttons"></div>
								</div>
								<div id="tab_buttons-3" class="tab-pane">
									<div id="triggers_url_buttons"></div>
								</div>
								<div id="tab_buttons-4" class="tab-pane">
									<div id="triggers_tags_buttons"></div>
								</div>
								<div id="tab_buttons-5" class="tab-pane">
									<div id="triggers_json_buttons"></div>
								</div>									
							</div>	
						</div>										
	  </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary save_button_msg" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>	