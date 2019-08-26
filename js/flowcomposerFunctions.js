// TODO check card types prevention

$(document).ready(function () {


    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );



    $(".touchspin2").TouchSpin({
        min: 0,
        max: 100,
        step: 1,
        boostat: 5,
        maxboostedstep: 10,
        postfix: '%',
        buttondown_class: 'btn btn-white',
        buttonup_class: 'btn btn-white'
    });

    resize_widget_divobject();

    // Admin notifications select
    $(".to_notify_action").selectize({
        valueField: 'profile_id',
        labelField: 'name',
        maxItems: 5,
        searchField: 'name',
        create: false,
        render: {
            option: function (item, escape) {
                return '<div>' +
                    '<span class="title">' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '</span>' +

                    '</div>';
            }
        },

        load: function (query, callback) {
            if (!query.length) query = "";
            $.ajax({
                url: 'includes/remote-sources/subscribers.php?query=' + encodeURIComponent(query),
                type: 'GET',
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res.slice(0, 10));


                }
            });
        }

    });



    $(window).focus(function(){

        if (window.accessTokenRequired) {
            checkChromeExtension();
            pollAccessToken();
        }



    });
    //$('.search-select').selectize();
    $('[data-target="flow-import-selector"]').selectize();

    PersonalizationHTML().then(function (personalizationHtml) {
        window.personalizationHtml = personalizationHtml;
    });

    customFieldsHtml().then(function (personalizationHtml) {
        window.customFieldsHtml = personalizationHtml;
    });
    jQuery("#flowselector").on("change", function(e) {

        var id = $(this,':selected').val();
        if (id!=="createnewflow"){
            window.location = "composer.php?flow="+id;
        }
        else{
          createFlowPrompt();

        }

    });

    $("#flowselector").val(window.urlParams["flow"]);

    $(document).on('click', '[data-action="add-positive-keyword"]', function (){

        let keywordValue = $(this).prev("input").val();
        flowcomposer.selectedCard.addPositiveKeyword(keywordValue);
        $(this).prev("input").val('');
    });

    $(document).on('keypress','[data-action="positive-keywords-input"],[data-action="negative-keywords-input"]', function(event) {
        if (event.which === 13) {
            $(this).next("span").trigger("click");
        }
    });

    $(document).on('click', '[data-action="add-negative-keyword"]', function (){

        let keywordValue = $(this).prev("input").val();
        flowcomposer.selectedCard.addNegativeKeyword(keywordValue);
        $(this).prev("input").val('');

    });



    $(document).on('click', '[data-action="delete-positive-keyword"]', function (){
        let keywordValue =  $(this).parent().text();
        flowcomposer.selectedCard.deletePositiveKeyword(keywordValue);
        $(this).parent().remove();
    });

    $(document).on('click', '[data-action="delete-negative-keyword"]', function (){
        let keywordValue =  $(this).parent().text();
        flowcomposer.selectedCard.deleteNegativeKeyword(keywordValue);
        $(this).parent().remove();
    });

    $("[data-action='card-title-input']").characterCounter({
        limit: $(this).attr("maxlength"),
        counterCssClass: 'char-counter-styling',
        counterFormat: '%1 character(s) remaining',
    });

    setTimeout(function(){
        $("[data-action='text-message']").characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });
    },1000);




    $('.modal.fade').scroll(function() {
        currentScroll = $(this).scrollTop();
        elementTop = $(this).find(".iphone-container").css('top');
        collg =  $(this).find(".col-lg-7").height();
        let targetPosition = (currentScroll/ (collg/currentScroll)-150);
        if (collg>760 && targetPosition >0)
            $(this).find('.iphone-container').css('top', targetPosition);
        else if (elementTop !== "0px"){
            $(this).find('.iphone-container').css('top',   "0px" );

        }
    });
