

$(document).ready(function() {
    var table = $('.table').DataTable({

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

    $(document).on('click', '.create_new_segment', function (){
        modalPrompt("Please enter your new segment's name:","",
            function(segmentName){
                if (segmentName !== null && segmentName !== "") {
                    window.location = "create_segment.php?name="+encodeURIComponent(segmentName);
                }
            },
            function(value){
                //user clicked cancel
            });
    });


    $(document).on('click', '.action_delete_segment', function (){
        var segmentID = $(this).data("segment_id");
        var segmentName = $(this).data("segment_name");
        jQuery('#segment_name').html(segmentName);
        $('.delete_segment_yes').attr('id', 'delete_'+segmentID);
        //jQuery('#modal_segment_delete').modal();
        modalConfirm("Are you sure you want to delete this segment?",
            function(){
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'delete_segment',
                    'segment_id': segmentID
                };
                jQuery.post(ajax_url, data, function() {
                    $('#segment_'+segmentID).remove();
                    //jQuery('#modal_segment_delete').modal('toggle');
                });
                var oTable = $('.table').dataTable();
                var nrow = $('tr#segment_'+segmentID);
                oTable.fnDeleteRow( nrow, null, true );
            },
            function(){
                //user clicked cancel
            });
    });

    $(document).on('click', '.rename_segment', function (){
        var segmentID = $(this).data("segment_id");
        modalPrompt("Please enter your new segment's name:","",
            function(segmentName){
                if (segmentName !== null || segmentName !== "") {
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'rename_segment',
                        'segment_id': segmentID,
                        'segment_name': segmentName
                    };
                    jQuery.post(ajax_url, data, function() {
                        $('#segmentname_'+segmentID).html(segmentName);

                    });

                }
            },
            function(value){
                //user clicked cancel
            });

    });

    $(document).on('click', '.delete_segment_yes', function (){
        var segmentID = jQuery(this).attr('id').replace('delete_', '');
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_segment',
            'segment_id': segmentID,
        };
        jQuery.post(ajax_url, data, function() {
            $('#segment_'+segmentID).remove();
            jQuery('#modal_segment_delete').modal('toggle');
        });
        var oTable = $('.table').dataTable();
        var nrow = $('tr#segment_'+segmentID);
        oTable.fnDeleteRow( nrow, null, true );

    });

    $(document).on('click', '#delete_bulk_segment', function (e){
        var selectedsegments = getSelectedSegments();
        if (selectedsegments.length>0) {
            e.preventDefault();
            jQuery('#segment_name').html();
            modalConfirm("Are you sure you want to delete the selected segment(s)?",
                function(){
                    var selectedSegments = getSelectedSegments();
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_segment',
                        'segment_ids': JSON.stringify(selectedSegments)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        for (var i=0;i<selectedSegments.length;i++)
                            $('#segment_'+selectedSegments[i]).remove();

                    });
                    for(var i=0;i<selectedSegments.length;i++){
                        var oTable = $('.table').dataTable();
                        var nrow = $('tr#segment_'+selectedSegments[i]);
                        oTable.fnDeleteRow( nrow, null, true );
                    }
                },
                function(){
                    //user clicked cancel
                });
        }else{
            modalAlert("Before you can delete, please make sure you selected at least one segment");
        }
    });


    $(document).on('click', '.delete_bulk_segment_yes', function (){
        var selectedSegments = getSelectedSegments();

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_bulk_segment',
            'segment_ids': JSON.stringify(selectedSegments),
        };
        jQuery.post(ajax_url, data, function(response) {
            for (var i=0;i<selectedSegments.length;i++)
                $('#segment_'+selectedSegments[i]).remove();
            jQuery('#modal_segment_bulk_delete').modal('toggle');

        });
        for(var i=0;i<selectedSegments.length;i++){
            var oTable = $('.table').dataTable();
            var nrow = $('tr#segment_'+selectedSegments[i]);
            oTable.fnDeleteRow( nrow, null, true );
        }

    });

    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });



});



function getSelectedSegments(){
    /*var segmentIDs = $("input:checkbox:checked").map(function(){
        return $(this).val();
    }).get(); //
    return segmentIDs;*/

    var segmentIDs = new Array();
    var rows = $('.table').dataTable().fnGetNodes();
    $(rows).each(function () {
        if ( $(this).find(".action_single_check").prop( "checked" ) ) {
            var segid = $(this).find(".action_single_check").val();
            segmentIDs.push(segid);
        }
    });
    return segmentIDs;
}