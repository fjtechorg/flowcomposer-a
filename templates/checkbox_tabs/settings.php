<div class="tab settings">
    <input type="radio" id="widget-type-1" name="widget_type" value="bar" style="display:none;">

    <p class="widget-sub-headings" style="margin-top:0px;">Language (Required)</p>

    <label class="full-width">
        Select the language in which the widget will be displayed.

        <select name="widget_language" id="widget_language" class="widget_language form-control">
            <?php $languages = smartbot_get_language_codes();
            foreach ($languages as $language){
                echo "<option value='".$language['language_code']."'>".$language['language_name']."</option>";
            }
            ?>
        </select>

        <p class="widget-sub-headings">Submit button identifier (Required)</p>

        <label class="full-width">

            <div style="padding-bottom:  10px;">Specify the NAME or ID attribute of your submit button -<a href="https://docs.clevermessenger.com/knowledge-base/how-to-find-a-button-name-or-id/" target="_blank"><b> Learn how</b></a></div>
            <input type="text" name="submit_selector" id="submit_selector" placeholder="Submit button NAME or ID attribute" data-delayed-change="1000">

        </label>


        <p class="widget-sub-headings">Tags<span class="tip-span pull-right"  data-toggle="tooltip" title="" data-placement="right" data-original-title="Additionally, any URL that contains the parameter clever_tag will be automatically set as a tag for the user. eg. http://yourwebsite.tld/checkout/?clever_tag=checkout">?</span></p>

    <label class="full-width">
        Tags separated by comma,i.e cart, check-out<br>
        <input type="text" name="button_ref" id="button_ref" placeholder="cart, check-out" data-delayed-change="1000">

    </label>

    <p class="widget-sub-headings">URL Parameters to Custom Fields
        <span class="tip-span pull-right" data-toggle="tooltip" title="" data-placement="right" data-original-title="If your page URL is : http://yourwebsite.tld/?email=test@test.com&points=1000 , to capture the email and points parameters in the above example and set them as custom fields.">?</span>
    </p>

    <label class="full-width">

        URL parameters to set as custom fields separated by comma.</br>
        <input type="text" name="url_params" id="url_params" placeholder="email, points" data-delayed-change="1000">

    </label>


    <p class="widget-sub-headings">Form Inputs to Custom Fields
        <span class="tip-span pull-right" data-toggle="tooltip" title="" data-placement="right" data-original-title="This feature will capture your form inputs as customfields.">?</span>
    </p>

    <label class="full-width">

        Specify Form input fields (NAME or ID attribute) to set as custom fields separated by comma.</br>

        <input type="text" name="form_inputs" id="form_inputs" placeholder="email, points" data-delayed-change="1000">

    </label>


        <input type="hidden" name="server_environement" value="<?php echo strpos($_SERVER['HTTP_HOST'], 'dev.clevermessenger.com') !== false ? "dev":"app" ?>" />


</div>

