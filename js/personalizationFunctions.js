$(document).ready(function(){




    $(document).on('mouseover', '.personalization-tag-picker', function () {
        if ($(this).hasClass("air-picker"))
            elem = $(this).closest(".personalization-tag-menu").parent().parent().find('.emoji-wysiwyg-editor,.pickers').first();

        else
        elem = $(this).closest(".personalization-tag-menu").parent().find('.emoji-wysiwyg-editor,.pickers').first();
        if (!elem.is(":focus")) {
            elem.focus();
            placeCaretAtEnd(elem.get(0));
        }
    });


        $('.ui.dropdown').dropdown();



    $(document).on('keydown', '', function (e) {

    });

    $(document).on('keydown', '.menu.transition', function (e) {
        if (e.keyCode === 13) {
            let val = $(this).find(".item.personalization-tag-item.active.selected").first().trigger("click");
        }
    });

    $(document).on('click', '.personalization-tag-item', function () {
        if ($(this).hasClass("air-variable-tag-item")) return;
        let tagValue = convertPersonalizationTagToSpan($(this).data("tag-value"));
        let $element = $(this).closest(".personalization-tag-menu").parent().find('.emoji-wysiwyg-editor,.pickers').first();

        if ($element.is("div")){
            $($element).insertAtCursor(tagValue);

        }
        else {
            $.initCursor($element);
            $($element).insertAtCursor(tagValue);
        }

        $element.trigger("change").trigger("input");


    });

    $(document).on('click', '.air-variable-tag-item', function (e) {


        let tagValue = $(this).data("tag-value");
        console.log(tagValue);
        if (tagValue === "manual") {
            e.preventDefault();

            let that = this;
            prompt = modalPrompt("Air variable name","variable name",
                function(airVariable){
                    if(airVariable !== null && airVariable !== "") {
                        airVariable = "__"+airVariable+"__";
                        let $element = $(that).closest(".personalization-tag-menu").parent().find('.emoji-wysiwyg-editor,.pickers').first();
                        if ($element.is("div")){
                            $($element).insertAtCursor(airVariable);
                        }
                        else {
                            $.initCursor($element);
                            $($element).insertAtCursor(airVariable);
                        }
                    }

                },
                function(value){
                    //user clicked cancel
                },"one");


            return;
        }
        let $element = $(this).closest(".personalization-tag-menu").parent().parent().find('.emoji-wysiwyg-editor,.pickers').first();
         tagValue = convertPersonalizationTagToSpan("__"+$(this).data("tag-value")+"__");

        if ($element.is("div")){
            $($element).insertAtCursor(tagValue);
        }
        else {
            $.initCursor($element);
            $($element).insertAtCursor(tagValue);
        }

        $element.trigger("change").trigger("input");


    });

    $(document).on('click', 'a[data-action="air-picker"]', function () {




    });

    if ($("[data-target='text-personalization']").length){
        PersonalizationHTML().then(function (personalizationHtml) {
            $("[data-target='text-personalization']").replaceWith(personalizationHtml);
            resetAirVariables();
            $(function() {
                $('.ui.dropdown').dropdown();
            });
        });
    }
});

function resetAirVariables(airVariables=[]){
    console.log(airVariables);
    let selector = "[data-target='air-picker']";
    $(selector).empty();
    console.log(airVariablesHtml(airVariables));
    $(selector).append(airVariablesHtml(airVariables));
    $('.ui.dropdown').dropdown();

}


function airPickerHtml() {

    return ('<div class="personalization-tag-menu ibox-tools air-picker-tag" >' +
        '<div>' +
        '<div style="list-style: none;" class="dropdown">' +
        '<a href="#" data-action="air-picker" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i style="" class="personalization-tag-picker air-picker icon-antenna"></i></a>' +
        '</div>' +
        '</div>' +
        '</div>');


}



