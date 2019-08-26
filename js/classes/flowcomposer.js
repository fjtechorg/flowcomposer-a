
class Flowcomposer {


    constructor(flowId) {

        this._cards = {};
        this._selectedCard = {};
        this._flowchart = {};
        this._selectedLink = -1;
        this._flowId = flowId;
        this._typesCount = {};
        this._links = {};
        this._positionMatrix = {};
        this._firstCard = false;
        this._disconnectedButtons = {};
        this._deletedCards = [];
        if (window.urlParams["reset"])
            this.load(true);
        else
            this.load();
    }


    get disconnectedButtons() {
        return this._disconnectedButtons;
    }

    set disconnectedButtons(value) {
        this._disconnectedButtons = value;
    }

    get firstCard() {
        return this._firstCard;
    }

    set firstCard(value) {
        this._firstCard = value;
    }

    get positionMatrix() {
        return this._positionMatrix;
    }

    set positionMatrix(value) {
        this._positionMatrix = value;
        Flowcomposer.$flowchart.panzoom("setMatrix", value);
        Flowcomposer.$flowchart.flowchart('setPositionRatio', Flowcomposer.currentZoom());

    }

    static getConnectedCards(id){
        let connectedCards = [];
        let links = Flowcomposer.getTemporaryLinks();
        for (let linkId in links){
            if (links[linkId].toOperator === id){
                let cardId = links[linkId].fromOperator;
                let buttonId = links[linkId].fromConnector;
                let connectedCard ={"card_id" : cardId, "button_id" : buttonId};
                connectedCards.push(connectedCard);
            }
        }

        return connectedCards;
    }


    static getAllConnectedCards(id,connectedCardsAll){
        let connectedCards = Flowcomposer.getConnectedCards(id);
        console.log(connectedCards);
        console.log(connectedCardsAll);
        if (Object.keys(connectedCards).length) {
            for (let i=0;i<connectedCards.length;i++) {
                console.log(i);
                connectedCardsAll.push(connectedCards[i].card_id);
                 Flowcomposer.getAllConnectedCards(connectedCards[i].card_id,connectedCardsAll);
            }
        }
        console.log(connectedCardsAll);
        return connectedCardsAll;
    }

    static currentZoom(){
        return  Flowcomposer.$flowchart.panzoom("getMatrix")[0];

    }


   static splitTestColors(){
        return  ["#ff25c6","#31bdd4","#6486d4","#69bf6f","#ad4a4a","#f99313","#a25bff","#063c6b","#b4b96a","#ff1414"];

    }

    static zoomIn(){
        Flowcomposer.$flowchart.panzoom("zoom");
        Flowcomposer.$flowchart.flowchart('setPositionRatio', Flowcomposer.currentZoom());
    }

    static zoomOut(){
        Flowcomposer.$flowchart.panzoom("zoom", true);
        Flowcomposer.$flowchart.flowchart('setPositionRatio', Flowcomposer.currentZoom());
    }

    static zoomReset(){
        Flowcomposer.$flowchart.panzoom("zoom", 0.1);
        Flowcomposer.$flowchart.flowchart('setPositionRatio', Flowcomposer.currentZoom());
    }

    static fullScreen(){
        $(document).toggleFullScreen();

    }

    static detectLoop(from,to){
        let links = Flowcomposer.getTemporaryLinks();

        while (typeof links[to] !== "undefined" && typeof links[to].toOperator !== "undefined"){

            if (links[to].toOperator === from && links[to].delayType === "immediately" ){

                return false;
            }
            else
                to = links[to].toOperator;
        }
        return true;
    }

    get links() {
        return this._links;
    }

    set links(value) {
        this._links = value;
    }

    get typesCount() {
        return this._typesCount;
    }

    set typesCount(value) {
        this._typesCount = value;
    }

    get flowId() {
        return this._flowId;
    }

    set flowId(value) {
        this._flowId = value;
    }

    static getTemporaryLinks(){
        return Flowcomposer.$flowchart.flowchart("getData").links;
    }

    static getTemporaryLinksToCard(cardId,type){
        let links = Flowcomposer.$flowchart.flowchart("getData").links;
        let temporaryLinks = [];
        for (let linkId in links){
            if (links[linkId].toOperator === cardId && links[linkId].type === type)
                temporaryLinks.push(links[linkId]);
        }

        return temporaryLinks;
    }

    static getTemporaryLinksFromCard(cardId,type){
        let links = Flowcomposer.$flowchart.flowchart("getData").links;
        let temporaryLinks = {};
        for (let linkId in links){
            if (links[linkId].fromOperator === cardId && links[linkId].type === type)
                temporaryLinks.push(links[linkId]);
        }

        return temporaryLinks;
    }


    static getFlowchartData(){
        return Flowcomposer.$flowchart.flowchart("getData");
    }

    static setFlowchartData(data){
        return Flowcomposer.$flowchart.flowchart("setData",data);
    }


    uploadHandler(responseJSON){
        try {
            if (typeof responseJSON.cdn !== "undefined") {
                this.selectedCard.url = "https://" + responseJSON.cdn.hostname + responseJSON.cdn.cdn_file_path;
                this.selectedCard.attachmentId = responseJSON.attachment_id;
                this.selectedCard.generateJson();
                this.setPreview();

            }
        }
        catch (e) {

        }
    }

    loadLinkSettings(linkId){

        let data = Flowcomposer.getFlowchartData();
        let links = data.links;
        let link = links[linkId];

        $("#links_settings_extended [data-action='link-delay-value']").removeClass("input-error");

        let selector = "#links_settings_extended";

        if (!link.canEdit)
            selector = "#links_settings";


        jQuery(selector+" [data-action='link-delay-type']").val(link.delayType).trigger("change");
        jQuery(selector+" [data-action='link-delay-value']").val(link.delayValue);
        changeSwitchery(jQuery(selector+" [data-action='typing-indicator']"), link.typingIndicator);

    }

