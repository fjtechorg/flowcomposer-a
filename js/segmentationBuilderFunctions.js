function getTags(){

    let ajax_url='includes/admin-ajax.php';
    let data={
        'action':'get_page_tags_for_segmentation',
    };
    return jQuery.post(ajax_url, data);

}

function getCustomFields(){

    let ajax_url='includes/admin-ajax.php';
    let data={
        'action':'get_customfields_for_segmentation',
    };
    return jQuery.post(ajax_url, data);
}

function getGlobalFields(){

    let ajax_url='includes/admin-ajax.php';
    let data={
        'action':'get_global_fields',
    };
    return jQuery.post(ajax_url, data);
}

function getExistingLanguages(){

    let ajax_url='includes/admin-ajax.php';
    let data={
        'action':'get_existing_languages_for_segmentation',
    };
    return jQuery.post(ajax_url, data);

}

function initBuilder(){
    $('#builder').queryBuilder({
        plugins: ['bt-tooltip-errors'],
        select_placeholder : "Select an attribute...",
        filters: [
            {
                id: 'first_name',
                label: 'First name',
                type: 'string',
                optgroup: 'System attributes',
                operators:   ['equal', 'not_equal', 'contains','not_contains','begins_with','not_begins_with','ends_with','not_ends_with']
            },  {
                id: 'last_name',
                label: 'Last name',
                optgroup: 'System attributes',
                type: 'string',
                operators:   ['equal', 'not_equal', 'contains','not_contains','begins_with','not_begins_with','ends_with','not_ends_with']
            },
            {
                id: 'gender',
                label: 'Gender',
                type: 'string',
                input: 'select',
                optgroup: 'System attributes',

                values: {
                    'male': 'Male',
                    'female': 'Female',
                },
                operators: ['equal', 'not_equal']
            },
            {
                id: 'origin',
                label: 'Entry point',
                type: 'string',
                input: 'select',
                optgroup: 'System attributes',

                values: {
                    'messenger': 'Direct message',
                    'customerchat': 'CustomerChat',
                    'sendtomessenger': 'Send to Messenger',
                    'checkbox': 'Checkbox Plugin'
                },
                operators: ['equal', 'not_equal']
            },
            {
                id: 'week-day',
                label: 'Week day',
                type: 'string',
                input: 'select',
                optgroup: 'System attributes',
                values: {
                    'monday': 'Monday',
                    'tuesday': 'Tuesday',
                    'wednesday': 'Wednesday',
                    'thursday': 'Thursday',
                    'friday': 'Friday',
                    'saturday': 'Saturday',
                    'sunday': 'Sunday'
                },
                operators: ['equal', 'not_equal']
            }]
    });

}

function getReadableCondition() {
    rules = $('#builder').queryBuilder('getRules');
    if (typeof (rules) === "undefined" || rules === null) {
        return "";
    }
    if (typeof (rules) !== null && typeof rules.condition === "undefined"){
        return "";
    }
    finaltext = "";
    finaltext = buildInnerQuery(rules.condition,rules.rules,finaltext,false);
    return finaltext;
}


function buildInnerQuery(outercondition, rules,finaltext,inner){
    if (typeof (rules)==="undefined"){
        return finaltext;
    }
    for (let i=0;i<rules.length;i++){

        if (typeof (rules[i].condition) !=="undefined") {
            finaltext+= ' (';
            finaltext =buildInnerQuery(rules[i].condition,rules[i].rules,finaltext,true);
        }


        ruleField = rules[i].field;
        if (ruleField === "first_name") ruleField = "first name";
        if (ruleField === "last_name") ruleField = "last Name";
        if (ruleField === "origin") ruleField = "entry point";
        if (ruleField === "week-day") ruleField = "week day";

        if (typeof(rules[i].field)!=="undefined") {
            if (rules[i].id==="tag") {
                ruleValue = tagsMap[rules[i].value];
            }
            else if (rules[i].id==="language") {
                ruleValue = languagesMap[rules[i].value];

            }

            else if (rules[i].id==="origin") {
                ruleValue = originsMap[rules[i].value];

            }


            else if ((rules[i].id.substring(0, 3))==="cs_") {
                ruleValue = rules[i].value;
                ruleField = customfieldsMap[rules[i].id];

            }

            else if ((rules[i].id.substring(0, 3))==="gf_") {
                ruleValue = rules[i].value;
                ruleField = globalFieldsMap[rules[i].id];

            }
            else{
                ruleValue = rules[i].value;
            }
            if (rules[i].type==="string" ) {
                if (rules[i].id === "gender" || rules[i].id === "week-day")
                    finaltext += ruleField + " " + rules[i].operator + " '" + ruleValue.capitalize() + "' ";
                else
                    finaltext += ruleField + " " + rules[i].operator + " '" + ruleValue + "' ";

            }
            else {
                if (rules[i].id === "tag")
                    finaltext += ruleField + " " + rules[i].operator + " '" + ruleValue + "' ";
                else
                    finaltext += ruleField + " " + rules[i].operator + " " + ruleValue+ " ";
            }
        }

        if (i!==(rules.length-1)){
            finaltext += " "+outercondition+" ";
        }
    }
    if (inner) finaltext += ')';


    return replaceOperators(finaltext).capitalize();

}



