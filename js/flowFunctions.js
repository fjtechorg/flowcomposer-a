$(document).ready(function() {


    $(".select_import").select2({
        placeholder: "Select a Flow to Import",
        allowClear: false,
        width: 'resolve'
    });

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
        language: { search: "" },
        "dom": 'T<"clear">lfrtip',
        "order": [],
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
    $(document).on('click', '.live_chat', function (e){
        e.preventDefault();
        var profile_id = $(this).data("profile_id");
        jQuery('#form_profile_id').val(profile_id);
        jQuery('#form2').submit();
        //should redirect to the page with the hidden details
    });

    $(document).ready(function(){
        $('.form-control.input-sm').on('change',function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });
        });

    });

    $(document).on('click', '.create_new_flow', function (){
        createFlowPrompt();
    });


    $(document).on('click', '.delete_flow', function (){
        var flow_id = $(this).data("flow_id");
        var flow_name = $(this).data("flow_name");
        //jQuery('#flow_name').html(flow_name);
        $('.delete_flow_yes').attr('id', 'delete_'+flow_id);
        //jQuery('#modal_flow_delete').modal();
        modalConfirm("Are you sure you want to delete this flow?",
            function(){
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'delete_flow',
                    'flow_id': flow_id
                };
                jQuery.post(ajax_url, data, function(response) {

                });
                var oTable = $('.table').dataTable();
                var nrow = $('tr#flow_'+flow_id);
                oTable.fnDeleteRow(nrow, null, true);
            },
            function(){
                //user clicked cancel
            });
    });

    $(document).on('click', '.rename_flow', function (){
        var flow_id = $(this).data("flow_id");
        modalPrompt("Please enter your new flow's name:","",
            function(flowname){
                if(flowname === null || flowname === "") {
                    //user did not enter anything
                    txt = "User cancelled the prompt.";
                }
                else{
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'rename_flow',
                        'flow_id': flow_id,
                        'flow_name': flowname
                    };
                    jQuery.post(ajax_url, data, function() {

                    });
                    $('#flowname_'+flow_id).html(flowname);
                }
            },
            function(value){
                //user clicked cancel
            });

    });

    $(document).on('click', '.delete_flow_yes', function (){
        var flow_id = jQuery(this).attr('id').replace('delete_', '');
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_flow',
            'flow_id': flow_id,
        };
        jQuery.post(ajax_url, data, function() {

        });
        var oTable = $('.table').dataTable();
        var nrow = $('tr#flow_'+flow_id);
        oTable.fnDeleteRow(nrow, null, true);
        jQuery('#modal_flow_delete').modal('toggle');
    });

    $(document).on('click', '#delete_bulk_flows', function (e){
        var selectedflows = getSelectedUsers();
        if (selectedflows.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected flow(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_flows',
                        'flow_ids': JSON.stringify(selectedflows),
                    };
                    jQuery.post(ajax_url, data, function(response) {

                    });
                    for (var i=0;i<selectedflows.length;i++) {
                        var oTable = $('.table').dataTable();
                        var nrow = $('tr#flow_' + selectedflows[i]);
                        oTable.fnDeleteRow(nrow, null, true);
                    }
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            modalAlert("Before you can delete, please make sure you selected at least one flow");
        }
    });

    $(document).on('click', '.delete_bulk_flows_yes', function (){
        var selectedflows = getSelectedUsers();
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_bulk_flows',
            'flow_ids': JSON.stringify(selectedflows),
        };
        jQuery.post(ajax_url, data, function(response) {

        });
        for (var i=0;i<selectedflows.length;i++) {
            var oTable = $('.table').dataTable();
            var nrow = $('tr#flow_' + selectedflows[i]);
            oTable.fnDeleteRow(nrow, null, true);
        }

        jQuery('#modal_flow_bulk_delete').modal('toggle');
    });

    $(document).on('click', '.share_flow', function (){
        var flow_id = $(this).data("flow_id");
        var flow_name = $(this).data("flow_name");
        jQuery('#share_flow_name').html(flow_name);
        $('.share_flow_yes').attr('id', 'share_'+flow_id);
        //see if we are already sharing this flow and thus we need to manage it
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'share_flow_content',
            'flow_id':flow_id,
            'flow_name':flow_name
        };
        jQuery.post(ajax_url, data, function(response) {
            if(response!==""){
                jQuery('#share_flow_content').html(response);
                jQuery('.share_flow_yes').hide();
            }else{
                jQuery('#share_flow_content').html('<p>Click the button below to share the flow "<span id="share_flow_name">'+flow_name+'</span>".<br>\n' +
                    '                    This will allow you to share the flow with everyone.</p>\n' +
                    '                    <p><strong>Private Share:</strong> <input type="checkbox" name="private_share" id="private_share" class=""><br>\n' +
                    '                    By selecting this box you can share the flow with selected users. If you do not select it the flow will be available to everyone</p>');
                jQuery('.share_flow_yes').show();
            }

        });
        jQuery('#modal_flow_share').modal();
    });


    $(document).on('click', '.duplicate_flow', function (){
        var flow_id = $(this).data("flow_id");
        var flow_name = $(this).data("flow_name");
        jQuery('#duplicate_flow_name').html(flow_name);
        $('.duplicate_flow_yes').attr('id', 'duplicate_'+flow_id);
        jQuery('#modal_flow_duplicate').modal();
    });


    $(document).on('click', '.delete_user', function (){
        var flow_id = $(this).data("flow_id");
        var user_id = $(this).data("user_id");

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_user_flow',
            'user_id':user_id,
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            jQuery('#user_'+user_id).remove();
        });

    });

    $(document).on('click', '.add_user', function (){
        var flow_id = $(this).data("flow_id");
        var user_name = $('#share_flow_content .share_user_name').val();
        $('#user_name').val('');$('#share_users_response').val('');

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'add_user_flow',
            'user_name':user_name,
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            var response_arr = response.split("|", 2);
            if(response_arr[0]==="fail"){
                $('#share_users_response').val('User:'+user_name+' not found');
            }
            if(response_arr[0]==="success"){
                $('#share_flow_content #flow_users').append(response_arr[1])
            }
        });

    });

    $(document).on('click', '.share_flow_delete', function (){
        var flow_id = $(this).data("flow_id");

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'share_flow_delete',
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            jQuery('#modal_flow_share').modal('toggle');
        });

    });

    $(document).on('click', '.share_flow_public', function (){
        var flow_id = $(this).data("flow_id");

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'share_flow_public',
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            jQuery('#modal_flow_share').modal('toggle');
        });

    });

    $(document).on('click', '.share_flow_private', function (){
        var flow_id = $(this).data("flow_id");

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'share_flow_private',
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            jQuery('#modal_flow_share').modal('toggle');
        });

    });

    $(document).on('click', '.import_flow', function (){
        var flow_id = $(this).data("flow_id");
        $('.import_flow_yes').attr('id', 'import_'+flow_id);
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'get_import_flows',
            'flow_id':flow_id
        };
        jQuery.post(ajax_url, data, function(response) {
            $('#select_share_flow').html(response);
        });
        jQuery('#modal_flow_import').modal();
    });



});

$('[data-toggle="tooltip"]').tooltip({
    'container': 'body'
});

function getSelectedUsers(){
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