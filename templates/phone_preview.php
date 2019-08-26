<?php
/**
 * here are the two functions that create the phone preview. Top and bottom
 * Done this as we have this preview all over the place
 */

function phone_preview_top()
{


    ?>
    <div class="iphone-container ">
    <div class="phone-shape small">
    <div class="top-details">
        <span class="camera"></span>
        <span class="circle"></span>
        <span class="speaker"></span>
    </div>


    <div class="_1e2-ScWauyWs93J5jC0B-v _3cG4qLlGcjzdjym34o9zqz">
        <div class="_3ptcyLboATxeZt4MTAwWp5">
            <div>
                <span class="Nh7ndFz3S2F0gsbGZE5js">
                    <i class="_2SNJk3E_bB0o_PiaMellh4 _11VuvCU-yjNg4EtOxk6Cta"></i>
                    <span class="_1DRPmsS_-s1jB_6gc5_x9">Home</span>
                </span>
            </div>
            <div class="_2fhPmiHWM0hjVd9zfqOqz5">
                <div class="_2PuJBScmALIu-tudUZC05e">
                    <div class="_2UDWIS1HFZEs9iWydMHsFi">
                        <span class="_1vVq7yzhSR041VG4Tkl1tk">
                                <?php
                                $this_page = smartbot_get_page_details($_SESSION['page_id']);
                                $page_name = $this_page['page_title'];
                                echo $page_name;
                                ?>
                        &nbsp;</span>
                        <i class="fa icon-chevron-right _3qbFcGOAmNyv0Igj7j1JA8" style="
											    position: absolute;
											    font-size: 8px;
											    top: 14px;
											    font-weight: 600;
											"></i>
                    </div>
                </div>
                <div class="_2y3yEY1G9fhLo0m02wPv8z _2rRhW2sCoasLgSIfeoqIpp"><span>Typically replies instantly</span>
                </div>
            </div>
            <div><span class="Nh7ndFz3S2F0gsbGZE5js"><span>Manage</span></span></div>
        </div>
    </div>
    <div class="phone-screen scroll_content2" style="overflow: hidden; width: auto; height: 479px;padding-bottom: 35px;">

    <?php

}

function phone_preview_bottom()
{
    ?>


    </div>

    <?php
    $pageName = basename($_SERVER['PHP_SELF'], '.php');
    if ($pageName != 'main_menu' && $pageName != 'welcome') {
        ?>
        <img id="phone_preview_button_image" src="/images/cm_previewphone_faux_co_2.png" alt=""
             style="bottom: 69px;position: absolute;left: 9px;border-bottom: 1px solid #efefef;">
    <?php } ?>


    <div class="circle-button"></div>
    </div>
    </div>
    <?php
}