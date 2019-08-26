var table = $('.table').DataTable({
    responsive: true,
    "dom": 'T<"clear">lfrtip',
    "bAutoWidth": false,
    "processing": true,
    "serverSide": true,
    "ajax":"../includes/datatablesSSP/adminIntegrationsTable.php",
    'createdRow': function( row, data, dataIndex ) {
        $(row).attr('id', 'service_'+data[0]);
    }
});
var create = '<div id="create_provider" class="dataTables_length"><label style="margin-left: 10px;padding:5px;">Create</label></div>';
$(".dataTables_length").after(create);

var container = document.getElementById("jsoneditor");
var options = {
    mode: 'tree'
};
var editor = new JSONEditor(container, options);

$(document).on('click','#create_provider',function(){
    $('#provider_name').val('');
    $('#provider_img').val('');
    $('#provider_cat').prop('selectedIndex',0);
    initializeJsonEditor();
    $('#save_provider').attr('data-type','create');
    $('#modal_service_provider_create').modal('toggle');
});

$(document).on('click','.edit_provider',function(){
    var id = $(this).attr('data-id');
    $('#save_provider').attr('data-type','update');
    $('#save_provider').attr('data-id',id);
    console.log(id);
    var ajax_url='../includes/admin-ajax.php';
    var data = {'action': 'admin_get_service_provider',
        'id': id
    };
    jQuery.post(ajax_url, data, function(res) {
        res = JSON.parse(res);
        $('#provider_name').val(res.name);
        $('#provider_img').val(res.logo);
        $('#provider_cat').val(res.type_id);
        var json = JSON.parse(res.details);
        editor.set(json);
        $('#modal_service_provider_create').modal('toggle');
    });
});

$(document).on('click','#save_provider',function(){
    console.log('triggered');
    var name = $('#provider_name').val();
    var img = $('#provider_img').val();
    var cat = $('#provider_cat').val();

    if(name==='' || img===''||cat===''){
        toastr.error('Please fill all fields','Error!');
    }
    var type = $(this).attr('data-type');
    var json = JSON.stringify(editor.get());
    var data = '';
    if(type === 'create'){
        data = {'action': 'admin_create_service_provider',
            'name': name,
            'img': img,
            'cat': cat,
            'json': json
        };
    }
    else if(type === 'update'){
        var id = $(this).attr('data-id');
        data = {'action': 'admin_update_service_provider',
            'id': id,
            'name': name,
            'img': img,
            'cat': cat,
            'json': json
        };
    }
    var ajax_url='../includes/admin-ajax.php';
    if(data!==''){
        jQuery.post(ajax_url, data, function(response) {
            var res = JSON.parse(response);
            if(res.status == 'success') {
                toastr.success(res.msg, 'Success!');
            }
            else if(res.status == 'error'){
                toastr.error(res.msg, 'Error!');
            }
            else{
                toastr.error('Something unexpected happened', 'Error!');
            }
            table.draw(false);
        });
    }
});

$(document).on('click','.edit_field_name',function(){
    $('form#fieldRenameForm input').val('');
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    var img = $(this).attr('data-img');
    $('#save_field_name').attr('data-id',id);
    $('form#fieldRenameForm .int_name').html(' '+name);
    $('form#fieldRenameForm .int_img').attr('src',img);
    $('#modal_service_provider_rename_field').modal('toggle');
});

$(document).on('click','#save_field_name',function(){
    var id = $('#save_field_name').attr('data-id');
    var oldName = $('#field_old_name').val();
    var newName = $('#field_new_name').val();
    if(id==''){
        toastr.error('Id is missing.','Error!');
        return false;
    }
    if(oldName==''||newName==''){
        toastr.error('Please enter both old name and new name.','Error!');
        return false;
    }
    if(oldName==newName){
        toastr.error('old name and new name cannot be same.','Error!');
        return false;
    }
    var ajax_url='../includes/admin-ajax.php';
    var data = {'action': 'admin_service_provider_renamefield',
        'id': id,
        'oldName':oldName,
        'newName':newName
    };
    jQuery.post(ajax_url, data, function() {
        toastr.success('Field name updated.','Success!');
        $('#modal_service_provider_rename_field').modal('toggle');
    });
});

$(document).on('click','.delete_provider',function(){
    var id = $(this).attr('data-id');
    if(confirm('Are you sure you want to delete this service provider?')){
        var ajax_url='../includes/admin-ajax.php';
        var data = {'action': 'admin_delete_service_provider',
            'id': id
        };
        jQuery.post(ajax_url, data, function() {
            toastr.success('Service provider deleted.','Success!');
            table.draw(false);
        });
    }
});

function initializeJsonEditor(){
    var json = {
        "type":"direct",
        "fields":[{"name":"activecampaign-url","placeholder":"enter activecampaign url"},{"name":"activecampaign-key","placeholder":"enter activecampaign key"}],
        "verify_url":"/ping/active-campaign",
        "raw":{
            "credentials": {
                "api_key": "%activecampaign-key%",
                "api_url": "%activecampaign-url%"
            }
        }
    };
    editor.set(json);
}