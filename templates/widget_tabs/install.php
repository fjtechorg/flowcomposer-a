<div class="tab install">
    <input type="hidden" name="widget_id" value="<?php if (isset($widget_data) && is_array($widget_data) && !empty($widget_data["widget_id"])) echo $widget_data['widget_id'] ?>">


    <p class="widget-sub-headings" style="margin-top: 0">Code snippet</p>


    <div data-if="widget-has-id">
        <label>Place this code snippet anywhere on the page, but preferably right before the closing &lt;/body&gt; tag.<br/>
            Make sure you whitelist the domain name that serves your web page from the <span style="color: #0a82fb"><a href="manage.php?action2=settings">configuration</a></span> page.
        </label>
        <textarea class="small-height code select-all initial_code">&lt;script src="<?php echo "https://".$_SERVER['SERVER_NAME']; ?>/clever/widget/{widget_id}.js"&gt;&lt;/script&gt;</textarea>
    </div>

    <div data-show-for-widget-type="embeddable">

    <div data-if="widget-has-id">
        <label>Additionally, place this div where you want to show the embeddable widget</label>
        <textarea class="small-height code select-all additional_code">&lt;div id='{widget_id}' class='clever-fb-stm-embeddable'></div></textarea>
    </div>

    </div>

    <div data-unless="widget-has-id">
        <label>Save the widget in order to get the script link.</label>
    </div>

</div>