    importFlow(flow){


        Flowcomposer.$canvas.block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Importing selected flow...</span>',
            overlayCSS: {opacity: .5}
        });

        let ajax_url = 'includes/admin-ajax.php';
        let data = {
            'action': 'import_flow',
            'flow_id': this.flowId,
            'imported_flow' : flow
        };

        jQuery.post(ajax_url, data, function (res) {
            let url = window.location.href;
            if (url.indexOf('?') > -1){
                url += '&reset=1'
            }else{
                url += '?reset=1'
            }
            window.location.href = url;
        });




    }


    static setLinkSettings(linkId,delayType,delayValue,typingIndicator){
        toastr.clear();
        let data = Flowcomposer.getFlowchartData();
        let link = data.links[linkId];
        link.delayType = delayType;
        link.delayValue = parseInt(delayValue);
        link.typingIndicator = typingIndicator;

        if (delayType !== "immediately" && !isInt(link.delayValue)){
            $("#links_settings_extended [data-action='link-delay-value']").addClass("input-error");
            toastr.error("Make sure you specify a valid delay eg. 10", "Invalid delay");
            return false;
        }

        $("#links_settings_extended [data-action='link-delay-value']").removeClass("input-error");
        Flowcomposer.$flowchart.flowchart("setLinkData",linkId,link);
        return true;
    }

    generateJson() {


        let data = Object.assign({}, this);
        data.data = Flowcomposer.$flowchart.flowchart("getData");
        delete data._selectedLink;
        delete data._selectedCard;
        delete data._disconnectedButtons;
        delete data._flowId;
        return JSON.stringify(data);

    }

    load(resetStats=false) {



        Flowcomposer.$canvas.block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Fetching flow...</span>',
            overlayCSS: {opacity: .5}
        });

        let ajax_url = 'includes/admin-ajax.php';
        let data = {
            'action': 'get_flow',
            'flow_id': this.flowId,
        };

        let that = this;
        jQuery.post(ajax_url, data, function (res) {
            try {
                res = JSON.parse(res);
                Flowcomposer.$flowchart.flowchart("setData", res.data);
                that.positionMatrix = res._positionMatrix;

                let newcard = {};

                for (let key in res._cards) {
                    let card = res._cards[key];

                    let temporaryButtons = {};
                    let temporaryQuickReplies = {};
                    let temporaryTemplateElements = {};
                    let temporaryIceBreakers = {};
                    let temporaryListButtons = {};
                    let temporaryVariants = {};

                    let buttonsRaw = card._buttons;
                    let quickRepliesRaw = card._quickReplies;
                    let variantsRaw = card._variants;
                    let iceBreakersRaw = card._iceBreakers;
                    let templateElementsRaw = card._templateElements;

                    if (resetStats)
                        card._analytics = undefined;

                    for (let buttonKey in buttonsRaw) {
                        let button = buttonsRaw[buttonKey];
                        if (resetStats)
                            button._analytics = undefined;
                        temporaryButtons[buttonKey] = new Button(button._title, button._id,button._type,button._payload,button._url,button._webviewHeightRatio,button._messengerExtensions,button._analytics);
                    }

                    for (let quickReplyKey in quickRepliesRaw) {
                        let quickReply = quickRepliesRaw[quickReplyKey];
                        if (resetStats)
                            quickReply._analytics = undefined;
                        temporaryQuickReplies[quickReplyKey] = new QuickReply(quickReply._title, quickReply._id, quickReply._payload,quickReply._analytics,quickReply._contentType);
                    }


                    for (let templateElementKey in templateElementsRaw) {
                        let templateElement = templateElementsRaw[templateElementKey];
                        if (resetStats)
                            templateElement._analytics = undefined;

                        temporaryListButtons = {};
                        for (let buttonKey in templateElement._buttons) {
                            let button = templateElement._buttons[buttonKey];
                            if (resetStats)
                                button._analytics = undefined;
                            temporaryListButtons[buttonKey] = new Button(button._title, button._id,button._type,button._payload,button._url,button._webviewHeightRatio,button._messengerExtensions,button._analytics);

                        }

                        if (card._type === "list")
                            temporaryTemplateElements[templateElementKey] = new ListElement(templateElement._id,templateElement._title, templateElement._subtitle, templateElement._imageUrl,temporaryListButtons,templateElement._defaultAction);
                        else
                            temporaryTemplateElements[templateElementKey] = new CarouselElement(templateElement._id,templateElement._title, templateElement._subtitle, templateElement._imageUrl,temporaryListButtons,templateElement._defaultAction);

                    }


                    for (let iceBreakerKey in iceBreakersRaw) {
                        let iceBreaker = iceBreakersRaw[iceBreakerKey];
                        if (resetStats)
                            iceBreaker._analytics = undefined;
                        temporaryIceBreakers[iceBreakerKey] = new IceBreaker(iceBreaker._title, iceBreaker._id);
                    }
                    if (card._type === "text") {
                        newcard = new TextCard(card._id, card._initialOutputs, card._text, temporaryButtons, temporaryQuickReplies,temporaryIceBreakers, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "email-input") {
                        newcard = new EmailInputCard(card._id, card._initialOutputs, card._customfield, card._text, temporaryButtons, temporaryQuickReplies, card._title,card._nextOnSuccess,card._nextOnFailure, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "multiple-input") {
                        newcard = new MultipleInputCard(card._id, card._initialOutputs, card._customfield,card._rules, card._text, temporaryButtons, temporaryQuickReplies, card._title,card._nextOnSuccess,card._nextOnFailure, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "phone-input") {
                        newcard = new PhoneInputCard(card._id, card._initialOutputs, card._customfield, card._text, temporaryButtons, temporaryQuickReplies, card._title,card._nextOnSuccess,card._nextOnFailure, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "location-input") {
                        newcard = new LocationInputCard(card._id, card._initialOutputs, card._customfield, card._text, temporaryButtons, temporaryQuickReplies, card._title,card._nextOnSuccess,card._nextOnFailure, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "free-input") {
                        newcard = new FreeInputCard(card._id, card._initialOutputs, card._customfield, card._text, temporaryButtons, temporaryQuickReplies, card._title,card._nextOnSuccess,card._nextOnFailure, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "image") {
                        newcard = new ImageCard(card._id, card._initialOutputs, card._url,card._attachmentId, temporaryButtons, temporaryQuickReplies, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }
                    else if (card._type === "video") {
                        newcard = new VideoCard(card._id, card._initialOutputs, card._url, card._attachmentId,temporaryButtons, temporaryQuickReplies, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "audio") {
                        newcard = new AudioCard(card._id, card._initialOutputs, card._url, card._attachmentId,temporaryButtons, temporaryQuickReplies, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "list") {
                        newcard = new ListCard(card._id, card._initialOutputs, card._topElementStyle,temporaryTemplateElements,temporaryButtons,temporaryQuickReplies,card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);
                    }

                    else if (card._type === "carousel") {
                        newcard = new CarouselCard(card._id, card._initialOutputs,temporaryTemplateElements,temporaryButtons,temporaryQuickReplies,card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);
                    }


                    else if (card._type === "file") {
                        newcard = new FileCard(card._id, card._initialOutputs, card._url, card._attachmentId, temporaryButtons, temporaryQuickReplies, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "phone") {
                        newcard = new PhoneCard(card._id, card._initialOutputs, card._payload, card._title);

                    }

                    else if (card._type === "share") {
                        newcard = new ShareCard(card._id, card._initialOutputs, card._title);

                    }

                    else if (card._type === "url") {
                        newcard = new UrlCard(card._id, card._initialOutputs, card._url, card._webviewHeightRatio,card._messengerExtensions, card._title,card._analytics);

                    }

                    else if (card._type === "whatsapp") {
                        newcard = new WhatsAppCard(card._id, card._initialOutputs, card._phone,card._content, card._webviewHeightRatio,card._messengerExtensions, card._title,card._analytics);

                    }

                    else if (card._type === "split-test") {
                        for (let variantKey in variantsRaw) {
                            let variant = variantsRaw[variantKey];
                            temporaryVariants[variantKey] = new Variant(variantKey,variant._title, variant._weight,variant._nextCard);
                        }
                        newcard = new SplitTestCard(card._id, card._initialOutputs, card._title,temporaryVariants, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);
                    }

                    else if (card._type === "randomizer") {
                        for (let variantKey in variantsRaw) {
                            let variant = variantsRaw[variantKey];
                            temporaryVariants[variantKey] = new Variant(variantKey,variant._title, variant._weight,variant._nextCard);
                        }
                        newcard = new RandomizerCard(card._id, card._initialOutputs, card._title,temporaryVariants, card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);
                    }


                    else if (card._type === "action") {
                        newcard = new ActionCard(card._id, card._initialOutputs, card._actionType, card._actionSettings, card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "contact-reach") {
                        newcard = new ContactReachCard(card._id, card._initialOutputs, card._title,card._actionType, card._actionSettings,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "wowing") {
                        newcard = new WowingCard(card._id, card._initialOutputs, card._title,card._actionType, card._actionSettings,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }

                    else if (card._type === "condition") {
                        newcard = new ConditionCard(card._id, card._initialOutputs, card._segment, card._title,card._nextOnSuccess,card._nextOnFailure,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);

                    }


                    else if (card._type === "flow") {
                        newcard = new FlowCard(card._id, card._initialOutputs, card._flowType, card._flowId, card._flowName, card._cardId,card._cardName,card._title,card._json,card._analytics,card._positiveKeywords,card._negativeKeywords);
                        newcard.flowType = card._flowType;
                        newcard.flowId = card._flowId;
                        newcard.cardId = card._cardId;
                    }

                    else if (card._type === "webhook") {
                        newcard = new WebhookCard(card._id, card._initialOutputs, card._request, card._title,card._json, card._analytics,card._positiveKeywords,card._negativeKeywords);
                    }
                    that.cards[card._id] = newcard;

                }

                that.links = res._links;
                that.cards[res._firstCard].setFirstCard(false);
                that.typesCount = res._typesCount;
                Flowcomposer.$canvas.unblock();
                if (window.urlParams["card"] !== "undefined"){
                    let cardId = window.urlParams["card"];
                    removeUrlParameter("card",window.location.href);
                    $(".card-edit-settings[data-card-id='"+cardId+"']").click();
                }

                if (resetStats) {
                    removeUrlParameter("reset", window.location.href);
                    flowcomposer.save(true);
                }

            }
            catch (e) {
                if (window.urlParams["wizard"] !== "undefined"){
                    $("#newflow_modal").modal();
                    removeUrlParameter("wizard",window.location.href);
                }
                Flowcomposer.$canvas.unblock();
            }
        });



    }

    static  colorizeConnector(connectorId,color){
        $("#"+connectorId+"_connector").css("color",color).css("border-color",color).next(".flowchart-operator-connector-arrow-button").css("border-left-color",color);

    }

    highlightDisconnectedButtons(){
        for (let buttonId in this.disconnectedButtons){
            Flowcomposer.colorizeConnector(buttonId,Flowcomposer.errorColor);
        }
    }

    save(fromImport=false) {
        /*
                if (Object.keys(this.disconnectedButtons).length){
                    this.highlightDisconnectedButtons();
                    toastr.error("Make sure all buttons are linked before saving.", "Disconnected Buttons");
                    return;
                }
        */
        if (!this.firstCard && Object.keys(this.cards).length){
            toastr.error("You need to set a first card <img src='"+Flowcomposer.firstCardIcon+"' width='15px' class='first-inactive'> before saving.", "First Card Required");
            return;
        }

        for (let index in this.cards){
            if (this.cards[index] instanceof WowingCard) {
                let card = this.cards[index];
                let ajax_url = 'includes/admin-ajax.php';
                try {
                    let tmpLinks = Flowcomposer.getTemporaryLinks();
                    console.log(tmpLinks);
                    console.log(tmpLinks["link-ready_" + index].toOperator);
                    let data = {
                        'action': 'save_wowing_webhook',
                        'account': card.actionSettings.account,
                        'automation': card.actionSettings.listId,
                        'link_ready': tmpLinks["link-ready_" + index].toOperator,
                        'video_watched': tmpLinks["video-watched_" + index].toOperator,
                        'flow_id': this.flowId,
                    };
                    console.log(data);
                    jQuery.post(ajax_url, data, function (res) {
                    console.log(res);
                    });
                }
                catch (e) {
                 console.log(e);
                }
            }
                if (this.cards[index] instanceof InputCard){
                if (this.cards[index].customfield === "select" || isNaN(parseInt(this.cards[index].customfield))) {
                    toastr.error("Make sure you specify a valid custom field for card " + this.cards[index].title + ".", "Custom field Required");
                    return;
                }
                else  if ((!this.cards[index].nextOnSuccess ||  !this.cards[index].nextOnFailure) && !this.cards[index] instanceof FreeInputCard  && !this.cards[index] instanceof MultipleInputCard )  {
                    toastr.error('Make sure "valid input" and "invalid input" outputs are connected for card ' + this.cards[index].title, "Missing output connections");
                    return;
                }

                else  if (!this.cards[index].nextOnSuccess && this.cards[index] instanceof FreeInputCard )  {
                    toastr.error('Make sure the "after answer" output is connected for card ' + this.cards[index].title, "Missing output connections");
                    return;
                }

                if (this.cards[index] instanceof MultipleInputCard) {
                    if (!this.cards[index].nextOnFailure && this.cards[index] instanceof MultipleInputCard) {
                        toastr.error('Make sure the "invalid choice" output is connected for card ' + this.cards[index].title, "Missing output connections");
                        return;
                    }
                    this.cards[index].generateRules();

                }
            }
        }

        this.positionMatrix = Flowcomposer.$flowchart.panzoom("getMatrix");
        Flowcomposer.$canvas.block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Saving flow...</span>',
            overlayCSS: {opacity: .5}
        });



        let ajax_url = 'includes/admin-ajax.php';
        let data = {
            'action': 'save_flow',
            'flow_id': this.flowId,
            'flow_data': this.generateJson()
        };
        jQuery.post(ajax_url, data, function (res) {
            if (fromImport)
                toastr.success("Flow successfully imported.", "Import completed");
            else
                toastr.success("Flow successfully saved.", "Save completed");
            Flowcomposer.$canvas.unblock();


        });

    }


    get selectedLink() {
        return this._selectedLink;
    }

    set selectedLink(value) {
        this._selectedLink = value;
    }


    static get buttonsContainerSelector() {
        return "#buttons_container";
    }

    static get templateElementContainerSelector() {
        return "#template_element_container";
    }

    static get templateElementPreviewSelector() {
        return "#template_element_preview";
    }
    static get carouselElementPreviewSelector() {
        return "#carousel_preview_container #slides #slides_container";
    }


    static get buttonsPreviewSelector() {
        return "#buttons_preview";
    }

    static get quickRepliesContainerSelector() {
        return "#quickreplies_container";
    }

    static get quickRepliesPreviewSelector() {
        return "#quickreplies_preview";
    }

    static get maximumListElements() {
        return 4;
    }

    static get maximumCarouselElements() {
        return 10;
    }

    static get maximumButtons() {
        return 3;
    }

    static get maximumVariants() {
        return 10;
    }

    static get maximumListButtons() {
        return 1;
    }

    static get maximumCarouselButtons() {
        return 3;
    }

    static get maximumQuickReplies() {
        return 11;
    }

    static get nextCardColor(){
        return "#58a8ff";
    }

    static get quickRepliesColor(){
        return "#58a8ff";
    }

    static get buttonsColor(){
        return "#4cc175";
    }

    static get templateElementColor(){
        return "#ff9d37";
    }

    static get errorColor(){
        return "#e46e6e";
    }

    static get $canvas() {
        return jQuery(".flowcanvascolumn");
    }

    static get buttonsTrimLength() {
        return 20;
    }

    static get textMessageTrimLength() {
        return 145;
    }

    static get defaultText() {
        return "Your text goes here, you can add personalization too [FIRST_NAME] and of course emojis are there so you can make the most out of your messages 🎉🎉";
    }

    static get defaultPhoneNumber() {
        return "+16505551234";
    }

    static get defaultUrl() {
        return "https://clevermessenger.com";
    }

    static get defaultCardImage() {
        return "https://dl.dropboxusercontent.com/s/y34ccdc138zzv54/default-image-card.png?dl=0";
    }

    static get defaultImageUrl() {
        return "https://www.facebook.com/clevermessenger/photos/a.2173787336237548.1073741829.1878330572449894/2173787366237545/";
    }

    static get defaultCardVideo() {
        return "https://dl.dropbox.com/s/u73nv46z4a9nlir/default-video-card.mp4?dl=0";
    }

    static get defaultVideoUrl() {
        return "https://www.facebook.com/clevermessenger/videos/2173799352903013/";
    }

    static get defaultCardAudio() {
        return "https://dl.dropbox.com/s/dbra60awqlzbyi0/default-audio-card.mp3?dl=0";
    }


    static get defaultCardFile() {
        return "https://dl.dropbox.com/s/lichd3o0v2m11k6/Messenger-Bot-Dos-and-Donts-by-CleverMessenger.pdf?dl=0";
    }

    static get defaultCardFileName() {
        return "Why use chatbots.pdf";
    }


    static get firstCardIcon() {
        return "https://cleverstorage.b-cdn.net/assets/cm-card-first-enabled.svg";
    }

    static get secondaryCardIcon() {
        return "https://cleverstorage.b-cdn.net/assets/cm-card-first-disabled.svg";
    }

    static trimString(string, length) {
        if (!length) length = 35;
        return _.truncate(string ,{length:length});

    }


    static generateID(length=10) {

        let text = "";
        let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (let i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return "CM" + text;
    }

    deleteLink(linkId) {
        delete this.links[linkId];
        let fromButton  = Flowcomposer.getTemporaryLinks()[linkId].fromConnector;
    }

    deleteSelectedLink() {
        let temporaryLinks = Flowcomposer.getTemporaryLinks();
        let fromButtonId = temporaryLinks[this.selectedLink].fromConnector;
        let fromCardId = temporaryLinks[this.selectedLink].fromOperator;
        Flowcomposer.$flowchart.flowchart("deleteLink", this.selectedLink);

        if (fromButtonId.indexOf("output_") === -1 && fromButtonId.indexOf("success_") === -1 && fromButtonId.indexOf("failure_") === -1) {
            this.disconnectedButtons[fromButtonId] = 1;
        }
        if (fromButtonId.indexOf("success_") !== -1){
            this.cards[fromCardId].nextOnSuccess = false;
        }

        if (fromButtonId.indexOf("failure_") !== -1){
            this.cards[fromCardId].nextOnFailure = false;
        }
        this.selectedLink = -1;

            if (typeof this.cards[fromCardId].templateElements !== "undefined" && flowcomposer.cards[fromCardId].templateElements.hasOwnProperty(fromButtonId)){
                this.cards[fromCardId].templateElements[fromButtonId].defaultAction = {};
            }
            else if (typeof flowcomposer.cards[fromCardId].templateElements !== "undefined" && fromButtonId.includes("elbt")){
                let elementId = fromButtonId.replace("elbt","").split("_")[0];
                flowcomposer.cards[fromCardId].templateElements[elementId].buttons[fromButtonId].type = "postback";
                flowcomposer.cards[fromCardId].templateElements[elementId].buttons[fromButtonId].payload = "null";
            }

            else if (typeof this.cards[fromCardId].buttons[fromButtonId] !== "undefined" ){
                this.cards[fromCardId].buttons[fromButtonId].type = "postback";
                this.cards[fromCardId].buttons[fromButtonId].payload = "null";

            }
            else if (typeof this.cards[fromCardId].quickReplies[fromButtonId] !== "undefined" ) {
                this.cards[fromCardId].quickReplies[fromButtonId].payload = "null";

            }
            else if (typeof this.cards[fromCardId].variants[fromButtonId] !== "undefined" ) {
                this.cards[fromCardId].variants[fromButtonId].nextCard = false;

            }
            this.cards[fromCardId].generateJson();


    }

    deleteCard(cardId) {
        if (this.firstCard === cardId)
            this.firstCard  = false;

        this.cards[cardId].deleteAllButtons().disconnectConnectedButtons();
        this._deletedCards.push(cardId);
        Flowcomposer.$flowchart.flowchart("deleteOperator", cardId);
        delete this.cards[cardId];
    }



    get cards() {
        return this._cards;
    }

    set cards(value) {
        this._cards = value;
    }

    get selectedCard() {
        return this._selectedCard;
    }

    set selectedCard(value) {
        this._selectedCard = value;
    }

    get flowchart() {
        return this._flowchart;
    }

    set flowchart(value) {
        this._flowchart = value;
    }

    handleVariantConnection(id,fromCard,toCard){
        let target = fromCard.buttons[buttonId];
        fromCard.variants[id].nextCard = toCard.id;
        if (elementButton && typeof fromCard.templateElements !== "undefined") {
            let elementId = buttonId.replace("elbt","").split("_")[0];
            target = fromCard.templateElements[elementId].buttons[buttonId];
        }

        if (toCard.type === "phone") {
            target.type = "phone_number";
            target.payload = toCard.payload;
        }
        else if (toCard instanceof UrlCard) {
            target.type = "web_url";
            target.url = toCard.url;
            target.webviewHeightRatio = toCard.webviewHeightRatio;
            target.messengerExtensions = toCard.messengerExtensions;


        }
        else if (toCard.type === "share") {
            target.type = "element_share";
            target.title = "Share";
            // Renaming output label to share
            if (toCard instanceof ShareCard) {
                let data = fromCard.getOperatorData();
                data.properties.outputs[buttonId].label = "Share";
                fromCard.setOperatorData(data);
            }
        }
        else {
            target.type = "postback";
            target.payload = this.flowId + ":" + fromCard.id + ":" + buttonId + ":" + toCard.id;

        }

        fromCard.generateJson();
        return target.type;

    }


    handleButtonConnection(buttonId,fromCard,toCard,elementButton=false){
        let target = fromCard.buttons[buttonId];
        if (elementButton && typeof fromCard.templateElements !== "undefined") {
            let elementId = buttonId.replace("elbt","").split("_")[0];
            target = fromCard.templateElements[elementId].buttons[buttonId];
        }

        if (toCard.type === "phone") {
            target.type = "phone_number";
            target.payload = toCard.payload;
        }

        else if (toCard instanceof UrlCard) {
            target.type = "web_url";
            target.url = toCard.url;
            target.webviewHeightRatio = toCard.webviewHeightRatio;
            target.messengerExtensions = toCard.messengerExtensions;

        }

        else if (toCard.type === "share") {
            target.type = "element_share";
            target.title = "Share";
            // Renaming output label to share
            if (toCard instanceof ShareCard) {
                let data = fromCard.getOperatorData();
                data.properties.outputs[buttonId].label = "Share";
                fromCard.setOperatorData(data);
            }
        }
        else {
            target.type = "postback";
            target.payload = this.flowId + ":" + fromCard.id + ":" + buttonId + ":" + toCard.id;

        }

        fromCard.generateJson();
        return target.type;

    }

    handleTemplateElementConnection(elementId,fromCard,toCard){
        if (toCard.type === "phone") {
            fromCard.buttons[elementId].type = "phone_number";
            fromCard.buttons[elementId].payload = toCard.payload;
        }
        else if (toCard instanceof UrlCard) {
            fromCard.templateElements[elementId].defaultAction.type = "web_url";
            fromCard.templateElements[elementId].defaultAction.url = toCard.url;
            fromCard.templateElements[elementId].defaultAction.webviewHeightRatio = toCard.webviewHeightRatio;
            fromCard.templateElements[elementId].defaultAction.messengerExtensions = toCard.messengerExtensions;


        }

        else {
            fromCard.buttons[elementId].type = "postback";
            fromCard.buttons[elementId].payload = this.flowId + ":" + fromCard.id + ":" + buttonId + ":" + toCard.id;

        }

        fromCard.generateJson();

    }



    handleQuickReplyConnection(quickReplyId,fromCard,toCard){

        fromCard.quickReplies[quickReplyId].type = "text";
        fromCard.quickReplies[quickReplyId].payload = this.flowId+":"+fromCard.id+":"+quickReplyId+":"+toCard.id;
        fromCard.generateJson();


    }

    checkValidLink(linkId, linkData) {

        linkData.loaded = true;
        if (typeof linkData.isValid === "undefined") {
            let fromCard = this.cards[linkData.fromOperator];
            let toCard = this.cards[linkData.toOperator];

            if (fromCard && toCard) {

                linkData.isValid = false;
                linkData.loaded = false;
                linkData.canEdit = true;
                linkData.delayType = "immediately";


                if (fromCard instanceof SplitTestCard) {
                    fromCard.variants[linkId].nextCard = toCard.id;
                    linkData.isValid = true;
                    let variantCounter= 0;
                    for (let index in fromCard.variants) {
                        if (index !== linkId) {
                            variantCounter++;
                        }
                        else
                            break;
                    }
                    fromCard.generateJson();
                    linkData.color = Flowcomposer.splitTestColors()[variantCounter];
                    Flowcomposer.colorizeConnector(linkData.fromConnector,linkData.color);
                    return linkData;
                }

                let elementId = linkData.fromConnector.replace("elbt","").split("_")[0];


                if ((typeof fromCard.templateElements !== "undefined") && (fromCard.templateElements.hasOwnProperty(elementId)) &&  (!linkData.fromConnector.includes("elbt")) && (!(toCard instanceof UrlCard))){
                    toastr.warning("List and carousel elements can only be linked with a Visit URL card", "Invalid action");
                    return linkData;
                }


                else if (toCard instanceof UrlCard || toCard instanceof PhoneCard || toCard instanceof ShareCard) {

                    if (toCard instanceof ShareCard && (!(fromCard instanceof ListCard)) && (!(fromCard instanceof CarouselCard))){
                        toastr.warning("Share cards can only be linked List and carousel buttons", "Invalid action");
                        return linkData;
                    }
                    linkData.canEdit = false;


                    if (typeof fromCard.templateElements !== "undefined" && fromCard.templateElements.hasOwnProperty(elementId)  &&  (!linkData.fromConnector.includes("elbt")) &&  (toCard instanceof PhoneCard) ) {
                        toastr.warning("List and carousel elements can only be linked with a Visit URL card", "Invalid action");
                        return linkData;
                    }


                    else if (!fromCard.buttons.hasOwnProperty(linkData.fromConnector)  && !(typeof fromCard.templateElements !== "undefined" && fromCard.templateElements.hasOwnProperty(elementId)) &&  !(typeof fromCard.templateElements !== "undefined" && typeof fromCard.templateElements[elementId] !== "undefined" )) {
                        toastr.warning("URL , Phone  and Share cards can only be linked with a button", "Invalid action");
                        return linkData;
                    }



                }

                else if (fromCard.iceBreakers.hasOwnProperty(linkData.fromConnector)) {
                    linkData.canEdit = false;
                    toastr.warning("Ice Breakers cannot be linked to any card", "Invalid action");
                    return linkData;


                }
                if (fromCard.buttons.hasOwnProperty(linkData.fromConnector)) {
                    linkData.color = Flowcomposer.buttonsColor;
                    this.handleButtonConnection(linkData.fromConnector,fromCard,toCard);
                    linkData.type = "button";
                }

                else if (typeof fromCard.templateElements !== "undefined" && fromCard.templateElements.hasOwnProperty(elementId) && linkData.fromConnector.includes("elbt")) {

                    linkData.color = Flowcomposer.buttonsColor;
                    this.handleButtonConnection(linkData.fromConnector, fromCard, toCard,true);


                    linkData.type = "button";


                }

                else if (typeof fromCard.templateElements !== "undefined" && fromCard.templateElements.hasOwnProperty(elementId)) {

                    linkData.color = Flowcomposer.templateElementColor;
                    this.handleTemplateElementConnection(linkData.fromConnector, fromCard, toCard);
                    linkData.type = "template-element";


                }



                else if (fromCard.quickReplies.hasOwnProperty(linkData.fromConnector)) {
                    linkData.color = Flowcomposer.quickRepliesColor;
                    this.handleQuickReplyConnection(linkData.fromConnector,fromCard,toCard);

                    linkData.type = "quickreply";


                }
                else {
                    linkData.color = Flowcomposer.nextCardColor;
                    if (linkData.fromConnector.includes("success")) {
                        linkData.color = Flowcomposer.buttonsColor;

                        fromCard.nextOnSuccess = toCard.id;
                    }
                    else if (linkData.fromConnector.includes("failure")) {
                        linkData.color = Flowcomposer.errorColor;

                        fromCard.nextOnFailure = toCard.id;
                    }

                    linkData.type = "card";

                }

                delete this.disconnectedButtons[linkData.fromConnector];
                linkData.isValid = true;

                fromCard.generateJson();

            }

            Flowcomposer.colorizeConnector(linkData.fromConnector,linkData.color);

            return linkData;

        }
        else {
            Flowcomposer.colorizeConnector(linkData.fromConnector,linkData.color);

            return linkData;
        }
    }
}

class Card {



    constructor(id, initialOutputs=false, type, buttons, quickReplies, title, json,analytics,positiveKeywords,negativeKeywords,iceBreakers) {
        if (!id)
            id = Flowcomposer.generateID(10);

        this._type = type;
        this._id = id;
        this._links = {};
        if (typeof analytics !== "undefined")
            this.analytics = analytics;
        else
            this.analytics = {_deliveries: 0,_opens : 0,_clicks : 0, };



        this._json = json;



        this._initialOutputs = initialOutputs;

        if (iceBreakers) {
            this._iceBreakers = iceBreakers;
        }
        else {
            this._iceBreakers = {};
        }

        if (buttons)
            this._buttons = buttons;
        else
            this._buttons = {};

        if (quickReplies)
            this._quickReplies = quickReplies;
        else
            this._quickReplies = {};



        if (typeof flowcomposer.typesCount[this.type] === "undefined")
            flowcomposer.typesCount[this.type] = 0;
        flowcomposer.typesCount[this.type]++;

        if (typeof title === "undefined") {
            if (this instanceof WhatsAppCard)
                this._title = "WhatsApp Card  #" + flowcomposer.typesCount[this.type];
            else if (this instanceof UrlCard) {
                this._title = this.type.toUpperCase() + " Card #" + flowcomposer.typesCount[this.type];
            }
            else {
                let cardTitle = this.type.replaceAll("-"," ").capitalize();
                if (this instanceof MultipleInputCard)
                    this._title = "Multiple choice #" + flowcomposer.typesCount[this.type];
                else if (this instanceof FreeInputCard)
                    this._title = "Other information #" + flowcomposer.typesCount[this.type];

                else if (this instanceof EmailInputCard)
                    this._title = "Ask for email address #" + flowcomposer.typesCount[this.type];

                else if (this instanceof PhoneInputCard)
                    this._title = "Ask for phone number #" + flowcomposer.typesCount[this.type];


                else if (this instanceof LocationInputCard)
                    this._title = "Ask for location #" + flowcomposer.typesCount[this.type];
                else {
                    let cardTitle = this.type.replaceAll("-", " ").capitalize();
                    this._title = cardTitle + " Card #" + flowcomposer.typesCount[this.type];
                }

            }


        }
        else
            this._title = title;

        if (positiveKeywords)
            this._positiveKeywords = positiveKeywords;
        else
            this._positiveKeywords = [];

        if (negativeKeywords)
            this._negativeKeywords = negativeKeywords;
        else
            this._negativeKeywords = [];

        this.applyCardTitle();


    }

    get iceBreakers() {
        return this._iceBreakers;
    }

    set iceBreakers(value) {
        this._iceBreakers = value;
    }

     getConnectedAirVariables(){
        let connectedCards = Flowcomposer.getAllConnectedCards(this.id,[]);
        console.log(connectedCards);
        let airVariables = {};
        for (let i=0;i<connectedCards.length;i++){
            let connectedCard = flowcomposer.cards[connectedCards[i]];
            console.log(connectedCard);
            let tmpVars = connectedCard.airVariables();
            for (let j=0;j<tmpVars.length;j++) {
                let category = tmpVars[j].key;
                let value = tmpVars[j].value;
                if (typeof airVariables[category] === "undefined") {
                    airVariables[category] = [];
                }
                airVariables[category].push(value);

            }
        }

        return airVariables;
    }

    setDuplicatedIds(outputsMap){
        for (var id in outputsMap){
            var newId = outputsMap[id].id;
            if (typeof outputsMap[id].type !== "undefined") {
                if (outputsMap[id].type === "quickreply") {
                    Object.defineProperty(this.quickReplies, newId,
                        Object.getOwnPropertyDescriptor(this.quickReplies, id));
                    delete this.quickReplies[id];
                    this.quickReplies[newId].id = newId;
                    this.quickReplies[newId]._contentType = "text";
                    this.quickReplies[newId].payload = "";
                }

                else if (outputsMap[id].type === "variant") {
                    Object.defineProperty(this.variants, newId,
                        Object.getOwnPropertyDescriptor(this.variants, id));
                    delete this.variants[id];
                    this.variants[newId].id = newId;

                }

                else if (outputsMap[id].type === "button") {
                    if (newId.includes("elbt")) {
                        var elementId = newId.replace("elbt","").split("_")[0];
                        Object.defineProperty(this.templateElements[elementId].buttons, newId,
                            Object.getOwnPropertyDescriptor(this.templateElements[elementId].buttons, id));
                        delete this.templateElements[elementId].buttons[id];
                        this.templateElements[elementId].buttons[newId].id = newId;
                        this.templateElements[elementId].buttons[newId].type = "postback";
                        this.templateElements[elementId].buttons[newId].payload = "null";
                    }
                    else {
                        Object.defineProperty(this.buttons, newId,
                            Object.getOwnPropertyDescriptor(this.buttons, id));
                        delete this.buttons[id];
                        this.buttons[newId].id = newId;
                        this.buttons[newId].type = "postback";
                        this.buttons[newId].payload = "null";
                    }
                }

                else if (outputsMap[id].type === "template-element") {

                    Object.defineProperty(this.templateElements, newId,
                        Object.getOwnPropertyDescriptor(this.templateElements, id));
                    delete this.templateElements[id];
                    this.templateElements[newId]._defaultAction = {};
                    this.templateElements[newId]._id =  newId;



                }
            }
            this.generateJson();

        }


    }



    getConnectedCards(){

        let connectedCards = [];
        let links = Flowcomposer.getTemporaryLinks();
        for (let linkId in links){
            if (links[linkId].toOperator === this.id){
                let cardId = links[linkId].fromOperator;
                let buttonId = links[linkId].fromConnector;
                let connectedCard ={"card_id" : cardId, "button_id" : buttonId};
                connectedCards.push(connectedCard);
            }
        }

        return connectedCards;
    }


    airVariables(){
        return [];
    }
    get positiveKeywords() {
        return this._positiveKeywords;
    }

    set positiveKeywords(value) {
        this._positiveKeywords = value;

    }

    get negativeKeywords() {
        return this._negativeKeywords;
    }

    set negativeKeywords(value) {
        this._negativeKeywords = value;
    }

    addPositiveKeyword(value){
        if (value.length) {
            value = value.trim();
            this.positiveKeywords.push(value);
            $("#" + this.type + "_card_settings  .positive-keywords-container").append('<span class="chat_tags positive_chat_tags">' + value + '<span data-action="delete-positive-keyword" class="delete_tag" > <i class="fa icon-cross"></i></span></span>');
        }
        return this;
    }

    addNegativeKeyword(value){
        if (value.length) {
            value = value.trim();
            this.negativeKeywords.push(value);
            $("#" + this.type + "_card_settings  .negative-keywords-container").append('<span class="chat_tags negative_chat_tags">' + value + '<span data-action="delete-negative-keyword" class="delete_tag" > <i class="fa icon-cross"></i></span></span>');
        }
    }

    deletePositiveKeyword(value){
        value = value.trim();
        let index = this.positiveKeywords.indexOf(value);
        if (index !== -1) this.positiveKeywords.splice(index, 1);

    }

    deleteNegativeKeyword(value){
        value = value.trim();
        let index = this.negativeKeywords.indexOf(value);
        if (index !== -1) this.negativeKeywords.splice(index, 1);
    }

    get $addTemplateElement() {
        return $("#"+this.type+"_card_settings [data-action='add-template-element']");

    }

    $addListButton(elementId) {
        return $("#"+this.type+"_card_settings #"+elementId+"_container [data-action='add-button']");

    }


    get $addButton() {
        return $("#"+this.type+"_card_settings [data-action='add-button']").filter(':parents(#template_element_container)');

    }


    get $addVariant() {
        return $("#"+this.type+"_card_settings [data-action='add-variant']");

    }
    get $addIceBreaker() {
        return $("#"+this.type+"_card_settings [data-action='add-ice-breaker']");

    }

    get $addQuickReply() {
        return $("#"+this.type+"_card_settings [data-action='add-quickreply']");
    }

    get json() {
        return this._json;
    }

    set json(value) {
        value = (convertPersonalizationTagsToFacebookTags(value));
        this._json = (value);

        try {
            let tmpJson = JSON.parse(value);
            if (tmpJson.hasOwnProperty("message")){
                if (tmpJson.message.hasOwnProperty("cm_preview_url"))
                    delete tmpJson.message.cm_preview_url;
            }
            $('.'+this.type+'_display_json_codes').html(library.json.prettyPrint(tmpJson).replaceAll("{","<span class='brace'>{</span>").replaceAll("}","<span class='brace'>}</span>").replaceAll("]","<span class='bracket'>]</span>").replaceAll("[","<span class='bracket'>[</span>"));
            $('.'+this.type+'_display_json_codes_raw').val((JSON.stringify(tmpJson)));
            if (value.indexOf("{{") !== -1) {
                $('#' + this.type + '_card_settings #json_personalization_warning').css("display", "block");
            }
            else
                $('#'+this.type+'_card_settings #json_personalization_warning').css("display","none");
        } catch (e) {
            return value;
        }



    }


    get analytics() {
        return this._analytics;
    }

    set analytics(value) {
        this._analytics = value;
        let that = this;


        $("." + that.id + "-stats .deliveries-stats").waitUntilExists(function() {
            $(this).html(value._deliveries);
        });

        $("." + that.id + "-stats .opens-stats").waitUntilExists(function() {
            $(this).html(value._opens);
        });

        $("." + that.id + "-stats .clicks-stats").waitUntilExists(function() {
            $(this).html(value._clicks);
        });


    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }

    loadFormValues(formValues){

        $("#" + this.type + "_card_settings #form_values_container").html("");

        for (let index in formValues){
            this.generateFormValueInput(formValues[index]._key,formValues[index]._value);
        }

    }

    generateFormValueInput(key,value) {
        let that = this;
        let html = '<div class="row form-value-container">' +
            '<div class="col-lg-11"> ' +
            '<div class="row">' +
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-form-data-key"  value="'+key+'" placeholder="Key" />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-form-data-value"  value="'+value+'"  placeholder="Value"  />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '</div>'+
            '</div>' +
            '<div class="col-lg-1 delete-button-container"> ' +
            '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-form-value"></i>' +
            '</div>' +
            '</div>';


        $("#" + that.type + "_card_settings #form_values_container").append(html);


    }

    clearTemplateElementContainer() {
        $("#" + this.type + "_card_settings " + Flowcomposer.templateElementContainerSelector).html("");
        $("#" + this.type + "_card_settings " + Flowcomposer.templateElementPreviewSelector).html("");
        $("#" + this.type + "_card_settings " + Flowcomposer.carouselElementPreviewSelector).html("");
        return this;

    }

    clearButtonsContainer() {
        $("#" + this.type + "_card_settings " + Flowcomposer.buttonsContainerSelector).html("");
        $("#" + this.type + "_card_settings " + Flowcomposer.buttonsPreviewSelector).html("");
        return this;

    }


    clearQuickRepliesContainer() {
        $("#" + this.type + "_card_settings " + Flowcomposer.quickRepliesContainerSelector).html("");
        $("#" + this.type + "_card_settings " + Flowcomposer.quickRepliesPreviewSelector).html("");
        return this;

    }

    loadTemplateElements() {

        let counter = 0;
        let maximumButtons  = 0;
        for (let key in this.templateElements) {
            if (this instanceof ListCard)
                this.generateListElementInput(this.templateElements[key].id, this.templateElements[key].title,this.templateElements[key].subtitle,this.templateElements[key].imageUrl,this.templateElements[key].defaultAction);
            else
                this.generateCarouselElementInput(this.templateElements[key].id, this.templateElements[key].title,this.templateElements[key].subtitle,this.templateElements[key].imageUrl,this.templateElements[key].defaultAction);


            maximumButtons = this.templateElements[key].loadButtons();

            if (maximumButtons) {
                this.$addListButton(key).hide();
            }
            else  {
                this.$addListButton(key).show();

            }
            counter++;
        }

        if (this instanceof ListCard)
            return (counter === Flowcomposer.maximumListElements);
        else
            return (counter === Flowcomposer.maximumCarouselElements);

    }

    loadButtons() {
        let counter = 0;
        for (let key in this.buttons) {
            this.generateButtonInput(this.buttons[key].id, this.buttons[key].title);
            counter++;
        }

        if (this instanceof ListCard)
            return (counter === Flowcomposer.maximumListButtons);

        else
            return (counter === Flowcomposer.maximumButtons);

    }

    loadIceBreakers() {

        let counter = 0;

        for (let key in this.iceBreakers) {
            this.generateIceBreakerInput(this.iceBreakers[key].id, this.iceBreakers[key].title);
            counter++;
        }

        return counter;


    }

    loadQuickReplies() {

        let counter = 0;

        for (let key in this.quickReplies) {
            this.generateQuickReplyInput(this.quickReplies[key].id, this.quickReplies[key].title,this.quickReplies[key].contentType);
        }

        return (counter === Flowcomposer.maximumQuickReplies);


    }

    generateListElementInput(id, title, subtitle, imageUrl,defaultAction) {
        let that = this;
        let display = "none";
        let imageClass = "";
        let imageStyle = "";
        let footerClass = "";
        let incrementHeight = 0;
        let url = "";
        let elementsCount = Object.keys(this.templateElements);
        let elementActions = '<i class="fa icon-cross delete-button pull-right" style="margin-bottom: 10px" aria-hidden="true" data-action="delete-template-element" data-element-id="'+id+'"></i>';
        if (elementsCount[0] === id){
            if (this.topElementStyle === "compact")
                elementActions = '<i class="fa icon-menu3 delete-button pull-right" style="margin-bottom: 10px" aria-hidden="true" data-action="set-list-cover" data-element-id="'+id+'"></i>';
            else
                elementActions = '<i class="fa icon-menu2 delete-button pull-right" style="margin-bottom: 10px" aria-hidden="true" data-action="unset-list-cover" data-element-id="'+id+'"></i>';

            if (that.topElementStyle === "large"){
                imageClass = "list-first-cover";
                imageStyle = "background:linear-gradient(rgba(0,0,0,0.45),rgba(0,0,0,0.45)),url("+ imageUrl +") no-repeat;";
            }
        }
        else if (elementsCount[1] === id){
            elementActions = "";
        }

        if (elementsCount[3] === id){
            footerClass = "preview-list-footer-last-child";
        }


     
        let html = '<div id="' + id + '_container" class="element-parent-container" data-element-id="'+id+'"  >' + elementActions +
            '<div class="styling_modal_fieldbackground" style="text-align: center;">' +
            '<div class="element-container">' +
            '<p class="lead emoji-picker-container template-element-p" >' +
            '<textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="element title" data-action="element-title"  maxlength="80" >'+title+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div class="element-container">' +
            '<p class="lead emoji-picker-container template-element-p">' +
            '<textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="element subtitle" data-action="element-subtitle"  maxlength="80" >'+subtitle+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div class="element-container element-image-container">' +
            '<p class="lead emoji-picker-container template-element-p disable-emoji" id="imageUrl_container " >' +
            '<textarea class="msg_text form-control input-lg text-card-text element-image-url-div"  data-emojiable="true" placeholder="Image URL" data-action="element-image-url"  maxlength="255" >'+imageUrl+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div id="buttons_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-button" data-target="button" data-type="button" >Add Button</span>'+
            '</div>' +
            '</div>';



        $("#" + that.type + "_card_settings " + Flowcomposer.templateElementContainerSelector).append(html);
        window.emojiPicker.discover();

        $("#" + that.type + "_card_settings #"+id+"_container [data-action='element-title'], #" + that.type + "_card_settings #"+id+"_container [data-action='element-subtitle']").characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });


        if (Object.keys(defaultAction).length) {
            display = "block";
            url = stripUrl(defaultAction.url);
        }


        $("#" + that.type + "_card_settings "+Flowcomposer.templateElementPreviewSelector).append(
            '<div id="'+id+'_preview" class="preview_list preview-list-element '+imageClass+'" style="'+imageStyle+'">'+
            '<div id="preview_list_content" class="'+imageClass+'" >'+
            '<div id="'+id+'_preview_title" class="template-element-title">'+convertEmojiUtfToImage(title)+'</div>'+
            '<div id="'+id+'_preview_subtitle" class="template-element-subtitle">'+convertEmojiUtfToImage(subtitle)+'</div>'+
            '<div id="'+id+'_preview_url" class="template-element-url" style="display: '+display+'">'+url+'</div>'+
            '</div>'+
            '<div id="'+id+'_preview_list_image" class="'+imageClass+'" style="'+imageStyle+'" >' +
            '<div id="'+id+'_preview_list_image" >' +
            '<img id="'+id+'_preview_image" class="preview_list_image" src="'+convertEmojiUtfToImage(imageUrl)+'"></div>' +
            '</div>'+
            '<div class="button_container_preview"></div>'+
            '<div class="preview_list_footer '+footerClass+'"></div>'+
            '</div>');

        $('.ui.dropdown').dropdown();


    }
    generateCarouselElementInput(id, title, subtitle, imageUrl,defaultAction) {
        let that = this;
        let display = "none";
        let url = "";
        let elementsCount = Object.keys(this.templateElements);
        let elementsLength = Object.keys(this.templateElements).length;
        let elementActions = '<i class="fa icon-cross delete-button pull-right" style="margin-bottom: 10px" aria-hidden="true" data-action="delete-template-element" data-element-id="'+id+'"></i>';

         if (elementsCount[0] === id){
            elementActions = "";
        }


        // Show & Hide carousel sliders
       that.adjustCarouselPreview("added");





        let html = '<div id="' + id + '_container" class="element-parent-container" data-element-id="'+id+'"  >' + elementActions +
            '<div class="styling_modal_fieldbackground" style="text-align: center;">' +
            '<div class="element-container">' +
            '<p class="lead emoji-picker-container template-element-p" >' +
            '<textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="element title" data-action="element-title"  maxlength="80" >'+title+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div class="element-container">' +
            '<p class="lead emoji-picker-container template-element-p">' +
            '<textarea class="msg_text form-control input-lg text-card-text"  data-emojiable="true" placeholder="element subtitle" data-action="element-subtitle"  maxlength="80" >'+subtitle+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div class="element-container element-image-container">' +
            '<p class="lead emoji-picker-container template-element-p disable-emoji" id="imageUrl_container " >' +
            '<textarea class="msg_text form-control input-lg text-card-text element-image-url-div"  data-emojiable="true" placeholder="Image URL" data-action="element-image-url"  maxlength="255" >'+imageUrl+'</textarea>' + window.personalizationHtml + airPickerHtml() +
            '</p>' +
            '</div>' +
            '<div id="buttons_container" class="buttons-container"></div><span data-msgtype="buttons" class="btn btn-primary add-button-button m-t"  data-action="add-button" data-target="button" data-type="button" >Add Button</span>'+
            '</div>' +
            '</div>';



        $("#" + that.type + "_card_settings " + Flowcomposer.templateElementContainerSelector).append(html);
        window.emojiPicker.discover();

        $("#" + that.type + "_card_settings #"+id+"_container [data-action='element-title'], #" + that.type + "_card_settings #"+id+"_container [data-action='element-subtitle']").characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });


        if (Object.keys(defaultAction).length) {
            display = "block !important";
            url = stripUrl(defaultAction.url);
        }


        $("#" + that.type + "_card_settings "+Flowcomposer.carouselElementPreviewSelector).append('' +
            '<div id="'+id+'_slide" class="carouselslide">' +
            '<div id="'+id+'_preview" class="broadcast_preview_carousel_item">' +
            '      <span id="'+id+'_preview_item">' +
            '      <span id="'+id+'_preview_image" class="template-element-image-url" style="background-image: url(\''+imageUrl+'\')"></span>' +
            '      <span id="'+id+'_preview_title" class="template-element-title">'+title+'</span>' +
            '      <span id="'+id+'_preview_subtitle" class="template-element-subtitle">'+subtitle+'</span>' +
            '      <span id="'+id+'_preview_url"  class="template-element-url" style="display: '+display+';" >'+stripUrl(url)+'</span>' +
            '<div class="button_container_preview"></div>'+
            '          </span>' +
            '      </span>' +
            '  </div>' +
            '</div>');

        $('.ui.dropdown').dropdown();

    }

    cardSelector(){
        return "#" + this.type + "_card_settings";
    }

    generateButtonInput(buttonId, buttonValue) {
        let that = this;


        let buttonHtml = '<div class="row button-container" id="' + buttonId + '_container">' +
            '<div class="col-lg-11"> ' +
            '<input type="text"  class="msg_text form-control input-lg" data-action="button-input"  data-emojiable="true" id="' + buttonId + '" value="' + buttonValue + '" placeholder="Button text (max. 20 characters)"  maxlength="20" />' + window.personalizationHtml + airPickerHtml() +
            '</div>' +
            '<div class="col-lg-1 delete-button-container"> ' +
            '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-button"  data-button-id="' + buttonId + '"  ></i>' +
            '</div>' +
            '</div>';



        $("#" + that.type + "_card_settings #buttons_preview").append('<div id="' + buttonId + '_preview"  class="broadcast_preview_buttons_bottom">' + convertEmojiUtfToImage(buttonValue.toUpperCase()) + '</div>');
        $("#" + that.type + "_card_settings " + Flowcomposer.buttonsContainerSelector).filter(':parents(#template_element_container)').append(buttonHtml);



        window.emojiPicker.discover();

        $("#" + that.type + "_card_settings #"+buttonId).characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });


        that.adjustButtonsPreview(true);

        $('.ui.dropdown').dropdown();



    }


    generateIceBreakerInput(buttonId, buttonValue) {
        let that = this;

        let buttonHtml = '<div class="row button-container" id="' + buttonId + '_container">' +
            '<div class="col-lg-11"> ' +
            '<input type="text"  class="msg_text form-control input-lg" data-action="ice-breaker-input"  data-emojiable="true" id="' + buttonId + '" value="' + buttonValue + '" placeholder="Ice Breaker text (max. 50 characters)"  maxlength="50" />' + window.personalizationHtml + airPickerHtml() +
            '</div>' +
            '<div class="col-lg-1 delete-ice-breaker-container"> ' +
            '<i class="fa icon-cross delete-ice-breaker" aria-hidden="true" data-action="delete-ice-breaker"  data-button-id="' + buttonId + '"  ></i>' +
            '</div>' +
            '</div>';



        $("#" + that.type + "_card_settings " + Flowcomposer.buttonsContainerSelector).append(buttonHtml);
        window.emojiPicker.discover();

        $("#" + that.type + "_card_settings #"+buttonId).characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });

        $("#" + that.type + "_card_settings #buttons_preview").append('<div id="' + buttonId + '_preview"  class="broadcast_preview_buttons_bottom ice-breaker">' + convertEmojiUtfToImage(buttonValue) + '</div>');

        $('.ui.dropdown').dropdown();

    }

    adjustCarouselPreview(event) {
        let count = Object.keys(this.templateElements).length;
        if (event === "deleted"){
            $("#" + this.type + "_card_settings .carousel_previous").trigger("click");
        }
        if (count === 1){
            $("#" + this.type + "_card_settings .carousel_next").css("display","none");
            $("#" + this.type + "_card_settings .carousel_previous").css("display","none");
        }

        else {
            $("#" + this.type + "_card_settings .carousel_next").css("display","block");
        }


    }

        adjustButtonsPreview(addedButton){
        let buttons = Object.keys(this.buttons);
        if (addedButton) buttons.length += 1;

            if (this instanceof MediaCard) {
                if (buttons.length){


                    this.hideLinkContainer(true);
                    this.showUploadContainer(true);
                }
                else {
                    if (typeof this.attachmentId !== "undefined" && this.attachmentId){
                        this.showUploadContainer(true);
                        this.hideLinkContainer(true);
                    }
                    else {
                        this.showLinkContainer(true);
                        this.hideUploadContainer(true);
                    }
                }
            }

        if (buttons.length === 1){
            $("#" + this.type + "_card_settings #" + buttons[0] + "_preview").removeClass().addClass("broadcast_preview_buttons_bottom");

        }


        else if (buttons.length === 2){
            $("#" + this.type + "_card_settings #" + buttons[0] + "_preview").removeClass().addClass("broadcast_preview_buttons_top");
            $("#" + this.type + "_card_settings #" + buttons[1] + "_preview").removeClass().addClass("broadcast_preview_buttons_bottom");

        }

        else if (buttons.length === 3){
            $("#" + this.type + "_card_settings #" + buttons[0] + "_preview").removeClass().addClass("broadcast_preview_buttons_top");
            $("#" + this.type + "_card_settings #" + buttons[1] + "_preview").removeClass().addClass("broadcast_preview_buttons_middle");
            $("#" + this.type + "_card_settings #" + buttons[2] + "_preview").removeClass().addClass("broadcast_preview_buttons_bottom");

        }

    }


    generateQuickReplyInput(quickReplyId, quickReplyValue,contentType) {

        // Modal HTML elements
        let quickReplyHtml = "";
        let that = this;
        if (contentType === "text") {

            quickReplyHtml = '<div class="row quickreply-container" id="' + quickReplyId + '_container">' +
                '<div class="col-lg-11"> ' +
                '<input type="text" class="msg_text form-control input-lg" data-action="quickreply-input" data-target-type="quickreply" data-emojiable="true" id="' + quickReplyId + '" value="' + quickReplyValue + '" placeholder="Quick Reply text (max. 20 characters)"  maxlength="20" />'  + window.personalizationHtml + airPickerHtml() +
                '</div>' +
                '<div class="col-lg-1 delete-quickreply-container"> ' +
                '<i class="fa icon-cross delete-quickreply" aria-hidden="true" data-action="delete-quickreply" data-target-type="quickreply" data-quickreply-id="' + quickReplyId + '"  ></i>' +
                '</div>' +
                '</div>';
            that.adjustQuickReply(quickReplyId,quickReplyValue,quickReplyHtml);

        }
        else {
            if (contentType === "user_email") {
                quickReplyHtml = '<div class="row quickreply-container" id="' + quickReplyId + '_container">' +
                    '<div class="col-lg-11"> ' +
                    '<div class="emoji-wysiwyg-editor msg_text form-control input-lg" maxlength="20"  style="height: 46px;">Subscriber\'s Email Address</div>' +
                    '</div>' +
                    '<div class="col-lg-1 delete-quickreply-container"> ' +
                    '<i class="fa icon-cross delete-quickreply" aria-hidden="true" data-action="delete-quickreply" data-target-type="quickreply" data-quickreply-id="' + quickReplyId + '"  ></i>' +
                    '</div>' +
                    '</div><div style="\n' +
                    '    margin-bottom: 20px;\n' +
                    '"></div>';

            }

            else if (contentType === "user_phone_number") {
                quickReplyHtml = '<div class="row quickreply-container" id="' + quickReplyId + '_container">' +
                    '<div class="col-lg-11"> ' +
                    '<div class="emoji-wysiwyg-editor msg_text form-control input-lg" maxlength="20"  style="height: 46px;">Subscriber\'s Phone number</div>' +
                    '</div>' +
                    '<div class="col-lg-1 delete-quickreply-container"> ' +
                    '<i class="fa icon-cross delete-quickreply" aria-hidden="true" data-action="delete-quickreply" data-target-type="quickreply" data-quickreply-id="' + quickReplyId + '"  ></i>' +
                    '</div>' +
                    '</div><div style="\n' +
                    '    margin-bottom: 20px;\n' +
                    '"></div>';

            }

            else if (contentType === "location") {
                quickReplyHtml = '<div class="row quickreply-container" id="' + quickReplyId + '_container">' +
                    '<div class="col-lg-11"> ' +
                    '<div class="emoji-wysiwyg-editor msg_text form-control input-lg" maxlength="20"  style="height: 46px;">Send location</div>' +
                    '</div>' +
                    '<div class="col-lg-1 delete-quickreply-container"> ' +
                    '<i class="fa icon-cross delete-quickreply" aria-hidden="true" data-action="delete-quickreply" data-target-type="quickreply" data-quickreply-id="' + quickReplyId + '"  ></i>' +
                    '</div>' +
                    '</div><div style="\n' +
                    '    margin-bottom: 20px;\n' +
                    '"></div>';

            }
            this.adjustQuickReply(quickReplyId,quickReplyValue,quickReplyHtml);
            $('.ui.dropdown').dropdown();

        }

    }

    adjustQuickReply(quickReplyId, quickReplyValue,quickReplyHtml){

        $("#" + this.type + "_card_settings " + Flowcomposer.quickRepliesContainerSelector).append(quickReplyHtml);



        // Preview HTML Elements
        $("#" + this.type + "_card_settings #quickreplies_preview").append('<div id="' + quickReplyId + '_preview" class="preview_quick_item">\n' +
            '                <div id="preview_quick_content">\n' +
            '                    <div id="' + quickReplyId + '_image_preview"></div>\n' +
            '                    <div id="' + quickReplyId + '_title_preview" class="preview_quick_title">' + convertEmojiUtfToImage(quickReplyValue) + '</div>\n' +
            '                </div>\n' +
            '            </div>');


        window.emojiPicker.discover();

        $("#" + this.type + "_card_settings #"+quickReplyId).characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });

    }

    generateIceBreakersJson() {

        let iceBreakers = [];
        for (let key in this.iceBreakers) {
            let iceBreaker = this.iceBreakers[key];
            let temporaryIceBreaker = {};
            temporaryIceBreaker.title = iceBreaker.title;


            iceBreakers.push(temporaryIceBreaker);
        }

        return iceBreakers;
    }

    generateQuickRepliesJson() {

        let quickReplies = [];
        for (let key in this.quickReplies) {
            let quickReply = this.quickReplies[key];
            let temporaryQuickReply = {};
            temporaryQuickReply.payload = quickReply.payload;
            temporaryQuickReply.title = quickReply.title;
            temporaryQuickReply.image_url = quickReply.imageUrl;
            temporaryQuickReply.content_type = quickReply.contentType;
            quickReplies.push(temporaryQuickReply);
        }

        return quickReplies;
    }


    generateTemplateElementsJson(){
        let elements = [];
        for (let id in this.templateElements) {
            let element = this.templateElements[id];
            let temporaryElement = {};
            temporaryElement.title = element.title;
            temporaryElement.subtitle = element.subtitle;
            temporaryElement.image_url = element.imageUrl;
            if (Object.keys(element.defaultAction).length) {
                temporaryElement.default_action = {};
                temporaryElement.default_action.type = element.defaultAction.type;
                temporaryElement.default_action.url = element.defaultAction.url;
                temporaryElement.default_action.messenger_extensions = element.defaultAction.messengerExtensions;
                temporaryElement.default_action.webview_height_ratio = element.defaultAction.webviewHeightRatio;

            }
            if (Object.keys(element.buttons).length)
                temporaryElement.buttons = element.generateButtonsJson();
            elements.push(temporaryElement);
        }

        return elements;
    }

    // Changes should be mirrored to template lists/generic lists
    generateButtonsJson() {

        let buttons = [];
        for (let key in this.buttons) {
            let button = this.buttons[key];
            let temporaryButton = {};
            temporaryButton.type = button.type;
            temporaryButton.title = button.title;
            if (button.type === "postback" || button.type=== "phone_number") {
                temporaryButton.payload = button.payload;
            }
            else if (button.type === "web_url"){
                temporaryButton.url = button.url;
                temporaryButton.webview_height_ratio = button.webviewHeightRatio;
                temporaryButton.messenger_extensions = button.messengerExtensions === "true";
            }

            else {
                delete temporaryButton.title;

            }


            buttons.push(temporaryButton);
        }

        return buttons;


    }

    get quickReplies() {
        return this._quickReplies;
    }

    set quickReplies(value) {
        this._quickReplies = value;
    }

    get initialOutputs() {
        return this._initialOutputs;
    }

    set initialOutputs(value) {
        this._initialOutputs = value;
    }

    get links() {
        return this._links;
    }

    set links(value) {
        this._links = value;
    }

    get title() {
        return this._title;
    }

    set title(value) {
        if (value.length)
            $("#"+this.type+"_card_settings [data-action='card-title-input']").removeClass("input-error");
        else
            $("#"+this.type+"_card_settings [data-action='card-title-input']").addClass("input-error");
        this._title = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get buttons() {
        return this._buttons;
    }

    set buttons(value) {
        this._buttons = value;
    }

    disconnectConnectedButtons(){
        let links = Flowcomposer.getTemporaryLinksToCard(this.id,"button");
        for (let linkId in links){
            flowcomposer.disconnectedButtons[links[linkId].fromConnector] = 1;
        }
    }

    applyCardTitle() {
        let data = this.getOperatorData();
        if (typeof data.properties !== "undefined") {

            data.properties.title = this.title;
            this.setOperatorData(data);
        }
    }

    addTemplateElement(templateElement) {
        let count = Object.keys(this.templateElements).length;
        let type = "list";
        let maximumInputs = Flowcomposer.maximumListElements;
        if (this instanceof CarouselCard){
            type = "carousel";
            maximumInputs = Flowcomposer.maximumCarouselElements;

        }


        if (count < maximumInputs) {

            this.templateElements[templateElement.id] = templateElement;

            if (type === "list")
                this.generateListElementInput(templateElement.id, templateElement.title,templateElement.subtitle,templateElement.imageUrl,templateElement.defaultAction);
            else
                this.generateCarouselElementInput(templateElement.id, templateElement.title,templateElement.subtitle,templateElement.imageUrl,templateElement.defaultAction);


            if (count === (maximumInputs - 1)) {
                this.$addTemplateElement.hide();
                return false;
            }

            return true;
        }

        this.$addTemplateElement.hide();
        return false;

    }


    addButton(button) {
        let buttonsCount = Object.keys(this.buttons).length;

        let maximumButtons = Flowcomposer.maximumButtons;

        if (this instanceof ListCard)
            maximumButtons = Flowcomposer.maximumListButtons;

        if (buttonsCount < maximumButtons) {
            this.generateButtonInput(button.id, button.title);
            this.buttons[button.id] = button;

            if (buttonsCount === (maximumButtons - 1)) {
                this.$addButton.hide();
                return false;
            }

            this.$addIceBreaker.hide();


            return true;
        }

        this.$addButton.hide();
        return false;

    }

    addIceBreaker(button) {

        this.generateIceBreakerInput(button.id, button.title);
        this.iceBreakers[button.id] = button;
        this.$addButton.hide();

        return true;

    }

    addQuickReply(quickReply) {

        let quickRepliesCount = Object.keys(this.quickReplies).length;
        if (quickRepliesCount  < 1) {
            $(".quick_next").css("visibility","hidden");
            $(".quick_previous").css("visibility","hidden");
        }
        else {
            $(".quick_next").css("visibility","visible").css("display","block");

        }
        if (quickRepliesCount < Flowcomposer.maximumQuickReplies) {
            this.generateQuickReplyInput(quickReply.id, quickReply.title,quickReply.contentType);

            this.quickReplies[quickReply.id] = quickReply;

            if (quickRepliesCount === (Flowcomposer.maximumQuickReplies - 1)) {
                this.$addQuickReply.hide();

                return false;
            }

            return true;
        }
        this.$addQuickReply.hide();

        return false;

    }


    deleteAllButtons(){
        for (let buttonId in this.buttons){
            this.deleteButton(buttonId);
        }

        return this;
    }


    deleteAllTemplateElements(){
        for (let elementId in this.templateElements){
            this.deleteTemplateElement(elementId);
        }

        return this;
    }

    deleteAllIceBreakers(){
        for (let buttonId in this.iceBreakers){
            this.deleteIceBreaker(buttonId);
        }

        return this;
    }

    deleteButton(id) {
        $("#" + this.type +"_card_settings #" + id + "_container").remove();
        $("#" + this.type +"_card_settings #" + id + "_preview").remove();
        $("#" + this.type +"_card_settings #" + id + "_preview_button").remove();

        if (id.includes("elbt")) {
            let elementId = id.replace("elbt","").split("_")[0];
            delete this.templateElements[elementId].buttons[id];
            this.$addListButton(elementId).show();
        }
        else {
            delete this.buttons[id];
            this.$addButton.show();
        }

        let data = Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);
        if (data.properties.outputs[id]) {
            delete data.properties.outputs[id];
        }


        if (!Object.keys(this.buttons).length)
            $("#" + this.type +"_card_settings [data-action='add-ice-breaker']").show();

        this.adjustButtonsPreview();
        delete flowcomposer.disconnectedButtons[id];
        Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);
        if (this instanceof MediaCard){
            if (!Object.keys(this.buttons).length)
            if (!this.url && !this.attachmentId) {
                this.showLinkContainer(true);
                this.hideUploadContainer(true);
            }
        }
    }

    deleteTemplateElement(id) {
        if (Object.keys(this.templateElements).length <= 2 && this instanceof ListElement) return 0;
        else if (Object.keys(this.templateElements).length <= 1 && this instanceof CarouselElement) return 0;
        delete this.templateElements[id];
        $("#" + this.type +"_card_settings #" + id + "_container").remove();
        $("#" + this.type +"_card_settings #" + id + "_preview").remove();
        $("#" + this.type +"_card_settings #" + id + "_slide").remove();
        this.$addTemplateElement.show();

        let data = Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);
        if (data.properties.outputs[id]) {
            delete data.properties.outputs[id];
        }


        if (!Object.keys(this.buttons).length)
            $("#" + this.type +"_card_settings [data-action='add-ice-breaker']").show();

        this.adjustCarouselPreview("deleted");
        delete flowcomposer.disconnectedButtons[id];
        Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);

    }

    deleteIceBreaker(id) {
        delete this.iceBreakers[id];
        $("#" + this.type +"_card_settings #" + id + "_container").remove();
        $("#" + this.type +"_card_settings #" + id + "_preview").remove();
        if (!Object.keys(this.iceBreakers).length)
            $("#" + this.type +"_card_settings [data-action='add-button']").show();

        let data = Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);
        if (data.properties.outputs[id]) {
            delete data.properties.outputs[id];
        }

        this.adjustButtonsPreview();
        delete flowcomposer.disconnectedButtons[id];
        Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);

    }

    deleteQuickReply(id) {
        delete this.quickReplies[id];
        $("#" + this.type +"_card_settings #" + id + "_container").remove();
        $("#" + this.type +"_card_settings #" + id + "_preview").remove();
        $("#" + this.type +"_card_settings [data-action='add-quickreply']").show();

        let data = Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);
        if (data.properties.outputs[id]) {
            delete data.properties.outputs[id];
        }
        Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);

    }


    getExistingLinks() {


        let links = Flowcomposer.$flowchart.flowchart("getData").links;
        let filteredLinks = [];
        let operatorId = this.id;

        $.each(links, function (key, link) {

            if (link.fromOperator === operatorId && link.fromConnector !== "output_0") {
                filteredLinks.push(links[key]);
            }
        });

        this.links = filteredLinks;
        return this.links;

    }

    deleteExistingLinks() {

        this.outputs = this.getExistingLinks();
        let data = Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);
        if ($.isEmptyObject(data) === false) {
            let outputLength = Object.keys(data.properties.outputs).length;

            $.each(data.properties.outputs, function (key, value) {
                if (!key.includes("output_") && !key.includes("success_") && !key.includes("failure_")) {
                    delete data.properties.outputs[key];
                }
            });

            Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);
            data = Flowcomposer.$flowchart.flowchart("getData");
        }
    }

    setFirstCard(showNotification) {

        let iconSource = Flowcomposer.firstCardIcon;
        $("[data-action='make-card-first'][data-card-id='" + this.id + "']").find("img").attr("src", iconSource).css("width","30px");
        flowcomposer.firstCard = this.id;
        if (showNotification)
            toastr.success(this.title+" set as first card", "Well done !");

    }

    unsetFirstCard() {
        let iconSource = Flowcomposer.secondaryCardIcon;
        $("[data-action='make-card-first'][data-card-id='" + this.id + "']").find("img").attr("src", iconSource).css("width","10px");


    }

    setAutoFirstCard() {
        let totalCards = Object.keys(flowcomposer.cards).length;

        if (totalCards === 1 && flowcomposer.firstCard !== this.id) {
            this.setFirstCard(true);
        }
        else if (flowcomposer.firstCard === this.id)
            $("[data-action='make-card-first'][data-card-id='" + this.id + "']").find("img").attr("src", Flowcomposer.firstCardIcon).css("width","30px");



    }

    loadSettingsShared() {

        let reachedMaximumButtons = this.loadButtons();
        if (reachedMaximumButtons) {
            this.$addButton.hide();
        }
        else  {
            this.$addButton.show();
            if (Object.keys(this.buttons).length)
                this.$addIceBreaker.hide();

        }

        let reachedMaximumQuickReplies = this.loadQuickReplies();

        if (reachedMaximumQuickReplies) {
            this.$addQuickReply.hide();
        }
        else {
            this.$addQuickReply.show();

        }


        if (this.loadIceBreakers()){
            this.$addButton.hide();

        }

        this.adjustButtonsPreview();

        $("#" + this.type + "_card_settings [data-action='card-title-input']").val(this.title);

        $("#" + this.type + "_card_settings .positive-keywords-container").html("");
        $("#" + this.type + "_card_settings .negative-keywords-container").html("");
        for (let index in this.positiveKeywords){
            $("#" + this.type + "_card_settings  .positive-keywords-container").append('<span class="chat_tags positive_chat_tags">' + this.positiveKeywords[index] + '<span data-action="delete-positive-keyword" class="delete_tag" > <i class="fa icon-cross"></i></span></span>');

        }
        for (let index in this.negativeKeywords){
            $("#" + this.type + "_card_settings  .negative-keywords-container").append('<span class="chat_tags negative_chat_tags">' + this.negativeKeywords[index] + '<span data-action="delete-negative-keyword" class="delete_tag" > <i class="fa icon-cross"></i></span></span>');

        }

        let mmlinkElement = $("#"+this.type+"_card_settings .mmlink");

        mmlinkElement.html(this.id+"_"+flowcomposer.flowId).closest("a").attr("href",mmlinkElement.parent().text());

        mmlinkElement = $("#"+this.type+"_card_settings .mmlink2");

        mmlinkElement.html(this.id+"_"+flowcomposer.flowId).closest("a").attr("href",mmlinkElement.parent().text());

        $("#"+this.type+"_card_settings .flow-id").html(flowcomposer.flowId);
        $("#"+this.type+"_card_settings .card-id").html(this.id);
        resetAirVariables(this.getConnectedAirVariables());
    }

    getEmptyButtons(){

        let emptyButtons = [];
        for (let buttonId in this.buttons){
            if (this.buttons[buttonId].title.length === 0)
                emptyButtons.push(this.buttons[buttonId].id);
        }

        return emptyButtons;

    }

    getEmptyQuickReplies(){

        let emptyQuickReplies = [];
        for (let quickReplyId in this.quickReplies){
            if (this.quickReplies[quickReplyId].title.length === 0)
                emptyQuickReplies.push(this.quickReplies[quickReplyId].id);
        }

        return emptyQuickReplies;

    }

    getEmptyElements(){
        let emptyButtons = this.getEmptyButtons();
        let emptyQuickReplies = this.getEmptyQuickReplies();

        return emptyButtons.concat(emptyQuickReplies);
    }


    highlightEmptyElements(){
        toastr.clear();
        let emptyElements = [];
        let invalidButtons = [];
        let invalidIceBreakers = [];
        let invalidQuickReplies = [];

        for (let buttonId in this.buttons){
            if (this.buttons[buttonId].title.length === 0) {
                $("#" + buttonId + " ~ .emoji-wysiwyg-editor").addClass("input-error");
                emptyElements.push(buttonId);
                invalidButtons.push(buttonId);
            }
            else
                $("#"+buttonId+ " ~ .emoji-wysiwyg-editor").removeClass("input-error");
        }


        if (invalidButtons.length)
            toastr.error("Button titles are required","Invalid button title");


        for (let buttonId in this.iceBreakers){
            if (this.iceBreakers[buttonId].title.length === 0) {
                $("#" + buttonId + " ~ .emoji-wysiwyg-editor").addClass("input-error");
                emptyElements.push(buttonId);
                invalidIceBreakers.push(buttonId);
            }
            else
                $("#"+buttonId+ " ~ .emoji-wysiwyg-editor").removeClass("input-error");
        }


        if (invalidIceBreakers.length)
            toastr.error("Button titles are required","Invalid ice breaker title");

        for (let quickReplyId in this.quickReplies){
            if (this.quickReplies[quickReplyId].title.length === 0) {
                $("#" + quickReplyId + " ~ .emoji-wysiwyg-editor").addClass("input-error");
                emptyElements.push(quickReplyId);
                invalidQuickReplies.push(quickReplyId);


            }
            else
                $("#"+quickReplyId+ " ~ .emoji-wysiwyg-editor").removeClass("input-error");
        }

        if (invalidQuickReplies.length)
            toastr.error("Quick reply titles are required","Invalid quick reply title");


        if (!this.title.length) {
            emptyElements.push(("title"));
            toastr.error("Card title is required","Invalid card title");
            $("#" + this.type + "_card_settings [data-action='card-title-input']").addClass("input-error");
        }

        if (typeof (this.text) !== "undefined" && !this.text.length) {
            toastr.error("Text message is required","Invalid text message");
            emptyElements.push(("id"));
            $("#" + this.type + "_card_settings [data-action='text-message']").addClass("input-error");
        }

        return emptyElements;

    }

    applySettings() {


        let emptyElements = this.highlightEmptyElements();
        if (emptyElements.length) {
            return false;
        }

        $(".filepond--file-info-sub").css("visibility","hidden");
        $(".filepond--file-info-main").css("margin-top","6px").css("height","15px");


        this.deleteExistingLinks();


        let data = this.getOperatorData();

        if ($.isEmptyObject(data) === false) {

            data = this.buildOutputs(data);
            data = this.buildTemplateElements(data);
            data = this.buildButtons(data);
            data = this.buildQuickReplies(data);
            data = this.buildIceBreakers(data);


            this.setOperatorData(data);
            this.redrawLinks();


        }


        Flowcomposer.$flowchart.flowchart("redrawLinksLayer");

        this.applyCardTitle();
        this.setPreview();
        this.setAutoFirstCard();
        this.generateJson();



        return true;

    }

    buildOutputs(data) {



        if (Object.keys(this.quickReplies).length && this instanceof EmailInputCard === false && this instanceof PhoneInputCard === false && this instanceof LocationInputCard === false  && this instanceof FreeInputCard === false && this instanceof MultipleInputCard === false ) {
            $.each(data.properties.outputs, function (key, value) {
                if (key.includes("output_"))
                    delete data.properties.outputs[key];
            });
        }
        else {


            if (this.initialOutputs) {
                let outputCount = 0, successCount = 0 , failureCount = 0 , eventCount = 0;
                for (let i = 0; i < this.initialOutputs.length; i++) {
                    if (this.initialOutputs[i].type === "output") {
                        if (!data.properties.outputs["output_" + outputCount])
                            data.properties.outputs["output_" + outputCount++] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class
                            };
                    }

                    else if (this.initialOutputs[i].type === "variant") {
                        if (!data.properties.outputs["output_" + outputCount])
                            data.properties.outputs["output_" + outputCount++] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class,
                                type : "variant",
                                index : i,
                            };
                    }
                    else if (this.initialOutputs[i].type === "success") {
                        if (!data.properties.outputs["success_" + successCount])
                            data.properties.outputs["success_" + successCount++] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class,
                                id: "success",

                            };
                    }
                    else if (this.initialOutputs[i].type === "failure") {
                        if (!data.properties.outputs["failure_" + failureCount])
                            data.properties.outputs["failure_" + failureCount++] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class,
                                id : "failure"
                            };
                    }

                    else if (this.initialOutputs[i].type === "link-ready") {
                        if (!data.properties.outputs["link-ready_" + this.id])
                            data.properties.outputs["link-ready_" + this.id] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class,
                                id : "link-ready"
                            };
                    }

                    else if (this.initialOutputs[i].type === "video-watched") {
                        if (!data.properties.outputs["video-watched_" + this.id])
                            data.properties.outputs["video-watched_" + this.id] = {
                                label: this.initialOutputs[i].label,
                                class: this.initialOutputs[i].class,
                                id : "video-watched"
                            };
                    }
                }
            }
        }

        return data;
    }

    buildQuickReplies(data) {


        for (let key in this.quickReplies) {
            let quickReply = this.quickReplies[key];
            if (quickReply.contentType !== "text") continue;
            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(quickReply.title, Flowcomposer.buttonsTrimLength),
                type: "quickreply",
                class: "quickreply",
                id: key,
            };
        }
        return data;
    }


    buildIceBreakers(data) {


        for (let key in this.iceBreakers) {
            let iceBreaker = this.iceBreakers[key];
            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(iceBreaker.title, Flowcomposer.buttonsTrimLength),
                type: "icebreaker",
                class: "icebreaker",
                id: key,
            };
        }
        return data;
    }

    buildTemplateElements(data) {

        for (let key in this.templateElements) {
            let templateElement = this.templateElements[key];


            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(templateElement.title, Flowcomposer.buttonsTrimLength),
                type: "template-element",
                class: "template-element",
                id: key,
            };

            for (let key in templateElement.buttons) {
                let button = templateElement.buttons[key];

                if (typeof button.type === "undefined" || (button.type === "phone" && button.payload === "null")) {

                    flowcomposer.disconnectedButtons[key] = 1;
                }

                data.properties.outputs[key] = {
                    label: Flowcomposer.trimString(button.title, Flowcomposer.buttonsTrimLength),
                    type: "button",
                    class: "button",
                    id: key,
                };

            }

        }



        return data;
    }

    buildButtons(data) {

        for (let key in this.buttons) {
            let button = this.buttons[key];

            if (typeof button.type === "undefined" || (button.type === "phone" && button.payload === "null")) {

                flowcomposer.disconnectedButtons[key] = 1;
            }

            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(button.title, Flowcomposer.buttonsTrimLength),
                type: "button",
                class: "button",
                id: key,
            };

        }




        return data;
    }

    redrawLinks() {
        let that = this;
        $.each(this.links, function (key, link) {

            Flowcomposer.$flowchart.flowchart("addLink", link);

        });

        this.resetLinks();
    }

    resetLinks() {
        this.links = [];

    }

    getOperatorData() {
        return Flowcomposer.$flowchart.flowchart("getOperatorData", this.id);

    }

    setOperatorData(data) {
        Flowcomposer.$flowchart.flowchart("setOperatorData", this.id, data);
        return data;
    }

}

