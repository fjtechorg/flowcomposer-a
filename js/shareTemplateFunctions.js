var templateId = '';
/*
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize
);
const inputElement = document.querySelector("input.filepond");
const pond = FilePond.create(
    inputElement,
    {
        labelIdle: 'Drag & Drop your picture or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 400
    }
);
*/
$(document).ready(function(){
    /*
    $('.summernote').summernote({
        height: 200,                 // set editor height
        minHeight: 200,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
    */
    loadTemplateData();

});

$('.save_template').on('click',function(){
    saveTemplate();
});

$('.click-to-copy').on('click',function(){
    var text = $(this).attr('data-original-link');
    copyTextToClipboard(text);
    toastr.success(text, 'Copied link!');
});

$('#share_template').on('change',function(){
    if($('#share_template').prop('checked')===true){
        setShareStatus(1);
    }
    else if($('#share_template').prop('checked')===false){
        setShareStatus(0);
    }
});

$('#regenerate_template_code').on('click',function(){
    var code = $('#share-code-link').attr('data-code');
    regenerateTemplateCode(code);
});

$('#request_approval').on('click',function(){
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'template_request_approval'
    };
    jQuery.post(ajax_url, data, function () {
        $('#public_share_status').css('color','#ffff00');
        $('#public_share_status').html('Pending');
        $('#request_approval').css('display','none');
        toastr.success('Requested approval for template.', 'Success!');
    });
});

function saveTemplate(){
    var name= $('#template-name').val();
    var shortDescSelector = $('#template-short-description');
    var fullDescSelector = $('#template-full-description');
    var shortDescription = shortDescSelector.val();
    var fullDescription = fullDescSelector.val();
    var author= $('#template-author').val();
    var tags= $('#template-tags').val();
    var category= $('#template-category').val();
    var type= $('#share-type').val();
    /*
    if(pond.getFile()==null){
        toastr.error('Please upload an image.', 'Error!');
        return false;
    }
    */
    if(name===''){
        toastr.error('Please enter name.', 'Error!');
        return false;
    }
    if(shortDescription===''){
        toastr.error('Please enter short description.', 'Error!');
        return false;
    }
    if(fullDescription===''){
        toastr.error('Please enter full description.', 'Error!');
        return false;
    }
    var shortDescriptionText = shortDescription.replace(/<\/?[^>]+(>|$)/g, '');
    var fullDescriptionText = fullDescription.replace(/<\/?[^>]+(>|$)/g, '');
    if(shortDescriptionText.length<100 || shortDescriptionText.length>140){
        toastr.error('Short description has '+shortDescriptionText.length+' character(s), it should be between 100 and 140 characters.', 'Error!');
        return false;
    }
    if(fullDescriptionText.length<350 || fullDescriptionText.length>500){
        toastr.error('Full description has '+fullDescriptionText.length+' character(s), it should be between 350 and 500 characters.', 'Error!');
        return false;
    }
    if(author===''){
        toastr.error('Please enter author.', 'Error!');
        return false;
    }
    if(tags===''){
        toastr.error('Please enter tags.', 'Error!');
        return false;
    }
    if(category===''){
        toastr.error('Please select a category.', 'Error!');
        return false;
    }
    if(type===''){
        toastr.error('Please select type.', 'Error!');
        return false;
    }
    /*
    var blob = pond.getFile().file;
    var reader = new FileReader();
    reader.readAsDataURL(blob);
    reader.onloadend = function() {
        var base64data = reader.result;
        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'save_page_template_data',
            'imageBase64' : base64data,
            'name': name,
            'shortDesc': shortDescription,
            'fullDesc': fullDescription,
            'author':author,
            'tags': tags,
            'categoryId': category,
            'type': type
        };
        $.post(ajax_url, data, function (response) {
                loadTemplateData();
                toastr.success("Template is saved.", "Success!");
        });
    };
    */
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'save_page_template_data',
        'imageBase64' : '',
        'name': name,
        'shortDesc': shortDescription,
        'fullDesc': fullDescription,
        'author':author,
        'tags': tags,
        'categoryId': category,
        'type': type
    };
    $.post(ajax_url, data, function (response) {
        loadTemplateData();
        toastr.success("Template is saved.", "Success!");
    });

}

