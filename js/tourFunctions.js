$(document).ready(function(){

        window.fbAsyncInit = function() {
            FB.init({
                appId            : '1464624563562028',
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v4.0'
            });

            FB.Event.subscribe('send_to_messenger', function (e) {
                if (e.event=== "opt_in") {

                    $('.self_subscription').fadeOut(500, function() {
                        $(this).empty().css("margin-right","unset");

                        $(this).append("<img src='https://i.ibb.co/r399j0k/ezgif-2-fa61eb4fa144.gif' style='width:100px;' />").show();

                    });
                    $(".self_subscription").empty().css("margin-right","unset").append("<img src='https://i.ibb.co/r399j0k/ezgif-2-fa61eb4fa144.gif' style='width:100px;' />")

                    markDoneExperience("self_subscription");
                    userpilot.identify(
                        window.userId,
                    {
                        "self_subscription": true

                    }
                );
                }
            });

        };

});

function createSubscriptionButton() {
    $(".self_subscription").append('<div class="fb-send-to-messenger" ' +
        '  messenger_app_id="1464624563562028" ' +
        '  page_id="'+window.pageId+'" ' +
        '  data-ref="self_subscription_'+window.userIndex+'" ' +
        '  cta_text="SUBSCRIBE_IN_MESSENGER" ' +
        '  color="blue" ' +
        '  size="xlarge">' +
        '</div>');

        FB.XFBML.parse();


}


function markDoneExperience(experience){

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'mark_done_experience',
        'experience':experience,
    };
    return jQuery.post(ajax_url, data);

}