class TextCard extends Card {


    constructor(id, outputs, text, buttons, quickReplies, iceBreakers,title,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "text", buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords,iceBreakers);
        if (!text)
            this._text = Flowcomposer.defaultText;
        else
            this._text = text;

        this.setPreview();
    }



    get text() {
        return this._text;
    }

    set text(value) {
        if (value.length)
            $("[data-action='text-message']").next(".emoji-wysiwyg-editor").removeClass("input-error");
        else
            $("[data-action='text-message']").next(".emoji-wysiwyg-editor").addClass("input-error");

        this._text = value;
        $("#text_card_settings #text_message_preview").html(convertEmojiUtfToImage(value));

    }


    generateJson(){
        let temporaryJson = {};
        temporaryJson.message = {};
        if (Object.keys(this.buttons).length){
            temporaryJson.message.attachment = {};
            temporaryJson.message.attachment.type = "template";
            temporaryJson.message.attachment.payload = {};
            temporaryJson.message.attachment.payload.template_type = "button";
            temporaryJson.message.attachment.payload.text = this.text;
            temporaryJson.message.attachment.payload.buttons = this.generateButtonsJson();
        }
        else {
            temporaryJson.message.text = this.text;
        }
        if (Object.keys(this.quickReplies).length) {
            temporaryJson.message.quick_replies = this.generateQuickRepliesJson();
        }
        if (Object.keys(this.iceBreakers).length) {
            temporaryJson.message.ice_breakers = this.generateIceBreakersJson();
        }
        this.json = JSON.stringify(temporaryJson);

    }
    loadSettings() {

        this.loadSettingsShared();
        this.generateJson();
        $("#text_card_settings [data-action='text-message']").siblings(".emoji-wysiwyg-editor").first().html(convertEmojiUtfToImage(this.text)).trigger("change");
        $("#text_card_settings #text_message_preview").css("display", "block");

    }

    setPreview() {

        $("#" + this.id + "_message").html(convertEmojiUtfToImage(Flowcomposer.trimString(this.text, Flowcomposer.textMessageTrimLength)));


    }


}

