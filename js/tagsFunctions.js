function getPageTags(){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_page_tags',
    };

    return jQuery.post(ajax_url, data);
}

function getTagName(tagId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_tag_name',
        'tag_id' : tagId
    };

    return jQuery.post(ajax_url, data);

}

function createTag(tag){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'create_tag',
        'tag' : tag
    };

    return jQuery.post(ajax_url, data);

}