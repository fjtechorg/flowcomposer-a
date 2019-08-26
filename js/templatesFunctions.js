var catOffset=0,currentCategory='',catOffsetEnd='false';
$(document).ready(function(){
    getTemplates('true');
});
$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() == $(document).height()) {
        getTemplates('false');
    }
});

$(document).on('click','.template-explore',function(){
    var title = $(this).attr('data-title');
    var image = $(this).attr('data-image');
    var author = $(this).attr('data-author');
    var category = $(this).attr('data-category');
    var demoPageId = $(this).attr('data-demo-page-id');
    $('.template-explore-title').html(title);
    $('.template-explore-category').html(category);
    $('.template-explore-author').html(author);
    $('.template-explore-image > i').attr('class',image);
    var ajax_url='includes/admin-ajax.php';
    var data = {
        'action': 'get_public_template_modal_data',
        'page_index_id': demoPageId
    };
    jQuery.post(ajax_url, data, function(res){
        res= JSON.parse(res);
        $('.template-explore-image > i').css('background-color',res['background_color']);
        $('.template-explore-image > i').css('color',res['font_color']);
        $('#template-explore-me-link > span').css('background-color',res['background_color']);
        $('#template-explore-me-link > span').css('border-color',res['background_color']);
        $('#install_template').css('color',res['background_color']);
        $('#install_template').css('border-color',res['background_color']);
        $('#temp_style').remove();
        var style = $('<style id="temp-style">#install_template:hover { border-color: '+res['background_color']+' !important; } #template-explore-me-link span.btn:hover{ border-color: '+res['background_color']+' !important; }</style>');
        $('html > head').append(style);
        $('.template-explore-author').css('color',res['background_color']);
        $('.template-explore-author :hover').css('color',res['background_color']);
        $('#template-explore-me-link').attr('href','https://m.me/'+res['page_id']);
        $('.template-explore-full-desc').html(res['full_desc']);
        $('#install_template').attr('data-id',res['id']);
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
            checkboxClass: 'icheckbox_square-grey'
        });
        $('#template_preview').modal();

    });
});

$(document).on('click','#install_template',function(){
    jQuery.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Please while installing the template, the process may take few minutes...</span>',
        overlayCSS: {opacity: .5}
    });
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
    var id = $(this).attr('data-id');
    var ajax_url='includes/admin-ajax.php';
    var data = {
        'action': 'install_public_template',
        'id': id,
        'options': options
    };
    jQuery.post(ajax_url, data, function(res){
        jQuery.unblockUI();
        if(res === 'not exists'){
            toastr.error('Template does not exist.','Error!')
        }
        else{
            window.location.replace("dashboard.php?template_installed=true");
        }
    });
});

$('#category_sort').on('change',function(){
    if($(this).val()===currentCategory){
     return false;
    }
    catOffsetEnd='false';
    currentCategory = $(this).val();
    catOffset=0;
    $('#search-templates').val('');
    getTemplates('true');
});

$('#search-templates').keyup(function(){
    catOffset=0;
    catOffsetEnd='false';
    getTemplates('true');
});

function getTemplates(clearList){
    if(catOffsetEnd==='true'){
      return false;
    }
    var categorySort = $('#category_sort').val();
    var searchTerm = $('#search-templates').val();
    var ajax_url='includes/admin-ajax.php';
    var data = {
        'action': 'get_templates',
        'category': categorySort,
        'catOffset': catOffset,
        'search': searchTerm
    };
    jQuery.post(ajax_url, data, function(res){
        res = JSON.parse(res);
        if(clearList==='true'){
            $('.templates-list').html('');
        }
        if(res.length>0){
            var resHtml ='';
            for(var i=0;i<=(res.length-1);i++){
                //console.log(res[i]);
                var title = res[i].title;
                var author = res[i].author;
                var image = res[i].image;
                var shortDesc = res[i].short_desc;
                var category = res[i].category;
                var pageIndexId = res[i].page_index_id;
                var catId = res[i].catId;
                resHtml = resHtml+   cardTemplate(title,author,image,shortDesc,category,catId,pageIndexId);
            }
            catOffset = catOffset+res.length;
            $('.templates-list').append(resHtml);
        }
        else{
            catOffsetEnd='true';
        }
    });
}

function cardTemplate(title,author,image,shortDesc,category,catId,pageIndexId){
    var descSub = shortDesc;
    /*var image = 'https://graph.facebook.com/'+demoPageId+'/picture?type=large';
    var templateClass = category.replace(/\s+/g, '-').toLowerCase();*/
    var templateClass = 'cat'+catId;
    var card = '<div class="col-lg-3 card-margin-bottom '+templateClass+'-cat-template">' +
                '    <div class="blog-card">' +
                '        <div class="title-content"><h3 style="color: #535f67;">'+title+'</h3>' +
                '            <hr class="card-line">' +
                '            <a class="intro card-author">by '+author +'</a></div>' +
                '        <div class="card-info-under">' +
                '            <i class="'+image+'"></i>' +
                '        </div>' +
                '        <div class="card-info">' +
                '            <i class="'+image+'"></i>' +
                '            <p>'+descSub+'</p>' +
                '            <button class="btn btn-primary template-explore" type="button" data-demo-page-id="'+pageIndexId+'" data-category="'+category+'" data-author="'+author+'" data-image="'+image+'" data-title="'+title+'" ><span class="bold">Discover</span></button>' +
                '        </div>' +
                '        <div class="utility-info">' +
                '            <ul class="utility-list">' +
                '                <li><a style="color: #9da4a8;">'+category+'</a></li>' +
                '            </ul>' +
                '        </div>' +
                '    </div>' +
                '</div>';
    return card;
}