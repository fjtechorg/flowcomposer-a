String.prototype.replaceAll = function(str1, str2, ignore)
{
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function getSearchParameters() {
    var prmstr = window.location.search.substr(1);
    return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}

function modalAlert(msg){
    if(msg!==''){
        $('#modal_alert_msg').html(msg);
    }
    else{
        return false;
    }
    $(document).off('keypress');
    $(document).on('keypress',function(ex){
        if(ex.which === 13){
            $('#modal_alert').modal('toggle');
            $(document).off('keypress');
        }
    });
    $('#modal_alert').modal('toggle');
}

function modalPrompt(msg,value,okFunc,cancelFunc,trigger="all"){
    var promptField = $('#modal_prompt_field');
    if(msg!==''){
        $('#modal_prompt_msg').html(msg);
        promptField.val('');
    }
    else{
        return false;
    }
    promptField.val(value);
    var promptOk = $("#modal_prompt_ok");
    var promptCancel = $("#modal_prompt_cancel");
    promptOk.off();
    promptCancel.off();
    promptOk.one('click',function(){
        var promptInput = $('#modal_prompt_field').val();
        okFunc(promptInput);
    });
    promptCancel.one('click',function(){
        var promptInput = $('#modal_prompt_field').val();
        cancelFunc(promptInput);
    });

    if (trigger === "one") {
        promptField.one('keydown', function (ex) {
            if (ex.which === 13) {
                promptOk.trigger('click');

            }

        });
    }
    else {
        promptField.on('keydown', function (ex) {
            if (ex.which === 13) {
                promptOk.trigger('click');

            }

        });
    }
    $('#modal_prompt').modal('toggle');
}

function modalConfirm(msg,okFunc,cancelFunc){
    $(document).off('keypress');
    if(msg!==''){
        $('#modal_confirm_msg').html(msg);
    }
    else{
        return false;
    }
    var confirmOk = $("#modal_confirm_ok");
    var confirmCancel = $("#modal_confirm_cancel");
    confirmOk.off();
    confirmCancel.off();
    confirmOk.on('click',function(){
        okFunc();
        $(document).off('keypress');
    });
    confirmCancel.on('click',function(){
        cancelFunc();
        $(document).off('keypress');
    });
    $(document).on('keypress',function(ex){
        if(ex.which === 13){
            confirmOk.trigger('click');
            $(document).off('keypress');
        }
    });
    $('#modal_confirm').modal('toggle');
}

function insertTextAtCursor(txtarea,text) {
    if (!txtarea) {
        return;
    }

    let scrollPos = txtarea.scrollTop;
    let strPos = 0;
    let areaValue = txtarea.value;
    let flag = false;
    if (!(txtarea instanceof HTMLInputElement)){
        flag = true;
        areaValue = txtarea.innerHTML;

    }
    let br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
        "ff" : (document.selection ? "ie" : false));
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart('character', -areaValue.length);
        strPos = range.text.length;
    } else if (br == "ff") {
        strPos = txtarea.selectionStart;
    }

    if (flag)
        strPos =getCaretCharacterOffsetWithin(txtarea)


    var front = (areaValue).substring(0, strPos);
    var back = (areaValue).substring(strPos, areaValue.length);
    if (!flag)
     txtarea.value = front + text + back;
    else
        txtarea.innerHTML =  front + text + back;
    strPos = strPos + text.length;
    if (br == "ie") {
        txtarea.focus();
        var ieRange = document.selection.createRange();
        ieRange.moveStart('character', -areaValue.length);
        ieRange.moveStart('character', strPos);
        ieRange.moveEnd('character', 0);
        ieRange.select();
    } else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }

    txtarea.scrollTop = scrollPos;
}

function getCaretCharacterOffsetWithin(element) {
    var caretOffset = 0;
    var doc = element.ownerDocument || element.document;
    var win = doc.defaultView || doc.parentWindow;
    var sel;
    if (typeof win.getSelection != "undefined") {
        sel = win.getSelection();
        if (sel.rangeCount > 0) {
            var range = win.getSelection().getRangeAt(0);
            var preCaretRange = range.cloneRange();
            preCaretRange.selectNodeContents(element);
            preCaretRange.setEnd(range.endContainer, range.endOffset);
            caretOffset = preCaretRange.toString().length;
        }
    } else if ( (sel = doc.selection) && sel.type != "Control") {
        var textRange = sel.createRange();
        var preCaretTextRange = doc.body.createTextRange();
        preCaretTextRange.moveToElementText(element);
        preCaretTextRange.setEndPoint("EndToEnd", textRange);
        caretOffset = preCaretTextRange.text.length;
    }
    return caretOffset;
}