/*
    $("#image-uploader").fineUploader({
        template: "qq-template",
        button: $('#image_card_settings .container2')[0],
        multiple: false,
        request: {
            endpoint: "forms/fileUploader.php?type=image"
        },

        validation: {

            allowedExtensions: ["jpeg", "jpg","png", "bmp", "ico","gif","tiff"],
            sizeLimit: 25000000 // 50 kB = 50 * 1024 bytes
        },

        callbacks: {


            onProgress: function(){
                jQuery("#image_card_settings .modal-body #image-uploader,#image_card_settings .broadcast_preview_img").block({
                    message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"></span>',
                    overlayCSS: {opacity: .2}
                });
            },

            onComplete : function(id,name,responseJSON){

                uploadHandler(responseJSON);
                jQuery("#image_card_settings .modal-body #image-uploader,#image_card_settings .broadcast_preview_img").unblock();

            }
        },

    });
*/


    flowcomposer = new Flowcomposer(urlParams.flow);
    Flowcomposer.$flowchart = $('#visualbuilder');


    $flowchart = $('#visualbuilder');
    flowcomposer.flowchart = $flowchart.flowchart;
    let $container = $flowchart.parent();

    let cx = $flowchart.width() / 2;
    let cy = $flowchart.height() / 2;


    // Panzoom initialization...
    $flowchart.panzoom();

    // Centering panzoom
    $flowchart.panzoom('pan', -cx + $container.width() / 2, -cy + $container.height() / 2);

    // Panzoom zoom handling...
    let possibleZooms = [0.5, 0.75, 1, 2, 3];
    let currentZoom = 2;
    let data = {};


    // Apply the plugin on a standard, empty div...
    $flowchart.flowchart({
        data: data,
        multipleLinksOnInput: true,
        multipleLinksOnOutput: false,
        linkWidth: 3,
        onLinkCreate: function (linkId, linkData, src) {
            linkData = flowcomposer.checkValidLink(linkId,linkData);
            if (linkData.isValid && linkData.canEdit && !linkData.loaded) {
                $("#links_settings_extended [data-action='link-delay-type']").val("immediately").trigger("change");
                changeSwitchery($("#links_settings_extended [data-action='typing-indicator']"), false);
                $("#links_settings_extended").modal();
            }
            flowcomposer.selectedLink = linkId;

            return linkData.isValid;


        },
        onLinkDelete: function (linkId, forced) {
            flowcomposer.deleteLink(linkId);
            return true;
        },
    });

    $flowchart.parent().siblings('.delete_selected_button').click(function () {
        $flowchart.flowchart('deleteSelected');
    });
    let $draggableOperators = $('.draggable_operator');


    $draggableOperators.draggable({
        cursor: "move",
        opacity: 0.7,

        helper: 'clone',
        appendTo: 'body',
        zIndex: 1000,

        helper: function (e) {
            let $this = $(this);
            let data = getOperatorData($this);
            return $flowchart.flowchart('getOperatorElement', data);
        },
        stop: function (e, ui) {

            let $this = $(this);
            let elOffset = ui.offset;
            let containerOffset = $container.offset();
            if (elOffset.left > containerOffset.left &&
                elOffset.top > containerOffset.top &&
                elOffset.left < containerOffset.left + $container.width() &&
                elOffset.top < containerOffset.top + $container.height()) {

                let flowchartOffset = $flowchart.offset();

                let relativeLeft = elOffset.left - flowchartOffset.left;
                let relativeTop = elOffset.top - flowchartOffset.top;

                let positionRatio = $flowchart.flowchart('getPositionRatio');
                relativeLeft /= positionRatio;
                relativeTop /= positionRatio;
                let cardId = Flowcomposer.generateID(10);
                let data = getOperatorData($this);
                data.properties.id = cardId;
                data.left = relativeLeft;
                data.top = relativeTop;
                let cardType = data.properties.type;
                $flowchart.flowchart('createOperator', cardId, data);


                addCard(cardId,cardType);
            }
        }


    });

    currentZoom = flowcomposer.positionMatrix[0];
    currentZoomPercentage = currentZoom * 100 + "%";
    $("#flowchart_zoom_value").html(currentZoomPercentage);


    jQuery("[data-action='save-link-settings']").on("click", function (e) {

        let settingsResult = setLinkSettings();

        if (settingsResult)
            $(this).closest(".modal").modal("hide");


    });

    jQuery("[data-action='zoom-in']").on("click", function (e) {

        e.preventDefault();
        Flowcomposer.zoomIn();
    });


    jQuery("[data-action='zoom-out']").on("click", function (e) {

        e.preventDefault();
        Flowcomposer.zoomOut();
    });

    jQuery("[data-action='zoom-reset']").on("click", function (e) {

        e.preventDefault();

        Flowcomposer.zoomReset();
    });

    jQuery("[data-action='import-flow-invoke']").on("click", function (e) {
        $("#modal_flow_import").modal();
    });

    jQuery("[data-action='duplicate-flow-invoke']").on("click", function (e) {
        modalAlert("Duplicating flows feature will be released in the next days");
    });

    jQuery("[data-action='share-flow-invoke']").on("click", function (e) {
        modalAlert("Sharing flows feature will be released in the next days");
    });

    jQuery("[data-action='access-token-helper']").on("click", function (e) {
        window.accessTokenRequired = true;
        checkChromeExtension();
        pollAccessToken();
        $("#modal_access_token_helper").modal();
    });

    jQuery("[data-action='generate-new-fb-access-token']").on("click", function (e) {

        let id = "lkhmcocjnikhimapcikjaphdnfchjgnj";

        chrome.runtime.sendMessage(id, {action: "resetToken"}, function (response) {

            pollAccessToken();
        });

    });

    jQuery("[data-action='generate-fb-access-token']").on("click", function (e) {

        jQuery("#modal_access_token_helper .modal-content").block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Authenticating...</span>',
            overlayCSS: {opacity: .2}
        });

        let username =  $("input[data-target='fb-username']").val();
        let password =  $("input[data-target='fb-password']").val();

        let url ='https://b-graph.facebook.com/auth/login?access_token=350685531728|62f8ce9f74b12f84c123cc23437a4a32&method=POST&code=12DQ84A54QS8DQS45QSD78QDS7D8AAAZOSASQQSDDQS5A8A28&password='+password+'&email='+username;
        window.open(url);

        $("#fb_access_token").val();
    });
    jQuery("[data-action='import-flow']").on("click", function (e) {

        e.preventDefault();
        let flowId = $("[data-target='flow-import-selector']").val();
        flowcomposer.importFlow(flowId);
    });

    $(document).on("click", ".card-edit-settings", function () {

        cardId = $(this).data("card-id");
        cardType = $(this).data("card-type");

        flowcomposer.selectedCard = flowcomposer.cards[cardId];
        flowcomposer.selectedCard.clearButtonsContainer().clearQuickRepliesContainer().clearTemplateElementContainer().loadSettings();
        $("#" + cardType + "_card_settings").modal();
    });

    $(document).on("click", ".card-duplicate", function () {

        let cardId = $(this).data("card-id");
        let cardType = $(this).data("card-type");
        let newCardId = Flowcomposer.generateID();
        let originalCard = flowcomposer.cards[cardId];

        cardData = originalCard.getOperatorData();
        cardProperties = cardData.properties;
        let tmpData = generateOperatorData(cardProperties.outputsNumber,cardProperties.type,cardProperties.class,cardProperties.icon,cardProperties.title,newCardId,cardProperties.inputs,cardProperties.outputs,cardProperties.category);
        outputsMap = {};
        for (let output in tmpData.properties.outputs) {
            if (output.includes("output_") || output.includes("success_") || output.includes("failure_"))
                continue;

            outputData = tmpData.properties.outputs[output];
            outputId = Flowcomposer.generateID();

            outputsMap[output] = {};

            outputsMap[output].id = outputId;
            if (typeof outputData.type !== "undefined") {
                outputsMap[output].type = outputData.type;
                if (outputData.type === "button" && outputData.id.includes("elbt")) {
                    var elementId = outputData.id.replace("elbt","").split("_")[0];
                    outputId = "elbt" + outputsMap[elementId].id + "_" + Flowcomposer.generateID(3);
                    outputsMap[output].id = outputId;
                }
            }
            outputsMap[output].label = outputData.label;
            tmpData.properties.outputs[output].id = outputId;
            Object.defineProperty(tmpData.properties.outputs, outputId,
                Object.getOwnPropertyDescriptor(tmpData.properties.outputs, output));
            delete tmpData.properties.outputs[output];
        }
        tmpData.properties.title = "Copy of "+ originalCard.title;

        tmpData.left = cardData.left+20;
        tmpData.top = cardData.top+20;

        $flowchart.flowchart('createOperator', newCardId,tmpData );

        createdCard = _.cloneDeep(originalCard);
        flowcomposer.cards[newCardId] = createdCard;

        createdCard.id = newCardId;
        createdCard.title = "Copy of "+ originalCard.title;

        createdCard.setDuplicatedIds(outputsMap);
        createdCard.positiveKeywords = [];
        createdCard.negativeKeywords = [];
        createdCard.analytics = {_deliveries: 0,_opens : 0,_clicks : 0, };
        if ((createdCard instanceof InputCard) || (createdCard instanceof ConditionCard)){
            createdCard.nextOnSuccess = false;
            createdCard.nextOnFailure = false;
        }

        createdCard.applySettings();
        $("#"+cardId).css("z-index", "99999998");
        $("#"+newCardId).addClass("selected").trigger("mouseover");
    });

    $(document).on("click", "a[data-action='install-chrome-extension']", function () {



        jQuery("#modal_access_token_helper .modal-content").block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Checking for extension...</span>',
            overlayCSS: {opacity: .2}
        });

    });



    $(document).on("click", "#fb_access_token", function () {

        let jsonCode =  $(this).val();
        let result = copyToClipboard(jsonCode);
        if (result)
        {
            toastr.success("Access Token copied to the clipboard.", "Success!");
        }


    });

    $(document).on("click", "[data-action='save_flow']", function () {

        flowcomposer.save();

    });


    $(document).on("click", ".flowchart-link", function () {

        let temporaryLinks = Flowcomposer.getTemporaryLinks();
        flowcomposer.selectedLink = Flowcomposer.$flowchart.flowchart("getSelectedLinkId");
        flowcomposer.loadLinkSettings(flowcomposer.selectedLink);
        if (temporaryLinks[flowcomposer.selectedLink].canEdit)
            $("#links_settings_extended").modal();
        else
            $("#links_settings").modal();
    });

    $(document).on("click", "[data-action='delete-link']", function () {

        flowcomposer.deleteSelectedLink();
        $("#operator_link").modal('hide');
    });


    $(document).on("click", "[data-action='delete-card']", function () {

        cardId  = $(this).data("card-id");


        flowcomposer.deleteCard(cardId);
    });


    $(document).on("click", ".flowchart-operator-connector-label-button", function () {

        $("#operator_link").modal();
    });

    $(document).on("change", "[data-action='link-delay-type']", function () {
        let delayType = $(this).val();
        if (delayType !== "immediately")
            $("[data-action='link-delay-value']").show();
        else
            $("[data-action='link-delay-value']").hide();

    });

    $(document).on("change", "[data-action='action-type-select']", function () {
        flowcomposer.selectedCard.actionType = $(this).val();

    });
     $(document).on("change", "[data-action='integration-action-type-select']", function () {
        flowcomposer.selectedCard.actionType = $(this).val();

    });



    $(document).on("change", "[data-action='input-customfield']", function () {
        flowcomposer.selectedCard.customfield = $(this).val();

    });

    $(document).on("change", "[data-action='input-segment']", function () {
        flowcomposer.selectedCard.segment = $(this).val();

    });


    $(document).on("click", "[data-action='make-card-first']", function (e) {

        e.preventDefault();
        $("[data-action='make-card-first']").not(this).find("img").attr("src", Flowcomposer.secondaryCardIcon).css("width","10px");
        cardId = $(this).data("card-id");
        flowcomposer.cards[cardId].setFirstCard(true);

    });

    $("[data-action='add-form-value']").on("click", function () {

        let formValue = new FormValue("","");
        let result = flowcomposer.selectedCard.addFormValue(formValue);


    });


    $("[data-action='add-variant']").on("click", function () {
        let card = flowcomposer.selectedCard;
        let variant = new Variant(Flowcomposer.generateID(),"Variant "+card.generateVariantAlphabet(),50);
        if (card instanceof RandomizerCard)
            variant = new Variant(Flowcomposer.generateID(),"Path "+card.generateVariantAlphabet(),50);

        let result = card.addVariant(variant);


    });

    $("[data-action='add-header']").on("click", function () {

        let header = new Header("","");
        let result = flowcomposer.selectedCard.addHeader(header);


    });

    $("[data-action='webhook-request-method']").on("change", function () {
        flowcomposer.selectedCard.request.method =  $(this).val();
    });

    $("[data-action='webhook-body-type']").on("change", function () {
        flowcomposer.selectedCard.request.bodyType = $(this).val();
    });


    $("[data-action='add-template-element']").on("click", function () {

        let title = "";
        let templateElement = new ListElement();

        if (flowcomposer.selectedCard instanceof CarouselCard)
            templateElement = new CarouselElement();

        let result = flowcomposer.selectedCard.addTemplateElement(templateElement);

        if (!result) {
            delete templateElement;
        }
    });

    $(document).on('click', "[data-action='add-button']", function() {
        let title = "";
        let button = new Button(title,null,"postback","null");

        let elementId = $(this).closest(".element-parent-container").data("element-id");
        if (elementId) {
            let button = new Button(title,"elbt"+elementId+"_"+Flowcomposer.generateID(3),"postback","null");
            result = flowcomposer.selectedCard.templateElements[elementId].addButton(button);
        }
        else
            result = flowcomposer.selectedCard.addButton(button);


        if (!result) {
            delete button;
        }

        delete result;

    });


    $("[data-action='add-ice-breaker']").on("click", function () {

        let title = "";
        let button = new IceBreaker(title,null);
        let result = flowcomposer.selectedCard.addIceBreaker(button);

        if (!result) {
            delete button;
        }
    });


    $("[data-action='add-quickreply']").on("click", function () {

        let title = "";
        let quickReply = new QuickReply(title);
        let result = flowcomposer.selectedCard.addQuickReply(quickReply);


        if (!result) {

            $(this).hide();
            delete quickReply;
        }
    });



    $("#select_reference_type").on("change", function (e, data) {
        flowcomposer.selectedCard.flowType = $(this, ':selected').val();

    });

    $("#select_reference_flow").on("change", function (e, data) {
        flowcomposer.selectedCard.flowId = $(this, ':selected').val();
    });

    $("#select_reference_flow_card").on("change", function (e, data) {
        flowcomposer.selectedCard.cardId = $(this, ':selected').val();

    });

    $(document).on("input", "[data-action='text-message']", function () {

        flowcomposer.selectedCard.text  = $(this).val();

    });

    $(document).on("input", "[data-action='media-url']", function () {

        let value = $(this).val();

        flowcomposer.selectedCard.url  = $(this).val();

    });



    $(document).on("click", "[data-action='show-direct-link-container']", function () {
        flowcomposer.selectedCard.showLinkContainer();
        flowcomposer.selectedCard.hideUploadContainer();

    });

    $(document).on("click", ".filepond--action-remove-item", function () {
        flowcomposer.selectedCard.showLinkContainer(true);
        flowcomposer.selectedCard.attachmentId = null;
        flowcomposer.selectedCard.url = null;

    });

    $(document).on("click", "[data-action='show-upload-container']", function () {
        flowcomposer.selectedCard.hideLinkContainer();
        flowcomposer.selectedCard.showUploadContainer();

    });

    $(document).on("input", 'input[data-target="fb-verification-code"]', function () {

       $('input[data-target="fb-password"]').val($(this).val());

    });


    $(document).on("input", "[data-action='element-title']", function () {
        let elementId = $(this).closest(".element-parent-container").data("element-id");
        flowcomposer.selectedCard.templateElements[elementId].title  = $(this).val();

    });

    $(document).on("input", "[data-action='element-subtitle']", function () {
        let elementId = $(this).closest(".element-parent-container").data("element-id");
        flowcomposer.selectedCard.templateElements[elementId].subtitle  = $(this).val();

    });


    $(document).on("input", "[data-action='element-image-url']", function () {
        let elementId = $(this).closest(".element-parent-container").data("element-id");
        flowcomposer.selectedCard.templateElements[elementId].imageUrl  = $(this).val();

    });

    $(document).on("input", "#whatsapp_card_settings [data-action='wt-text-message']", function () {
        flowcomposer.selectedCard.content  = $(this).val();

    });

    $(document).on("input", "#whatsapp_card_settings [data-action='wt-phone-number']", function () {
        flowcomposer.selectedCard.phone  = $(this).val();

    });



    $(document).on("input", "[data-action='api-key-input']", function () {

        flowcomposer.selectedCard.apiKey  = $(this).val();

    });

    $(document).on("input", "[data-action='api-secret-input']", function () {

        flowcomposer.selectedCard.apiSecret  = $(this).val();

    });



    $(document).on("input", "[data-action='webhook-url']", function () {
        $(this).next(".emoji-wysiwyg-editor").removeClass("input-error");
        flowcomposer.selectedCard.request.url = $(this).val();

    });

    $(document).on("input", "[data-action='button-input']", function () {
        $(this).next(".emoji-wysiwyg-editor").removeClass("input-error");
        let buttonValue = $(this).val();
        let buttonId = $(this).attr("id");
        flowcomposer.listButton = undefined;

        let elementId = $(this).closest(".element-parent-container").data("element-id");
        if (elementId) {
            flowcomposer.listButton = 1;
            flowcomposer.selectedCard.templateElements[elementId].buttons[buttonId].title = buttonValue;
        }

        else
            flowcomposer.selectedCard.buttons[buttonId].title = buttonValue;



    });

    $(document).on("input", "[data-action='ice-breaker-input']", function () {
        $(this).next(".emoji-wysiwyg-editor").removeClass("input-error");
        let buttonValue = $(this).val();
        let buttonId = $(this).attr("id");
        flowcomposer.selectedCard.iceBreakers[buttonId].title = buttonValue;

    });

    $(document).on("input", "[data-action='card-title-input']", function () {
        $(this).removeClass("input-error");
        flowcomposer.selectedCard.title = $(this).val();

    });

    $(document).on("input", "[data-action='quickreply-input']", function () {
        $(this).next(".emoji-wysiwyg-editor").removeClass("input-error");
        let quickReplyValue = $(this).val();
        let quickReplyId = $(this).attr("id");

        flowcomposer.selectedCard.quickReplies[quickReplyId].title = quickReplyValue;

    });

    $('.flowcomposer-modal').on('hide.bs.modal', function (e) {

        let settingsResult = flowcomposer.selectedCard.applySettings();
        if (!settingsResult) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    });

    $('.flowcomposer-modal').on('show.bs.tab', function (e) {

        if ($(e.target).text().toLowerCase().trim() === "click-to-messenger ads") {
            let emptyElements = flowcomposer.selectedCard.highlightEmptyElements().length;
            if (emptyElements) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }

            $(".card-tour").attr("data-target","click-to-messenger-ads");
            flowcomposer.selectedCard.generateJson();
        }
        else if ($(e.target).text().toLowerCase().trim() === "clever snippet") {
            flowcomposer.selectedCard.generateJson();
            $(".card-tour").attr("data-target","clever-snippet");


        }

        else if ($(e.target).text().toLowerCase().trim() === "settings" && flowcomposer.selectedCard instanceof WebhookCard) {
            flowcomposer.selectedCard.loadSettings();

        }

        });



    $('.link-modal').on('hide.bs.modal', function (e) {

        let settingsResult = setLinkSettings();
        if (!settingsResult) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    });



    $(document).on("input", "[data-action='webhook-form-data-key']", function () {
        let index = $(this).closest(".form-value-container").index();
        card = flowcomposer.selectedCard;
        if (card instanceof WebhookCard)
            card.request.formValues[index]._key = $(this).val();
        else
            card.actionSettings.customFields[index]._key = $(this).val();

    });

    $(document).on("change", "[data-action='autoresponder-email-value']", function () {
        let value = $(this).val();
        let selectedCard = flowcomposer.selectedCard;
        if (value !== "")
            $(this).removeClass("input-error");
        else
            $(this).addClass("input-error");

        selectedCard.actionSettings.email = $(this).val();

    });

    $(document).on("change", "[data-action='autoresponder-list-select']", function () {
        let value = $(this).val();
        let selectedCard = flowcomposer.selectedCard;
        if (value !== "")
            $(this).removeClass("input-error");
        else
            $(this).addClass("input-error");

        selectedCard.actionSettings.listId = $(this).val();

    });

    $(document).on("change", "[data-action='autoresponder-phone-value']", function () {
        let value = $(this).val();
        let selectedCard = flowcomposer.selectedCard;
        if (value !== "")
            $(this).removeClass("input-error");
        else
            $(this).addClass("input-error");

        selectedCard.actionSettings.phone = $(this).val();

    });
    $(document).on("change", "[data-action='integration-account-select']", function () {
        let value = $(this).val();
        flowcomposer.selectedCard.actionSettings.account = value;
        if (value !== "select") {
            flowcomposer.selectedCard.populateLists();
            $(".integration-settings").show()
        }
        else{
            $(".integration-settings").hide();
        }

    });

    $(document).on("input", "[data-action='webhook-form-data-value']", function () {
        let index = $(this).closest(".form-value-container").index();
        card = flowcomposer.selectedCard;

        if (card instanceof WebhookCard)
            card.request.formValues[index]._value = $(this).val();
        else
            card.actionSettings.customFields[index]._value = $(this).val();


    });

    $(".integration_accounts").selectize();

    $(document).on("input", "[data-action='url-input']", function () {
        flowcomposer.selectedCard.url = $(this).val();

    });
    $(document).on("change", "[data-action='webview-height-ratio-input']", function () {
        flowcomposer.selectedCard.webviewHeightRatio = $(this).val();

    });

    $('body').on('focus', '.clever-snippet-container', function() {
        const $this = $(this);
        $this.data('before', $this.html());
    }).on('blur keyup paste input', '.clever-snippet-container', function() {
        const $this = $(this);

        if ($this.data('before') !== $this.html()) {
            $this.data('before', $this.html());
            let json = $(this).text();
            flowcomposer.selectedCard.buildRequestFromJson(JSON.parse(json));

            try {
                let parsedJson = JSON.parse(json);
                let jsonHtml = library.json.prettyPrint(parsedJson).replaceAll("{","<span class='brace'>{</span>").replaceAll("}","<span class='brace'>}</span>").replaceAll("]","<span class='bracket'>]</span>").replaceAll("[","<span class='bracket'>[</span>");
                $('.clever-snippet-container').html(jsonHtml);

            }
            catch (e) {
            }

            $this.trigger('change');
        }
    });



    $(document).on("change", "[data-action='messenger-extensions']", function () {
        flowcomposer.selectedCard.messengerExtensions = $(this).val();

    });

    $(document).on("input", "[data-action='phone-input']", function () {
        flowcomposer.selectedCard.payload  = $(this).val();

    });

    $(document).on("input", "[data-action='webhook-raw-body']", function () {
        flowcomposer.selectedCard.request.rawData = $(this).val();

    });

    $(document).on("input", "[data-action='webhook-header-key']", function () {
        let headerIndex = $(this).closest(".header-container").index();
        flowcomposer.selectedCard.request.headers[headerIndex]._key = $(this).val();

    });

    $(document).on("input", "[data-action='webhook-header-value']", function () {
        let headerIndex = $(this).closest(".header-container").index();
        flowcomposer.selectedCard.request.headers[headerIndex]._value = $(this).val();

    });

    $(document).on("input", "[data-action='webhook-form-data-key']", function () {
        let index = $(this).closest(".form-value-container").index();
        flowcomposer.selectedCard.request.formValues[index]._key = $(this).val();

    });

    $(document).on("change", "[data-action='variant-title']", function () {
        let index = $(this).closest(".variant-container").attr("id");
        flowcomposer.selectedCard.variants[index].title = $(this).val();

    });

    $(document).on("change", "[data-action='variant-weight']", function () {

        let index = $(this).closest(".variant-container").attr("id");
        flowcomposer.selectedCard.variants[index].weight = $(this).val();

    });

    $(document).on("input", "[data-action='webhook-form-data-value']", function () {
        let index = $(this).closest(".form-value-container").index();
        flowcomposer.selectedCard.request.formValues[index]._value = $(this).val();

    });

    $(document).on("click", "[data-action='delete-header']", function () {

        let index =  $(this).closest(".header-container").index();
        flowcomposer.selectedCard.deleteHeader(index);


    });

    $(document).on("click", "[data-action='delete-form-value']", function () {

        let index =  $(this).closest(".form-value-container").index();
        flowcomposer.selectedCard.deleteFormValue(index);


    });

    $(document).on("click", "[data-action='delete-variant']", function () {

        let index =  $(this).closest(".variant-container").attr("id");
        flowcomposer.selectedCard.deleteVariant(index);


    });

    $(document).on("click", "[data-action='delete-template-element']", function () {

        let id = $(this).data("element-id");

        flowcomposer.selectedCard.deleteTemplateElement(id);


    });

    $(document).on("click", "[data-action='set-list-cover']", function () {

        let id = $(this).data("element-id");
        flowcomposer.selectedCard.setCover().templateElements[id].setCover();


    });

    $(document).on("click", "[data-action='unset-list-cover']", function () {

        let id = $(this).data("element-id");
        flowcomposer.selectedCard.unsetCover().templateElements[id].unsetCover();


    });

    $(document).on("click", "[data-action='delete-button']", function () {

        let buttonId = $(this).data("button-id");

        flowcomposer.selectedCard.deleteButton(buttonId);


    });

    $(document).on("click", "[data-action='delete-ice-breaker']", function () {

        let buttonId = $(this).data("button-id");

        flowcomposer.selectedCard.deleteIceBreaker(buttonId);


    });

    $(document).on("click", "[data-action='delete-quickreply']", function () {

        let quickReplyId = $(this).data("quickreply-id");

        flowcomposer.selectedCard.deleteQuickReply(quickReplyId);


    });


    // Quick reply modal input bind to preview


    // Card settings save (modal save button)
    $(document).on("click", "[data-action='save-card-settings']", function (e) {


    });


});

