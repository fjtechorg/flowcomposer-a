<?php
echo phone_preview_top();
?>
<div class="boxlayout_big" id="broadcast_msg_preview">
    <div id="text_message_preview" style="display: none" class="broadcast_preview_text message-left"></div>
    <div class="image-message-preview-container" style="display: none">
        <img id="image_message_preview"  class="image-modal-preview" src="">
    </div>
    <div class="video-message-preview-container" style="display: none" id="video_message_preview_container">
        <video id="video_message_preview" controls ><source src="" type="video/mp4">Your browser does not support the video tag</video>
    </div>
    <div class="audio-message-preview-container" style="display: none" id="audio_message_preview_container">
        <audio id="audio_message_preview" controls ><source src="" type="audio/mp3">Your browser does not support the audio tag</audio>
    </div>

    <div id="flow-preview-container" style="height:auto;display: none"></div>
    <div class="file-message-preview-container" style="display: none" id="file_message_preview_container">
        <a id="file_message_preview" target="_blank"><i class="icon-file-empty"></i><span id="file_name_preview"></span></a>
    </div>
    <div style="clear:both;"></div>
    <div id="template_element_preview" style="display: none" class="preview_list preview_list_container"></div>
    <div style="clear:both;"></div>
    <div id="carousel_preview_container" style="display: none" class="carousel-preview carousel-preview-container">
        <div id="preview_carousel">
            <div class="broadcast_preview_carousel">
                <div id="carousel_items" class="preview_slider">
                    <div id="slides" class="carouselslides">
                        <div id="slides_container"></div>

                    </div>
                    <span class="controls carousel_previous" style="display: none" data-slide_id="">&lt;</span>
                    <span class="controls carousel_next" data-slide_id="">&gt;</span>
                </div>
            </div>
    </div>
    </div>

    <div style="clear:both;"></div>
    <div id="buttons_preview" class="buttons-container-preview"></div>
    <div style="clear:both;"></div>



    <div class="qslides">
        <span id="quick_previous" class="controls quick_previous" data-slide_id="" style="visibility: hidden;">&lt;</span>
        <span class="controls quick_next" data-slide_id="" style="visibility: hidden">&gt;</span>
        <div id="quickreplies_preview"  class="broadcast_preview_quick quickreplies-container-preview">

        </div>
    </div>
</div>



<?php
echo phone_preview_bottom();
?>