var tabs = new Tabs();

resize_widget_divobject();
$(window).resize(resize_widget_divobject);

function resize_widget_divobject() {
    var a = $('#side-menu').height(), b = $('.nav-header').height();
    a = a - b;
    $(".scroll_widget_builder").css("height", a);
}

resize_widget_divobject2();
$(window).resize(resize_widget_divobject2);

$(window).bind("load", function() {
   // charCountSet("[data-charcounter='true']");
});

function parseFBElements(){
    setTimeout(function(){
        jQuery('.scroll_widget_builder_preview').unblock();
    },20000);

    setTimeout(function(){
        jQuery('#posts').unblock();
    },20000);

        FB.XFBML.parse(document.getElementById("wizard_select_posts"), function () {


            jQuery('.scroll_widget_builder_preview').unblock();
            jQuery('#posts').unblock();
            $(".message > .pull-right").remove();


            if (window.postId !== '') {
                var postId = window.postId.split(',');
                for (var x = 0; x < postId.length; x++) {
                    var fbcSelector = '#' + postId[x];
                    $(fbcSelector).attr('data-selector', 'selected');
                    fbcSelector = fbcSelector + ' > .message';
                    $(fbcSelector).css('border', '5px solid #04D392');
                    if (window.postId === "catch_all") disableAllPosts();
                }
            }
        });

}

function resize_widget_divobject2() {
    var a = $('#side-menu').height(), b = $('.nav-header').height();
    a = a - b;
    $(".scroll_widget_builder_preview").css("height", a);
}

$("#loadMore").click(function(){


    fetchFacebookPosts("load");

});

$(function () {
    $('.scroll_widget_builder_preview').on('scroll', function (e) {
        if ($('#nomoreposts').val() === 'none') {
            return false;
        }

        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            e.preventDefault();



        }
    });
});