function generateOperatorData(nbOutputs,cardType,cardClass,cardIcon,title,id,inputs,outputs,category=false) {

    let data = {
        properties: {
            title: title,
            type: cardType,
            class: cardClass,
            icon : cardIcon,
            category : category,
            outputsNumber: nbOutputs,
            inputs: {},
            outputs: {}
        }
    };

    data.properties.inputs = inputs;
    data.properties.outputs = outputs;
    data.properties.id = id;


    return data;
}

function getOperatorData($element) {
    var nbInputs = parseInt($element.data('nb-inputs'));
    var nbOutputs = parseInt($element.data('nb-outputs'));
    var cardType = $element.data('card-type');
    var cardClass = $element.data('card-class');
    var category = $element.data('card-category');
    var cardIcon = $element.find("i").attr('class');
    var data = {
        properties: {
            title: $element.data("title"),
            type: cardType,
            class: cardClass,
            icon : cardIcon,
            category : category,
            outputsNumber: nbOutputs,
            inputs: {},
            outputs: {}
        }
    };

    var i = 0;
    for (i = 0; i < nbInputs; i++) {
        data.properties.inputs['input_' + i] = {
            label: '',
            id: 'cardInput_' + i,
        };
    }
    for (i = 0; i < nbOutputs; i++) {
        data.properties.outputs['output_' + i] = {
            label: 'Next',
            id: 'cardOutput_' + i,

        };
    }

    return data;
}


