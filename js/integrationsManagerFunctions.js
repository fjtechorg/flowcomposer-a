//global vars
//redirectPost is used to identify if the page is redirected after an integration auth is requested
redirectPost = false;
$(document).ready(function() {
    getIntegrationParams();

    /*------------Bulk Checks---------------------------------------*/
    $(document).on('ifChecked', '.action_bulk_check', function (e) {
        var table = $('.table').DataTable();
        var rows = table.rows({ page: 'current' }).nodes();
        $(rows).each(function () {
            $(this).find(".action_single_check").iCheck('check');
        });
    });

    $(document).on('ifUnchecked', '.action_bulk_check', function (e) {
        var table = $('.table').DataTable();
        var rows = table.rows({ page: 'all' }).nodes();
        $(rows).each(function () {
            $(this).find(".action_single_check").iCheck('uncheck');
        });
    });
    /*------------Bulk Checks---------------------------------------*/

    var table = $('.table').DataTable({
        responsive: true,
        "bAutoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax":"includes/datatablesSSP/integrationsManagerTable.php",
        'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', 'integration_'+data[102]);
        },
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        language: { search: "" },
        "dom": 'T<"clear">lfrtip',
        "aaSorting": [[ 3, "desc" ]],
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        },
        'drawCallback': function(settings){
            //iCheck for checkbox
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });
        }
    });

    $("input.input-sm").attr("placeholder","Search...");
    $('.dataTables_filter label').append('<i class="search-mag fa icon-magnifier" aria-hidden="true"></i>');



    $('.form-control.input-sm').on('change',function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });

    var intSelector = $('[data-target="integration-selector"]').selectize({
        persist: false,
        create: true
    });

    $(document).on('click', '.create_integration', function (){
        $('#integration-form').css('display','none');
        $('#integration-form').html('');
        $('#save-integration').css('display','none');
        var selectize = intSelector[0].selectize;
        selectize.clear();
        $('#integration-error').html('');
        $('#modal_create_integration').modal('toggle');
    });

    $(document).on('change', '[data-target="integration-selector"]', function (){
        var id = $(this).val();
        $('#save-integration').attr('data-int-id',id);
        $('#integration-form').css('display','none');
        $('#integration-name').css('display','none');
        $('#integration-form').html('');
        $('#integration-error').html('');
        if(redirectPost == true){
            return false;
        }
        createIntegrationDisplayForm(id);
    });

    $(document).on('click', '#save-integration', function (){
        var id = $(this).attr('data-int-id');
        saveIntegration(id);
    });

    $(document).on('click', '.change_status_int', function (){
        var id = $(this).attr("data-id");
        var currentStatus = $(this).attr("data-status");
        changeIntStatus(id,currentStatus);
    });

    $(document).on('click', '.delete_integration', function (){
        var id = $(this).attr("data-id");
        modalConfirm("Are you sure you want to delete this integration?",
            function(){
                deleteIntegration(id);
            },
            function(){
                //user clicked cancel
            });
    });

    $(document).on('click', '#delete_bulk_integrations', function (e){
        var selectedIntegrations = getSelectedRows();
        if (selectedIntegrations.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected integration(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'bulk_delete_page_integrations',
                        'ids': JSON.stringify(selectedIntegrations)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        for (var i=0;i<selectedIntegrations.length;i++) {
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#integration_' + selectedIntegrations[i]);
                            oTable.fnDeleteRow(nrow, null, true);
                        }
                        toastr.success('Integration(s) deleted successfully','Success!');
                    });
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            modalAlert("Before you can delete, please make sure you selected at least one integration");
        }
    });

    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });

});


function deleteIntegration(id){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'delete_page_integration',
        'id': id
    };
    jQuery.post(ajax_url, data, function(res) {
        var oTable = $('.table').dataTable();
        var nrow = $('tr#integration_' + id);
        oTable.fnDeleteRow(nrow, null, true);
        toastr.success('Integration deleted successfully','Success!');
    });
}

