<div class="tab submitted">

    <p class="widget-sub-headings" style="margin-top: 0">Flow to send after submission</p>

    <label class="full-width" style="margin-bottom: 10px;padding-bottom: 50px;">

        <?php
        include __DIR__."/../flow_selector.php";
        ?>



        <div data-show-for-widget-type="bar">
            <label class="full-width" style="padding-top: 30px;">
                <input type="checkbox" name="send_once" id="send_once" value="0" >
                Send flow only once per user
            </label>
        </div>

    </label>

    <br>

</div>