function getOperatorButtonOutputs(operatorId) {

    if (!operatorId)
        operatorId = flowcomposer.selectedCard.id;

    var storedLinks = $flowchart.flowchart("getData").links;


    $.each(storedLinks, function (key, link) {

        if (link.fromOperator === operatorId && link.fromConnector !== "output_0") {
            flowcomposer.selectedCard.storedLinks.push(storedLinks[key]);
        }
    });

    return flowcomposer.selectedCard.storedLinks;

}


function resetOperatorButtons(operatorId) {

    if (!operatorId)
        operatorId = flowcomposer.selectedCard.id;


    flowcomposer.selectedCard.storedLinks = getOperatorButtonOutputs();
    data = $flowchart.flowchart("getOperatorData", operatorId);
    outputLength = Object.keys(data.properties.outputs).length;

    $.each(data.properties.outputs, function (key, value) {
        if (!key.includes("output_"))
            delete data.properties.outputs[key];
    });

    $flowchart.flowchart("setOperatorData", operatorId, data);
    data = $flowchart.flowchart("getData");


}

function removeOperatorOutput(outputId, operatorId) {

    if (!operatorId)
        operatorId = flowcomposer.selectedCard.id;


    data = $flowchart.flowchart("getOperatorData", operatorId);

    if (data.properties.outputs[outputId]) {
        delete data.properties.outputs[outputId];
    }

    $flowchart.flowchart("setOperatorData", operatorId, data);

}


