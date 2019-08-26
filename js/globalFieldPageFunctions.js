var selz;


$(document).ready(function() {

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
        "ajax":"includes/datatablesSSP/globalFieldsTable.php",
        'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', 'field_'+data[101]);
        },
        /*
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        */
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

    $(document).on('change', '#global_field_type', function (){
        var type = $(this).val();
        var cfValue = $('#global_field_value');
        var temp = cfValue.val();
        destroyValueInstances();
        cfValue.attr('class','form-control');
        cfValue.val(temp);
        switch(type) {
            case 'list':
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Start typing to add elements');
                initializeLists();
                break;
            case 'numeric':
                cfValue.attr('type','number');
                cfValue.attr('placeholder','Specify a numeric value');
                break;
            case 'date':
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Click to pick a date');
                initializeDatePicker();
                break;
            case 'url':
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Specify a valid URL e.g. https://clevermessenger.com');
                break;
            case 'email':
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Specify a valid Email e.g. support@clevermessenger.com');
                break;
            case 'phone':
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Specify a valid Phone number e.g. +12223334444');
                break;
            default:
                cfValue.attr('type','text');
                cfValue.attr('placeholder','Specify a value');
        }
    });

    $(document).on('click', '.create_global_field', function (){
        clearFormFields();
        $('.form-control').css('border-color','');
        $('.cf-title').html('Create global field');
        $('.save_global_field_yes').attr('data-type','create');
        $('#modal_global_field_create').modal('toggle');
    });

    $(document).on('click', '.edit_global_field', function (){
        var id = $(this).attr('data-id');
        $('.form-control').css('border-color','');
        $('.cf-title').html('Edit global field');
        $('.save_global_field_yes').attr('data-type','update');
        $('.save_global_field_yes').attr('data-id',id);
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'get_global_field',
            'id': id
        };
        jQuery.post(ajax_url, data, function(res) {
            if(res!=='null') {
                res = JSON.parse(res);
                $('#global_field_name').val(res.name);
                $('#global_field_desc').val(res.description);
                $('#global_field_value').attr('type','text');
                $('#global_field_value').val(res.value);
                $('#global_field_type').val(res.type).trigger( "change" );
                $('#modal_global_field_create').modal('toggle');
            }
        });

    });

    $(document).on('click', '.save_global_field_yes', function (){
        $('.form-control').css('border-color','');
        var op = $('.save_global_field_yes').attr('data-type');
        var name = $('#global_field_name').val();
        var type = $('#global_field_type').val();
        var description = $('#global_field_desc').val();
        var value = $('#global_field_value').val();
        //validations
        if(name==''){
            $('#global_field_name').css('border-color','red');
            toastr.error('Please enter a name.','Error!');
            return false;
        }
        if(type==''){
            $('#global_field_type').css('border-color','red');
            toastr.error('Please select a type.','Error!');
            return false;
        }
        if(value==''){
            $('#global_field_value').css('border-color','red');
            toastr.error('Please enter a value.','Error!');
            return false;
        }
        //value validations
        switch(type) {
            case 'date':

                break;
            case 'url':
                if(!isValidURL(value)){
                    $('#global_field_value').css('border-color','red');
                    toastr.error('Please enter a valid url.','Error!');
                    return false;
                }
                break;
            case 'email':
                if(!isValidEmailAddress(value)){
                    $('#global_field_value').css('border-color','red');
                    toastr.error('Please enter a valid email.','Error!');
                    return false;
                }
                break;
            case 'phone':
                if(!isValidPhoneNumber(value)){
                    $('#global_field_value').css('border-color','red');
                    toastr.error('Please enter a valid phone number.','Error!');
                    return false;
                }
                break;
            default:

        }

        if(op=='create'){
            saveGlobalField(name,type,description,value);
        }
        else if(op=='update'){
            var id = $(this).attr('data-id');
            editGlobalField(id,name,type,description,value);
        }
    });

    $(document).on('click', '.delete_global_field', function (){
        var id = $(this).attr("data-id");
        modalConfirm("Are you sure you want to delete the global field?",
            function(){
                deleteGlobalFields([id]);
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on('click', '#delete_bulk_global_fields', function (e){
        var selectedFields = getSelectedRows();
        if (selectedFields.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected global field(s)?",
                function(){
                    deleteGlobalFields(selectedFields);
                },
                function(){
                    //user clicked cancel
                });
        }else{
            modalAlert("Before you can delete, please make sure you selected at least one global field.");
        }
    });

    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });

});

function editGlobalField(id,name,type,description,value){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'update_global_field',
        'id': id,
        'name': name,
        'type': type,
        'desc':description,
        'value':value
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            $('#modal_global_field_create').modal('toggle');
            var t = $('.table').DataTable();
            t.ajax.reload();
            toastr.success('Global field updated.','Success!');
        }
        else{
            toastr.error(res,'Error!');
        }
    });
}

function deleteGlobalFields(ids){
    //ids should be array of ids
    ids = JSON.stringify(ids);
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'delete_global_fields',
        'ids': ids
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res>=1) {
            var t = $('.table').DataTable();
            t.ajax.reload();
            toastr.success('Global field(s) deleted successfully', 'Success!');
        }
    });
}

function saveGlobalField(name,type,description,value){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'save_global_field',
        'name': name,
        'type': type,
        'desc':description,
        'value':value
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            $('#modal_global_field_create').modal('toggle');
            var t = $('.table').DataTable();
            t.ajax.reload();
            toastr.success('Global field added.','Success!');
        }
        else{
            toastr.error(res,'Error!');
        }
    });
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

function initializeLists(){
    $('#global_field_value').attr('class','');
    selz = $('#global_field_value').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
}

function destroyLists(){
    var selectize = selz[0].selectize;
    selectize.destroy();
}

function initializeDatePicker(){
    var dateVal = $("#global_field_value").val();
    window.flatpickrVar = flatpickr("#global_field_value", {
        enableTime: false,
        altInput: true,
        onChange: function(dateObj, dateStr) {
            //window.broadcast_time = Date.parse(dateStr)/1000 - ((parseTimezoneOffset($('#timezone_select').find(":selected").data("offset")) + (new Date().getTimezoneOffset()/60))*3600);
        }
    });
    if(dateVal){
        window.flatpickrVar.set('defaultDate',dateVal);
    }
}

function destroyDatePicker(){
    window.flatpickrVar.destroy();
}

function destroyValueInstances(){
    var cfValue = $('#global_field_value');
    if(cfValue.hasClass('selectized')){
        destroyLists();
    }
    else if(cfValue.hasClass('flatpickr-input')){
        destroyDatePicker();
    }
}

function clearFormFields(){
    $('#global_field_name').val('');
    $('#global_field_desc').val('');
    $('#global_field_value').val('');
    $('#global_field_type').val('').trigger( "change" );
}
/*
var $select = $(document.getElementById('mySelect')).selectize(options);
var selectize = $select[0].selectize;
selectize.addOption({value: 1, text: 'whatever'});
selectize.refreshOptions();
 */