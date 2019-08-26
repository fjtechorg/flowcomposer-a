<div class="tab submitted">

    <p class="widget-sub-headings" style="margin-top:0px;">Flow to send after submission</p>

    <label class="full-width">

        <?php
        include ("templates/flow_selector.php");
        ?>


    </label>


    <p class="widget-sub-headings">Submission behavior</p>

    <label class="full-width">
        <input type="checkbox" name="show_error" id="show_error" value="0" unchecked>
        Prevent the form to be submitted when the checkbox unchecked
    </label>

<div id="error_text_div">
    <p class="widget-sub-headings">Unchecked checkbox error text</p>

    <label class="full-width">
        Specify the error message if the checkbox was not checked.

    </label>
    <input type="text" style="margin-top:5px;" name="error_text" id="error_text" placeholder="Error text" value="Please check for messenger updates in order to continue." >
</div>

</div>


