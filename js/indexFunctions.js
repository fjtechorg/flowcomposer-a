$(document).ready(function(){
    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('create')){
        if(urlParams.get('create')==='newbot'){
            var ThisID = '';
            botNextStep(ThisID);
        }
    }
    /*
    else if(urlParams.has('template') && urlParams.has('code')){
        jQuery.blockUI({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Verifying template code...</span>',
            overlayCSS: {opacity: .5}
        });
        var template = urlParams.get('template');
        var code = urlParams.get('code');
        var ajax_url='includes/admin-ajax.php';
        var data = {'action': 'check_private_template',
            'template': template,
            'code': code
        };
        jQuery.post(ajax_url, data, function(res) {
            jQuery.unblockUI();
            if(res=='exists'){
                loadTemplateDetails(template);
            }
            else if(res=='not exists'){
                window.location.replace("404");
            }
        });
    }
    */
    $('.carousel').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: false,
        prevArrow: '<a class="slick-prev slick-arrow" style=\display: block;"><i class="icon-chevron-left carousel-nav"></i></a>',
        nextArrow: '<a class="slick-next slick-arrow" style=\display: block;"><i class="icon-chevron-right carousel-nav"></i></a>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('#dfy_template').click(function() {
        $('#dfy_content').load('dfy_funnels.php');
        $('#dfy_content').css('display', 'block');
    });

});

jQuery(function($) {

    $(document).on('keyup', '#pageSearch',function() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("pageSearch");
        filter = input.value.toUpperCase();

        ul = document.getElementById("forum-list");
        li = ul.getElementsByClassName("forum-item");
        for (i = 0; i < li.length; i++) {
            a = li[i].getAttribute("data-page-name");
            if (a.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
                //li[i].getElementById("bot_button").setAttribute('data-visible', 'true');

            } else {
                li[i].style.display = "none";
                //li[i].getElementById("bot_button").setAttribute('data-visible', 'false');

            }
        }
    });

    $(document).on('click', '.bot_card', function (e){
        e.stopPropagation();
        var ThisBotId = $(this).data('bot_id');
        jQuery('#'+ThisBotId+'_dropdown').dropdown('toggle');
    });

    $(document).on('click', '.delete_bot', function () {
        var ThisPageId = $(this).data('page_id');
        var ThisBotId = $(this).data('bot_id');
        jQuery('#SelectedPage').val(ThisPageId);
        jQuery('#SelectedBot').val(ThisBotId);
        modalConfirm("Are you sure you want to delete this bot?",
            function(){
                var BotType="";
                if(ThisPageId!==""){
                    jQuery('#'+ThisPageId+'_card').remove();
                    BotType = 'page';
                }else{
                    jQuery('#'+ThisBotId+'_card').remove();
                    BotType = 'bot';
                }
                var ajax_url='includes/admin-ajax.php';
                var data = {'action': 'delete_bot',
                    'page_id': ThisPageId,
                    'bot_id': ThisBotId,
                    'bot_type':	BotType
                };
                jQuery.post(ajax_url, data, function(response) {
                    if(response!==''){
                        jQuery('#'+response+'_card').remove();
                    }
                });
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on("click",".action_create_bot",function(e){
        e.preventDefault();
        redirectURL = $(this).attr("href");
        $("#facebook_login_button").data("redirect",redirectURL);
        $("#facebook_login").modal();
    });


    $(document).on("click","#facebook_login_button",function(e){
        redirectURL = $(this).data("redirect");
        window.location = redirectURL;
    });

    $(document).on("click","#template_confirm",function(){
        $('#template_preview').modal('toggle');
        loadTemplatePageList();
    });

    $(document).on("click",".install_page_template",function(){
        jQuery.blockUI({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Installing template...</span>',
            overlayCSS: {opacity: .5}
        });

        var pageId = $(this).attr('data-page_id');
        var submitSelector = '#template_install_'+pageId;
        var template = '';
        var code = '';
        var urlParams = new URLSearchParams(window.location.search);

        if(urlParams.has('template') && urlParams.has('code')){
            template = urlParams.get('template');
            code = urlParams.get('code');
        }
        else{
            window.location.replace("404");
        }

        //template options
        var options = {};
        if($('#check_install_template_flows').is(":checked")==true){
            options['flows']=true;
        }
        else{
            options['flows']=false;
        }
        if($('#check_install_capture_tools').is(":checked")==true){
            options['capture_tools']=true;
        }
        else{
            options['capture_tools']=false;
        }
        if($('#check_install_main_menus').is(":checked")==true){
            options['main_menus']=true;
        }
        else{
            options['main_menus']=false;
        }
        if($('#check_install_welcome_message').is(":checked")==true){
            options['welcome_message']=true;
        }
        else{
            options['welcome_message']=false;
        }
        if($('#check_install_default_reply').is(":checked")==true){
            options['default_reply']=true;
        }
        else{
            options['default_reply']=false;
        }
        if($('#check_install_greeting_text').is(":checked")==true){
            options['greeting_text']=true;
        }
        else{
            options['greeting_text']=false;
        }
        options = JSON.stringify(options);
        var ajax_url='includes/admin-ajax.php';
        var data = {'action': 'install_private_template',
            'page_id' : pageId,
            'template': template,
            'code': code,
            'options': options
        };
        jQuery.post(ajax_url, data, function(res) {
            jQuery.unblockUI();
            if(res=='not exists'){
                window.location.replace("404");
            }
            else{
                $(submitSelector).trigger('click');
            }
        });
    });

});



function botNextStep(ThisID){
    var ajax_url='includes/admin-ajax.php';
    var ThisName = jQuery('#bot_name').val();
    var data = {
        'action': 'smartbot_create_bot',
        'bot_name': ThisName,
        'bot_type':'blank'	};

    jQuery.post(ajax_url, data, function(response) {
        var ajax_url='includes/admin-ajax.php';
        jQuery('#bot_id').val(response);
        jQuery('#visual_bot_id').val(response);

        //lets do the fb connection settings now as well
        var data2 = {
            'action': 'connect_to_fb',
            'bot_id': response,
            'page_id': '',
            'bot_page':''
        };
        jQuery.blockUI({
            message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Please hold on while fetching Facebook pages...</span>',
            overlayCSS: {opacity: .5}
        });
        jQuery.post(ajax_url, data2, function(response2) {
            jQuery.unblockUI();
            jQuery('#wizard_connect_fb').html(response2);
            jQuery('#createbot_modal').modal();
            //userpilot.trigger("154947756nZlh4487");
        });

    });
}

function loadTemplatePageList(){
    jQuery.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Loading bot pages...</span>',
        overlayCSS: {opacity: .5}
    });
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'get_private_templates_pages_html'
    };
    jQuery.post(ajax_url, data, function(res) {
        $('#templates_page_list').html(res);
        jQuery.unblockUI();
        $('#template_select_page_modal').modal();
    });
}

