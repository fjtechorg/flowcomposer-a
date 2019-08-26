<?php
echo phone_preview_top();
?>
    <div class="boxlayout_big" id="welcome_preview">
        <div class="top_welcome_preview" id="welcome_preview_img"></div>
        <div id="welcome_preview_icon"><img class="img-circle" src="<?php echo $_SESSION['page_image'];?>" height="75px"></div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_title"><?php echo $_SESSION['page_name'];?></div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_cat"><?php echo $_SESSION['page_cat'];?></div>
        <div style="clear: both"></div>
        <div class="styling_welcome_mobilepreview_likes">12345 people like this, including R2D2, C3PO and 17 friends</div>
        <div style="clear: both"></div>
        <div id="welcome_preview_status"><div class="welcome_preview_status"><img src="./images/cm-greeting-getstarted11.png"></div><div class="welcome_preview_status">Typically replies instantly</div></div>
        <div style="clear: both"></div>
        <div id="welcome_preview_greeting"><div class="welcome_preview_greeting"><img src="./images/cm-greeting-getstarted22.png"></div><div  class="welcome_preview_greeting" id="greeting_preview_txt"><?php echo $greeting_text;?></div></div>
        <div style="clear: both"></div>
        <div id="welcome_preview_get_started">When you tap Get Started, <?php echo $_SESSION['page_name'];?> will see your public information.
            <span class="btn btn-primary styling_welcome_mobilepreview_getstartedbtn">Get Started</span>
        </div>

    </div>
<?php
echo phone_preview_bottom();
?>