function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}


function stripUrl(url){
    return url.replace(/(^\w+:|^)\/\//, '');

}
function charCountSet(jsSelector){
    $(jsSelector).characterCounter({
        limit: $(this).attr("maxlength"),
        counterCssClass: 'char-counter-styling',
        counterFormat: '%1 character(s) remaining',
    });
}
function maxLengthCheck(object)
{

    if (parseInt(object.value) > parseInt(object.max))
        object.value = object.max
}



function secondsToHms(d) {
    d = Number(d);
    if (d===0) return '0 seconds';

    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " hour " : " hours ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay;
}

function trimLastComma(str){
    str = str.replace(/,\s*$/, "");
    return str;
}

function Tabs() {
    var self = this;

    /**
     * The enclosing element of grouped tab buttons and tabs
     * @type jQuery
     */
    self.$tabsMain = jQuery('div.tabs-parent');

    /**
     * The tab buttons and their parent
     * @type jQuery
     */
    self.$buttonsParent = self.$tabsMain.find('> div.tab-buttons');

    /**
     * The indicator for the current tab (bottom border, moving)
     * @type jQuery
     */
    self.$tabIndicator = self.$buttonsParent.find('> div.tab-indicator');

    /**
     * The element that scrolls the tabs in/out of view
     * @type jQuery
     */
    self.$tabsScroller = self.$tabsMain.find('> div.tabs > div.tabs-scroller');

    /**
     * All the individual tabs
     * @type jQuery
     */
    self.$allTabs = self.$tabsScroller.find('> div');

    /**
     * The properties of the current tab
     * @type Object
     */
    self.currentTab = {
        $button: null,
        index: null,
        $label: null,
        label: null
    };

    /**
     * Tab button indexes, of the tabs that have errors
     * This prevents creating redundant error indicators
     * @type Array
     */
    self.hasErrors = [];


    /**
     * Get the initial checked radio button at pageload
     * Which is the first one, if there is no checked one
     * @param  jQuery  $parent  The buttons parent element
     * @return Object
     */
    self.getRadioButtons = function ($parent) {
        var $all = $parent.find('> input[type="radio"]');
        var $enabled = $all.not('[disabled]');
        var $disabled = $all.filter('[disabled]');

        // Get a checked radio button, and use that as the initial one if it exists
        var $checked = $enabled.filter(':checked');
        if ($checked.length > 0)
            var $initial = $checked;

        // Otherwise use the first radio button, and set it on checked
        else {
            var $initial = jQuery($enabled.get(0));
            $initial.prop('checked', true);
        }

        return {
            // All the tab buttons
            all: $all,
            // The enabled tab button (no 'disabled' attribute)
            enabled: $enabled,
            // The disabled tab button (with 'disabled' attribute)
            disabled: $disabled,
            // The currently active tab button
            initial: {
                button: $initial,
                // The label element of the tab button
                label: $initial.next('label'),
                // The tab button index (in the set of tab buttons)
                index: $all.index($initial)
            }
        };
    };


    /**
     * Update the tab scroller height, based on a tab index
     * @param  Integer  index  (Optional) uses current index if not given
     */
    self.updateHeight = function (index) {
        if (jQuery.type(index) == 'undefined')
            index = self.currentTab.index;

        self.$tabsScroller.css('height', jQuery(self.$allTabs.get(index)).outerHeight());
    };


    /**
     * Enable the tabs mechanism
     */
    self.enableTabs = function () {
        // All the radio buttons, including the initial one to respond to
        var $radioButtons = self.getRadioButtons(self.$buttonsParent);

        // This width is needed for the horizontal scrolling amount
        var mainWidth = self.$tabsMain.width();

        // Set the initial tab indicator position/size
        var labelLeftPadding = parseInt($radioButtons.initial.label.parent().css("padding-left")) / 2;
        self.$tabIndicator.css({
            left: $radioButtons.initial.label.position().left + labelLeftPadding,
            width: $radioButtons.initial.label.width()
        });

        // Scroll to the initial tab
        self.$tabsScroller.css({
            display: 'block',
            left: (0 - mainWidth) * $radioButtons.initial.index
        });

        // Set the scroller height, based on the current tab
        // For some reason it needs to happen in a new thread (height = -40 in the main thread at this point)
        setTimeout(function () {
            self.currentTab = {
                $button: $radioButtons.initial.button,
                index: $radioButtons.initial.index,
                $label: $radioButtons.initial.label,
                label: $radioButtons.initial.label.text()
            };
            self.updateHeight();

            // Trigger the 'tab switched' event
            //   App.variables.$event.trigger('tabSwitched.cm', self.currentTab);
        }, 10);

        // Update the indicator and scroller when clicking a button
        $radioButtons.all.each(function (index, radiobutton) {
            var $radioButton = jQuery(radiobutton);
            var $label = $radioButton.next('label');
            var $labelPadding = parseInt($radioButton.parent().css('padding-left')) / 2;

            $radioButton.on('change', function (event) {
                // Move/resize the indicator, according to the clicked button
                self.$tabIndicator.stop().animate({
                    left: $label.position().left + $labelPadding,
                    width: $label.width()
                }, self.animation = {
                    duration: 500,
                    easing: 'easeOutExpo'
                });

                // Move the scroller to the correct tab
                self.$tabsScroller.stop().animate({
                    left: (0 - mainWidth) * index
                }, self.animation = {
                    duration: 500,
                    easing: 'easeOutExpo'
                });

                // Set the scroller height, based on the current tab
                self.currentTab = {
                    $button: $radioButton,
                    index: index,
                    $label: $label,
                    label: $label.text()
                };
                self.updateHeight();

                // Trigger the 'tab switched' event
                //  App.variables.$event.trigger('tabSwitched.cm', self.currentTab);
            });
        });
    };


    /**
     * Get the tab button at the given index
     * @param   Integer  index
     * @return  Object   {$button, index, $label, label}
     */
    self.getButton = function (index) {
        var $radioButton = jQuery(self.$buttonsParent.find('input[type="radio"]').get(index));
        var $label = jQuery(self.$buttonsParent.find('label').get(index));

        return {
            $button: $radioButton,
            index: index,
            $label: $label,
            label: $label.text()
        }
    };


    /**
     * Set an error inidcator on a tab button at the given index
     * @param  Integer  index
     */
    self.setError = function (index) {
        // Only add an error, if the button doesn't have it set yet
        if (jQuery.inArray(index, self.hasErrors) < 0) {
            // Get the tab button and add the error class
            var $tabButton = self.getButton(index);
            $tabButton.$label.addClass('error');

            // Add tab indicators with an error class
            self.$tabIndicator
                .clone()
                .addClass('error')
                .css({
                    left: $tabButton.$label.offset().left,
                    width: $tabButton.$label.width()
                })
                .appendTo(self.$buttonsParent);

            self.hasErrors.push(index);
        }
    };


    /**
     * Remove all error indicators and reset the errors array
     */
    self.removeErrors = function () {
        // Remove the error class from the tab buttons
        self.$buttonsParent.find('label').removeClass('error');

        // Remove the error indicator elements
        self.$buttonsParent.find('> div.tab-indicator').filter('.error').remove();

        // Reset the errors array
        self.hasErrors = [];
    };
}

window.urlParams = getSearchParameters();


function isValidURL(url) {
    var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if (pattern.test(url)) {
        return true;
    }
    return false;

}

function getFileNameFromUrl(url){
    return url.split('/').pop().split('?')[0]
}


function isValidPhoneNumber(phoneNumber) {
    var pattern = /\+?\d+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/;
    if (pattern.test(phoneNumber) && phoneNumber.length>4) {
        return true;
    }
    return false;
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}

function isInt(n) {
    return +n === n && !(n % 1);
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function changeSwitchery(element, checked) {
    if ( ( element.is(':checked') && checked == false ) || ( !element.is(':checked') && checked == true ) ) {
        element.parent().find('.switchery').trigger('click');
    }
}


function setEndOfContenteditable(contentEditableElement)
{
    var range,selection;
    if(document.createRange)//Firefox, Chrome, Opera, Safari, IE 9+
    {
        range = document.createRange();//Create a range (a range is a like the selection but invisible)
        range.selectNodeContents(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        selection = window.getSelection();//get the selection object (allows you to change selection)
        selection.removeAllRanges();//remove any selections already made
        selection.addRange(range);//make the range you have just created the visible selection
    }
    else if(document.selection)//IE 8 and lower
    {
        range = document.body.createTextRange();//Create a range (a range is a like the selection but invisible)
        range.moveToElementText(contentEditableElement);//Select the entire contents of the element with the range
        range.collapse(false);//collapse the range to the end point. false means collapse to end rather than the start
        range.select();//Select the range (make it the visible selection
    }
}

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();

    $.each(a, function() {

        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function removeUrlParameter(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }

    window.history.replaceState('',document.title,rtn);
    return rtn;
}

$(document).ready(function(){

    $('body').on('focus', 'div.emoji-wysiwyg-editor', function() {
        $(this).prev("span.char-counter-styling").insertAfter($(this)).css("display","block");
    }).on('blur', 'div.emoji-wysiwyg-editor', function() {
        $(this).next("span.char-counter-styling").insertBefore($(this)).css("display","none");
    });
    autoResponseWarning();
    $(document).on('click','#hide-warning-autoresponses',function(){
        var ajax_url = 'includes/admin-ajax.php';
        var data = {'action': 'hide_auto_responses_warning'};
        jQuery.post(ajax_url, data, function (response) {
            $('.warning-autoresponses').remove();
        });
    });

    $('.warning1').on('click',function(){
        var ajax_url = 'includes/admin-ajax.php';
        var data = {'action': 'hidewarning'};
        jQuery.post(ajax_url, data, function (response) {
                $('.warning1').remove();
                $('#wrapper').css('margin-top','');
        });
    });


});


function autoResponseWarning(){
    var ajax_url = 'includes/admin-ajax.php';
    var data = {'action': 'get_auto_responses_status'};
    jQuery.post(ajax_url, data, function (res) {
        if(res=='2'){
            $('<div class="warning-autoresponses" id="auto_responses_warning">Warning: Your bot automation is paused, please activate it on the configuration page. <a class="btn btn-primary warning-autoresponses-button" href="/manage.php">Configure Page</a><a id="hide-warning-autoresponses" ><i  class="icon-cross fa-3 warning-icon-close"></i></a></div>').insertBefore('#wrapper');
        }
    });
}

function getSegmentName(segmentID){

    let ajax_url = 'includes/admin-ajax.php';
    let data = {
        'action': 'get_segment_name',
        'segment_id' : segmentID
    };
    return jQuery.post(ajax_url, data);

}

function placeCaretAtEnd(el) {
    el.focus();
    if (typeof window.getSelection != "undefined"
        && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}

jQuery.expr[':'].parents = function(a,i,m){
    return jQuery(a).parents(m[3]).length < 1;
};

(function($) {
    if ($.fn.style) {
        return;
    }

    // Escape regex chars with \
    var escape = function(text) {
        return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    };

    // For those who need them (< IE 9), add support for CSS functions
    var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;
    if (!isStyleFuncSupported) {
        CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
            return this.getAttribute(a);
        };
        CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
            this.setAttribute(styleName, value);
            var priority = typeof priority != 'undefined' ? priority : '';
            if (priority != '') {
                // Add priority manually
                var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
                    '(\\s*;)?', 'gmi');
                this.cssText =
                    this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
            }
        };
        CSSStyleDeclaration.prototype.removeProperty = function(a) {
            return this.removeAttribute(a);
        };
        CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
            var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
                'gmi');
            return rule.test(this.cssText) ? 'important' : '';
        }
    }

    // The style function
    $.fn.style = function(styleName, value, priority) {
        // DOM node
        var node = this.get(0);
        // Ensure we have a DOM node
        if (typeof node == 'undefined') {
            return this;
        }
        // CSSStyleDeclaration
        var style = this.get(0).style;
        // Getter/Setter
        if (typeof styleName != 'undefined') {
            if (typeof value != 'undefined') {
                // Set style property
                priority = typeof priority != 'undefined' ? priority : '';
                style.setProperty(styleName, value, priority);
                return this;
            } else {
                // Get style property
                return style.getPropertyValue(styleName);
            }
        } else {
            // Get CSSStyleDeclaration
            return style;
        }
    };
})(jQuery);


