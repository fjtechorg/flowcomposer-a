<div id="operator_typing" class="modal fade" role="dialog">  
<div class="modal-dialog">    
	 <!-- Modal content-->    
	 	  <div class="modal-content">      
		  	   <div class="modal-header">        
			   <button type="button" class="close" data-dismiss="modal">&times;</button>        
			   <h4 class="modal-title">Typing Action</h4>      </div>      
			   <div class="modal-body">	  
			   <label>Typing indicator duration</label>
			   <select id="typing_view" name="typing_view" class="form-control input-lg" >	  
			   <?php	  for($i=1;$i<20;$i++){	  echo '<option value="'.$i.'">'.$i.' Second';	  if($i>1){echo 's';}	  
			   echo '</option>';	  }	  ?>	  	  
			   </select>													  
			    <br /><br />  	  
				</div>      
		<div class="modal-footer">       
	 <button type="button" class="btn btn-primary save_typing_msg" data-dismiss="modal">Save</button>
</div>    </div>  </div></div>