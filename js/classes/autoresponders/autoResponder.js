class AutoresponderCard extends IntegrationCard {
    constructor(id, outputs, title, type, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, title,type, null, null, json, analytics, positiveKeywords, negativeKeywords);

        if (actionSettings)
            this._actionSettings = actionSettings;
        else
            this._actionSettings  = {account: "",email : "",phone: "",listId : "",customFields: []};

        if (actionType)
            this._actionType = actionType;
        else
            this._actionType = "select";

        this.setPreview();

    }




    get emailFieldSelector (){
        return this.cardSelector + ' [data-action="autoresponder-email-value"]';
    }




    populateLists(){

        let that = this;

        jQuery(this.cardSelector).block({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Fetching account data...</span>',
            overlayCSS: {opacity: .2}
        });

        getIntegrationAccountLists(this.actionSettings.account,this.type).done(function(lists){
            console.log(lists);
            try {
                let listsObj = JSON.parse(lists);
                for (let i = 0; i<listsObj.length;i++ ){
                    $('[data-action="autoresponder-list-select"]').append("<option value='"+listsObj[i].id+"'>"+listsObj[i].name+"</option>");
                }
            }
            catch (e) {
                console.log(e);

            }
            finally {
                jQuery(that.cardSelector).unblock();
            }
        })


    }

    applySettings() {
        let emptyElements = this.highlightEmptyElements();
        if (emptyElements.length) {
            return false;
        }
        return super.applySettings();

    }

    setPreview() {
        let previewSelector = "#" + this.id + "_message";
        switch (this.actionType) {
            case "create_contact":
                $(previewSelector).html("Create contact <b>"+this.actionSettings.email+" </b>");
                break;

            case "create_list_contact":
                $(previewSelector).html("Add subscriber to campaign");
                break;

            case "delete_list_contact":
                $(previewSelector).html("Remove subscriber from campaign");
                break;
            default:
                $(previewSelector).html("Specify a valid <b>action </b>");
                break;

        }
    }

    highlightEmptyElements() {
        return [];
        toastr.clear();
        let emptyCustomFields = [];
        let emptyEmail = false;
        let emptyAccount = false;
        let emptyAction = false;
        let emptyElements = [];
        let invalidWeights = [];

        if (this.actionSettings.account === ""){
            emptyAccount = true;
            emptyElements.push(1);

        }

        if ((this.actionType !== "select" && this.actionType !== "") && this.actionSettings.email === ""){
            emptyEmail = true;
            $(this.emailFieldSelector).addClass("input-error");
            toastr.error("Email value is required","Invalid email value");

            emptyElements.push(1);

        }
        if (this.actionType === ""){
            emptyAction = true;
            emptyElements.push(1);

        }


        // More things like custom fields if the key has a value and the value is empty
        return emptyElements;
    }



    loadSettings() {

        this.loadSettingsShared();

        if (this.actionSettings.account)
            $(this.accountSelector).val(this.actionSettings.account).trigger("change");
        //$(this.accountSelector).selectize()[0].selectize.setValue("select").trigger("change");
        if (this.actionType)
            $(this.actionTypeSelector).val(this.actionType).trigger("change");

        //$(this.actionTypeSelector).selectize()[0].selectize.setValue(this.actionType);
        $(this.emailFieldSelector).val(this.actionSettings.email);

        switch (this.actionType) {
            case "create_contact":
            case "update_contact":
            case "create_list_contact":
                // Load the custom fields
                this.loadFormValues(this.actionSettings.customFields);


        }
        this.setPreview();

    }

    addFormValue(formValue){
        this.generateFormValueInput(formValue._key,formValue._value);
        this.actionSettings.customFields.push(formValue);
    }

    deleteFormValue(index){
        this.actionSettings.customFields.splice(index, 1);
        $(".form-value-container").eq(index).remove();
    }


}
