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
        "ajax":"includes/datatablesSSP/tagsManagerTable.php",
        'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', 'tag_'+data[101]);
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



    $(document).on('click', '.create_tag', function (){
        modalPrompt("Please enter your new tag's name:","",
            function(name){
                if(name === null || name === "") {
                    //user did not enter anything
                    txt = "User cancelled the prompt.";
                }
                else{
                    saveTag(name);
                }
            },
            function(value){
                //user clicked cancel
            });
    });

    $(document).on('click', '.rename_tag', function (){
        var id = $(this).attr('data-id');
        var oldName = $(this).attr('data-name');
        modalPrompt("Please enter new name for this tag :",oldName,
            function(name){
                if(name === null || name === "") {
                    //user did not enter anything
                    txt = "User cancelled the prompt.";
                }
                else{
                    renameTag(id,name);
                }
            },
            function(value){
                //user clicked cancel
            });
    });

    $(document).on('click', '.delete_tag', function (){
        var id = $(this).attr("data-id");
        modalConfirm("Are you sure you want to delete this tag?",
            function(){
                deleteTag(id);
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on('click', '#delete_bulk_tags', function (e){
        var selectedtags = getSelectedRows();
        if (selectedtags.length>0) {
            e.preventDefault();
            modalConfirm("Are you sure you want to delete the selected tag(s)?",
                function(){
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_tags',
                        'ids': JSON.stringify(selectedtags)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                        for (var i=0;i<selectedtags.length;i++) {
                            var oTable = $('.table').dataTable();
                            var nrow = $('tr#tag_' + selectedtags[i]);
                            oTable.fnDeleteRow(nrow, null, true);
                        }
                    });
                },
                function(){
                    //user clicked cancel
                });
        }else{
            modalAlert("Before you can delete, please make sure you selected at least one tag");
        }
    });

    $('[data-toggle="tooltip"]').tooltip({
        'container': 'body'
    });

});

function renameTag(id,name){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'rename_tag',
        'id': id,
        'name': name
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1) {
            var tagNameP = $('#tag_name_'+id);
            tagNameP.html(name);
            tagNameP.closest('tr').find('.rename_tag').attr('data-name',name);
            toastr.success('Tag renamed successfully.','Success!');
        }
        else if(res=='exists'){
            toastr.error('Tag name already exists.','Error!');
        }
    });
}

function deleteTag(id){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'delete_tag',
        'id': id
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1) {
            var oTable = $('.table').dataTable();
            var nrow = $('tr#tag_' + id);
            oTable.fnDeleteRow(nrow, null, true);
            toastr.success('Tag deleted successfully', 'Success!');
        }
    });
}

function saveTag(name){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'create_tag',
        'tag': name
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res!=='exists'){
            res = JSON.parse(res);
            var t = $('.table').DataTable();
            var rowNode = t.row.add( [
                '<input type="checkbox" value="'+res.id+'" class="i-checks action_single_check"/>',
                '<p id="tag_name_'+res.id+'">'+res.tag_name+'</p>',
                res.subs_count,
                res.date_added,
                "<div class='btn-group'>"+
                "<button data-toggle='dropdown' class='btn btn-default btn-sm dropdown-toggle' aria-expanded='false' style='background: white;color: grey;'>Actions <span class='caret'></span></button>"+
                "<ul class='dropdown-menu pull-right'>"+
                    "<li><a class='rename_tag' data-name='"+res.tag_name+"' data-id='"+res.id+"'>Rename</a></li>"+
                    "<li class='divider'></li>"+
                    "<li><a class='delete_tag' data-id='"+res.id+"'>Delete</a></li>"+
                "</ul>"+
                "</div>"
            ] ).draw(false);
            rowNode.id='tag_'+res;
            toastr.success('Tag created successfully','Success!');
        }
        else if('exists'){
            toastr.error('Tag name already exists.','Error!');
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