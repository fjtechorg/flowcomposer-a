function getIntegrations(name){

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_integrations_by_name',
        'name' : name
    };
    return jQuery.post(ajax_url, data);

}

function getIntegrationName(id){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_integration_name',
        'id' : id
    };

    return jQuery.post(ajax_url, data);

}

function getIntegrationAccountLists(id,service){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_integration_account_lists',
        'account' : id,
        'service' : service,
    };

    return jQuery.post(ajax_url, data);

}