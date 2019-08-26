


class ConstantContact extends AutoresponderCard {
    constructor(id, outputs, title, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, title, "constant-contact", actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords)
    }


}

class ContactReachCard extends AutoresponderCard {
    constructor(id, outputs, title, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, title, "contact-reach", actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords)
    }

    airVariables(){
        switch (this.actionType) {
            case "create_list_contact":
                return [new AirVariable("Contact Reach","id"), new AirVariable("Contact Reach","redeem-link"), new AirVariable("Contact Reach","Unsubscribe link")];
            default:
                return [];
        }
    }

}


class WowingCard extends AutoresponderCard {
    constructor(id, outputs, title, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, title, "wowing", actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords)
    }

    airVariables(){
        switch (this.actionType) {
            case "create_list_contact":
                return [new AirVariable("Wowing","Landing Page URL"), new AirVariable("Wowing","Ready File URL")];
            default:
                return [];
        }
    }

}