$(document).ready(function () {

    /*------------Bulk Checks---------------------------------------*/
    $(document).on('ifChecked', '.action_bulk_check', function (e) {
        var table = $('.table').DataTable();
        var rows = table.rows({page: 'current'}).nodes();
        $(rows).each(function () {
            $(this).find(".action_single_check").iCheck('check');
        });
    });

    $(document).on('ifUnchecked', '.action_bulk_check', function (e) {
        var table = $('.table').DataTable();
        var rows = table.rows({page: 'all'}).nodes();
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
        "ajax":"includes/datatablesSSP/customFieldsTable.php",
        'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', 'customfield_'+data[101]);
        },
        order:[[ 0, "desc" ]],
        "aoColumns": [
            null,
            null,
            null,
            { "sClass": "special-td" }
        ],
        language: { search: "" },
        "dom": 'T<"clear">lfrtip',
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

    $("input.input-sm").attr("placeholder", "Search...");
    $('.dataTables_filter label').append('<i class="search-mag fa icon-magnifier" aria-hidden="true"></i>');
    $(document).on('click', '.live_chat', function (e) {
        e.preventDefault();
        var profile_id = $(this).data("profile_id");
        jQuery('#form_profile_id').val(profile_id);
        jQuery('#form2').submit();
        //should redirect to the page with the hidden details
    });

    $(document).ready(function () {

        $(document).on('click', '.create_new_customfield', function () {
            jQuery('#modal_customfield_create').modal();
        });

        $(document).on('click', '.create_customfield_yes', function () {
            var customFieldName = $('#customfield_name').val();
            var customFieldType = $('#customfield_type').val();
            if (customFieldName !== "" && customFieldType !== "") {
                var ajax_url = 'includes/admin-ajax.php';
                var data = {
                    'action': 'create_customfield',
                    'customFieldName': customFieldName,
                    'customFieldType': customFieldType
                };
                jQuery.post(ajax_url, data, function (response) {
                    if (response != "" && response > 0) {
                        var t = $('.table').DataTable();
                        t.ajax.reload();
                        toastr.success('Custom field saved successfully','Success!');
                        //add a table row to the table customFieldsTable

                    }
                });

                //window.location.reload();
                $('#customfield_name').val('');
                $('#select_type').val('');

                jQuery("#customfield_type").removeClass("missing_title");
                jQuery("#customfield_type").removeAttr("style");
                jQuery("#customfield_name").removeClass("missing_title");
                jQuery("#customfield_name").removeAttr("style");


                jQuery('#modal_customfield_create').modal('toggle');
            } else {
                //one of the parts is not filled out
                if (customFieldName === "") {
                    jQuery("#customfield_name").addClass("missing_title");
                    jQuery("#customfield_name").css({
                        "background-color": "#f2dede",
                        "color": "#a94442",
                        "border-color": "#a94442"
                    });
                    jQuery('#customfield_name').attr('placeholder', 'Please enter the custom field Name first');
                }

                if (customFieldType === "") {
                    jQuery("#customfield_type").addClass("missing_title");
                    jQuery("#customfield_type").css({
                        "background-color": "#f2dede",
                        "color": "#a94442",
                        "border-color": "#a94442"
                    });
                }

            }


        });


        /*delete single custom field*/
        $(document).on('click', '.delete_customfield', function () {
            var customFieldID = $(this).data("id");
            var customFieldName = $(this).data("name");
            jQuery('#customfield_name').html(customFieldName);
            $('.delete_customfield_yes').data('id', customFieldID);
            //jQuery('#modal_customfield_delete').modal();
            modalConfirm("Are you sure you want to delete this Custom Field?",
                function () {
                    var ajax_url = 'includes/admin-ajax.php';
                    var data = {
                        'action': 'delete_customfield',
                        'customFieldID': customFieldID
                    };
                    jQuery.post(ajax_url, data, function (response) {
                    });
                    var oTable = $('.table').dataTable();
                    var nrow = $('tr#customfield_' + customFieldID);
                    oTable.fnDeleteRow(nrow, null, true);
                },
                function () {
                    //user clicked cancel
                });
        });
        $(document).on('click', '.delete_customfield_yes', function () {
            var customFieldID = jQuery(this).data("id");
            var ajax_url = 'includes/admin-ajax.php';
            var data = {
                'action': 'delete_customfield',
                'customFieldID': customFieldID
            };
            jQuery.post(ajax_url, data, function (response) {
            });
            var oTable = $('.table').dataTable();
            var nrow = $('tr#customfield_' + customFieldID);
            oTable.fnDeleteRow(nrow, null, true);
            jQuery('#modal_customfield_delete').modal('toggle');
        });
        /*delete multiple custom fields*/
        $(document).on('click', '#delete_bulk_customfields', function (e) {
            var selectedcustomfields = getSelectedUsers();
            if (selectedcustomfields.length > 0) {
                e.preventDefault();
                jQuery('#customfield_name').html();
                modalConfirm("Are you sure you want to delete the selected Custom Field(s)?",
                    function () {
                        var ajax_url = 'includes/admin-ajax.php';
                        var data = {
                            'action': 'delete_bulk_customfields',
                            'ids': JSON.stringify(selectedcustomfields)
                        };
                        jQuery.post(ajax_url, data, function (response) {
                        });
                        for (var i = 0; i < selectedcustomfields.length; i++) {
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#customfield_' + selectedcustomfields[i]);
                            oTable.fnDeleteRow(nrow, null, true);
                        }
                    },
                    function () {
                        //user clicked cancel
                    });
            }
            else {
                modalAlert("Before you can delete, please make sure you selected at least one Custom field");
            }
        });
        $(document).on('click', '.delete_bulk_customfields_yes', function () {
            var selectedcustomfields = getSelectedUsers();
            var ajax_url = 'includes/admin-ajax.php';
            var data = {
                'action': 'delete_bulk_customfields',
                'ids': JSON.stringify(selectedcustomfields)
            };
            jQuery.post(ajax_url, data, function (response) {
            });
            for (var i = 0; i < selectedcustomfields.length; i++) {
                var oTable = $('.table').dataTable();
                var nrow = $('tr#customfield_' + selectedcustomfields[i]);
                oTable.fnDeleteRow(nrow, null, true);
            }

            jQuery('#modal_customfields_bulk_delete').modal('toggle');
        });


        $('.form-control.input-sm').on('change', function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });
        });

    });

});

$('[data-toggle="tooltip"]').tooltip({
    'container': 'body'
});

function getSelectedUsers() {
    var searchIDs = new Array();
    var rows = $('.table').dataTable().fnGetNodes();
    $(rows).each(function () {
        if ($(this).find(".action_single_check").prop("checked")) {
            var wid = $(this).find(".action_single_check").val();
            searchIDs.push(wid);
        }
    });
    return searchIDs;
}