class ListCard extends Card {


    constructor(id, outputs, topElementStyle,templateElements,buttons, quickReplies,title,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "list", buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);
        if (!topElementStyle)
            this.topElementStyle = "compact";
        else
            this.topElementStyle = topElementStyle;

        if (!templateElements) {
            let firstId  = Flowcomposer.generateID();
            let secondId  = Flowcomposer.generateID();
            let title  = "Clever Messenger";
            let subtitle  = "More ways to make it yours!";
            let imageUrl  = "https://dl.dropbox.com/s/2lkkgdiwzn6qcah/clever-messenger.png?dl=0";
            this._templateElements = {};
            this._templateElements[firstId] = new ListElement(firstId,title,subtitle,imageUrl);
            this._templateElements[secondId] = new ListElement(secondId,title,subtitle,imageUrl);
        }
        else
            this._templateElements= templateElements;

        this.setPreview();

    }


    get topElementStyle() {
        return this._topElementStyle;
    }

    set topElementStyle(value) {
        this._topElementStyle = value;
    }

    get templateElements() {
        return this._templateElements;
    }

    set templateElements(value) {
        this._templateElements = value;
    }


    setCover(elementId){
        this.topElementStyle = "large";
        return this;
    }

    unsetCover(){
        this.topElementStyle = "compact";
        return this;

    }
    generateJson(){
        let temporaryJson = {};
        temporaryJson.message = {};
        temporaryJson.message.attachment = {};
        temporaryJson.message.attachment.type = "template";
        temporaryJson.message.attachment.payload = {};
        temporaryJson.message.attachment.payload.template_type = "list";
        temporaryJson.message.attachment.payload.top_element_style = this.topElementStyle;
        temporaryJson.message.attachment.payload.elements = this.generateTemplateElementsJson();
        temporaryJson.message.attachment.payload.buttons = this.generateButtonsJson();


        if (Object.keys(this.quickReplies).length) {
            temporaryJson.message.quick_replies = this.generateQuickRepliesJson();
        }
        if (Object.keys(this.iceBreakers).length) {
            temporaryJson.message.ice_breakers = this.generateIceBreakersJson();
        }
        this.json = JSON.stringify(temporaryJson);

    }


    loadSettings(uploadSuccess) {

        this.loadSettingsShared();
        this.generateJson();
        $("#list_card_settings #template_element_preview").css("display", "block");


        let reachedMaximumElements = this.loadTemplateElements();

        if (reachedMaximumElements) {
            this.$addTemplateElement.hide();
        }
        else  {
            this.$addTemplateElement.show();
        }
        
  

    }

    setPreview() {

    }


}

