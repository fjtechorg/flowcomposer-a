class ActiveCampaignCard extends AutoresponderCard {
    constructor(id, outputs, title, actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords) {

        super(id, outputs, title, "active-campaign", actionType, actionSettings, json, analytics, positiveKeywords, negativeKeywords)
    }
}