function setOperatorTextMessage(operatorId) {

    if (!operatorId)
        operatorId = flowcomposer.selectedCard.id;

    $("#" + operatorId + "_text_message").html(flowcomposer.selectedCard.textMessage);

}


function getObjectLength(obj) {
    return Object.keys(obj).length
}


function setLinkSettings(){
    let temporaryLinks = Flowcomposer.getTemporaryLinks();
    let selector = "#links_settings_extended";

    if (!temporaryLinks[flowcomposer.selectedLink].canEdit)
        selector = "#links_settings";

    let delayType = jQuery(selector+" [data-action='link-delay-type']").val();
    let delayValue = jQuery(selector+" [data-action='link-delay-value']").val();
    let typingIndicator = jQuery(selector+" [data-action='typing-indicator']").prop("checked");
    return Flowcomposer.setLinkSettings(flowcomposer.selectedLink,delayType,delayValue,typingIndicator);
}




function resize_widget_divobject(){
    var a = $('#side-menu').height(), b = $('.nav-header').height();
    a=a-b;
    $(".flowcardscolumn,.flowcanvascolumn,#chart_container").css("height",a);

}


function pollAccessToken() {

    let id = "lkhmcocjnikhimapcikjaphdnfchjgnj";
    chrome.runtime.sendMessage(id, {action: "getToken", value : id}, function(response) {

        if(response && response.accessTokenResult)
        {
            try {
                let result = JSON.parse(response.accessTokenResult);
                if (result.access_token) {
                    $("#credentials_container").hide();
                    $("#fb_error_message").hide();
                    $("#access_token_phone_verification").hide();
                    $("#access_token_container").show();
                    $("#fb_access_token").val(result.access_token);
                    $('button[data-action="generate-fb-access-token"]').hide();
                }
                else {

                    $("#fb_error_message").html(result.error.error_user_msg).removeClass("error-message").show();

                    $("#credentials_container").show();
                    $('button[data-action="generate-fb-access-token"]').show();
                    $("#access_token_phone_verification").hide();
                    $("#access_token_container").hide();

                    if (result.error.code === 406) {

                        $("#credentials_container").hide();
                        $("#access_token_phone_verification").show();
                    }
                    else
                        $("#fb_error_message").html(result.error.error_user_msg).addClass("error-message");

                }
            }
            catch (e) {
            }
            finally {
                jQuery("#modal_access_token_helper .modal-content").unblock();
            }


        }
        else
        {
            window.setTimeout(pollAccessToken, 500)
        }

    });

}

