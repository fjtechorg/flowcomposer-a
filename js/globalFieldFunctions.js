
function getGlobalFields(){

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_global_fields',
    };
    return jQuery.post(ajax_url, data);

}

function getGlobalFieldName(id){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_global_field_name',
        'global_field_id' : id
    };

    return jQuery.post(ajax_url, data);

}