class CarouselCard extends Card {


    constructor(id, outputs,templateElements,buttons, quickReplies,title,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "carousel", buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);


        if (!templateElements) {
            let firstId  = Flowcomposer.generateID();
            let title  = "Clever Messenger";
            let subtitle  = "More ways to make it yours!";
            let imageUrl  = Flowcomposer.defaultCardImage;
            this._templateElements = {};
            let buttonA = "elbt"+firstId+"_"+Flowcomposer.generateID(3);
            let buttonB = "elbt"+firstId+"_"+Flowcomposer.generateID(3);
            let buttons = {[buttonA] : new Button("Visit Us",buttonA,"postback","null"),[buttonB]:new Button("Call Us",buttonB,"postback","null")};

            this._templateElements[firstId] = new CarouselElement(firstId,title,subtitle,imageUrl,buttons);
        }
        else
            this._templateElements= templateElements;

        this.setPreview();

    }



    get templateElements() {
        return this._templateElements;
    }

    set templateElements(value) {
        this._templateElements = value;
    }


    setCover(elementId){
        this.topElementStyle = "large";
        return this;
    }

    unsetCover(){
        this.topElementStyle = "compact";
        return this;

    }
    generateJson(){

        let temporaryJson = {};
        temporaryJson.message = {};
        temporaryJson.message.attachment = {};
        temporaryJson.message.attachment.type = "template";
        temporaryJson.message.attachment.payload = {};
        temporaryJson.message.attachment.payload.template_type = "generic";
        temporaryJson.message.attachment.payload.elements = this.generateTemplateElementsJson();


        if (Object.keys(this.quickReplies).length) {
            temporaryJson.message.quick_replies = this.generateQuickRepliesJson();
        }
        if (Object.keys(this.iceBreakers).length) {
            temporaryJson.message.ice_breakers = this.generateIceBreakersJson();
        }
        this.json = JSON.stringify(temporaryJson);

    }
    loadSettings(uploadSuccess) {

        this.loadSettingsShared();
        this.generateJson();
        $("#carousel_card_settings #carousel_preview_container").css("display", "block");


        let reachedMaximumElements = this.loadTemplateElements();

        if (reachedMaximumElements) {
            this.$addTemplateElement.hide();
        }
        else  {
            this.$addTemplateElement.show();
        }
       

    }

    setPreview() {

        //$("#" + this.id + "_message").html(convertEmojiUtfToImage(Flowcomposer.trimString(this.text, Flowcomposer.textMessageTrimLength)));


    }


}

class InputCard extends Card {


    constructor(id, outputs, type, customfield, text, buttons, quickReplies, nextOnSuccess, nextOnFailure,title,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, type, buttons, quickReplies,title,json,analytics,positiveKeywords,negativeKeywords);
        if (!text)
            this._text = Flowcomposer.defaultText;
        else
            this._text = text;

        this._customfield = customfield;


        this.setPreview();
        this._nextOnSuccess = nextOnSuccess;
        this._nextOnFailure = nextOnFailure;
    }


    get nextOnSuccess() {
        return this._nextOnSuccess;
    }

    set nextOnSuccess(value) {
        this._nextOnSuccess = value;
    }

    get nextOnFailure() {
        return this._nextOnFailure;
    }

    set nextOnFailure(value) {
        this._nextOnFailure = value;
    }


    get customfield() {
        return this._customfield;
    }

    set customfield(value) {
        $("#"+this.type+"_card_settings [data-action='input-customfield']").val(value);
        this._customfield = value;
    }

    get text() {
        return this._text;
    }

    set text(value) {
        if (value.length)
            $("#"+this.type+"_card_settings [data-action='text-message']").next(".emoji-wysiwyg-editor").removeClass("input-error");
        else
            $("#"+this.type+"_card_settings [data-action='text-message']").next(".emoji-wysiwyg-editor").addClass("input-error");

        this._text = value;
        $("#"+this.type+"_card_settings #text_message_preview").html(convertEmojiUtfToImage(value));

    }


    generateJson(){
        let temporaryJson = {};
        temporaryJson.message = {};

        if (Object.keys(this.buttons).length){
            temporaryJson.message.attachment = {};
            temporaryJson.message.attachment.type = "template";
            temporaryJson.message.attachment.payload = {};
            temporaryJson.message.attachment.payload.template_type = "button";
            temporaryJson.message.attachment.payload.text = this.text;
            temporaryJson.message.attachment.payload.buttons = this.generateButtonsJson();
        }
        else {
            temporaryJson.message.text = this.text;
        }
        if (Object.keys(this.quickReplies).length) {
            temporaryJson.message.quick_replies = this.generateQuickRepliesJson();
        }
        if (Object.keys(this.iceBreakers).length) {
            temporaryJson.message.ice_breakers = this.generateIceBreakersJson();
        }
        this.json = JSON.stringify(temporaryJson);

    }
    loadSettings() {

        $("#"+this.type+"_card_settings [data-action='input-customfield']").val(this.customfield);
        this.loadSettingsShared();
        this.generateJson();
        $("#"+this.type+"_card_settings [data-action='text-message']").siblings(".emoji-wysiwyg-editor").first().html(convertEmojiUtfToImage(this.text)).trigger("change");
        $("#"+this.type+"_card_settings #text_message_preview").css("display", "block");

    }

    setPreview() {

        $("#" + this.id + "_message").html(convertEmojiUtfToImage(Flowcomposer.trimString(this.text, Flowcomposer.textMessageTrimLength)));


    }

}

class MediaCard extends Card {
    constructor(id, outputs, type,url,attachmentId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords ) {
        super(id, outputs, type, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);

        this._attachmentId = attachmentId;
        this._url = url;

    }


    get url() {
        return this._url;
    }

    set url(value) {
        this._url = value;

        if (value)
            $("#"+this.type+"_card_settings [data-action='media-url'] ~ .emoji-wysiwyg-editor").removeClass("input-error");
        else
            $("#"+this.type+"_card_settings [data-action='media-url'] ~ .emoji-wysiwyg-editor").addClass("input-error");

        this.setPreview();

    }

    get attachmentId() {
        return this._attachmentId;
    }

    set attachmentId(value) {
        this._attachmentId = value;
    }

    deleteAttachment(){
        this.attachmentId = null;
        this.url = null;
        $("#"+this.type+"_card_settings [data-action='media-url']").val("");
        $("#"+this.type+"_card_settings [data-action='media-url'] ~ .emoji-wysiwyg-editor").html("");

    }

    deleteLink(){
        this.url = null;
        $("#"+this.type+"_card_settings [data-action='media-url']").val("");
        $("#"+this.type+"_card_settings [data-action='media-url'] ~ .emoji-wysiwyg-editor").html("");

    }
    setUploadError(error){

            $("#"+this.type+"_card_settings").find(".filepond--file-status-sub").waitUntilExists(function() {
                let that = this;
                setTimeout(function(){
                    $(that).html(error);
                    }, 200);

            });



    }

    highlightEmptyElements(){
        toastr.clear();
        let emptyFiles = [];


        if (!this.attachmentId && !this.url){
            $("#"+this.type+"_card_settings [data-action='media-url'] ~ .emoji-wysiwyg-editor").addClass("input-error");
            emptyFiles.push(1);
            toastr.error("Make sure you input the "+this.type+" URL or upload one.","Missing media");

        }

        else  if (Object.keys(this.buttons).length && (typeof this.attachmentId === "undefined" || !this.attachmentId)){
            emptyFiles.push(1);
            toastr.error("Image or video file needs to be uploaded for media cards with buttons.","Missing media");

        }



        return emptyFiles;

    }


    generateJson(){
        let temporaryJson = {};
        let element = {
            media_type : this.type,
            attachment_id: this.attachmentId,
        };

       /* if (typeof this.attachmentId==="undefined" || this.attachmentId===null) {
            delete element.attachment_id;
            let url = this._url;
            if (this.type === "image")
                url = Flowcomposer.defaultImageUrl;
            else if (this.type === "video")
                url = Flowcomposer.defaultVideoUrl;
            element.url = url;
        }
*/

        temporaryJson.message = {};
        temporaryJson.message.attachment = {};
        temporaryJson.message.attachment.payload = {};

        if ((this.type === "image" || this.type==="video") && Object.keys(this.buttons).length) {
            temporaryJson.message.attachment.type = "template";
            temporaryJson.message.cm_preview_url = this.url;
            temporaryJson.message.attachment.payload.template_type = "media";
            temporaryJson.message.attachment.payload.elements = [];
            element.buttons = this.generateButtonsJson();
            temporaryJson.message.attachment.payload.elements.push(element);
        }
        else {
            temporaryJson.message.attachment.type = this.type;
            if (typeof this.attachmentId==="undefined" || this.attachmentId===null) {
                temporaryJson.message.attachment.payload.url = this.url;
            }
            else
                temporaryJson.message.attachment.payload.attachment_id = this.attachmentId;
        }
        if (Object.keys(this.quickReplies).length)
            temporaryJson.message.quick_replies = this.generateQuickRepliesJson();
        this.json = JSON.stringify(temporaryJson);
        return this.json;
    }


    hideLinkContainer(labels=false){
        $("#"+this.type+"_card_settings .direct-link-container").hide();
        if (labels) {
            $("#" + this.type + "_card_settings .direct-upload-label").hide();
            $("#" + this.type + "_card_settings .upload-label").show();
        }
    }

    showLinkContainer(labels=false,labelsOnly=false){
        if (!labelsOnly)
            $("#"+this.type+"_card_settings .direct-link-container").show();

        if (labels) {
            $("#" + this.type + "_card_settings .upload-label").hide();
            $("#" + this.type + "_card_settings .direct-upload-label").show();
        }
    }

    hideUploadContainer(){
        $("#"+this.type+"_card_settings .upload-container").hide();
    }


    showUploadContainer(){
        $("#"+this.type+"_card_settings .upload-container").show();
    }


    linkUploadVisibilityHandler(){

        if (this.attachmentId) {
            this.hideLinkContainer(true)
        }

        else {
         this.showLinkContainer(true);
         this.hideUploadContainer(false);
        }


    }
    loadSettings(uploadSuccess) {

        this.loadSettingsShared();
        this.generateJson();

        $("#"+this.type+"_card_settings [data-action='media-url']").siblings(".emoji-wysiwyg-editor").first().html(this.url);
        let allowedExtensions = [];

        if (this.type === "video")
            allowedExtensions =  ["video/3g2","video/3gp","video/3gpp","video/asf","video/avi" ,"video/dat","video/divx","video/dv","video/f4v","video/flv","video/gif","video/m2ts","video/m4v","video/mkv","video/mod","video/mov","video/mp4","video/mpe","video/mpeg","video/mpeg4","video/mpg","video/mts","video/nsv","video/ogm","video/ogv","video/qt","video/tod","video/ts","video/vob","video/wmv"];
        else if (this.type === "image")
            allowedExtensions =  ["image/jpeg", "image/jpg","image/png", "image/bmp", "image/ico","image/gif","image/tiff"];
        else if (this.type === "audio")
            allowedExtensions = ["audio/mp3","audio/wma","audio/ogg"];

        let mockFile = [];
        if (this.attachmentId){
            mockFile = [
                {
                    options: {
                        type: 'local',

                        file: {
                            name: getFileNameFromUrl(this.url),
                            size: 0,
                            type: 'image/png'
                        }
                    }
                }
            ]
        }

        let pondOptions = {
            files: mockFile,
            acceptedFileTypes: allowedExtensions,
            fileValidateTypeLabelExpectedTypes: [
                'Try a different image'
            ],
            allowFileSizeValidation : true,
            maxFileSize : "20MB",
            labelIdle : "Drag & Drop your "+this.type+" or <span class='filepond--label-action'> Browse </span>",
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort) => {

                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);

                    const request = new XMLHttpRequest();
                    request.open('POST', 'forms/pondUploader.php?type=' + that.type);

                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };


                    request.onload = function () {
                        if (request.status >= 200 && request.status < 300) {
                            let res = request.responseText;
                            try {
                                res = JSON.parse(res);

                                if (typeof res.cdn !== "undefined") {
                                    flowcomposer.uploadHandler(res);

                                    load(res.file_id);
                                }
                                else {
                                    error('Something went wrong, try again or contact support.');
                                    that.setUploadError(res.error);
                                }
                            }
                            catch (e) {
                                error('Something went wrong, try again or contact support.');

                            }
                        }
                        else {
                            error('Something went wrong, try again or contact support.');
                        }
                    };

                    request.send(formData);

                    return {
                        abort: () => {
                            request.abort();
                            abort();
                        }
                    };
                }
            }

        };

        let that = this;
        $("#"+that.type+"_card_settings .upload-container").empty()
            .append(' <input type="file"' +
            'class="filepond"' +
            'name="filepond"' +
            'id="'+that.type+'-uploader">');

        let selector = "#"+that.type+"_card_settings #"+that.type+"-uploader";


            let inputElement = document.querySelector(selector);
            if ($(selector).hasClass("filepond--root")) {

                FilePond.destroy(inputElement);

            }
                let pond = FilePond.create(inputElement, pondOptions);
                that.linkUploadVisibilityHandler();
                that.adjustButtonsPreview();
                pond.on('removefile', function () {
                    that.showLinkContainer(true, true);
                   that.deleteAttachment();

                });

                pond.on('addfile', function () {
                    if (!that.attachmentId) {
                        $("#"+that.type+"_card_settings").find(".filepond--file-info-sub").waitUntilExists(function() {
                            let that = this;
                            setTimeout(function(){
                                $(that).css("visibility","visible");
                                $(".filepond--file-info-main").css("margin-top","0");
                            }, 500);

                        });

                    }
                    that.hideLinkContainer(true);
                });


        this.setPreview();
    }

    setUploadHtml(uploadSuccess,title){

        let deleteAttachmentHtml = '<i  style="position : relative;left: 156px;top: 28px;z-index: 999999" class="fa icon-cross" data-action="delete-attachment" data-id="CMBzQAhH8Jwc"></i>';
        let uploadClass = "qq-upload";
        if (typeof this.attachmentId === "undefined" ) title = "No "+this.type+" has been uploaded";
        if (uploadSuccess) uploadClass = "qq-upload-success";
        $("#"+this.type+"_card_settings .qq-upload-list").html('<li class="qq-file-id-0 '+uploadClass+'" qq-file-id="0">' +
            '                <div class="qq-progress-bar-container-selector qq-hide">' +
            '                    <div role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar" style="width: 100%;"></div>' +
            '                </div>' +
            '                <span class="qq-upload-spinner-selector qq-upload-spinner qq-hide"></span>\n' +
            '                <span class="qq-upload-file-selector qq-upload-file" title="'+title+'">'+getFileNameFromUrl(title)+'</span>' +
            '            </li>');

        $("#"+this.type+"_card_settings .qq-uploader").prepend(deleteAttachmentHtml).attr("qq-drop-area-text", "Drop your "+this.type+" here");;

    }
}
class ImageCard extends MediaCard {