async function PersonalizationHTMLOld(){
    let html = "";

    let promise = new Promise((resolve, reject) => {

        getCustomFields().done(function(result){
            try {
                let customFields = JSON.parse(result);
                html = '<div class="personalization-tag-menu ibox-tools personalization-picker-tag">' +
                    '<div>' +
                    '<div style="list-style: none;" class="dropdown">' +
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker icon-user"></i></a>' +
                    '<ul class="dropdown-menu" style="min-width: auto;top: 0;">' +
                    '<li value="1"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">FIRST NAME</a></li>' +
                    '<li value="2"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">LAST NAME</a></li>' +
                    '<li value="3"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">FULL NAME</a></li>' +
                    '<li value="4"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">PAGE NAME</a></li>' +
                    '<li value="5"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">MESSENGER ID</a></li>'+
                    '<li value="6"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">PROFILE PICTURE</a></li>'+
                    '<li value="7"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">TAGS</a></li>';

                for (index in customFields){
                    html+= '<li value="'+customFields[index].id+'"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">'+customFields[index].customfield_name.toUpperCase()+'</a></li>';

                }
                html +=
                    '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                resolve (html);
            }
            catch (e) {
                resolve ('<div class="personalization-tag-menu ibox-tools">' +
                    '<div>' +
                    '<div style="list-style: none;" class="dropdown">' +
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker icon-user"></i></a>' +
                    '<ul class="dropdown-menu" style="min-width: auto;top: 0;">' +
                    '<li value="1"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">FIRST NAME</a></li>' +
                    '<li value="2"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">LAST NAME</a></li>' +
                    '<li value="3"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">FULL NAME</a></li>' +
                    '<li value="4"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">PAGE NAME</a></li>' +
                    '<li value="5"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">MESSENGER ID</a></li>' +
                    '<li value="6"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">PROFILE PICTURE</a></li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            }

        });

    });

    return await promise;
}
async function PersonalizationHTML(){
    let html = "";

    let promise = new Promise((resolve, reject) => {

        getCustomFields().done(function(result) {
            getGlobalFields().done(function (result2) {

                try {
                    let customFields = JSON.parse(result);
                    let globalFields = JSON.parse(result2);
                    html = '<div class="ui dropdown up floating upward personalization-tag-menu ibox-tools">\n' +
                        '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker icon-user"></i></a>' +
                        '  <div class="menu">' +
                        '    <div class="ui search icon input">' +
                        '      <i class="search icon"></i>' +
                        '      <input type="text" name="search" placeholder="Search personalization tags...">' +
                        '    </div>' +
                        '    <div class="divider"></div>' +
                        '    <div class="header">' +
                        '     System attributes' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[FIRST_NAME]">' +
                        '      First name' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[LAST_NAME]">' +
                        '      Last name' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[FULL_NAME]">' +
                        '      Full name' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[PROFILE_PICTURE]">' +
                        '      Profile Picture' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[GENDER]">' +
                        '      Gender' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[MESSENGER_ID]">' +
                        '      Messenger ID' +
                        '    </div>' +
                        '    <div class="item personalization-tag-item" data-tag-value="[TAGS]">' +
                        '      Tags' +
                        '    </div>' +
                        '    <div class="divider"></div>' +
                        '    <div class="header">' +
                        '      Custom Fields' +
                        '    </div>';


                    for (index in customFields) {
                        html +=
                            '    <div class="item personalization-tag-item" data-tag-value="{{' + customFields[index].customfield_name.toUpperCase() + '}}">' +
                            customFields[index].customfield_name.capitalize() +
                            '    </div>';
                    }

                    html +=    '    <div class="divider"></div>' +
                        '    <div class="header">' +
                        '      Global Fields' +
                        '    </div>';

                    for (index in globalFields) {
                        html +=
                            '    <div class="item personalization-tag-item" data-tag-value="[{' + globalFields[index].name.toUpperCase() + '}]">' +
                            globalFields[index].name.capitalize() +
                            '    </div>';
                    }
                    html += '</div>' +
                        '</div>';
                    resolve(html);
                } catch (e) {
                    resolve('');
                }

            });
        });

    });

        $('.ui.dropdown').dropdown();

    return await promise;
}

async function customFieldsHtml(){
    let html = "";

    let promise = new Promise((resolve, reject) => {

        getCustomFields().done(function(result){
            try {
                let customFields = JSON.parse(result);
                html = '<div class="personalization-tag-menu ibox-tools personalization-picker-tag">' +
                    '<div>' +
                    '<div style="list-style: none;" class="dropdown">' +
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker icon-user"></i></a>' +
                    '<ul class="dropdown-menu" style="min-width: auto;top: 0;">' ;

                for (index in customFields){
                    html+= '<li value="'+customFields[index].id+'"><a href="javascript:void(0)" class="personalization-tag-item sidebar_link">'+customFields[index].customfield_name.toUpperCase()+'</a></li>';

                }
                html +=
                    '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                resolve (html);
            }
            catch (e) {
                resolve ('<div class="personalization-tag-menu ibox-tools">' +
                    '<div>' +
                    '<div style="list-style: none;" class="dropdown">' +
                    '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker icon-user"></i></a>' +
                    '<ul class="dropdown-menu" style="min-width: auto;top: 0;">' +
                    '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            }

        });

    });

    return await promise;
}
function convertPersonalizationTagsToFacebookTags(string){
  return (string.replaceAll("[FIRST_NAME]","{{user_first_name}}").replaceAll("[LAST_NAME]","{{user_last_name}}").replaceAll("[FULL_NAME]","{{user_full_name}}"));
}



 function airVariablesHtml(airVariables=[]){
    let html = "";



     html = '<div class="ui dropdown up floating upward personalization-tag-menu ibox-tools">\n' +
         '<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="personalization-tag-picker air-picker icon-antenna"></i></a>' +
         '  <div class="menu">' +
         '    <div class="ui search icon input">' +
         '      <i class="search icon"></i>' +
         '      <input type="text" name="search" placeholder="Search personalization tags...">' +
         '    </div>' +
         '    <div class="divider"></div>' +

         '    <div class="item personalization-tag-item air-variable-tag-item" data-tag-value="manual">' +
         '      Specify manually' +
         '    </div>' ;
     for (let category in airVariables){
            html +=    '    <div class="divider"></div>' +
                '    <div class="header">' +
                category +
                '    </div>';
            console.log(category);
         for (let i=0;i<airVariables[category].length;i++){
             html +=   '    <div class="item personalization-tag-item air-variable-tag-item" data-tag-value="'+toKebabCase(category)+'.'+toKebabCase(airVariables[category][i])+'">' +
                 airVariables[category][i]     +
                 '    </div>' ;
             console.log(airVariables[category][i]);

         }
     }
             html +=
              '</div>' +
                 '</div>';

     $('.ui.dropdown').dropdown();

    return html;
}

function convertPersonalizationTagToSpan(tag) {
    let suffixFind = ["{{", "}}", "[{", "}]", "[{", "}]", "[", "]","__", "_"];

    if (new RegExp(suffixFind.join("|")).test(tag)) {

        let suffixReplace = ["<span class='hide'>{{</span>", "<span class='hide'>}}</span>", "<span class='hide'>[{</span>", "<span class='hide'>}]</span>", "<span class='hide'>[{</span>", "<span class='hide'>]}</span>", "<span class='hide'>[</span>", "<span class='hide'>]</span>", "<span class='hide'>__</span>","<span style='visibility: hidden'>_</span>"];

        return '<span class="label personalization-tag-badge" contenteditable="false">' + replaceBulk(tag, suffixFind, suffixReplace) + '</span>';
    }
    return tag;
}


function replaceBulk( str, findArray, replaceArray ){
    var i, regex = [], map = {};
    for( i=0; i<findArray.length; i++ ){
        regex.push( findArray[i].replace(/([-[\]{}()*+?.\\^$|#,])/g,'\\$1') );
        map[findArray[i]] = replaceArray[i];
    }
    regex = regex.join('|');
    str = str.replace( new RegExp( regex, 'g' ), function(matched){
        return map[matched];
    });
    return str;
}

function extractPersonalizationTags(string){
    let re = /\[\S*\]|\{\{\S*\}\}|\[\{\S*\}\]|\_\_\S*\_\_/g;
    return string.match(re);
}