function saveIntegration(id){
    $('#integration-error').html('');
    var isValid;
    $("#integration-form input").each(function() {
        var element = $(this);
        if (element.val() == "") {
            isValid = false;
            return false;
        }
    });
    if(isValid == false){
        toastr.error('Please enter all fields.','Error!');
        return false;
    }
    var name = $('#integration-name').val();
    if(name == ''){
        toastr.error('Please enter a name for the integration.','Error!');
        return false;
    }
    var formData = $('#integration-form').serializeArray();
    var jsonData = JSON.stringify(formData);
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'save_integration_keys',
        'formData': jsonData,
        'id': id,
        'name': name
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res=='saved'){
            var t = $('.table').DataTable();
            t.ajax.reload();
            toastr.success('Integration saved successfully','Success!');
            $('#modal_create_integration').modal('toggle');
        }
        else{
            var serv = $('[data-target="integration-selector"] :selected').html();
            toastr.error('Could not connect your '+serv+' account','Error!');
            //toastr.error(res,'Error!');
            $('#integration-error').html('<span class="error-message"><br>'+res+'</span>');
        }
    });
}

function changeIntStatus(id,currentStatus){
    if(currentStatus==1){
        var newStatus=0;
        var newStatusText='Enable';
        var newStatusHtml = '<span class="integration_status label" data-id="'+id+'" style="color: white;background-color:#ec4758;">Disabled</span>';
    }
    else if(currentStatus==0){
        var newStatus=1;
        var newStatusText='Disable';
        var newStatusHtml = '<span class="integration_status label label-primary" data-id="'+id+'" style="background-color: #13ce66;">Enabled</span>';
    }
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'change_integration_status',
        'id': id,
        'newStatus': newStatus
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            $('.change_status_int[data-id="'+id+'"]').attr('data-status',newStatus);
            $('.change_status_int[data-id="'+id+'"]').html(newStatusText);
            $('.integration_status[data-id="'+id+'"]').replaceWith(newStatusHtml);
            toastr.success('Integration status changed.','Success!');
        }
    });
}

function createIntegrationDisplayForm(id){
    if(id==''){
        return false;
    }

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_integration_form',
        'id': id
    };
    jQuery.post(ajax_url, data, function(response) {
        if(response!=''){
            response = JSON.parse(response);
            var name = response.name;
            var logoUrl = response.logo;
            $('#integration-form').append('<h5 class="panel-title"><a href="#"><img src="'+logoUrl+'" width="60" style="margin-right:20px;">'+name+'</a></h5><br>');
            var res = response.details;
            if(res == ''){
                return false;
            }
            res = JSON.parse(res);
            if(res.type == 'direct'){
                for(var i=0;i<res.fields.length;i++){
                    $('#integration-form').append('<input class="form-control" type="text" name="'+res.fields[i].name+'" placeholder="'+res.fields[i].placeholder+'"><br>');
                }
                $('#integration-form').css('display','');
                $('#integration-name').css('display','');
                $('#save-integration').css('display','');
            }
            else if(res.type == 'post'){
                $('#integration-form').append(response.button);
                $('#integration-form').css('display','');
            }
        }
    });
}

function getIntegrationParams(){
    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('int_id')){
        var paramObj = {};
        for(var value of urlParams.keys()) {
            paramObj[value] = urlParams.get(value);
        }
        var paramJSON = JSON.stringify(paramObj);
        console.log(paramJSON);
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'return_integration_get_vars',
            'getParams': paramJSON
        };
        jQuery.post(ajax_url, data, function(res) {
            if(res!=''){
                redirectPost = true;
                res = JSON.parse(res);
                $('[data-target="integration-selector"]').val(urlParams.get('int_id'));
                for (var fieldKey in res) {
                    var fieldValue = res[fieldKey];
                    $('#integration-form').append('<input class="form-control" type="text" name="'+fieldKey+'" value="'+fieldValue+'"><br>');
                    console.log(fieldKey, fieldValue);
                }
                $('#save-integration').attr('data-int-id',urlParams.get('int_id'));
                $('#integration-name').css('display','');
                $('#save-integration').css('display','');
                $('#modal_create_integration').modal('toggle');
                redirectPost = false;
            }
        });
    }
}

function getSelectedRows(){
    var flowIDs = new Array();
    var rows = $('.table').dataTable().fnGetNodes();
    $(rows).each(function () {
        if ( $(this).find(".action_single_check").prop( "checked" ) ) {
            var wid = $(this).find(".action_single_check").val();
            flowIDs.push(wid);
        }
    });
    return flowIDs;
}