    constructor(id, outputs, url, attachmentId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "image", url,attachmentId,buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);

        if (!url)
            this.url = Flowcomposer.defaultCardImage;

        this.setPreview();
    }


    setPreview() {
        $("#image_card_settings #image_message_preview").attr("src", this.url).parent().css("display", "block");

        $("#" + this.id + "_message").addClass("card-image-preview").css("background-image", 'url(\'' + this.url + '\')');

    }

}

class VideoCard extends MediaCard {


    constructor(id, outputs, url, attachementId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "video",url,attachementId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);

        if (!url)
            this._url = Flowcomposer.defaultCardVideo;


        this.setPreview();

    }


    setPreview() {

        $("#video_card_settings #video_message_preview source:first-of-type ").attr("src",this.url).parent().load();
        $("#video_card_settings #video_message_preview_container").css("display","block");
        Plyr.setup(("#video_card_settings #video_message_preview"), {
            controls: ['play-large','progress', 'fullscreen']
        });

        $("#" + this.id + "_message").html('<video controls><source src="' + this.url + '" type="video/mp4">Your browser does not support the video tag</video>');


        Plyr.setup(("#" + this.id + "_message video"), {
            controls: ['play-large']
        });

    }


}

class AudioCard extends MediaCard {


    constructor(id, outputs, url, attachmentId,buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "audio", url,attachmentId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);

        if (!url)
            this.url = Flowcomposer.defaultCardAudio;

        this.setPreview();

    }



    setPreview() {

        $("#audio_card_settings #audio_message_preview source:first-of-type ").attr("src",this.url).parent().load();
        $("#audio_card_settings #audio_message_preview_container").css("display","block");
        Plyr.setup(("#audio_card_settings #audio_message_preview"), {
            controls: ['play','progress', 'current-time' ]
        });

        $("#" + this.id + "_message").html('<audio controls ><source src="' + this.url + '" type="audio/mp3">Your browser does not support the audio tag</audio>');


        Plyr.setup(("#" + this.id + "_message audio"), {
            controls: ['play', 'progress', 'current-time']
        });

    }


}

class FlowCard extends Card {


    constructor(id, outputs, flowType, flowId, flowName, cardId,cardName,title,json,analytics,positiveKeywords,negativeKeywords) {
        if (!id)
            id = Flowcomposer.generateID(10);
        super(id, outputs,"flow",null,null,title,json,analytics,positiveKeywords,negativeKeywords);
        if (!flowType)
            this._flowType = "flow";
        else
            this._flowType = flowType;

        if (!flowId)
            this._flowId = 0;
        else
            this._flowId = flowId;

        if (!cardId)
            this._cardId = 0;
        else
            this._cardId = cardId;

        this._cardName = cardName;
        this._flowName = flowName;

    }


    get flowName() {
        return this._flowName;
    }

    set flowName(value) {
        this._flowName = value;
    }

    get cardName() {
        return this._cardName;
    }

    set cardName(value) {
        this._cardName = value;
    }

    get cardsMap() {
        return this._cardsMap;
    }

    set cardsMap(value){
        this._cardsMap = value;
    }

    get flowType() {
        return this._flowType;
    }

    set flowType(value) {

        this._flowType = value;
        let that = this;

        if (value === "flowcard") {
            $("#flow_card_settings #select_reference_flow_card_container").show();
            $("#flow_card_settings #select_reference_flow_container").show();
            getFlowCards(this.flowId).done(function(messages){
                window.cardsMap = JSON.parse(messages);
                populateFlowCards("#flow_card_settings #select_reference_flow_card",messages,that.cardId);

            });

        }
        else if (value === "flow") {
            $("#flow_card_settings #select_reference_flow_container").show();
            $("#flow_card_settings #select_reference_flow_card_container").hide();
            $("#flow_card_settings #select_reference_flow").change();
        }


    }

    get flowId() {
        return this._flowId;
    }

    set flowId(value) {

        let selector = "#flow_card_settings #flow-preview-container";
        $(selector).css("display","block");


        if (!value) {
            $("#flow_card_settings #select_reference_flow").addClass("input-error");
        }
        else {

            $("#flow_card_settings #select_reference_flow").removeClass("input-error");
            let that = this;

            let ajax_url = 'includes/admin-ajax.php';
            let data = {
                'action': 'get_flow_data',
                'flow_id' : value
            };
            jQuery.post(ajax_url, data, function (response) {

                if (response) {
                    try {
                        let flowData = JSON.parse(response);
                        if (flowData.name)
                            that.flowName = flowData.name;
                        that.setPreview();
                    }
                    catch (ex){

                    }
                }
            });

            this._flowId = value;
            if (this.flowType === "flowcard") {
                getFlowCards(this.flowId).done(function(messages){
                    window.cardsMap = JSON.parse(messages);
                    populateFlowCards("#flow_card_settings #select_reference_flow_card",messages,that.cardId);
                    that.cardId = that.cardId;
                    that.setPreview();

                });
            }


        }



    }

    get cardId() {

        return this._cardId;
    }

    getCardMap(){

    }
    set cardId(value) {

        let selector = "#flow_card_settings #flow-preview-container";
        $(selector).css("display","block");


        if (!value) {
            $("#flow_card_settings #select_reference_flow_card").addClass("input-error");
        }
        else {
            $("#flow_card_settings #select_reference_flow_card").removeClass("input-error");

        }

        let that = this;


        if (typeof window.cardsMap !== "undefined" && window.cardsMap.hasOwnProperty(value)){
            if (typeof window.cardsMap[value]._json !=="undefined") {
                let jsonArray = [];
                jsonArray.push(window.cardsMap[value]._json);
                this.cardName = window.cardsMap[value]._title;

            }
        }

        this._cardId = value;
    }


    loadSettings() {
        this.loadSettingsShared();
        $("#flow_card_settings #select_reference_type").val(this.flowType);
        $("#flow_card_settings #select_reference_flow").val(this.flowId);
        $("#flow_card_settings #select_reference_flow_card").val(this.cardId);
        this.flowType = this.flowType;
        this.flowId = this.flowId;
        this.cardId = this.cardId;

    }

    generateJson() {
        let temporaryJson = {};
        temporaryJson.message = {};
        temporaryJson.message.flow_data = {};
        temporaryJson.message.flow_data.flow_type = this.flowType;
        temporaryJson.message.flow_data.flow_id = this.flowId;
        temporaryJson.message.flow_data.card_id = this.cardId;
        temporaryJson.message.flow_data.card_name = this.cardName;
        temporaryJson.message.flow_data.flow_name = this.flowName;
        this.json =  JSON.stringify(temporaryJson);
        return this.json;
    }
    applySettings() {

        this.applyCardTitle();
        this.setPreview();
        this.generateJson();
        this.setAutoFirstCard();
        if (this.flowType === "flowcard" && !this.cardId) {
            toastr.error("Make sure you select a valid single message.", "Invalid single message");
            return false;
        }
        else if (this.flowType === "flow" && !this.flowId) {
            toastr.error("Make sure you select a valid flow.", "Invalid flow");
            return false;
        }

        return true;

    }

    setPreview() {
        if (this.flowType === "flowcard" && this.cardId)
            $("#" + this.id + "_message").html('<b>Go to card </b><a target="_blank" href="composer.php?flow=' + this.flowId + '&card='+this.cardId+'">'+this.cardName +'</a>');
        else if (this.flowType === "flow" && this.flowId) {
            $("#" + this.id + "_message").html('<b>Go to flow </b><a target="_blank" href="composer.php?flow=' + this.flowId + '">' + this.flowName + '</a>');
        }
        else
            $("#" + this.id + "_message").html('Pick a <b>flow</b> or <b>single card</b>');

    }


}

class FileCard extends MediaCard {


    constructor(id, outputs, url, attachmentId, buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "file", url,attachmentId,buttons, quickReplies, title,json,analytics,positiveKeywords,negativeKeywords);

        if (!url)
            this._url = Flowcomposer.defaultCardFile;


        this.setPreview();


    }


    setPreview() {

        $("#file_card_settings #file_name_preview").html(getFileNameFromUrl(this.url));
        $("#file_card_settings #file_message_preview").attr("href",this.url).parent().css("display","block");
        $("#" + this.id + "_message").html('<a href="' + this.url + '" target="_blank">' + getFileNameFromUrl(this.url) + '</a>');

    }


}

class UrlCard extends Card {


    constructor(id, outputs, url, webviewHeightRatio, messengerExtensions, title,analytics) {

        super(id, outputs, "url", null,null, title,null,analytics);

        if (!url) {
            this._url = Flowcomposer.defaultUrl;
        }
        else
            this._url = url;

        if (!webviewHeightRatio)
            this._webviewHeightRatio = "full";
        else
            this._webviewHeightRatio = webviewHeightRatio;

        if (!messengerExtensions)
            this._messengerExtensions = "false";
        else
            this._messengerExtensions = messengerExtensions;



        this.setPreview();

    }

    get messengerExtensions() {
        return this._messengerExtensions;
    }

    set messengerExtensions(value) {
        this._messengerExtensions = value;
    }


    get url() {
        return this._url;
    }

    set url(value) {
        if (!value) {
            $("#url_card_settings [data-action='url-input']").addClass("input-error");
        }
        else
            $("#url_card_settings [data-action='url-input']").removeClass("input-error");

        this._url = value;
    }

    get webviewHeightRatio() {
        return this._webviewHeightRatio;
    }

    set webviewHeightRatio(value) {
        this._webviewHeightRatio = value;
    }

    applySettings() {



        let connectedCards = this.getConnectedCards();
        for (let i=0;i<connectedCards.length;i++){
            var cardId = connectedCards[i].card_id;
            var buttonId = connectedCards[i].button_id;
            if (typeof flowcomposer.cards[cardId].templateElements !== "undefined" && flowcomposer.cards[cardId].templateElements.hasOwnProperty(buttonId)){
                flowcomposer.cards[cardId].templateElements[buttonId].defaultAction.url = this.url;
                flowcomposer.cards[cardId].templateElements[buttonId].defaultAction.webviewHeightRatio = this.webviewHeightRatio;
                flowcomposer.cards[cardId].templateElements[buttonId].defaultAction.messengerExtensions = this.messengerExtensions;
            }
            else if (typeof flowcomposer.cards[cardId].templateElements !== "undefined" && buttonId.includes("elbt")){
                var elementId = buttonId.replace("elbt","").split("_")[0];
                flowcomposer.cards[cardId].templateElements[elementId].buttons[buttonId].url = this.url;
                flowcomposer.cards[cardId].templateElements[elementId].buttons[buttonId].webviewHeightRatio = this.webviewHeightRatio;
                flowcomposer.cards[cardId].templateElements[elementId].buttons[buttonId].messengerExtensions = this.messengerExtensions;
            }

            else {
                flowcomposer.cards[cardId].buttons[buttonId].url = this.url;
                flowcomposer.cards[cardId].buttons[buttonId].webviewHeightRatio = this.webviewHeightRatio;
                flowcomposer.cards[cardId].buttons[buttonId].messengerExtensions = this.messengerExtensions;
            }

            flowcomposer.cards[cardId].generateJson();
        }


        this.setPreview();
        return true;


    }

    loadSettings(){
        this.loadSettingsShared();
        $("#url_card_settings [data-action = 'url-input']").val(this.url);
        $("#url_card_settings [data-action = 'webview-height-ratio-input']").val(this.webviewHeightRatio);
        $("#url_card_settings [data-action = 'messenger-extensions']").val(this.messengerExtensions);
    }

    setPreview() {
        if (this.messengerExtensions === "true")
            $("#" + this.id + "_message p").html("<b>URL : </b> <a href='" + this.url + "' target='_blank'>" + Flowcomposer.trimString(this.url) + "</a><br/><b>Messenger Extensions SDK </b>");
        else
            $("#" + this.id + "_message p").html("<b>URL : </b> <a href='" + this.url + "' target='_blank'>" + (this.url) + "</a><br/><b>Height : </b><a href='#'>" + this.webviewHeightRatio + "</a>");

    }


}

class WhatsAppCard extends UrlCard {


    constructor(id, outputs, phone, content, webviewHeightRatio, messengerExtensions, title,analytics) {

        super(id, outputs, "https://api.whatsapp.com/send?phone=&text=test", webviewHeightRatio, messengerExtensions, title,analytics);

        this.type = "whatsapp";
        if (!phone) {
            this.phone = Flowcomposer.defaultPhoneNumber;
        }
        else
            this.phone = phone;

        if (!content) {
            this.content = Flowcomposer.defaultText;
        }
        else
            this.content = content;



        this.setPreview();


    }


    get phone() {
        return this._phone;
    }

    set phone(value) {
        this._phone = value;
        this.url = 'https://api.whatsapp.com/send?phone='+value.replace("+","")+'&text='+(this.content);
    }

    get content() {
        return this._content;
    }

    set content(value) {
        this._content = value;
        this.url = 'https://api.whatsapp.com/send?phone='+this.phone.replace("+","")+'&text='+(value);
    }




    loadSettings(){
        this.loadSettingsShared();

        $("#whatsapp_card_settings [data-action ='wt-phone-number']").val(this.phone);
        $("#whatsapp_card_settings [data-action ='wt-text-message']").siblings(".emoji-wysiwyg-editor").first().html(convertEmojiUtfToImage(this.content)).trigger("change");
    }

    setPreview() {

            $("#" + this.id + "_message p").html("<b>To : </b> <a href='tel:" + this.phone + "' target='_blank'>" + (this.phone) + "</a><br/><span>" + this.content + "</span>");

    }


}

class ShareCard extends Card {


    constructor(id, outputs, title) {

        super(id, outputs, "share", null, null, title,null,null);
        this.setPreview();

    }




    applySettings() {


        /*  let connectedCards = this.getConnectedCards();
          for (let i=0;i<connectedCards.length;i++){
              flowcomposer.cards[connectedCards[i].card_id].buttons[connectedCards[i].button_id].payload = this.payload;
          }
  */
        this.setPreview();
        return true;

    }

    loadSettings(){
        this.loadSettingsShared();
    }

    setPreview() {
        $("#" + this.id + "_message p").html("<b>Share connected card</b> ");

    }


}


class PhoneCard extends Card {


    constructor(id, outputs, phone, title) {

        super(id, outputs, "phone", null, null, title,null,null);
        if (!phone)
            this._payload = Flowcomposer.defaultPhoneNumber;
        else
            this._payload = phone;

        this.setPreview();

    }



    get payload() {
        return this._payload;
    }

    set payload(value) {
        if (!value.length) {
            $("#phone_card_settings [data-action='phone-input']").addClass("input-error");
        }
        else
            $("#phone_card_settings [data-action='phone-input']").removeClass("input-error");

        this._payload = value;
    }


    applySettings() {

        /*
                if (!isValidPhoneNumber(this.payload)) {
                    toastr.error("Make sure you specify a valid phone number. eg: +16505551234", "Invalid phone number");
                    return false;
                }
        */

        let connectedCards = this.getConnectedCards();
        for (let i=0;i<connectedCards.length;i++){
            var cardId = connectedCards[i].card_id;
            var buttonId = connectedCards[i].button_id;
            if (typeof flowcomposer.cards[cardId].templateElements !== "undefined" && flowcomposer.cards[cardId].templateElements.hasOwnProperty(buttonId)){
                flowcomposer.cards[cardId].templateElements[buttonId].defaultAction.payload = this.payload;
            }
            else if (typeof flowcomposer.cards[cardId].templateElements !== "undefined" && buttonId.includes("elbt")){
                var elementId = buttonId.replace("elbt","").split("_")[0];
                flowcomposer.cards[cardId].templateElements[elementId].buttons[buttonId].payload = this.payload;
            }

            else {
                flowcomposer.cards[cardId].buttons[buttonId].payload = this.payload;

            }

            flowcomposer.cards[cardId].generateJson();
        }



        this.setPreview();
        return true;

    }

    loadSettings(){
        this.loadSettingsShared();
        $("#phone_card_settings [data-action = 'phone-input']").val(this.payload);
    }

    setPreview() {
        $("#" + this.id + "_message p").html("<b>Call </b> <a target='_blank' href='tel:"+this.payload+"'>" + this.payload + "</a>");

    }


}

class ConditionCard extends Card {



    constructor(id, outputs, segment, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {
        super(id, outputs, "condition", null, null, title,json,analytics,positiveKeywords,negativeKeywords);

        if (segment)
            this._segment = segment;
        else
            this._segment = "select";

        this.setPreview();
        this._segment = segment;
        this._nextOnSuccess = nextOnSuccess;
        this._nextOnFailure = nextOnFailure;
    }


    get nextOnSuccess() {
        return this._nextOnSuccess;
    }

    set nextOnSuccess(value) {
        this._nextOnSuccess = value;
    }

    get nextOnFailure() {
        return this._nextOnFailure;
    }

    set nextOnFailure(value) {
        this._nextOnFailure = value;
    }

    get segment() {
        return this._segment;
    }

    set segment(value) {
        $("#"+this.type+"_card_settings [data-action='input-segment']").val(value);
        this._segment = value;
    }

    generateJson() {
        let temporaryJson = {};
        temporaryJson.segment  = this.segment;
        this.json =  JSON.stringify(temporaryJson);
        return this.json;
    }


    loadSettings(){
        this.loadSettingsShared();
        $("#"+this.type+"_card_settings [data-action='input-segment']").val(this.segment);


    }

    setPreview() {
        let previewSelector = "#" + this.id + "_message";

        if (this.segment) {
            getSegmentName(this.segment).done(function(segmentName){
                $(previewSelector).html("Segment <b> " +   segmentName + "</b>");

            });

        }
        else
            $(previewSelector).html("Specify a <b>segment </b>");



    }


}

class ActionCard extends Card {

    get actionSettingsExtra() {
        return this._actionSettingsExtra;
    }

    set actionSettingsExtra(value) {
        this._actionSettingsExtra = value;
    }



    constructor(id, outputs, actionType, actionSettings, title, json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "action", null, null, title,json,analytics,positiveKeywords,negativeKeywords);

        this._actionSettings = actionSettings;
        this._actionSettingsExtra = {};
        if (actionType)
            this._actionType = actionType;
        else
            this.actionType = "select";

        this.setPreview();

    }

    get actionType() {
        return this._actionType;
    }

    set actionType(value) {
        this._actionType = value;

        $(this.cardSelector()+ " .action-container").hide();
        $(this.cardSelector()+ " #"+value+"_action_settings_container").show();
        let that = this;
        let selector = "";
        switch (value){
            case "remove_tag":
                selector = this.cardSelector()+ ' [data-action="tag-to-remove"]';
                getPageTags().done(function(tags){
                    try {
                        tags = JSON.parse(tags);
                        $(selector).html("");
                        for (let i=0;i<tags.length;i++){
                            if (that.actionSettings.tag !== "undefined" && that.actionSettings.tag && that.actionSettings.tag === tags[i].id )
                                $(selector).append("<option selected value='"+tags[i].id+"'>"+tags[i].tag_name+"</option>");
                            else
                                $(selector).append("<option value='"+tags[i].id+"'>"+tags[i].tag_name+"</option>");


                        }
                    } catch(e) {
                    }
                });
                break;
            case "set_custom_field_value":
                selector = this.cardSelector()+' select[data-action="custom-field-to-set"]';
                getCustomFields().done(function(customFields){
                    try {
                        customFields = JSON.parse(customFields);
                        $(selector).html("");
                        for (let i=0;i<customFields.length;i++){
                            if (that.actionSettings.customField !== "undefined" && that.actionSettings.customField && that.actionSettings.customField === customFields[i].id )
                                $(selector).append("<option selected value='"+customFields[i].id+"'>"+customFields[i].customfield_name+" ("+customFields[i].customfield_type+")</option>");
                            else
                                $(selector).append("<option  value='"+customFields[i].id+"'>"+customFields[i].customfield_name+" ("+customFields[i].customfield_type+")</option>");


                        }
                    } catch(e) {
                    }
                });
                break;

            case "set_global_field_value":
                selector = this.cardSelector()+' select[data-action="global-field-to-set"]';
                getGlobalFields().done(function(globalFields){
                    try {
                        globalFields = JSON.parse(globalFields);
                        $(selector).html("");
                        for (let i=0;i<globalFields.length;i++){
                            if (that.actionSettings.globalField !== "undefined" && that.actionSettings.globalField && that.actionSettings.globalField === globalFields[i].id )
                                $(selector).append("<option selected value='"+globalFields[i].id+"'>"+globalFields[i].name+" ("+globalFields[i].type+")</option>");
                            else
                                $(selector).append("<option  value='"+globalFields[i].id+"'>"+globalFields[i].name+" ("+globalFields[i].type+")</option>");


                        }
                    } catch(e) {
                    }
                });
                break;

            case "clear_global_field":
                selector = this.cardSelector()+' select[data-action="global-field-to-clear"]';
                getGlobalFields().done(function(globalFields){
                    try {
                        globalFields = JSON.parse(globalFields);
                        $(selector).html("");
                        for (let i=0;i<globalFields.length;i++){
                            if (that.actionSettings.globalField !== "undefined" && that.actionSettings.globalField && that.actionSettings.globalField === globalFields[i].id )
                                $(selector).append("<option selected value='"+globalFields[i].id+"'>"+globalFields[i].name+" ("+globalFields[i].type+")</option>");
                            else
                                $(selector).append("<option  value='"+globalFields[i].id+"'>"+globalFields[i].name+" ("+globalFields[i].type+")</option>");


                        }
                    } catch(e) {
                    }
                });
                break;


            case "clear_custom_field":
                selector = this.cardSelector()+' select[data-action="custom-field-to-clear"]';
                getCustomFields().done(function(customFields){
                    try {
                        customFields = JSON.parse(customFields);
                        $(selector).html("");
                        for (let i=0;i<customFields.length;i++){
                            if (that.actionSettings.customField !== "undefined" && that.actionSettings.customField && that.actionSettings.customField === customFields[i].id )
                                $(selector).append("<option selected value='"+customFields[i].id+"'>"+customFields[i].customfield_name+" ("+customFields[i].customfield_type+")</option>");
                            else
                                $(selector).append("<option  value='"+customFields[i].id+"'>"+customFields[i].customfield_name+" ("+customFields[i].customfield_type+")</option>");


                        }
                    } catch(e) {
                    }
                });
                break;

        }

    }

