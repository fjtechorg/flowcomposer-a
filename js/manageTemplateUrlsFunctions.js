var templateId='';

$(document).ready(function() {

    /*------------get page template id--------*/
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_page_template_id'
    };
    jQuery.post(ajax_url, data, function(res) {
        templateId =res;
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



    $(document).on('click', '.create_template_url', function (){
        jQuery('#modal_create_code').modal();

    });

    $(document).on('change','#create_code_type',function(){
        var limit = $(this).val();
        if(limit === 'unlimited'){
            $('#create_code_value').css('display','none');
        }
        else if(limit === 'limited'){
            $('#create_code_value').css('display','');
        }
    });

    $(document).on('click', '.save_create_new_code', function (){
        var type = $('#create_code_type').val();
        if(type === 'unlimited'){
            type = 0;
        }
        else if(type === 'limited'){
            type = $('#create_code_value').val();
            if(type<1){
                toastr.error('Limited usage value should be greater than 0.', 'Error!');
                return false;
            }
        }

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'create_template_share_code',
            'type': type
        };
        jQuery.post(ajax_url, data, function(res) {
            if(res.length>1){
                res = JSON.parse(res);
                res = res[0];
                var link = 'https://app.clevermessenger.com/templates/'+res.template_id+'/'+res.code;
                var remove = '<li><a class="delete_code" data-id="'+res.id+'">Delete</a></li>';
                var checkCol = '<input type="checkbox" value="'+res.id+'" class="i-checks action_single_check"/>';
                var linkCol = '<a href="'+link+'" target="_blank" data-url-code="'+res.code+'">'+link+'</a>';
                var actionsCol = '<div class="btn-group">' +
                    '                            <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle" aria-expanded="false" style="background: white;color: grey;">Actions <span class="caret"></span></button>' +
                    '                            <ul class="dropdown-menu pull-right">' +
                    '                                <li><a class="regenerate_code" data-code="'+res.code+'">Regenerate Code</a></li>'+remove+
                    '                            </ul>' +
                    '                        </div>';
                if(res.type==0){
                    res.type = 'Unlimited';
                }
                table.row.add([
                    checkCol,
                    linkCol,
                    res.type,
                    res.used,
                    actionsCol
                ]).node().id = 'code_'+res.id;
                table.draw(false);
                toastr.success('New link created.', 'Success!');
            }
        });
    });


    $(document).on('click', '.delete_code', function (){
        var id = $(this).attr("data-id");
        var row = $(this).closest('tr');
        modalConfirm("Are you sure you want to delete this link?",
            function(){
                $.blockUI({
                    message: '<div class="sk-spinner sk-spinner-three-bounce" style="margin: 10px auto;"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div><span style="text-shadow: black 0px 0px 5px;">Loading data...</span>',
                    overlayCSS: {opacity: .5}
                });
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'delete_template_code',
                    'id': id
                };
                jQuery.post(ajax_url, data, function(res) {
                    var oTable = $('.table').dataTable();
                    var nrow = row;
                    oTable.fnDeleteRow(nrow, null, true);
                    $.unblockUI();
                });
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on('click', '#delete_bulk_codes', function (e){
        var selectedmenus = getSelectedUsers();
        if (selectedmenus.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected Link(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_template_codes',
                        'ids': JSON.stringify(selectedmenus)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        for (var i=0;i<selectedmenus.length;i++) {
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#code_' + selectedmenus[i]);
                            oTable.fnDeleteRow(nrow, null, true);
                        }
                    });
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            modalAlert("Before you can delete, please make sure you selected at least one link");
        }
    });

    $(document).on('click', '.regenerate_code', function (){
        if(templateId!='') {
            var regen = $(this);
            var oldCode = regen.attr('data-code');
            var ajax_url = 'includes/admin-ajax.php';
            var data = {
                'action': 'regenerate_template_code',
                'code': oldCode
            };
            jQuery.post(ajax_url, data, function (res) {
                if (res.length > 1) {
                    var newLink = 'https://app.clevermessenger.com/templates/'+templateId+'/'+ res;
                    regen.attr('data-code', res);
                    var selector = "a[data-url-code$='" + oldCode + "']";
                    $(selector).attr('href', newLink);
                    $(selector).html(newLink);
                    $(selector).attr('data-url-code', res);

                    toastr.success('Link regenerated.', 'Success!')
                }
            });
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