function getProfileIdwithIndexId(profileIndexId){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'get_profile_id_with_indexid',
        'profile_index_id': profileIndexId,
    };
    jQuery.post(ajax_url, data, function(res) {
        $('#form_profile_id').val(res);
    });
}
function getProfileDetailsBlock(profile_id) {
    var ajax_url='includes/admin-ajax.php';
    var data = {'action':'get_profile_details_block',
        'profile_id': profile_id
    };
    jQuery.post(ajax_url, data, function(res) {
        $(".profile-container").html(res);
        $('[data-toggle="tooltip"]').tooltip({
            'container': 'body'
        });
    });
}
function addTagValue(profileId,tagValue){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'add_tag_profile',
        'profile_id': profileId,
        'tag':tagValue
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery("#edit_tags").append(response);
        jQuery("#tag_value").val('');

    });
}
function addEmailValue(profileId,emailValue){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'set_email',
        'profile_id': profileId,
        'email':emailValue
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery("#edit_email").html(response);
    });
}
function addCustomField(profileId,customValue,customKey,customKeyName){
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'add_customfield',
        'profile_id': profileId,
        'customKey':customKey,
        'customValue':customValue
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery('#customfield_'+response).remove();
        var responseTxt = '<div class="style_customfields_values_container" id="customfield_'+response+'"><span class="chat_tags styling_span_tags customfield_key style_customfield_key">'+customKeyName+'</span><span class="chat_tags styling_span_tags customfield_value style_customfield_value click-to-copy" data-field-val="'+customValue+'" data-toggle="tooltip" data-original-title="Click to copy">'+customValue+' </span><span class="delete_customfield style_customfields_delete" data-customfield_id="'+response+'"> <i class="fa icon-cross"></i></span></span></div>';
        jQuery("#edit_customfields").append(responseTxt);
        jQuery("#customfield_value").val('');
        $('[data-toggle="tooltip"]').tooltip({
            'container': 'body'
        });

    });
}
$(document).on('click', '#add_tag', function (){
    var tagValue = jQuery('#tag_value').val();
    var profileId = jQuery('#form_profile_id').val();
    addTagValue(profileId,tagValue);
});
$(document).on('keypress','#tag_value', function(e) {
    if (e.which === 13 && $(e.target).is('#tag_value')) {
        e.preventDefault();
        var tagValue = jQuery('#tag_value').val();
        var profileId = jQuery('#form_profile_id').val();
        addTagValue(profileId,tagValue);
    }
});
$(document).on('click', '.delete_tag', function (){
    var tag_id = $(this).data("tag_id");
    var profile_id = jQuery('#form_profile_id').val();
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'delete_tag_profile',
        'profile_id': profile_id,
        'tag_id':tag_id
    };
    jQuery.post(ajax_url, data, function() {
        jQuery('#tag_'+tag_id).remove();
    });
});
$(document).on('click', '#add_email', function (){
    var emailValue = jQuery('#email_value').val();
    var profileId = jQuery('#form_profile_id').val();
    addEmailValue(profileId,emailValue);
});
$(document).on('keypress','#email_value', function(e) {
    if (e.which === 13 && $(e.target).is('#email_value')) {
        e.preventDefault();
        var emailValue = jQuery('#email_value').val();
        var profileId = jQuery('#form_profile_id').val();
        addEmailValue(profileId,emailValue);
    }
});
$(document).on('click', '#add_customfield', function (){
    var customValue = jQuery('#customfield_value').val();
    var customKey = jQuery('#customfields_keys').val();
    var customKeyName = $( "#customfields_keys option:selected" ).text();
    var profileId = jQuery('#form_profile_id').val();
    if(customValue!='' && customKeyName !=''&& customKey !='') {
        addCustomField(profileId, customValue, customKey, customKeyName);
    }
});
$(document).on('keypress','#customfield_value', function(e) {
    if (e.which === 13 && $(e.target).is('#customfield_value')) {
        e.preventDefault();
        var customValue = jQuery('#customfield_value').val();
        var customKey = jQuery('#customfields_keys').val();
        var customKeyName = $( "#customfields_keys option:selected" ).text();
        var profileId = jQuery('#form_profile_id').val();
        if(customValue!=='' && customKeyName !==''&& customKey !='') {
            addCustomField(profileId, customValue, customKey, customKeyName);
        }
    }
});
$(document).on('click', '.delete_customfield', function (){
    var customfieldId = $(this).data("customfield_id");
    var profileId = jQuery('#form_profile_id').val();
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'delete_customfield_profile',
        'profile_id': profileId,
        'customfield_id':customfieldId
    };
    jQuery.post(ajax_url, data, function() {
        jQuery('#customfield_'+customfieldId).remove();
    });

});
$(document).on('click', '.chat_pause_span', function (){
    var profile_id = $('#form_profile_id').val();
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'set_chat_status',
        'profile_id': profile_id
    };
    jQuery.post(ajax_url, data, function(response) {
        var response_arr = response.split("|", 4);
        jQuery("#edit_pause_status").html(response_arr[0]);
        jQuery(".chat_profile_active_status").html(response_arr[1]);
        var PauseIcon = jQuery('#pause_icon');
        PauseIcon.removeClass('icon-pause-circle');
        PauseIcon.removeClass('fa-play');
        PauseIcon.addClass(response_arr[2]);
        if($('tr#profile_'+profile_id).length){
            $('tr#profile_'+profile_id+' > td:eq(3)').html(response_arr[3]);
        }
    });
});

$(document).on('click','.click-to-copy',function(){
    var text = $(this).attr('data-field-val');
    copyTextToClipboard(text);
    toastr.success('Custom field value is copied to the clipboard.', 'Success!');
});