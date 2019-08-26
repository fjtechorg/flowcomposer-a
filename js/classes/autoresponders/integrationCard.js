class IntegrationCard extends Card {
    constructor(id, outputs, title, type, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, type, null, null, title, json, analytics, positiveKeywords, negativeKeywords);

        if (actionSettings)
            this._actionSettings = actionSettings;
        else
            this._actionSettings  = {account: ""};

        if (actionType)
            this._actionType = actionType;
        else
            this._actionType = "select";

        this.setPreview();

    }



    get cardSelector(){
        return "#" + this.type + "_card_settings";
    }

    get accountSelector() {
        return this.cardSelector +  " [data-action='integration-account-select']";
    }
    get actionTypeSelector (){
        return this.cardSelector + ' [data-action="integration-action-type-select"]';
    }


    get actionType() {
        return this._actionType;
    }

    set actionType(value) {
        this._actionType = value;
        $(".action-container").hide();
        $("."+value+"_action_settings_container").show();
    }



    get actionSettings() {

        return this._actionSettings;
    }

    set actionSettings(value) {
        this._actionSettings = value;
    }


    applySettings() {
        let emptyElements = this.highlightEmptyElements();
        if (emptyElements.length) {
            return false;
        }
        return super.applySettings();

    }




    generateJson() {
        let temporaryJson = {};
        temporaryJson.action_type = this.actionType;
        temporaryJson.action_settings = this.actionSettings;
        temporaryJson.service_provider = this.type;
        this.json = JSON.stringify(temporaryJson);
        return this.json;
    }


    loadSettings() {

        this.loadSettingsShared();

        if (this.actionSettings.account)
            $(this.accountSelector).val(this.actionSettings.account).trigger("change");
        //$(this.accountSelector).selectize()[0].selectize.setValue("select").trigger("change");
        if (this.actionType)
            $(this.actionTypeSelector).val(this.actionType).trigger("change");

        //$(this.actionTypeSelector).selectize()[0].selectize.setValue(this.actionType);

        this.setPreview();

    }




}
