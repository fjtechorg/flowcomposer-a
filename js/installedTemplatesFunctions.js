$(document).ready(function(){
    $(".table").DataTable({

        "language": { "search":"" },
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
    $("input.input-sm").attr("placeholder","Search...");
    $('.dataTables_filter label').append('<i class="search-mag fa icon-magnifier" aria-hidden="true"></i>');


    $('.form-control.input-sm').on('change',function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });

    $(document).on('click', '.uninstall_template', function (e) {

        var id = $(this).attr('data-id');
        console.log(id);
        modalConfirm("Are you sure you want to uninstall this template?",
            function(){
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'uninstall_template',
                    'id': id
                };
                jQuery.post(ajax_url, data, function(res) {
                    console.log(res);
                    var oTable = $('.table').dataTable();
                    var nrow = $('tr#template_'+id);
                    oTable.fnDeleteRow( nrow, null, true );
                    toastr.success('Template uninstalled.','Success!');
                });
            },
            function(){
                //user clicked cancel
            });
    });

    $(document).on('click', '#bulk_uninstall', function (e){
        var ids = getSelectedIds();
        if (ids.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to uninstall the selected templates(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'bulk_uninstall_templates',
                        'ids': JSON.stringify(ids)
                    };
                    jQuery.post(ajax_url, data, function() {
                        for(var i=0;i<ids.length;i++){
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#template_'+ids[i]);
                            oTable.fnDeleteRow( nrow, null, true );
                        }
                        toastr.success('Templates uninstalled.','Success!');
                    });
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            modalAlert("Before you can uninstall, please make sure you selected at least one template");
        }
    });
});

function getSelectedIds(){
    var iDs = new Array();
    var rows = $('.table').dataTable().fnGetNodes();
    $(rows).each(function () {
        if ( $(this).find(".action_single_check").prop( "checked" ) ) {
            var segid = $(this).find(".action_single_check").val();
            iDs.push(segid);
        }
    });
    return iDs;
}