$(document).ready(function () {


    $(".widget_language").select2({
        placeholder: "Select a language",
        allowClear: false,
        width: 'resolve'
    });


    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });

    $('.form-control.input-sm').on('change', function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });


    /*load existing values*/
    jQuery('.styling_widget_builder_left').block({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Fetching settings...</span>',
        overlayCSS: {opacity: .5}
    });

    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'get_saved_facebook_comment',
        'fbcid': window.urlParams.id,
    };
    jQuery.post(ajax_url, data, function (res) {
        try {
            var res = jQuery.parseJSON(res);
            if (res.ignore_nested_comment === '1') {
                $('#actionIgnoreNestedComment').prop("checked", true);
            }

            if (res.send_once === '1') {
                $("#send_once").prop("checked", true);
            }

            window.postId = res.post_id;

            if (res.reply_active === '1') {
                $('#actionReplyActive').prop("checked", true);
            }
            if (res.send_after_type)
                $('.actionSendAfterType').val(res.send_after_type).trigger("change");
            if (res.send_after_value)
                $('.actionSendAfterValue').val(res.send_after_value);

            $('#msg_text').val(res.msg_text).nextAll(".emoji-wysiwyg-editor").html(res.msg_text);

            if (res.optin_message_type) {
                $("#select_reference_type").val(res.optin_message_type).trigger("change");
                if (res.flow_card_id !== '')
                    $("#select_reference_flow").trigger("change", {
                        cardToSelect: res.flow_card_id,
                        flowId: res.flow_id,
                        flowType: res.optin_message_type
                    });
                else
                    $("#select_reference_flow").val(res.flow_id).trigger("change");
            }


            if (res.neg_keywords) {
                var negativeKeywords = res.neg_keywords.split(',');
                for (var x = 0; x < negativeKeywords.length; x++) {
                    var tag = '<span class="chat_tags negative_chat_tags" data-neg="' + negativeKeywords[x] + '">' + negativeKeywords[x] + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
                    $('#negative_edit_tags').append(tag);
                }
            }

            if (res.keywords) {
                var positiveKeywords = res.keywords.split(',');
                for (var x = 0; x < positiveKeywords.length; x++) {
                    var tag = '<span class="chat_tags positive_chat_tags" data-posi="' + positiveKeywords[x] + '">' + positiveKeywords[x] + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
                    $('#positive_edit_tags').append(tag);
                }
            }

            if (res.positive_reactions) {
                positiveReactions = res.positive_reactions.split(',');
                for (x = 0; x < positiveReactions.length; x++) {
                    dataReactionIcon = "<span class='like-btn-" + positiveReactions[x] + "' style='display:inline;padding-right:20px;'></span>";
                    dataReactionName = "<span style='margin-left:5px;'>" + positiveReactions[x] + "</span>";
                    tag = '<span class="chat_tags positive_reaction_tags" data-reaction="' + positiveReactions[x] + '">' + dataReactionIcon + dataReactionName + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
                    $('#positive_reaction_tags').append(tag);
                }
            }


            jQuery('.styling_widget_builder_left').unblock();
            tabs.enableTabs();


            fetchFacebookPosts();

            /*/load existing values*/
        }
        catch (e) {
            jQuery('.scroll_widget_builder_preview').unblock();

        }

    });


    jQuery("#send_after_type").on("change", function (e) {
        if ($(this).val() === "immediately") {
            $("#send_after_value").hide();
        }
        else {
            $("#send_after_value").show();
        }
    });

    $('.action_save_comment').on('click', function () {

        facebookCommentSettings = {};
        if ($("#actionIgnoreNestedComment").prop('checked') === true) {
            facebookCommentSettings.ignoreNestedComment = 1;
        }
        else {
            facebookCommentSettings.ignoreNestedComment = 0;
        }

        if ($("#actionReplyActive").prop('checked') === true) {
            facebookCommentSettings.replyActive = 1;
        }
        else {
            facebookCommentSettings.replyActive = 0;
        }

        if ($("#send_once").prop('checked') === true) {
            facebookCommentSettings.sendOnce = 1;
        }
        else {
            facebookCommentSettings.sendOnce = 0;
        }

        facebookCommentSettings.msgText = $("#msg_text").val();
        facebookCommentSettings.sendAfterType = $('.actionSendAfterType option:selected').val();
        facebookCommentSettings.sendAfterValue = $('.actionSendAfterValue').val();
        facebookCommentSettings.optinMessageType = $('#select_reference_type').val();
        facebookCommentSettings.flowId = $('#select_reference_flow').val();
        facebookCommentSettings.flowCardId = $('#select_reference_flow_card').val();
        facebookCommentSettings.negativeKeywords = facebookCommentSettings.positiveKeywords = facebookCommentSettings.positiveReactions = facebookCommentSettings.negativeReactions = '';


        var count = 0;
        var maxCount = $('#negative_edit_tags').children().length;
        for (count = 1; count <= maxCount; count++) {
            var tagSelector = "#negative_edit_tags .negative_chat_tags:nth-child(" + count + ")";
            var tagVal = $(tagSelector).attr('data-neg');
            if (count != maxCount) {
                facebookCommentSettings.negativeKeywords = facebookCommentSettings.negativeKeywords + tagVal + ',';
            }
            if (count == maxCount) {
                facebookCommentSettings.negativeKeywords = facebookCommentSettings.negativeKeywords + tagVal;
            }
        }
        var maxCount = $('#positive_edit_tags').children().length;
        for (count = 1; count <= maxCount; count++) {
            var tagSelector = "#positive_edit_tags .positive_chat_tags:nth-child(" + count + ")";
            var tagVal = $(tagSelector).attr('data-posi');
            if (count != maxCount) {
                facebookCommentSettings.positiveKeywords = facebookCommentSettings.positiveKeywords + tagVal + ',';
            }
            if (count == maxCount) {
                facebookCommentSettings.positiveKeywords = facebookCommentSettings.positiveKeywords + tagVal;
            }
        }

        maxCount = $('#positive_reaction_tags').children().length;
        for (count = 1; count <= maxCount; count++) {
            tagSelector = "#positive_reaction_tags .positive_reaction_tags:nth-child(" + count + ")";
            tagVal = $(tagSelector).attr('data-reaction');
            if (count != maxCount) {
                facebookCommentSettings.positiveReactions = facebookCommentSettings.positiveReactions + tagVal + ',';
            }
            if (count == maxCount) {
                facebookCommentSettings.positiveReactions = facebookCommentSettings.positiveReactions + tagVal;
            }
        }


        facebookCommentSettings.postId = getSelectedFBPosts();

        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'save_facebook_comment',
            'id': window.urlParams.id,
            'facebook_comment_settings': JSON.stringify(facebookCommentSettings),
        };
        jQuery.post(ajax_url, data, function (response) {
            //should be output here
            if (facebookCommentSettings.msgText.length)
                toastr.success("Your post engagement capture tool is saved.", "Saved");
            else
                toastr.warning("Your post engagement capture tool is saved, however note that you haven't specified any private reply yet, this will prevent messages from being sent.", "Saved");


        });

    });


    $(document.body).on('click', '.message_overlay', function () {
        var status = $(this).parents('.item').attr('data-selector');
        if (status === undefined) {
            status = '';
        }

        let selectedPost = $(this).parents('.item').attr('id');


        if ($('#default').attr('data-selector') != 'selected') {

            if (status === '') {
                if (selectedPost === "catch_all") disableAllPosts();
                $(this).parents('.item').attr('data-selector', 'selected');
                $(this).parents('.message').css('border', '5px solid #04D392');
            }
            else if (status === 'selected') {
                if (selectedPost === "catch_all") enableAllPosts();

                $(this).parents('.item').attr('data-selector', '');
                $(this).parents('.message').css('border', '');
            }
        }


    });

    $(document).on('keypress', '#negative_tag_value', function (event) {
        if (event.which === 13) {
            var tagValue = $('#negative_tag_value').val();
            if (tagValue == '') {
                return false;
            }
            addNegativeTag(tagValue);
        }
    });
    $(document).on('click', '#add_negative_tag', function () {
        var tagValue = $('#negative_tag_value').val();
        if (tagValue == '') {
            return false;
        }
        addNegativeTag(tagValue);
    });

    $(document).on('keypress', '#positive_tag_value', function (event) {
        if (event.which === 13) {
            var tagValue = $('#positive_tag_value').val();
            if (tagValue == '') {
                return false;
            }
            addPositiveTag(tagValue);
        }
    });
    $(document).on('click', '#add_positive_tag', function () {
        var tagValue = $('#positive_tag_value').val();
        if (tagValue == '') {
            return false;
        }
        addPositiveTag(tagValue);
    });

    $(document).on('click', '.delete_tag', function () {
        $(this).closest('.chat_tags').remove();
    });

    $(".positive > .reaction").on("click", function () {   // like click
        var data_reaction = $(this).attr("data-reaction").toLowerCase();
        var existingReactions = [];
        $('.positive_reaction_tags').each(function () {
            existingReactions.push($(this).attr("data-reaction"));
        });
        $('.negative_reaction_tags').each(function () {
            existingReactions.push($(this).attr("data-reaction"));
        });
        if (existingReactions.includes(data_reaction)) {
            return false;
        }
        var dataReactionIcon = "<span class='like-btn-" + data_reaction + "' style='display:inline;padding-right:20px;'></span>";
        var dataReactionName = "<span style='margin-left:5px;'>" + data_reaction + "</span>";
        var tagToAppend = '<span class="chat_tags positive_reaction_tags" data-reaction="' + data_reaction + '"> ' + dataReactionIcon + dataReactionName + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
        $('#positive_reaction_tags').append(tagToAppend);
        tabs.enableTabs();

    });

    $(".negative > .reaction").on("click", function () {   // like click
        var data_reaction = $(this).attr("data-reaction").toLowerCase();
        var existingReactions = [];
        $('.positive_reaction_tags').each(function () {
            existingReactions.push($(this).attr("data-reaction"));
        });
        $('.negative_reaction_tags').each(function () {
            existingReactions.push($(this).attr("data-reaction"));
        });
        if (existingReactions.includes(data_reaction)) {
            return false;
        }
        var dataReactionIcon = "<span class='like-btn-" + data_reaction + "' style='display:inline;padding-right:20px;'></span>";
        var dataReactionName = "<span style='margin-left:5px;'>" + data_reaction + "</span>";
        var tagToAppend = '<span class="chat_tags negative_reaction_tags" data-reaction="' + data_reaction + '"> ' + dataReactionIcon + dataReactionName + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
        $('#negative_reaction_tags').append(tagToAppend);
    });

    $(".msg_text").on("resize",function(){
       tabs.enableTabs();
    });

    $('#actionReplyActive').on('change', function () {
        var checkVal = $('#actionReplyActive').prop("checked");
        if (checkVal == true) {
            toastr.success("Facebook comments reply is enabled", "Success!");
            var ThisAction = "fb_comment_on";
            //FbCommentOnOff(ThisAction);
        }
        if (checkVal == false) {
            toastr.warning("Facebook comments reply is disabled", "Paused!");
            var ThisAction = "fb_comment_off";
            //FbCommentOnOff(ThisAction);

        }
    });

});