function replaceOperators(text){
    text = text.replaceAll("greater_or_equal","<b>greater or equal</b>").replaceAll("less_or_equal","<b>less or equal</b>").replaceAll("not_equal","<b>is not</b>").replaceAll("equal","<b>is</b>").replaceAll("less","<b>less than</b>").replaceAll("greater","<b>greater than</b>").replaceAll("not_contains","<b>does not contain</b>").replaceAll("contains","<b>contains</b>").replaceAll("not_begins_with","<b>does not begin with</b>").replaceAll("not_ends_with","<b>does not end with</b>").replaceAll("begins_with","<b>begins with</b>").replaceAll("ends_with","<b>ends with</b>");
    return text;
}





$(document).ready(function(){

    $('button [data-add="group"]').hide();
    window.loadedRules = 0;

    let myTable =  $('#audience-preview').dynatable({
        table: {
            bodyRowSelector: 'li',
            rowReader: function(index, li, record) {
                let $li = $(li);
                record.profile_pic = $li.find('.profile_pic').text();
                record.first_name = $li.find('.first_name').text();
                record.last_name = parseFloat($li.find('.last_name').text());
            }
        },
        features: {
            paginate: true,
            recordCount: false,
            perPageSelect: false,
            search:false,
            pageText: ''
        },
        writers: {
            _rowWriter: ulWriter
        },
        dataset: {
            perPageDefault: 10,
        }
    });

    $.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Fetching data...</span>',
        overlayCSS: {opacity: .5}
    });


    window.originsMap = {
        "messenger" : "Direct message",
        "customerchat" : "CustomerChat",
        "checkbox" : "Checkbox Plugin",
        "sendtomessenger" : "Send to Messenger"
    };
    initBuilder();




    $('#btn-reset').on('click', function() {
        $('#builder').queryBuilder('reset');
    });

    $('#btn-set').on('click', function() {
        $('#builder').queryBuilder('setRules', rules_basic);
    });

    $('#btn-get').on('click', function() {
        let result = $('#builder').queryBuilder('getRules');

        //if (!$.isEmptyObject(result)) {
        //   alert(JSON.stringify(result, null, 2));
        //}
    });


    getExistingLanguages().done(function(response){
        eval('var objectValues='+response);
        window.languagesMap = objectValues;

        tagObject =  [{
            id: 'language',
            label: 'Language',
            type: 'string',
            input: 'select',
            values: objectValues,
            optgroup: 'System attributes',
            operators: ['equal', 'not_equal']
        }];

        $('#builder').queryBuilder('addFilter',tagObject,4); // To add new filters object.

        getTags().done(function(response) {
            eval('var objectValues=' + response);
            window.tagsMap = objectValues;
            tagObject = [{
                id: 'tag',
                label: 'Tag',
                type: 'integer',
                optgroup: 'System attributes',
                input: 'select',
                values: objectValues,
                operators: ['equal', 'not_equal']
            }];

            $('#builder').queryBuilder('addFilter', tagObject, 5); // To add new filters object.

            window.customfieldsMap = window.globalFieldsMap = {};

            getCustomFields().done(function (response) {
                customfields = JSON.parse(response);
                for (let i=0;i<customfields.length;i++) {
                    customfieldsMap["cs_"+customfields[i].id] = customfields[i].customfield_name;
                    if (customfields[i].customfield_type === "numeric" ) {
                        customfieldType = "integer";
                        operators =   ['equal', 'not_equal', 'greater','greater_or_equal','less','less_or_equal'];

                    }
                    else {
                        customfieldType = "string";
                        operators =   ['equal', 'not_equal', 'contains','not_contains','begins_with','not_begins_with','ends_with','not_ends_with'];

                    }
                    tagObject = [{
                        id: "cs_"+customfields[i].id,
                        label: customfields[i].customfield_name.capitalize(),
                        type: customfieldType,
                        optgroup: 'Custom fields',
                        operators: operators

                    }];

                    $('#builder').queryBuilder('addFilter', tagObject, (7)); // To add new filters object.
                }

                getGlobalFields().done(function (globalFields) {
                    globalFields = JSON.parse(globalFields);
                    for (let i = 0; i < globalFields.length; i++) {
                        globalFieldsMap["gf_" + globalFields[i].id] = globalFields[i].name;
                        if (globalFields[i].type === "numeric") {
                            globalFieldsType = "integer";
                            operators = ['equal', 'not_equal', 'greater', 'greater_or_equal', 'less', 'less_or_equal'];

                        } else {
                            globalFieldsType = "string";
                            operators = ['equal', 'not_equal', 'contains', 'not_contains', 'begins_with', 'not_begins_with', 'ends_with', 'not_ends_with'];

                        }
                        tagObject = [{
                            id: "gf_" + globalFields[i].id,
                            label: globalFields[i].name.capitalize(),
                            type: globalFieldsType,
                            optgroup: 'Global fields',
                            operators: operators

                        }];

                        $('#builder').queryBuilder('addFilter', tagObject, (8)); // To add new filters object.
                    }

                    var ajax_url = 'includes/admin-ajax.php';

                    var data = {
                        'action': 'get_segment_data',
                        'id': $("#segment_id").val()
                    };
                    jQuery.post(ajax_url, data, function (response) {
                        segment = JSON.parse(response);
                        if (segment.rules === "null") {
                            $.unblockUI();
                        } else {
                            eval('var savedRules=' + segment.rules);

                            if (segment.rules) {
                                window.loadedRules = 1;
                                $("#querytext").html("");
                                $('#builder').queryBuilder('reset');
                                $('#builder').queryBuilder('setRules', savedRules);

                            } else {
                                $("#querytext").html("Start building your segment below in order to see the resulting query.");
                            }
                            $.unblockUI();
                        }
                    });

                });
            });
        });
    });




    setTimeout(function(){
        jQuery("#builder").on("rulesChanged.queryBuilder",function(){
            rules = $('#builder').queryBuilder('getRules');
            if (typeof (rules) === "undefined" || rules === null) {
                return 0;
            }
            var Query = getReadableCondition();
            if (Query.length) {
                $("#querytext").html(Query);
                getSegmentationRecipients();
            }
        });
        if (window.loadedRules){
            $("#builder").trigger("rulesChanged.queryBuilder")
        }

    }, 3000);



    function getSegmentationRecipients(){

        let ajax_url='includes/admin-ajax.php';

        let data = {
            'action': 'get_segmentation_recipients',
            'segmentation_query':JSON.stringify( $('#builder').queryBuilder('getRules')),
            'broadcast_type': "subscription",
        };

        jQuery.post(ajax_url, data, function(response) {

            response = JSON.parse(response);

            let subscribersCount = response.length;

            let dynatable = $('#audience-preview').data('dynatable');

            dynatable.settings.dataset.originalRecords =  response;
            dynatable.process();


            if (subscribersCount<=10)
                $("#dynatable-pagination-links-audience-preview").hide();
            else
                $("#dynatable-pagination-links-audience-preview").show();
            $("#subscount").html(subscribersCount);


        });
    }

    $(".save_segment").click(function(){
        let ajax_url='includes/admin-ajax.php';

        let data = {
            'action': 'save_segment',
            'segmentation_query':JSON.stringify( $('#builder').queryBuilder('getRules')),
            'id': $("#segment_id").val(),
        };

        jQuery.post(ajax_url, data, function(response) {
            if (response==='1'){
                toastr.success("Segment saved successfully.", "Success!");
            }
            else
                toastr.error("Segment could not be saved, try again.", "Error!");

        });

    });

});

function ulWriter(rowIndex, record, columns, cellWriter) {
    li = '<li><div class="feed-element"><a href="#" class="pull-left"><img alt="image" class="img-circle" src="'+record.profile_pic+'"></a><div class="media-body "><h3>'+record.first_name +' ' + record.last_name+'</h3></div></div></li>' ;
    return li;
}