<div class="tab submitted">

    <p class="widget-sub-headings" style="margin-top: 0">Flow to send after submission</p>

    <label class="full-width" style="margin-bottom: 10px;">

        <?php
        include __DIR__."/../flow_selector.php";
        ?>


    </label>

    <br>


    <p class="widget-sub-headings">After the Send to Messenger button is clicked</p>

    <label>
        <input type="radio" name="after_submit" value="update" data-toggle-element="#submitted-update" checked>
        Update the widget
    </label>

    <label>
        <input type="radio" name="after_submit" value="redirect" data-toggle-element="#submitted-redirect">
        Go to a different page
    </label>





    <div id="submitted-update" class="toggle-target">

        <div data-hide-for-widget-type="embeddable">

        <label style="display: none" class="full-width">
            Headline<br>
            <textarea class="small-height" name="submitted_headline" id="submitted_headline">Thank you!</textarea>
        </label>

        <div data-hide-for-widget-type="bar">
            <label style="display: none" class="full-width">
                Description<br>
                <textarea name="submitted_description" id="submitted_description">Thank you for subscribing.</textarea>
            </label>
        </div>

        <div data-hide-for-widget-type="bar">

            <input type="hidden" name="submitted_image" id="submitted_image" value="<?php if (isset($widget_data) && is_array($widget_data) && !empty($widget_data["widget_id"])) echo $widget_data['submitted_image']; ?>">

            <p class="widget-sub-headings">Layout</p>
            <p class="error-message image-error-message"></p>

            <label>
                <input type="radio" name="submitted_image_location" value="above_headline" checked>
                Image above headline
            </label>

            <label>
                <input type="radio" name="submitted_image_location"  value="above_description">
                Image above description
            </label>

            <label>
                <input type="radio" name="submitted_image_location"  value="below_description">
                Image below description
            </label>
        </div>





        <div data-hide-for-widget-type="bar" style="margin:20px 0px;">


            <label class="full-width">
                <input type="checkbox" name="submitted_show_description" id="submitted_show_description" value="1" checked>
                Show description
            </label>
        </div>





        <p class="widget-sub-headings">Colors</p>

        <div class="row">
        <label class="col-lg-4">
            Background<br>
            <input type="text" name="submitted_background_color" id="submitted_background_color" class="colorpicker_full" value="#0a82fb">
        </label>

        <label class="col-lg-4">
            Headline<br>
            <input type="text" name="submitted_headline_color" id="submitted_headline_color" class="colorpicker_full" value="#FFFFFF">
        </label>

        <div class="col-lg-4" data-hide-for-widget-type="bar">
            <label class="full-width">
                Description<br>
                <input type="text" name="submitted_description_color" id="submitted_description_color" class="colorpicker_full" value="#CCCCCC">
            </label>
        </div>

        </div>

        </div>





        <p class="widget-sub-headings">View button</p>

        <label class="full-width">
            Button text<br>
            <input type="text" name="submitted_button_text" id="submitted_button_text" value="View in Messenger">
        </label>

        <div class="row">
        <label class="col-lg-4">
            Background<br>
            <input type="text" name="submitted_button_background_color" id="submitted_button_background_color" class="colorpicker_full" value="#FFFFFF">
        </label>

        <label class="col-lg-4">
            Text<br>
            <input type="text" name="submitted_button_text_color" id="submitted_button_text_color" class="colorpicker_full" value="#000000">
        </label>
        </div>
    </div>





    <div id="submitted-redirect" class="toggle-target">

        <label>
             <input type="text" name="after_submit_url" style="margin: 10px;margin-left: 0" placeholder="e.g https://name.tld/thankyou" id="after_submit_url">
        </label>

        <p>The new page opens:</p>

        <label>
            <input type="radio" name="after_submit_url_window" value="current" checked>
            In the current tab
        </label>

        <label>
            <input type="radio" name="after_submit_url_window" value="new">
            In a new tab
        </label>
    </div>
</div>


