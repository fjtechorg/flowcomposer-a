<div class="tab settings">

    <p class="widget-sub-headings" style="margin-top: 0">Language</p>

    <label class="full-width">
        The language in which the button will be displayed on your site.<br>

        <select name="widget_language" id="widget_language" class="widget_language form-control">
            <?php $languages = smartbot_get_language_codes();
            foreach ($languages as $language){
                echo "<option value='".$language['language_code']."'>".$language['language_name']."</option>";
            }
            ?>
        </select>

    </label>
    <p class="widget-sub-headings">Tags<span class="tip-span pull-right"  data-toggle="tooltip" title="" data-placement="right" data-original-title="Additionally, any URL that contains the parameter clever_tag will be automatically set as a tag for the user. eg. http://yourwebsite.tld/checkout/?clever_tag=checkout">?</span></p>

    <label class="full-width">
        Tags separated by comma,i.e cart, check-out<br>
        <input style="margin-top: 15px;" type="text" name="button_ref" id="button_ref" placeholder="cart, check-out" data-delayed-change="1000">

    </label>


    <p class="widget-sub-headings">URL Parameters to Custom Fields
        <span class="tip-span pull-right" data-toggle="tooltip" title="" data-placement="right" data-original-title="If your page URL is : http://yourwebsite.tld/?email=test@test.com&points=1000 , to capture the email and points parameters in the above example and set them as custom fields.">?</span>
    </p>

    <label class="full-width">

        URL parameters to set as custom fields separated by comma.</br>

        <input style="margin-top: 15px;" type="text" name="url_params" id="url_params" placeholder="email, points" data-delayed-change="1000">

    </label>


    <p class="widget-sub-headings">Display</p>

    <label>Widget displays:</label>

    <div class="row" style="margin:0px;">
        <div class="col-md-29">
            <label class="full-width">
                <select name="display_moment_when" id="display_moment_when">
                    <option value="immediately" selected>Immediately</option>
                    <option value="exit">On exit intent</option>
                    <option value="click" data-show="1">Element is clicked (Provide element ID or NAME attribute)</option>
                    <option value="scroll" data-show="1">When scrolled (%):</option>
                    <option value="target" data-show="1">When scrolled to element (CSS selector):</option>
                    <option value="seconds" data-show="1">After a number of seconds:</option>
                </select>
            </label>
        </div>

        <div class="col-md-1"></div>

        <div class="col-md-30">
            <label class="full" data-select-name="display_moment_when">
                <input type="text" name="display_moment_option" id="display_moment_option">
            </label>
        </div>
    </div>

    <label>Show to the same visitor again:</label>

    <div class="row" style="margin:0px;">
        <div class="col-md-29">
            <label class="full-width">
                <select name="display_again_when" id="display_again_when">
                    <option value="always" selected>At every visit</option>
                    <option value="never">Never</option>
                    <option value="hours" data-show="1">After hours have passed:</option>
                    <option value="days" data-show="1">After days have passed:</option>
                </select>
            </label>
        </div>

        <div class="col-md-1"></div>

        <div class="col-md-30">
            <label class="full" data-select-name="display_again_when">
                <input type="text" name="display_again_option" id="display_again_option">
            </label>
        </div>
    </div>

    <div data-hide-for-widget-type="embeddable">

    <label>After a visitor closed the widget, show again:</label>

    <div class="row" style="margin:0px;">
        <div class="col-md-29">
            <label class="full-width">
                <select name="display_again_closed_when" id="display_again_closed_when">
                    <option value="always">At every visit</option>
                    <option value="never">Never</option>
                    <option value="hours" data-show="1">After hours have passed:</option>
                    <option value="days" data-show="1" selected>After days have passed:</option>
                </select>
            </label>
        </div>

        <div class="col-md-1"></div>

        <div class="col-md-30">
            <label class="full" data-select-name="display_again_closed_when">
                <input type="text" name="display_again_closed_option" id="display_again_closed_option" value="7">
            </label>
        </div>
    </div>

    </div>



    <p class="widget-sub-headings">Display widget on</p>

    <label>
        <input type="radio" name="display_on_device" value="all" checked>
        Mobile and desktop
    </label>

    <label>
        <input type="radio" name="display_on_device" value="desktop">
        Desktop only
    </label>

    <label>
        <input type="radio" name="display_on_device" value="mobile">
        Mobile only
    </label>

    <input type="hidden" name="server_environement" value="<?php echo strpos($_SERVER['HTTP_HOST'], 'dev.clevermessenger.com') !== false ? "dev":"app" ?>" />

</div>

