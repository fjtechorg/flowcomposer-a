function changeReferenceFlowCard(flowid, flowcard) {


    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'Get_flow_cards',
        'flow_id': flowid
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {
            $("#select_reference_flow_card").html(response).val(flowcard);
        }


    });
}


function getFlowCards(flowId) {

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'Get_flow_cards_new',
        'flow_id': flowId
    };
    return $.post(ajax_url, data);

}

function initializeEmojiObject() {
    window.emojiPicker = new EmojiPicker({

        emojiable_selector: '[data-emojiable=true]',

        assetsPath: 'img/',

        popupButtonClasses: 'icon-smile'

    });
}

function createGreetingPreview() {
    $('#broadcast_msg_preview').html("");

    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'get_greeting_preview',
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {
            $('#broadcast_msg_preview').html(response);
        }
    });

}

function createPreviewFromObject(msgObj) {
    $('#broadcast_msg_preview').html("");
    for (i in msgObj) {
        if (!isNaN(i)) {
            thisMsgObj = JSON.parse('{' + msgObj[i] + '}');
            var previewContainer = $('#broadcast_msg_preview');
            CreatePreviewMsgElements(thisMsgObj, previewContainer, '');
        }
    }
}

function flowCardChange(referenceType, id) {
    if (referenceType === "general" || referenceType === "select") {
        return;
    }

    $("#phone_preview_button_image").show();


    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'Get_flow_preview_single',
        'msg_id': id
    };
    jQuery.post(ajax_url, data, function (response) {
        if (response !== "") {

            msgObj = JSON.parse(response);

            createPreviewFromObject(msgObj);


        }


    });
}

function createFlowPreview(referenceType, flowID, flowCardID,direct=false,container=false) {
    if (!container)
        container = "#broadcast_msg_preview";
    if (referenceType === "general") {
        createGreetingPreview();


    }
    else if (referenceType === "select") {
        $(container).html("");

    }
    else if (referenceType === "flowcard") {

        getFlowCards(flowID).done(function(messages){
            window.cardsMap = JSON.parse(messages);
            populateFlowCards("#select_reference_flow_card",messages,flowCardID);
            if (direct){
                let jsonArray = [];
                jsonArray.push(window.cardsMap[flowCardID]._json);
                buildPreviewFromJson(jsonArray,container);

            }

        });

    }

    else {

        buildFlowPreview(flowID,container);

    }
}


jQuery(document).ready(function () {


    initializeEmojiObject();

    jQuery("#select_reference_flow").on("change", function (e, data) {

        if (typeof (data) !== "undefined") {
            $("#select_reference_flow").val(data.flowId);
            createFlowPreview(data.flowType, data.flowId, data.cardToSelect);
        }

        else {
            let id = $(this, ':selected').val();
            let referenceType = $('#select_reference_type').find(":selected").val();
            let container = $('#select_reference_type').data("target");
            if (typeof container === "undefined")
                    container = false;
            createFlowPreview(referenceType, id, 0,false,container);
        }
    });

    jQuery("#select_reference_flow_card").on("change", function (e) {

        let id = $(this, ':selected').val();
        let jsonArray = [];
        let container = "#broadcast_msg_preview";
        jsonArray.push(window.cardsMap[id]._json);
        if ($(this).data('target')) {
          container =   $(this).data('target');
        }
        buildPreviewFromJson(jsonArray,container);

    });


    $(document).on('click', '.action_open_preview', function () {

        $("#open_preview").modal();
    });

    jQuery("#select_reference_type").on("change", function (e, data) {

        let id = $(this, ':selected').val();

        if (id === "general") {
            $("#welcome_preview_get_started").show();
            $("#phone_preview_button_image").hide();
            createGreetingPreview();
        }

        else if (id === "select") {
            if ($(this).data('target')) {
            $($(this).data('target')).html("");
            }
            else
                $('#broadcast_msg_preview').html("");

        }


        if (id !== "flow" && id !== "flowcard") {
            $("#select_reference_flow_container").hide();
            $("#select_reference_flow_card_container").hide();

        }


        else {
            $("#welcome_preview_get_started").hide();
            $("#phone_preview_button_image").show();

            if (id === "flow") {


                $("#select_reference_flow_container").show();
                $("#select_reference_flow_card_container").hide();


            }
            if (id === "flowcard") {
                $("#select_reference_flow_container").show();
                $("#select_reference_flow_card_container").show();
            }

            if (typeof (data) === "undefined") {
                $("#select_reference_flow").change();
            }
        }

        if (typeof tabs !== "undefined") {
            tabs.enableTabs();
        }

    });


});