function loadTemplateData(){
    $.blockUI({
        message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Loading data...</span>',
        overlayCSS: {opacity: .5}
    });
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'get_page_template_data'
    };
    jQuery.post(ajax_url, data, function (response) {
        if(response != 'null'){
            var decoded = JSON.parse(response);
            templateId = decoded['id'];
            //const img = JSON.parse(decoded['image_base64']);
            //pond.addFile(img);
            $('#private_share_links_block').css('display','');
            $('#share_status_block').css('display','');
            if(decoded['new']==='true'){
                $('#template-name').val(decoded['title']);
                $('#template-short-description').val(decoded['short_desc']);
                $('#template-author').val(decoded['author']);
                $('#private_share_links_block').css('display','none');
                $('#share_status_block').css('display','none');
                $.unblockUI();
                return false;
            }
            $('#template-name').val(decoded['title']);
            $('#template-short-description').val(decoded['short_desc']);
            $('#template-full-description').val(decoded['full_desc']);
            $('#template-author').val(decoded['author']);
            $('#template-tags').val(decoded['tags']);
            $('#template-category').val(decoded['category_id']);
            $('#share-type').val(decoded['type']);
            $('#template-installs').html(decoded['installed']);
            //share status
            if(decoded['share_status']==0){
                $('#share_template').prop('checked',false);
            }
            else if(decoded['share_status']==1){
                $('#share_template').prop('checked',true);
            }
            //approval status
            if(decoded['approval_status']==0){
                $('#public_share_status').html('Not submitted');
            }
            else if(decoded['approval_status']==1){
                $('#public_share_status').css('color','#ffff00');
                $('#public_share_status').html('Pending');
                $('#request_approval').css('display','none');
            }
            else if(decoded['approval_status']==2){
                $('#public_share_status').css('color','#04D392');
                $('#public_share_status').html('Approved');
                $('#request_approval').css('display','none');
                $('.declined_msg_block').css('display','none');
            }
            else if(decoded['approval_status']==3){
                $('#public_share_status').css('color','#FF1C1C');
                $('#public_share_status').html('Denied');
                $('.declined_msg_block').css('display','');
                var declinedMsg = '<spam>'+decoded['declined_time']+'</spam><p>'+decoded['declined_comment']+'</p>';
                $('#declined_msg').html(declinedMsg);
            }

            //code link
            $('#share-code-link').attr('data-code',decoded['code']);
            var link = 'https://app.clevermessenger.com/templates/'+templateId+'/'+decoded['code'];
            var title = 'Click to copy: '+link;
            $('#share-code-link').attr('data-original-link',link);
            $('#share-code-link').attr('data-original-title',title);
            $('#share-code-link').html(link);
        }
        $.unblockUI();
    });
}

function copyTextToClipboard(copyText){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(copyText).select();
    document.execCommand("copy");
    $temp.remove();
}

function setShareStatus(status){
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'set_public_share_template_status',
        'status': status
    };
    jQuery.post(ajax_url, data, function (response) {
        if(response==1){
            toastr.success("Template status changed.", "Success!");
        }
        else if(response==0){
            $('#share_template').prop('checked',false);
            toastr.error("Template not found, please save the template for the first time then change status.", "Error!");
        }
    });
}

function regenerateTemplateCode(code){
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'regenerate_template_code',
        'code': code
    };
    return jQuery.post(ajax_url, data, function (response) {
        var newCode = response;
        $('#share-code-link').attr('data-code',newCode);
        var newLink = 'https://app.clevermessenger.com/templates/'+templateId+'/'+newCode;
        var title = 'Click to copy: '+newLink;
        $('#share-code-link').attr('data-original-link',newLink);
        $('#share-code-link').attr('data-original-title',title);
        $('#share-code-link').html(newLink);
    });
}