function loadTemplateDetails(template){
    var ajax_url='includes/admin-ajax.php';
    var data = {
        'action': 'get_private_template_modal_data',
        'template': template
    };
    jQuery.post(ajax_url, data, function(res){
        res= JSON.parse(res);
        $('.template-explore-title').html(res['title']);
        $('.template-explore-category').html(res['category']);
        $('.template-explore-author').html(res['author']);
        $('.template-explore-image > i').attr('class',res['image']);
        $('.template-explore-image > i').css('background-color',res['background_color']);
        $('.template-explore-image > i').css('color',res['font_color']);
        $('#template-explore-me-link').attr('href','https://m.me/'+res['page_id']);
        $('.template-explore-full-desc').html(res['full_desc']);
        $('#template_confirm').attr('data-id',res['id']);
        $('.template-explore-tags').html(res['tags']);
        var contains = '<ul style="list-style-type: none;">';
        if(res['count_flows']!=0) {
            contains = contains + '<li><input id="check_install_template_flows" class="i-checks" type="checkbox" checked disabled> ' + res['count_flows'] + ' Flow(s)</li>';
        }
        if(res['count_capture_tools']!=0) {
            contains = contains+'<li><input id="check_install_capture_tools" class="i-checks" type="checkbox" checked> '+res['count_capture_tools']+' Capture tool(s)</li>';
        }
        if(res['count_menu']!=0) {
            contains = contains+'<li><input id="check_install_main_menus" class="i-checks" type="checkbox" checked> '+res['count_menu']+' Main menu(s)</li>';
        }
        if(res['welcome_msg']==true) {
            contains = contains+'<li><input id="check_install_welcome_message" class="i-checks" type="checkbox" checked> Welcome message</li>';
        }
        if(res['default_reply']==true) {
            contains = contains+'<li><input id="check_install_default_reply" class="i-checks" type="checkbox" checked> Default reply</li>';
        }
        if(res['greeting_text']==true) {
            contains = contains+'<li><input id="check_install_greeting_text" class="i-checks" type="checkbox" checked> Greeting text</li>';
        }
        contains = contains+'</ul>';

        $('.template-explore-contains').html(contains);
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
        $('#template_preview').modal();
    });
}

function checkPrivateTemplate(template,code){
    jQuery.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;"> Verifying template code...</span>',
        overlayCSS: {opacity: .5}
    });
    var ajax_url='includes/admin-ajax.php';
    var data = {'action': 'check_private_template',
        'template': template,
        'code': code
    };
    jQuery.post(ajax_url, data, function(res) {
        jQuery.unblockUI();
        if(res=='exists'){
            loadTemplateDetails(template);
        }
        else if(res=='not exists'){
            window.location.replace("404");
        }
    });
}