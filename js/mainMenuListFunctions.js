$(document).ready(function() {

    $("#selected_create_locale").select2({
        placeholder: "Select locale",
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



    $('.form-control.input-sm').on('change',function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });



    $(document).on('click', '.create_new_menu', function (){
        jQuery('#persistent_menu_modal_create_menu').modal();

    });

    $(document).on('click', '.save_create_new_menu', function (){
        var locale = $('#selected_create_locale').val();
        var language = $('#selected_create_locale :selected').html();
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'create_new_persistent_menu_locale',
            'locale': locale
        };
        jQuery.post(ajax_url, data, function(res) {
            if(res=='duplicate'){
                toastr.error("Language/Locale already exists", "Error!");
            }
            if(res=='1'){
                window.location = 'main_menu.php?lang='+locale+'&language='+language;
            }

        });

    });


    $(document).on('click', '.delete_menu_locale', function (){
        var id = $(this).attr("data-id");
        modalConfirm("Are you sure you want to delete this locale/language menu?",
            function(){
                $.blockUI({
                    message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Loading data...</span>',
                    overlayCSS: {opacity: .5}
                });
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'delete_persistent_menu',
                    'id': id
                };
                jQuery.post(ajax_url, data, function(res) {
                    var oTable = $('.table').dataTable();
                    var nrow = $('tr#menu_' +id);
                    oTable.fnDeleteRow(nrow, null, true);
                    $.unblockUI();
                });
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on('click', '#delete_bulk_menus', function (e){
        var selectedmenus = getSelectedUsers();
        if (selectedmenus.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected menu(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_persistent_menus',
                        'ids': JSON.stringify(selectedmenus)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        for (var i=0;i<selectedmenus.length;i++) {
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#menu_' + selectedmenus[i]);
                            oTable.fnDeleteRow(nrow, null, true);
                        }
                    });
                },
                function(){
                    //user clicked cancel
                });
        }else{
            modalAlert("Before you can delete, please make sure you selected at least one menu");
        }
    });

    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });

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