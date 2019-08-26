<div class="tab looks">




    <p class="widget-sub-headings" style="margin-top: 0">Choose loading state</p>

    <label class="full-width">
        <div style="padding-bottom: 10px;">Choose how the widget will show</div>
        <select name="widget_minimized" id="widget_minimized">
            <option value="show" selected>Show both Messenger bubble and greeting dialog</option>
            <option value="hide">Show messenger bubble only</option>
            <option value="fade" data-show="1">Show greeting dialog after delay, then hide it</option>
        </select>
    </label>

    <div class="col-md-30">
        <label class="full" data-select-name="widget_minimized">
            <input type="text"  name="greeting_dialog_delay" id="greeting_dialog_delay" placeholder="Sets the number of seconds of delay before the greeting dialog is shown after the plugin is loaded">
        </label>
    </div>


    <p class="widget-sub-headings" style="margin-top: 0">Color Theme</p>

    <label class="full-width">
        <div style="padding-bottom: 10px;">Choose the color scheme for your chat widget</div>
        <input type='text' name="theme_color" id="theme_color" class="colorpicker_full"/>

    </label>


    <p class="widget-sub-headings" style="margin-top: 0">Greeting message</p>

    <label class="full-width">
        <div style="padding-bottom: 10px;">For logged in users</div>
        <textarea rows="3" data-emojiable="true" data-charcounter="true" style="height:70px;" class="" name="logged_in_greeting" placeholder="The greeting text that will be displayed if the user is currently logged in to Facebook. Maximum 80 characters" maxlength="80" id="logged_in_greeting" data-delayed-change="1000"></textarea>

    </label>


    <label class="full-width">
        <div style="padding-bottom: 10px;">For non-logged in users</div>
        <textarea rows="3" data-emojiable="true" data-charcounter="true" style="height:70px;" name="logged_out_greeting" placeholder=" The greeting text that will be displayed if the user is currently not logged in to Facebook. Maximum 80 characters." maxlength="80" id="logged_out_greeting" data-delayed-change="1000" ></textarea>

    </label>

</div>