function addNegativeTag(tagValue) {
    var tag = '<span class="chat_tags negative_chat_tags" data-neg="' + tagValue + '">' + tagValue + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
    $('#negative_edit_tags').append(tag);
    $('#negative_tag_value').val('');
    tabs.enableTabs();

}

function addPositiveTag(tagValue) {
    var tag = '<span class="chat_tags positive_chat_tags" data-posi="' + tagValue + '">' + tagValue + '<span class="delete_tag"> <i class="fa icon-cross"></i></span></span>';
    $('#positive_edit_tags').append(tag);
    $('#positive_tag_value').val('');
    tabs.enableTabs();

}

function getSelectedFBPosts(){

    postId = "";

    var maxCount = $(".item[data-selector='selected']").length;
    for (count = 0; count < maxCount; count++) {
        var selectedPost = $(".item[data-selector='selected']");
        var selectedPostId = $(selectedPost[count]).attr('id');

            postId = postId + selectedPostId + ',';

    }

    return trimLastComma(postId);
}



function disableAllPosts(){
    $('.message_overlay:not(.catch-all)').each(function( index ) {
        $(this).parents('.item').attr('data-selector', '');
        $(this).parents('.message').css('border', '');
        $(this).addClass("disabled-card");

    });
}

function enableAllPosts(){
    $('.message_overlay:not(.catch-all)').each(function( index ) {
        $(this).removeClass("disabled-card");
    });
}

function  fetchFacebookPosts(type="initial") {

    if (type === "initial")

    jQuery('.scroll_widget_builder_preview').block({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Fetching page posts...</span>',
        overlayCSS: {opacity: .5}
    });

    else

    jQuery('#posts').block({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Fetching page posts...</span>',
        overlayCSS: {opacity: .5}
    });


    let ajax_url = 'includes/admin-ajax.php';

    let data = {
        'action': 'get_the_posts',
        'after': jQuery("#after_url").val(),
        'fbcid': window.urlParams.id,
    };
    jQuery.post(ajax_url, data, function (response) {

        //should be output here
        var response_arr = response.split("|", 3);
        jQuery('#wizard_select_posts').append(response_arr[1]);
        jQuery('#after_url').val(response_arr[2]);
        var chtcnt = $(".item").length;
        if (response_arr[2].length === 0 || chtcnt < 9) {
            $(".loadMore").hide();
            $('#nomoreposts').val('none');
            $('.nomoreposts').css('display', 'block');

        }
        else {
            $(".loadMore").show();
        }

        parseFBElements();

    });
}