<div class="tab install">
    <input type="hidden" name="widget_id" value="">


    <p class="widget-sub-headings" style="margin-top: 0">Code snippet</p>


    <div data-if="widget-has-id">
        <label>Place this code snippet anywhere on the page, but preferably right before the closing &lt;/body&gt; tag.<br/>
            Make sure you whitelist the domain name that serves your web page from the <span style="color: #0a82fb"><a href="manage.php?action2=settings">configuration</a></span> page.
        </label>
        <textarea class="small-height code select-all initial_code">&lt;script src="<?php echo "https://".$_SERVER['SERVER_NAME']; ?>/clever/customerchat/{widget_id}.js"&gt;&lt;/script&gt;</textarea>
    </div>

    <div data-unless="widget-has-id">
        <label>Please save the widget first, so a code snippet can be made.</label>
    </div>
</div>
