
function pauseKeyword(keywordId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'pause_keyword',
        'keyword_id' : keywordId
    };

    return jQuery.post(ajax_url, data);

}

function activateKeyword(keywordId){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'activate_keyword',
        'keyword_id' : keywordId
    };

    return jQuery.post(ajax_url, data);

}

$(document).ready(function(){
   $(document).on('click',"[data-action='activate-keyword']",function () {
       let target = $(this);
       let keywordId = target.data("keyword-id");
       let targetElement = target.closest('tr').find("[data-target='status']");

       activateKeyword(keywordId).done(function () {
           targetElement.removeClass("paused-label").addClass("active-label").text("Active");
           target.html('Pause');
           target.attr('data-action','pause-keyword');

       })
   });

    $(document).on('click',"[data-action='pause-keyword']",function () {
        let target = $(this);
        let keywordId = target.data("keyword-id");
        let targetElement = target.closest('tr').find("[data-target='status']");
        pauseKeyword(keywordId).done(function () {
            targetElement.removeClass("active-label").addClass("paused-label").text("Paused");
            target.html('Activate');
            target.attr('data-action','activate-keyword');
        })
    })
});