(function(e,a,d,g){var b="shadonghongCaret";var c=function(){if(d.getSelection){var h=d.getSelection();if(h.rangeCount>0){return h.getRangeAt(0)}}else{if(a.selection){return a.selection.createRange()}}return null};var f=function(h){e(h).off("mouseup keyup").on("mouseup keyup",function(){e(this).data(b,c())});e(h).each(function(){if(!e(this).hasfocus){e(this).focus();var i=c();i.selectNodeContents(this);i.collapse(false);e(this).data(b,i);e(this).blur()}})};e.initCursor=f;e.fn.insertAtCursor=function(h){return this.each(function(){var r=this,k,t=0,u=0,q=("selectionStart" in r&&"selectionEnd" in r),p,i,n,s,l;if(!((r.tagName&&r.tagName.toLowerCase()==="textarea")||(r.tagName&&r.tagName.toLowerCase()==="input"&&r.type.toLowerCase()==="text"))){if(!e(r).hasfocus){e(r).focus()}if(d.getSelection&&d.getSelection().getRangeAt){n=e(r).data(b)||(f(r),e(r).data(b));n.collapse(false);l=n.createContextualFragment(h);var o=l.lastChild;n.insertNode(l);if(o){n.setEndAfter(o);n.setStartAfter(o)}var m=d.getSelection();m.removeAllRanges();m.addRange(n)}else{if(a.selection&&a.selection.createRange){a.selection.createRange().pasteHTML(h)}}}else{k=r.scrollTop;if(q){t=r.selectionStart;u=r.selectionEnd}else{r.focus();n=a.selection.createRange();n.moveStart("character",-r.value.length);t=n.text.length}if(u<t){u=t}p=(r.value).substring(0,t);i=(r.value).substring(u,r.value.length);r.value=p+h+i;t=t+h.length;if(q){r.selectionStart=t;r.selectionEnd=t}else{n=a.selection.createRange();n.moveStart("character",t);n.moveEnd("character",0);n.select()}r.scrollTop=k}})}})(jQuery,document,window);