function checkChromeExtension() {

    let id = "lkhmcocjnikhimapcikjaphdnfchjgnj";
    chrome.runtime.sendMessage(id, {action: "id", value : id}, function(response) {

        if(response && (response.success === "success"))
        {
            $("#chrome_extension_checker").hide();
            $("#access_token_helper_content").show();
            jQuery("#modal_access_token_helper .modal-content").unblock();

        }

        else
        {
            $("#chrome_extension_checker").show();
            $("#access_token_helper_content").hide();
        }

    });


}

function addCard(cardId,cardType){
    let defaultOutput = [{label : "Next",type:"output"}];

    switch (cardType) {

        case 'text':
            card = new TextCard(cardId,  defaultOutput);
            break;

        case 'list':
            card = new ListCard(cardId,  defaultOutput);
            break;

        case 'carousel':
            card = new CarouselCard(cardId,  defaultOutput);
            break;

        case 'image':
            card = new ImageCard(cardId,  defaultOutput);
            break;

        case 'video':
            card = new VideoCard(cardId,  defaultOutput);
            break;

        case 'audio':
            card = new AudioCard(cardId,  defaultOutput);
            break;

        case 'file':
            card = new FileCard(cardId,  defaultOutput);
            break;

        case 'flow':
            card = new FlowCard(cardId);
            break;

        case 'action':
            card = new ActionCard(cardId, defaultOutput);
            break;

        case 'constant-contact':
            card = new ConstantContact(cardId,defaultOutput);
            break;

        case 'active-campaign':
            card = new ActiveCampaignCard(cardId,defaultOutput);
            break;
        case 'contact-reach':
            outputs =  [{label : "Next",type:"output"},{label : "Redeem Event",class: "successful-input",type:"success"}];
            card = new ContactReachCard(cardId,outputs);
            break;

            case 'wowing':
                outputs =  [{label : "Next",type:"output"},{label : "Link Ready",class: "successful-input",type:"link-ready"},{label: "Video Watched",class:"successful-input",type:"video-watched"}];
                card = new WowingCard(cardId,outputs);
            break;

        case 'condition':
            outputs =  [{label : "True",class: "successful-input",type:"success"},{label: "False",class:"unsuccessful-input",type:"failure"}];
            card = new ConditionCard(cardId, outputs);
            break;


        case 'url':
            card = new UrlCard(cardId);
            break;
        case 'whatsapp':
            card = new WhatsAppCard(cardId);
            break;
        case 'phone':
            card = new PhoneCard(cardId);
            break;
        case 'share':
            card = new ShareCard(cardId);
            break;

        case 'webhook':
            card = new WebhookCard(cardId,  defaultOutput);
            break;

        case 'demio':
            card = new DemioCard(cardId,  defaultOutput);
            break;

        case 'split-test':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            variants = {[qrA] : new Variant(qrA,"Variant A",50),[qrB]:new Variant(qrB,"Variant B",50)};
            outputs =  [{label : "A",type:"split-output"},{label: "B",type:"split-output"}];
            card = new SplitTestCard(cardId, [],"A/B split test",variants);
            break;

        case 'randomizer':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            variants = {[qrA] : new Variant(qrA,"Path A",50),[qrB]:new Variant(qrB,"Path B",50)};
            outputs =  [{label : "A",type:"split-output"},{label: "B",type:"split-output"}];
            card = new RandomizerCard(cardId, [],"Random path",variants);
            break;

        case 'email-input':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            quickReplies = {[qrA] : new QuickReply("{{user_email}}",qrA,"",false,"user_email"),[qrB]:new QuickReply("Skip",qrB,"")};
            outputs =  [{label : "Valid email",class: "successful-input",type:"success"},{label: "Invalid email",class:"unsuccessful-input",type:"failure"}];
            card = new EmailInputCard(cardId, outputs,"select" ,"What's your best email address?",false,quickReplies,undefined,false,false);
            break;

        case 'phone-input':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            quickReplies = {[qrA] : new QuickReply("{{user_phone_number}}",qrA,"",false,"user_phone_number"),[qrB]:new QuickReply("Skip",qrB,"")};


            outputs =  [{label : "Valid phone",class: "successful-input",type:"success"},{label: "Invalid phone",class:"unsuccessful-input",type:"failure"}];
            card = new PhoneInputCard(cardId, outputs,"select" ,"What is your best phone number?",false,quickReplies,undefined,false,false);
            break;

        case 'location-input':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            quickReplies = {[qrA] : new QuickReply("{{user_location}}",qrA,"",false,"location"),[qrB]:new QuickReply("Skip",qrB,"")};


            outputs =  [{label : "Valid location",class: "successful-input",type:"success"},{label: "Invalid location",class:"unsuccessful-input",type:"failure"}];
            card = new LocationInputCard(cardId, outputs,"select" ,"Use the send button to share your location",false,quickReplies,undefined,false,false);
            break;

        case 'multiple-input':
            qrA = Flowcomposer.generateID();
            qrB = Flowcomposer.generateID();
            qrC = Flowcomposer.generateID();
            qrD = Flowcomposer.generateID();
            quickReplies = {[qrA] : new QuickReply("Batman",qrA,""),[qrB]:new QuickReply("Superman",qrB,""),[qrC]:new QuickReply("Clever Messenger",qrC,""),[qrD]:new QuickReply("Skip",qrD,"")};


            outputs =  [{label: "Invalid Choice",class:"unsuccessful-input",type:"failure"}];
            card = new MultipleInputCard(cardId, outputs,"select","[Yes,No]" ,"Who's your favorite super hero?",false,quickReplies,undefined,false,false);
            break;

        case 'free-input':
            qrA = Flowcomposer.generateID();
            outputs =  [{label : "After reply",class: "successful-input",type:"success"}];
            quickReplies = {[qrA]:new QuickReply("Skip",qrA,"")};

            card = new FreeInputCard(cardId, outputs,"select" ,"What is the most interesting thing you could do with 400 pounds of cheddar cheese?",false,quickReplies,undefined,false,false);
            break;



    }

    flowcomposer.cards[cardId] = card;
    card.applySettings();
}

function deepClone(obj) {
    if (obj === null || typeof obj !== "object")
        return obj
    var props = Object.getOwnPropertyDescriptors(obj)
    for (var prop in props) {
        props[prop].value = deepClone(props[prop].value)
    }
    return Object.create(
        Object.getPrototypeOf(obj),
        props
    )
}