    get actionSettings() {
        return this._actionSettings;
    }

    set actionSettings(value) {
        this._actionSettings = value;
    }



    generateJson() {
        let temporaryJson = {};
        temporaryJson.action_type  = this.actionType;
        temporaryJson.action_settings = this.actionSettings;
        this.json =  JSON.stringify(temporaryJson);
        return this.json;
    }

    applySettings() {

        this.actionSettings = $(this.cardSelector()+" #"+this.actionType+"_action_settings_container form").serializeObject();
        if (typeof this.actionSettingsExtra !== "undefined")
            this.actionSettings.extra = this.actionSettingsExtra;
        let selector = "";
        let errorFlag = 0;
        for (let inputName in this.actionSettings){
            if (inputName === "extra" || inputName === "search") continue;
            selector = this.cardSelector()+" #"+this.actionType+"_action_settings_container [name='"+inputName+"']";
            if (!$(selector).val().length){
                errorFlag = 1;
                $(selector).addClass("input-error");

            }
            else
                $(selector).removeClass("input-error");


        }
        if (errorFlag) {
            toastr.error("Make sure you specify valid values for the highlighted elements", "Missing values");
            return false;
        }

        if (this.actionType === "add_tag" && this.actionSettings.tag.length ){
            createTag(this.actionSettings.tag)
        }
        this.applyCardTitle();
        this.setPreview();
        this.generateJson();
        this.setAutoFirstCard();


        return true;

    }

    loadSettings(){
        this.loadSettingsShared();
        let typeSelector = this.cardSelector()+' [data-action="action-type-select"]';
        $(typeSelector).val(this.actionType).trigger("change");

        for (let inputName in this.actionSettings){

            let that = this;
            if (this.actionType !== "notify_admin") {

                setTimeout(function () {
                    $(this.cardSelector()+ " #" + that.actionType + "_action_settings_container [name='" + inputName + "']").val(that.actionSettings[inputName]);

                }, 200);
            }
            else {
                let selectizeElement =  $(this.cardSelector()+ " #" + that.actionType + "_action_settings_container [name='notification_recipient_ids']")[0].selectize;
                if (typeof selectizeElement !== "undefined")
                    selectizeElement.destroy();


                let recipients = that.actionSettings["notification_recipient_ids"];
                let options = [];

                if (typeof that.actionSettings["notification_recipient_ids"] === "undefined")
                    recipients = "false";
                else if (that.actionSettings["notification_recipient_ids"].constructor === Array)
                 recipients = that.actionSettings["notification_recipient_ids"].join();


                    let ajax_url = 'includes/admin-ajax.php';
                    let data = {
                        'action': 'get_subscribers_from_ids',
                        'recipients' : recipients,
                    };

                    jQuery.post(ajax_url, data, function (response) {
                        try {
                           options = JSON.parse(response);
                        } catch (e) {
                            options = [];
                        }

                        $(this.cardSelector()+" #" + that.actionType + "_action_settings_container [name='notification_recipient_ids']").selectize({
                            valueField: 'profile_id',
                            labelField: 'name',
                            maxItems: 5,
                            searchField: 'name',
                            create: false,
                            options: options,

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

                        selectizeElement =  $(this.cardSelector()+ " #" + that.actionType + "_action_settings_container [name='notification_recipient_ids']")[0].selectize;
                        for (let notifId in options) {
                            selectizeElement.addItem(options[notifId].profile_id);
                        }
                    });




                $(this.cardSelector()+" #" + that.actionType + "_action_settings_container [name='notification_message']").siblings(".emoji-wysiwyg-editor").first().html(convertEmojiUtfToImage(this.actionSettings.notification_message)).trigger("change");


            }

        }

    }

    setPreview() {
        let previewSelector = "#" + this.id + "_message";

        switch (this.actionType) {
            case "add_tag":
                $(previewSelector).html("Add tag <b> "+ this.actionSettings.tag+"</b>");
                break;

            case "remove_tag":
                getTagName(this.actionSettings.tag).done(function(tagName){
                    $(previewSelector).html("Remove tag <b> " +tagName+"</b>");

                });
                break;

            case "notify_admin":
                let person = "person";
                let number = "1";
                if (typeof this.actionSettings.notification_recipient_ids !== "undefined" &&  this.actionSettings.notification_recipient_ids.length) {
                    if (Object.prototype.toString.call(this.actionSettings.notification_recipient_ids ) === '[object Array]') {
                        person = "people";
                        number = this.actionSettings.notification_recipient_ids.length;
                    }
                    $(previewSelector).html("Notify <b> " + number + " " +person+"</b> via Messenger");
                }

                else
                    $(previewSelector).html("Select Notification <b>Recipients</b>");

                break;

            case "set_custom_field_value":
                var that = this;
                getCustomFieldName(this.actionSettings.custom_field).done(function(name){
                    $(previewSelector).html("Set Custom Field <b> " +name+"</b> to <b> "+that.actionSettings.custom_field_value+"</b>");

                });
                break;

            case "set_global_field_value":
                var that = this;
                getGlobalFieldName(this.actionSettings.global_field).done(function(name){
                    $(previewSelector).html("Set Global Field <b> " +name+"</b> to <b> "+that.actionSettings.global_field_value+"</b>");

                });
                break;

            case "clear_global_field":
                getGlobalFieldName(this.actionSettings.global_field).done(function(name){
                    $(previewSelector).html("Clear Global Field <b> " +name+"</b>");

                });
                break;

            case "clear_custom_field":
                getCustomFieldName(this.actionSettings.custom_field).done(function(name){
                    $(previewSelector).html("Clear Custom Field <b> " +name+"</b>");

                });
                break;


            case "pause_automation":
                $(previewSelector).html("Pause Bot Automation");
                break;

            case "resume_automation":
                $(previewSelector).html("Resume Bot Automation");
                break;

            case "clear_input":
                $(previewSelector).html("Clear Required Input");
                break;

            case "unsubscribe_flow":
                $(previewSelector).html("Unsubscribe from Flow");
                break;

            case "subscribe":
                $(previewSelector).html("Subscribe to Bot");
                break;


            case "unsubscribe":
                $(previewSelector).html("Unsubcribe from Bot");
                break;

            case "export_profile":
                $(previewSelector).html("Send Profile Data");
                break;


            case "delete_profile":
                $(previewSelector).html("Delete Profile");
                break;




            default :
                $(previewSelector).html("Specify a valid <b>action </b>");
                break;
        }

    }


}


class EmailInputCard extends InputCard
{


    constructor(id, outputs,customfield, text, buttons, quickReplies, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "email-input", customfield, text, buttons, quickReplies,nextOnSuccess,nextOnFailure, title,json,analytics,positiveKeywords,negativeKeywords);

    }




}



class PhoneInputCard extends InputCard
{


    constructor(id, outputs,customfield, text, buttons, quickReplies, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "phone-input", customfield, text, buttons, quickReplies,nextOnSuccess,nextOnFailure, title,json,analytics,positiveKeywords,negativeKeywords);

    }


}


class LocationInputCard extends InputCard
{


    constructor(id, outputs,customfield, text, buttons, quickReplies, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "location-input", customfield, text, buttons, quickReplies,nextOnSuccess,nextOnFailure, title,json,analytics,positiveKeywords,negativeKeywords);

    }




}

class FreeInputCard extends InputCard
{


    constructor(id, outputs,customfield, text, buttons, quickReplies, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "free-input", customfield, text, buttons, quickReplies,nextOnSuccess,nextOnFailure, title,json,analytics,positiveKeywords,negativeKeywords);

    }




}

class MultipleInputCard extends InputCard
{


    constructor(id, outputs,customfield, rules, text, buttons, quickReplies, title, nextOnSuccess, nextOnFailure,json, analytics,positiveKeywords,negativeKeywords) {

        super(id, outputs, "multiple-input", customfield, text, buttons, quickReplies,nextOnSuccess,nextOnFailure, title,json,analytics,positiveKeywords,negativeKeywords);

        this._rules = rules;
    }


    get rules() {
        return this._rules;
    }

    set rules(value) {
        this._rules = value;
    }

    generateRules() {
        let rules = [];
        let rule = {};
        for (let index in this.quickReplies) {
            rule = {};
            rule.title = this.quickReplies[index].title.toLowerCase();

            let quickReplyData = this.quickReplies[index].payload.split(":");
            rule.button_id = quickReplyData[2];

            if (typeof quickReplyData !== "undefined" && typeof quickReplyData[3] !== "undefined") {
                rule.next_card = quickReplyData[3];
            }
            else rule.next_card = false;

            rules.push(rule);

        }

        this.rules = rules;
    }

    applySettings() {

        let emptyElements = this.highlightEmptyElements();
        if (emptyElements.length) {
            return false;
        }

        this.deleteExistingLinks();


        let data = this.getOperatorData();

        if ($.isEmptyObject(data) === false) {


            data = this.buildOutputs(data);
            data = this.buildButtons(data);
            data = this.buildQuickReplies(data);
            data = this.buildIceBreakers(data);


            this.setOperatorData(data);
            this.redrawLinks();


        }


        Flowcomposer.$flowchart.flowchart("redrawLinksLayer");

        this.applyCardTitle();
        this.setPreview();
        this.setAutoFirstCard();
        this.generateJson();
        this.generateRules();


        return true;

    }

}

class ButtonInterface {

    constructor(id, title, type, analytics) {

        if (!id)
            id = Flowcomposer.generateID(10);

        this._id = id;
        if (title)
            this._title = title;
        else {
            if (this instanceof Button)
                this._title = "Button";
            else
                this._title = "Quick Reply";

        }

        if (typeof analytics !== "undefined")
            this._analytics = analytics;
        else
            this._analytics = {clicks : 0 };

        this._type = type;
        this._analytics = analytics;
    }


    get analytics() {
        return this._analytics;
    }

    set analytics(value) {
        this._analytics = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;

    }

    get title() {
        return this._title;
    }

    set title(value) {

        if (value.length)
            $("#"+this._id).next(".emoji-wysiwyg-editor").removeClass("input-error");
        else
            $("#"+this._id).next(".emoji-wysiwyg-editor").addClass("input-error");
        this._title = value;
        if (((flowcomposer.selectedCard instanceof ListCard) || (flowcomposer.selectedCard instanceof CarouselCard))  && typeof flowcomposer.listButton !== "undefined") {
            value = value.toUpperCase();
            $("#" + flowcomposer.selectedCard.type + "_card_settings #" + this._id + "_preview_button").html(convertEmojiUtfToImage(value));
        }
        else {
            if (this instanceof Button) value = value.toUpperCase();
            $("#" + flowcomposer.selectedCard.type + "_card_settings #" + this._id + "_preview").html(convertEmojiUtfToImage(value));
        }

    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }
}

class Button extends ButtonInterface {

    constructor(title, id,type,payload,url,webviewHeightRatio,messengerExtensions,analytics) {
        super(id, title,type,analytics);
        this._payload = payload;
        this._url = url;
        this._webviewHeightRatio = webviewHeightRatio;
        this._messengerExtensions = messengerExtensions;

    }


    get webviewHeightRatio() {
        return this._webviewHeightRatio;
    }

    set webviewHeightRatio(value) {
        this._webviewHeightRatio = value;
    }

    get messengerExtensions() {
        return this._messengerExtensions;
    }

    set messengerExtensions(value) {
        this._messengerExtensions = value;
    }

    get fallbackUrl() {
        return this._fallbackUrl;
    }

    set fallbackUrl(value) {
        this._fallbackUrl = value;
    }


    get payload() {
        return this._payload;
    }

    set payload(value) {
        this._payload = value;
    }


    get url() {
        return this._url;
    }

    set url(value) {
        this._url = value;
    }


}

class Request {
    get rawData() {
        return this._rawData;
    }

    set rawData(value) {
        this._rawData = value;
    }

    get formValues() {
        return this._formValues;
    }

    set formValues(value) {
        this._formValues = value;
    }

    constructor(url,method,bodyType,rawData,formValues,headers){

        this._url = url;
        this._method = method;
        this._bodyType = bodyType;
        this._rawData = rawData;

        if (!this.rawData)
            this._rawData = "";
        else
            this._rawData = rawData;

        if (!this.method)
            this._url = "https://clevermessenger.com";
        else
            this._url = url;

        if (!this.method)
            this.method = "get";
        else
            this._method = method;

        if (!formValues)
            this._formValues = [];
        else
            this._formValues = formValues;

        if (!headers)
            this._headers = [];
        else
            this._headers = headers;

    }


    get url() {
        return this._url;
    }

    set url(value) {
        this._url = value;

    }

    get method() {
        return this._method;
    }

    set method(value) {
        this._method = value;
        if (value === "get"){
            $("#webhook_card_settings #data_container").hide();
        }
        else {
            $("#webhook_card_settings #data_container").show();
        }
    }

    get bodyType() {
        return this._bodyType;
    }

    set bodyType(value) {
        this._bodyType = value;
        if (value === "form-data"){
            $("#form_data_container").show();
            $("#raw_container").hide();
        }
        else if (value === "raw"){
            $("#form_data_container").hide();
            $("#raw_container").show();
        }
    }

    get body() {
        return this._body;
    }

    set body(value) {
        this._body = value;
    }

    get headers() {
        return this._headers;
    }

    set headers(value) {
        this._headers = value;
    }

    generateJson(){
        return JSON.stringify(this);
    }

}


class IceBreaker extends ButtonInterface {

    constructor(title, id) {
        super(id, title,"ice_breaker",false);

    }


}

class QuickReply extends ButtonInterface {


    constructor(title, id, payload, analytics,contentType=false) {

        super(id, title,analytics);
        if (!contentType)
            this._contentType = "text";
        else
            this._contentType = contentType;
        if (payload)
            this._payload = payload;
        else
            this._payload = "";
        this._imageUrl = "";

    }


    get contentType() {
        return this._contentType;
    }

    set contentType(value) {
        this._contentType = value;
    }

    get imageUrl() {
        return this._imageUrl;
    }

    set imageUrl(value) {
        this._imageUrl = value;
    }


    get payload() {
        return this._payload;
    }

    set payload(value) {
        this._payload = value;
    }


}


class Link {

    constructor(id, from, to, type) {
        this._id = id;
        this._from = from;
        this._to = to;
        this._type = type;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get from() {
        return this._from;
    }

    set from(value) {
        this._from = value;
    }

    get to() {
        return this._to;
    }

    set to(value) {
        this._to = value;
    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }
}

class WebhookCard extends Card {



    constructor(id, outputs, request, title,json,analytics,positiveKeywords,negativeKeywords,type="webhook") {

        super(id, outputs, type, null,null, title,json,analytics,positiveKeywords,negativeKeywords);


        if (!request)
            this._request = new Request();
        else {
            this._request = new Request(request._url,request._method,request._bodyType,request._rawData,request._formValues,request._headers);
        }


        this.setPreview();

    }




    get request() {
        return this._request;
    }

    set request(value) {
        this._request = value;


    }

    addFormValue(formValue){
        this.generateFormValueInput(formValue._key,formValue._value);
        this.request.formValues.push(formValue);
    }

    addHeader(header){
        this.generateHeaderInput(header._key,header._value);
        this.request.headers.push(header);
    }
    generateHeaderInput(key,value) {
        let that = this;
        let html = '<div class="row header-container">' +
            '<div class="col-lg-11"> ' +
            '<div class="row">' +
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-header-key"  value="'+key+'" placeholder="Key" />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-header-value"  value="'+value+'"  placeholder="Value"  />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '</div>'+
            '</div>' +
            '<div class="col-lg-1 delete-button-container"> ' +
            '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-header"></i>' +
            '</div>' +
            '</div>';


        $("#" + that.type + "_card_settings #headers_container").append(html);

        $('.ui.dropdown').dropdown();

    }

    buildRequestFromJson(json){
        this.request.url = json.request.url;
        this.request.method = json.request.method;
        this.request.bodyType = json.request.body_type;
        this.request.rawData = json.request.raw_data;
        this.request.formValues = json.request.form_data;
        this.request.headers = json.request.headers;
    }

    generateJson(){
        let temporaryJson = {};
        temporaryJson.request = {};
        temporaryJson.request.url = this.request.url;
        temporaryJson.request.method = this.request.method;
        temporaryJson.request.body_type = this.request.bodyType;
        temporaryJson.request.raw_data = this.request.rawData;
        temporaryJson.request.form_data = this.request.formValues;
        temporaryJson.request.headers = this.request.headers;

        this.json =  JSON.stringify(temporaryJson);

    }


    deleteHeader(index){
        this.request.headers.splice(index, 1);
        $(".header-container").eq(index).remove();
    }

    deleteFormValue(index){
        this.request.formValues.splice(index, 1);
        $(".form-value-container").eq(index).remove();
    }

    generateFormValueInput(key,value) {
        let that = this;
        let html = '<div class="row form-value-container">' +
            '<div class="col-lg-11"> ' +
            '<div class="row">' +
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-form-data-key"  value="'+key+'" placeholder="Key" />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '<div class="form-group col-xs-6">'+
            '<input type="text"  class="msg_text form-control input-lg pickers" data-action="webhook-form-data-value"  value="'+value+'"  placeholder="Value"  />' + window.personalizationHtml + airPickerHtml() +
            '</div>'+
            '</div>'+
            '</div>' +
            '<div class="col-lg-1 delete-button-container"> ' +
            '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-form-value"></i>' +
            '</div>' +
            '</div>';


        $("#" + that.type + "_card_settings #form_values_container").append(html);

        $('.ui.dropdown').dropdown();

    }




    applySettings() {

        this.applyCardTitle();
        this.setPreview();
        this.generateJson();
        this.setAutoFirstCard();


        return true;


    }

    loadHeaders(headers){
        $("#" + this.type + "_card_settings #headers_container").html("");

        for (let index in headers){
            this.generateHeaderInput(headers[index]._key,headers[index]._value);
        }
    }

    loadFormValues(formValues){

        $("#" + this.type + "_card_settings #form_values_container").html("");

        for (let index in formValues){
            this.generateFormValueInput(formValues[index]._key,formValues[index]._value);
        }

    }

    loadSettings(){
        this.loadSettingsShared();
        this.loadHeaders(this.request._headers);
        this.loadFormValues(this.request._formValues);
        $("#" + this.type + "_card_settings [data-action='webhook-url']").val(this.request._url);
        if (typeof this.request._method !== "undefined")
            $("#" + this.type + "_card_settings [data-action='webhook-request-method']").val(this.request._method).trigger("change");
        if (typeof this.request._bodyType !== "undefined")
            $("#" + this.type + "_card_settings [data-action='webhook-body-type']").val(this.request._bodyType).trigger("change");
        $("#" + this.type + "_card_settings [data-action='webhook-raw-body']").val(this.request._rawData);

    }

