function getCustomFields(){

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_customfields_for_segmentation',
    };
    return jQuery.post(ajax_url, data);

}

function getCustomFieldName(customFieldId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_custom_field_name',
        'custom_field_id' : customFieldId
    };

    return jQuery.post(ajax_url, data);

}