function pasteHtmlAtCaretPersonalization(html, selectPastedContent) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            var firstNode = frag.firstChild;
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                if (selectPastedContent) {
                    range.setStartBefore(firstNode);
                } else {
                    range.collapse(true);
                }
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if ( (sel = document.selection) && sel.type != "Control") {
        // IE < 9
        var originalRange = sel.createRange();
        originalRange.collapse(true);
        sel.createRange().pasteHTML(html);
        var range = sel.createRange();
        range.setEndPoint("StartToStart", originalRange);
        range.select();
    }
}
function pasteHtmlAtCaret(html, selectPastedContent) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // only relatively recently standardized and is not supported in
            // some browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            var firstNode = frag.firstChild;
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                if (selectPastedContent) {
                    range.setStartBefore(firstNode);
                } else {
                    range.collapse(true);
                }
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if ( (sel = document.selection) && sel.type != "Control") {
        // IE < 9
        var originalRange = sel.createRange();
        originalRange.collapse(true);
        sel.createRange().pasteHTML(html);
        if (selectPastedContent) {
            range = sel.createRange();
            range.setEndPoint("StartToStart", originalRange);
            range.select();
        }
    }
}

function copyToClipboard(texttocopy) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(texttocopy).select();
    document.execCommand("copy");
    $temp.remove();

    try {
        var successful = document.execCommand('copy');
        if (successful) return true;
        else return false;
    } catch (err) {
        $temp.remove();
        return false;
    }
}

function getMatches(string, regex, index) {
    index || (index = 1); // default to the first capturing group
    var matches = [];
    var match;
    while (match = regex.exec(string)) {
        matches.push(match[index]);
    }
    return matches;
}


function copyTextToClipboard(copyText){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(copyText).select();
    document.execCommand("copy");
    $temp.remove();
}

$.fn.destroyDropdown = function() {
    return $(this).each(function() {
        $(this).parent().dropdown( 'destroy' ).replaceWith( $(this) );
    });
};

const toKebabCase = str =>
    str &&
    str
        .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
        .map(x => x.toLowerCase())
        .join('-');