    setPreview() {
        if (typeof this.request.url !== "undefined")
            $("#" + this.id + "_message p").html("<b>URL : </b> <a href='" + this.request.url + "' target='_blank'>" + Flowcomposer.trimString(this.request.url) + "</a><br/><b>Method : </b><a href='#'>" + this.request.method.toUpperCase() + "</a>");
        else
            $("#" + this.id + "_message p").html("Configure your webhook");

    }


}

class SplitTestCard extends Card {
    get variants() {
        return this._variants;
    }

    set variants(value) {
        this._variants = value;
    }

    sumVariants(){
        let sum = 0;
        for (let index in this.variants){
            sum+= parseInt(this.variants[index].weight);
        }
        return sum;
    }
    buildVariants(data) {

        let i = 0;
        for (let key in this.variants) {

            let variant = this.variants[key];
            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(variant._title, Flowcomposer.buttonsTrimLength),
                type: "variant",
                class: "output",
                index : i++,
                id: key,
            };
        }
        return data;
    }



    constructor(id, outputs, title,variants,json,analytics,positiveKeywords,negativeKeywords,type="split-test") {

        super(id, outputs, type, null,null, title,json,analytics,positiveKeywords,negativeKeywords);


        if (!variants)
            this._variants = {};
        else {
            this._variants = variants;
        }


        this.setPreview();

    }



    generateVariantAlphabet(){
        let alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        return alphabet[Object.keys(this.variants).length];
    }

    generateJson(){
        let temporaryJson = {};
        temporaryJson.variants = [];
        let temporaryVariant = {};
        for (let index in this.variants){
            temporaryVariant = {};
            temporaryVariant.title = this.variants[index].title;
            temporaryVariant.weight = this.variants[index].weight;
            temporaryVariant.id = index;
            temporaryVariant.next_card = this.variants[index].nextCard;
            temporaryJson.variants.push(temporaryVariant);
        }
        temporaryJson.weight = this.sumVariants();
        this.json =  JSON.stringify(temporaryJson);

    }


    addVariant(variant){

        let variantsCount = Object.keys(this.variants).length;

        if (variantsCount === (Flowcomposer.maximumVariants - 1)) {
            this.$addVariant.hide();
        }
        else if (variantsCount === Flowcomposer.maximumVariants)
            return false;
        let generatedId = Flowcomposer.generateID();
        this.variants[generatedId] = variant;
        this.generateVariantInput(generatedId,variant.title,variant.weight);

    }


    deleteVariant(index){
        delete this.variants[index];
        $("#"+index).remove();
        this.$addVariant.show();

    }

    generateVariantInput(id,title,weight) {
        let that = this;

        let elementsCount = Object.keys(this.variants);
        let html = '';
        if (elementsCount[0] === id)
            html = '<div id="'+id+'" class="row variant-container">' +
            '<div class="col-lg-11"> ' +
            '<div class="row">' +
            '<div class="form-group col-xs-6">'+
            '<label>Variant name</label>' +
            '<input type="text" id="'+id+'_title" class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
            '</div>'+
            '<div class="form-group col-xs-6">'+
            '<label>Variant weight</label>' +
            '<input type="text" id="'+id+'_weight" class="msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
            '</div>'+
            '</div>'+
            '</div>' +
            '</div>';
        else if (elementsCount[1] === id)
            html = '<div id="'+id+'" class="row variant-container">' +
                '<div class="col-lg-11"> ' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">'+
                '<input type="text"  id="'+id+'_title" class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
                '</div>'+
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_weight" class="msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
                '</div>'+
                '</div>'+
                '</div>' +
                '</div>';
        else
            html = '<div id="'+id+'" class="row variant-container">' +
                '<div class="col-lg-11"> ' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_title"  class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
                '</div>'+
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_weight" class="msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
                '</div>'+
                '</div>'+
                '</div>' +
                '<div class="col-lg-1 delete-button-container"> ' +
                '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-variant"></i>' +
                '</div>' +
                '</div>';



        $("#" + that.type + "_card_settings #variants_container").append(html);


    }


    loadVariants(variants){

        $("#" + this.type + "_card_settings #variants_container").html("");

        for (let index in variants){
            this.generateVariantInput(index,variants[index].title,variants[index].weight);
        }

    }

    loadSettings(){
        this.loadSettingsShared();
        this.loadVariants(this.variants);

    }

    setPreview() {
            if (isNaN(this.sumVariants()))
                $("#" + this.id + "_message").html('Total weight : <b>dynamic weight</b>');
            else
                $("#" + this.id + "_message").html('Total weight : <b>'+this.sumVariants()+'</b>');

    }

    applySettings(){


        let emptyElements = this.highlightEmptyElements();
        if (emptyElements.length) {
            return false;
        }




        this.deleteExistingLinks();

        let data = this.getOperatorData();

        if ($.isEmptyObject(data) === false) {


            data = this.buildOutputs(data);
            data = this.buildVariants(data);

            this.setOperatorData(data);
            this.redrawLinks();


        }

        this.applyCardTitle();
        this.generateJson();
        this.setAutoFirstCard();
        this.setPreview();


        return true;

    }

    highlightEmptyElements(){
        toastr.clear();
        let emptyTitles = [];
        let emptyWeights = [];
        let emptyElements = [];
        let invalidWeights = [];


        for (let id in this.variants){
            if (this.variants[id].title.length === 0) {
                $("#" + id + "_title").addClass("input-error");
                emptyTitles.push(id);
                emptyElements.push(id)
            }
            else if (this.variants[id].weight.length === 0) {
                $("#" + id + "_weight").addClass("input-error");
                emptyWeights.push(id);
                emptyElements.push(id)

            }
            else if (isNaN(this.variants[id].weight) && !this.variants[id].weight.includes("__") && !this.variants[id].weight.includes("{")) {
                $("#" + id + "_weight").addClass("input-error");
                invalidWeights.push(id);
                emptyElements.push(id);

            }
            else {
                $("#" + id + "_title").removeClass("input-error");
                $("#" + id + "_weight").removeClass("input-error");
            }
        }


        if (emptyTitles.length)
            toastr.error("Variant titles are required","Invalid variant title");
        else if (emptyWeights.length)
            toastr.error("Variant weights are required","Invalid variant weight");
        else if (invalidWeights.length)
            toastr.error("Invalid variant weight","Invalid path weight");



        return emptyElements;

    }


}

class RandomizerCard extends SplitTestCard {



    buildVariants(data) {

        let i = 0;
        for (let key in this.variants) {

            let variant = this.variants[key];
            data.properties.outputs[key] = {
                label: Flowcomposer.trimString(variant._title, Flowcomposer.buttonsTrimLength),
                type: "variant",
                class: "output",
                index : i++,
                id: key,
            };
        }
        return data;
    }



    constructor(id, outputs, title,variants,json,analytics,positiveKeywords,negativeKeywords,type="randomizer") {

        super(id, outputs, title,variants,json,analytics,positiveKeywords,negativeKeywords,type);


    }


    generateJson(){
        let temporaryJson = {};
        temporaryJson.paths = [];
        let temporaryVariant = {};
        for (let index in this.variants){
            temporaryVariant = {};
            temporaryVariant.title = this.variants[index].title;
            temporaryVariant.weight = this.variants[index].weight;
            temporaryVariant.id = index;
            temporaryVariant.next_card = this.variants[index].nextCard;
            temporaryJson.paths.push(temporaryVariant);
        }
        temporaryJson.weight = this.sumVariants();
        this.json =  JSON.stringify(temporaryJson);

    }



    generateVariantInput(id,title,weight) {
        let that = this;

        let elementsCount = Object.keys(this.variants);
        let html = '';
        if (elementsCount[0] === id)
            html = '<div id="'+id+'" class="row variant-container">' +
                '<div class="col-lg-11"> ' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">'+
                '<label>Path name</label>' +
                '<input type="text" id="'+id+'_title" class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
                '</div>'+
                '<div class="form-group col-xs-6">'+
                '<label>Path weight</label>' +
                '<input type="text" id="'+id+'_weight" class="touchspin2 msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
                '</div>'+
                '</div>'+
                '</div>' +
                '</div>';
        else if (elementsCount[1] === id)
            html = '<div id="'+id+'" class="row variant-container">' +
                '<div class="col-lg-11"> ' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">'+
                '<input type="text"  id="'+id+'_title" class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
                '</div>'+
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_weight" class="touchspin2 msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
                '</div>'+
                '</div>'+
                '</div>' +
                '</div>';
        else
            html = '<div id="'+id+'" class="row variant-container">' +
                '<div class="col-lg-11"> ' +
                '<div class="row">' +
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_title"  class="msg_text form-control input-lg" data-action="variant-title"  value="'+title+'" placeholder="Variant title" required="" />'+
                '</div>'+
                '<div class="form-group col-xs-6">'+
                '<input type="text" id="'+id+'_weight" class="touchspin2 msg_text form-control input-lg pickers variant-weight" data-action="variant-weight"  value="'+weight+'"  placeholder="Variant probability"  />' + window.customFieldsHtml + airPickerHtml() +
                '</div>'+
                '</div>'+
                '</div>' +
                '<div class="col-lg-1 delete-button-container"> ' +
                '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-variant"></i>' +
                '</div>' +
                '</div>';



        $("#" + that.type + "_card_settings #variants_container").append(html);


    }



    highlightEmptyElements(){
        toastr.clear();
        let emptyTitles = [];
        let emptyWeights = [];
        let emptyElements = [];
        let invalidWeights = [];

        for (let id in this.variants){
            if (this.variants[id].title.length === 0) {
                $("#" + id + "_title").addClass("input-error");
                emptyTitles.push(id);
                emptyElements.push(id)
            }
            else if (this.variants[id].weight.length === 0) {
                $("#" + id + "_weight").addClass("input-error");
                emptyWeights.push(id);
                emptyElements.push(id);

            }
            else if (isNaN(this.variants[id].weight) && !this.variants[id].weight.includes("__") && !this.variants[id].weight.includes("{")) {
                $("#" + id + "_weight").addClass("input-error");
                invalidWeights.push(id);
                emptyElements.push(id)

            }
            else {
                $("#" + id + "_title").removeClass("input-error");
                $("#" + id + "_weight").removeClass("input-error");
            }
        }


        if (emptyTitles.length)
            toastr.error("Path titles are required","Invalid path title");
        else if (emptyWeights.length)
            toastr.error("Path weights are required","Invalid path weight");
        else if (invalidWeights.length)
            toastr.error("Invalid path weight","Invalid path weight");


        return emptyElements;

    }


}

class KeyValue {
    constructor(key,value) {
        this._key = key;
        this._value = value;
    }

    get key() {
        return this._key;
    }

    set key(value) {
        this._key = value;
    }

    get value() {
        return this._value;
    }

    set value(value) {
        this._value = value;
    }
}

class Header extends KeyValue {
    constructor(key, value) {
        super(key, value);
    }
}

class FormValue extends KeyValue{
    constructor(key,value){
        super(key,value);
    }
}

class TemplateElement {

    constructor(id,title,subtitle,imageUrl,buttons={},defaultAction={}){


        if (!id)
            this._id = Flowcomposer.generateID();
        else
            this._id = id;

        if (title)
            this._title = title;
        else
            this._title = "Clever Messenger";

        if (subtitle)
            this._subtitle = subtitle;
        else
            this._subtitle = "More ways to make it yours!";

        if (imageUrl)
            this._imageUrl = imageUrl;
        else {
            if (this instanceof ListElement)
            this._imageUrl = "https://dl.dropbox.com/s/2lkkgdiwzn6qcah/clever-messenger.png?dl=0";
            else
                this._imageUrl = Flowcomposer.defaultCardImage;
        }
        if (typeof buttons !== "undefined")
            this._buttons = buttons;

        this._defaultAction = defaultAction;
    }


    generateButtonInput(buttonId, buttonValue, type) {
        let that = this;
        let buttonHtml = '<div class="row button-container" id="' + buttonId + '_container">' +
            '<div class="col-lg-11"> ' +
            '<input type="text"  class="msg_text form-control input-lg" data-action="button-input"  data-emojiable="true" id="' + buttonId + '" value="' + buttonValue + '" placeholder="Button text (max. 20 characters)"  maxlength="20" />' + window.personalizationHtml + airPickerHtml() +
            '</div>' +
            '<div class="col-lg-1 delete-button-container"> ' +
            '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-button"  data-button-id="' + buttonId + '"  ></i>' +
            '</div>' +
            '</div>';
        if (type === "element_share") {

             buttonHtml = '<div class="row button-container" id="' + buttonId + '_container">' +
                '<div class="col-lg-11"> ' +
                '<div style="margin-bottom: 10px !important;" class="emoji-wysiwyg-editor msg_text form-control input-lg" >Share - <em>Auto-translates to subscriber\'s language </em></div>' +
                '</div>' +
                '<div class="col-lg-1 delete-button-container"> ' +
                '<i class="fa icon-cross delete-button" aria-hidden="true" data-action="delete-button"  data-button-id="' + buttonId + '"  ></i>' +
                '</div>' +
                '</div>';
        }


        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview .button_container_preview").append('<button id="'+buttonId+'_preview_button" class="preview_list_button" style="">' + convertEmojiUtfToImage(buttonValue.toUpperCase()) + '</button>');
        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_container " + Flowcomposer.buttonsContainerSelector).append(buttonHtml);



        window.emojiPicker.discover();

        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+buttonId).characterCounter({
            limit: $(this).attr("maxlength"),
            counterCssClass: 'char-counter-styling',
            counterFormat: '%1 character(s) remaining',
        });

        $('.ui.dropdown').dropdown();

    }


    loadButtons() {
        let counter = 0;
        for (let key in this.buttons) {
            this.generateButtonInput(this.buttons[key].id, this.buttons[key].title,this.buttons[key].type);
            counter++;
        }


        if (this instanceof ListElement)
            return (counter === Flowcomposer.maximumListButtons);

        else
            return (counter === Flowcomposer.maximumCarouselButtons);
    }

    addButton(button) {

        let buttonsCount = Object.keys(this.buttons).length;

        let maximumButtons = Flowcomposer.maximumListButtons;

        if (this instanceof CarouselElement)
            maximumButtons = Flowcomposer.maximumCarouselButtons;


        if (buttonsCount < maximumButtons) {
            this.generateButtonInput(button.id, button.title,button.type);
            this.buttons[button.id] = button;

            if (buttonsCount === (maximumButtons - 1)) {
                flowcomposer.selectedCard.$addListButton(this.id).hide();
                return false;
            }

            return true;
        }

        flowcomposer.selectedCard.$addListButton(this.id).hide();
        return false;

    }




    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get title() {
        return this._title;
    }

    set title(value) {
        this._title = value;
        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview_title").html(convertEmojiUtfToImage(value));

    }

    get subtitle() {
        return this._subtitle;
    }

    set subtitle(value) {
        this._subtitle = value;
        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview_subtitle").html(convertEmojiUtfToImage(value));

    }

    get imageUrl() {
        return this._imageUrl;
    }

    set imageUrl(value) {
        this._imageUrl = value;

        let elementsCount = Object.keys(flowcomposer.selectedCard.templateElements);

        if (flowcomposer.selectedCard.topElementStyle === "large" && elementsCount[0] === this.id)
            $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview").css("background",'linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('+value+')');
        else
            $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview_image").attr("src",value);

    }

    // Changes are related to original buttons JSON
    generateButtonsJson() {

        let buttons = [];
        for (let key in this.buttons) {
            let button = this.buttons[key];
            let temporaryButton = {};
            temporaryButton.type = button.type;
            temporaryButton.title = button.title;
            if (button.type === "postback")
                temporaryButton.payload = button.payload;
            else if (button.type === "web_url"){
                temporaryButton.url = button.url;
                temporaryButton.webview_height_ratio = button.webviewHeightRatio;
                temporaryButton.messenger_extensions = button.messengerExtensions === "true";
            }
            else if (button.type === "phone_number"){
                temporaryButton.payload = button.payload;
            }
            else {
                delete temporaryButton.title;

            }


            buttons.push(temporaryButton);
        }

        return buttons;


    }


    get buttons() {
        return this._buttons;
    }

    set buttons(value) {
        this._buttons = value;
    }

    get defaultAction() {
        return this._defaultAction;
    }

    set defaultAction(value) {
        this._defaultAction = value;
    }

}

class ListElement extends TemplateElement{

    constructor(id,title,subtitle,imageUrl,buttons={},defaultAction={}){

        super(id,title,subtitle,imageUrl,buttons,defaultAction);


    }

    setCover(){
        $('#'+this.id+'_preview').addClass("list-first-cover").css("background",'linear-gradient(rgba(0,0,0,0.45),rgba(0,0,0,0.45)),url('+this.imageUrl+')').find("#preview_list_content").addClass("list-first-cover");
        $('[data-action="set-list-cover"][data-element-id="'+this.id+'"').attr("data-action","unset-list-cover").removeClass("icon-menu3").addClass("icon-menu2");
    }

    unsetCover(){
        $('#'+this.id+'_preview').removeClass("list-first-cover").css("background",'').find("#preview_list_content").removeClass("list-first-cover");
        $('[data-action="unset-list-cover"][data-element-id="'+this.id+'"').attr("data-action","set-list-cover").addClass("icon-menu3").removeClass("icon-menu2");

    }



}

class CarouselElement extends TemplateElement{

    constructor(id,title,subtitle,imageUrl,buttons={},defaultAction={}){

        super(id,title,subtitle,imageUrl,buttons,defaultAction);


    }

    get imageUrl(){
        return this._imageUrl;
    }
    set imageUrl(value) {
        this._imageUrl = value;
        $("#" + flowcomposer.selectedCard.type + "_card_settings #"+this.id+"_preview_image").css("background-image",'url(\''+value+'\')');

    }

}

class DemioCard extends WebhookCard{


    constructor(id, outputs,apiKey, apiSecret, request, title, json, analytics, positiveKeywords, negativeKeywords) {
        super(id, outputs, request, title, json, analytics, positiveKeywords, negativeKeywords,"demio");
        if (request)
            this.request = request;
        else{
            let formValues = [new FormValue("name","[FULL_NAME]"),new FormValue("email","{{email}}")];
            let headers = [new Header("api-key",apiKey), new Header("api-secret",apiSecret)];
            request = new Request("https://my.demio.com/api/v1/events","put","form-data",false,formValues,headers);
        }
        this._apiKey = apiKey;
        this._apiSecret = apiSecret;
    }



    get apiKey() {
        return this._apiKey;
    }

    set apiKey(value) {
        this._apiKey = value;
    }

    get apiSecret() {
        return this._apiSecret;
    }

    set typingTimer(value) {
        this._typingTimer = value;
    }

    get typingTimer() {
        return this._typingTimer;
    }


    set apiSecret(value) {
        this._apiSecret = value;
        let that = this;
        if (this.apiKey.length>15 && value.length>10) {
            if (typeof that.typingTimer !== "undefined") {
                clearTimeout(that.typingTimer);
            }
            that.typingTimer = setTimeout(function () {
                that.getEventsList();
            }, 500);
        }

    }


    getEventsList(){
        let that = this;
        let ajax_url = 'includes/admin-ajax.php';
        let data = {
            'action': 'get_demio_events',
            'api_key' : this.apiKey,
            'api_secret' : this.apiSecret
        };
        jQuery.post(ajax_url, data, function (response) {
            $("#" + that.type + "_card_settings [data-action='api-key-input']","#" + that.type + "_card_settings [data-action='api-secret-input']").removeClass("input-error").removeClass("input-sucess");

            if (response) {
                try {
                    let events = JSON.parse(response);
                    $("#" + that.type + "_card_settings [data-action='api-key-input'],#" + that.type + "_card_settings [data-action='api-secret-input']").addClass("input-success");
                    $("#" + that.type + "_card_settings [data-action='demio-events-input']").empty();
                    for (let i=0;i<events.length;i++){
                        $("#" + that.type + "_card_settings [data-action='demio-events-input']").append($('<option>').text(events[i].name).val(events[i].id));

                    }

                }
                catch (e) {

                    $("#" + that.type + "_card_settings [data-action='api-key-input'],#" + that.type + "_card_settings [data-action='api-secret-input']").addClass("input-error");

                }
            }
            else {
                $("#" + that.type + "_card_settings [data-action='api-key-input'],#" + that.type + "_card_settings [data-action='api-secret-input']").addClass("input-error");

            }
        });
    }
}

class Variant {
    constructor(id,title,weight, nextCard=false){
        if (!id)
            this._id = Flowcomposer.generateID();
        else
            this._id = id;
        this._title = title;
        this._weight = weight;
        this._nextCard = nextCard;
    }


    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get nextCard() {
        return this._nextCard;
    }

    set nextCard(value) {
        this._nextCard = value;
    }

    get title() {
        return this._title;
    }

    set title(value) {
        this._title = value;
        $("#" + this.id + "_title").removeClass("input-error");

    }

    get weight() {
        return this._weight;
    }

    set weight(value) {
        this._weight = value;
        $("#" + this.id + "_weight").removeClass("input-error");

    }
}

class AirVariable extends KeyValue{
    constructor(key,value){
        super(key,value);
    }

}