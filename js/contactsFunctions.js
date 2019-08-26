//global vars
var currentStatusSort='';
var currentProfileId='';
$(document).ready(function() {
    var pfd = $('tbody tr:first').attr('data-profile_id');
    getProfileDetailsBlock(pfd);
    /*-------------testing slide in function------------------*/
    var used = 0;
    $(document).on('click', 'tbody tr td:not(.special-td)',function () {
        if(used == 1)
        {
            return;
        }
        used = 1;
        $("tbody tr").removeClass('selected');
        $(this).parent().addClass('selected');
        var inx = $(this).parent().index().toString();
        var slide = $('.table-slide');
        var table = $( ".table-col" );
        var oldinx = slide.attr('data-inx');
        var profile_id = $(this).parent().attr("id");
        profile_id = profile_id.replace('profile_', '');
        var profile_name = $(this).parent().attr("data-profile_name");
        $('.table-slide span.table-col-title').html(profile_name);
        if(inx !== oldinx){
            slide.attr('data-inx',inx);
            if(table.hasClass( "col-lg-12" ))
            {
                table.removeClass("col-lg-12");
                table.addClass("col-lg-8");
                $('.overflow-name-td').addClass('overflow-name-td-ellipsis');
                /* fill table-slide data according to tr clicked*/
                //slide_getuserdata(profile_id,profile_name);
                jQuery('#form_profile_id').val(profile_id);
                currentProfileId = profile_id;
                getProfileDetailsBlock(profile_id);
                slide.addClass( "col-lg-4" );
                slide.css('display','block');
                used = 0;

            }
            else if(table.hasClass( "col-lg-8" ))
            {
                slide.css('display','none');
                /* change table-slide data according to tr clicked*/
                //slide_getuserdata(profile_id,profile_name);
                jQuery('#form_profile_id').val(profile_id);
                currentProfileId = profile_id;
                getProfileDetailsBlock(profile_id);
                slide.css('display','block');
                used = 0;

            }
        }
        else{
            $("tbody tr").removeClass('selected');
            slide.attr('data-inx','');
            slide.css('display','none');
            jQuery('#form_profile_id').val('');
            currentProfileId = '';
            slide.removeClass( "col-lg-4" );
            table.removeClass( "col-lg-8" );
            $('.overflow-name-td').removeClass('overflow-name-td-ellipsis');
            table.addClass( "col-lg-12" );
            used = 0;

        }
    });

    $('.action-close-slide').on('click', function(){
        var slide = $('.table-slide');
        var table = $( ".table-col" );
        slide.attr('data-inx','');
        slide.css('display','none');
        slide.removeClass( "col-lg-4" );
        table.removeClass( "col-lg-8" );
        $('.overflow-name-td').removeClass('overflow-name-td-ellipsis');
        table.addClass( "col-lg-12" );
    });
    /*------------testing slide in function end---------------------*/

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
        "bAutoWidth": false,
        "processing": true,
        "serverSide": true,
        "ajax":"includes/datatablesSSP/contactsTable.php",
        'createdRow': function( row, data, dataIndex ) {
            $(row).attr('id', 'profile_'+data[103]);
            $(row).attr('data-profile_id', data[103]);
            var fullName = '';
            if(!data[105]){
                fullName = data[104]+' '+data[106];
            }
            else{
                fullName = data[104]+' '+data[105]+' '+data[106];
            }

            console.log(data[105]);
            $(row).attr('data-profile_name',fullName);
        },
        /*
        "aoColumns": [
            { data: 'checkCol' } ,
            { data: 'nameCol' },
            { data: 'genderCol'},
            { data: 'statusCol' },
            { data: 'subscribedCol' } ,
            { data: 'activeCol'},
            { data: 'actionsCol' }
        ],
        */
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    return data ;
                },
                "targets": 1,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).addClass('overflow-name-td');
                    console.log('rowData');
                    console.log(rowData);
                    var fullName ='';
                    if(rowData[105]!=null){
                        fullName = rowData[104]+' '+rowData[105]+' '+rowData[106];
                    }
                    else{
                        fullName = rowData[104]+' '+rowData[106];
                    }
                    $(td).attr('data-original-title',fullName);
                    $(td).attr('data-toggle','tooltip');
                },

            },
            {
                "targets": 6,
                'createdCell':  function (td, cellData, rowData, row, col) {
                    $(td).addClass('special-td');
                }
            }
        ],

        "language": { "search":"" },
        "dom": 'T<"clear"><"#status_sort">lfrtip',
        "order": [[ 5, "desc" ]],
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        },
        'drawCallback': function(settings){
            if($('.table-slide').hasClass('col-lg-4')){
                $('.overflow-name-td').addClass('overflow-name-td-ellipsis');
            }
            //iCheck for checkbox
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });
            $('[data-toggle="tooltip"]').tooltip({
                'container': 'body'
            });
        }

    });

    $('#status_sort').html('<label style="position: absolute;right: 384px;top: 10px;"><select id="status_sort" aria-controls="DataTables_Table_0" class="form-control input-sm" style="border-color: #F9FAFC;border-radius: 5px;font-weight: initial;"><option value="false">Filter by status</option><option value="all">All</option><option value="8">Active</option><option value="9">Inactive</option><option value="2">Paused</option><option value="0">Unsubscribed</option></select></label>');
    $("input.input-sm").attr("placeholder","Search...");
    $('.dataTables_filter label').append('<i class="search-mag fa icon-magnifier" aria-hidden="true"></i>');

    $(document).on('change', '#status_sort', function (e){
        if(this.value=='false'){
            console.log('false--');
            return false;
        }
        if(this.value!=currentStatusSort){
            currentStatusSort = this.value;
            var ajaxUrl = table.ajax.url();
            var newAjaxUrl = '';
            console.log('value> '+this.value);

            if(this.value=='all'){
                newAjaxUrl = ajaxUrl.replace(/status=([0-9]){0}\w/g,'');
                table.ajax.url(newAjaxUrl).load();
                console.log('remove status new url> '+newAjaxUrl);
                return false;
            }

            else if(ajaxUrl.includes('status=')){
                newAjaxUrl = ajaxUrl.replace(/status=([0-9]){0}\w/g,'status='+String(this.value));
                table.ajax.url(newAjaxUrl).load();
                console.log('replaced new url> '+newAjaxUrl);
                return false;
            }
            else if(ajaxUrl.includes('?')){
                newAjaxUrl = ajaxUrl+'&status='+String(this.value);
                table.ajax.url(newAjaxUrl).load();
                console.log('added new url> '+newAjaxUrl);
                return false;
            }
            else{
                newAjaxUrl = ajaxUrl+'?status='+String(this.value);
                table.ajax.url(newAjaxUrl).load();
                console.log('new url> '+newAjaxUrl);
                return false;
            }
        }
    });
    $(document).on('click', '.live_chat', function (e){
        e.preventDefault();
        var profile_id = $(this).data("profile_id");
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'open_conversation',
            'profile_id': profile_id
        };
        jQuery.post(ajax_url, data, function() {
            var protocol = window.location.protocol;
            var host = window.location.hostname;
            var page = '/livechat.php';
            window.location.replace(protocol+'//'+host+page);
        });
        //jQuery('#form_profile_id').val(profile_id);
        //jQuery('#form2').submit();
        //should redirect to the page with the hidden details
    });

    $('.form-control.input-sm').on('change',function(){
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green'
        });
    });


    $(document).on('click', '.edit_profile', function (){

        jQuery('#email_value').val("");
        jQuery('#edit_email').html("");

        var ajax_url='includes/admin-ajax.php';
        var profile_id = $(this).data("profile_id");
        jQuery('#form_profile_id').val(profile_id);
        var data = {'action': 'get_chat_profile',
            'profile_id': profile_id,
        };
        jQuery.post(ajax_url, data, function(response) {
            jQuery("#edit_profile").html(response);
        });

        var data2 = {'action': 'get_chat_status',
            'profile_id': profile_id,
        };
        jQuery.post(ajax_url, data2, function(response2) {
            jQuery("#edit_pause_status").html(response2);
        });

        var data3 = {'action': 'get_tags',
            'profile_id': profile_id,

        };
        jQuery.post(ajax_url, data3, function(response3) {
            jQuery("#edit_tags").html(response3);
        });


        var data4 = {'action':'get_email',
            'profile_id': profile_id,
        };
        jQuery.post(ajax_url, data4, function(response4) {
            jQuery('#email_value').val(response4);
        });


        jQuery('#modal_profile_edit').modal();
    });




    $(document).on('click', '.delete_profile', function (){
        var profile_id = $(this).data("profile_id");
        var profile_name = $(this).data("profile_name");
        jQuery('#profile_name').html(profile_name);
        $('.delete_profile_yes').attr('id', 'delete_'+profile_id);
        //jQuery('#modal_profile_delete').modal();
        modalConfirm("Are you sure you want to delete the selected user",
            function(){
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'delete_profile',
                    'profile_id': profile_id
                };
                jQuery.post(ajax_url, data, function() {

                });
                var oTable = $('.table').dataTable();
                var nrow = $('tr#profile_'+profile_id);
                oTable.fnDeleteRow(nrow, null, true);

                var slide = $('.table-slide');
                var table = $( ".table-col" );
                $("tbody tr").removeClass('selected');
                slide.attr('data-inx','');
                slide.css('display','none');
                jQuery('#form_profile_id').val('');
                slide.removeClass( "col-lg-4" );
                table.removeClass( "col-lg-8" );
                $('.overflow-name-td').removeClass('overflow-name-td-ellipsis');
                table.addClass( "col-lg-12" );
                used = 0;
            },
            function(){
                //user clicked cancel
            });
    });


    $(document).on('click', '.action_download_profile_data', function (){
        var profile_id = $(this).data("profile_id");
        modalConfirm("You are going to export all user data of your subscriber. This information can be confidential and it is strictly forbidden to share any part of this export with any third party without a consent of your subscriber.",
            function(){
                var ajax_url='includes/admin-ajax.php';
                var data={
                    'action':'download_profile_data',
                    'profile_id': profile_id
                };
                jQuery.post(ajax_url, data, function(response) {
                    window.location.href = "data_export.php?f="+response;
                });
            },
            function(){
                //user clicked cancel
            });

    });

    $(document).on('click', '.delete_profile_yes', function (){
        var profile_id = jQuery(this).attr('id').replace('delete_', '');
        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_profile',
            'profile_id': profile_id,
        };
        jQuery.post(ajax_url, data, function() {

        });
        var oTable = $('.table').dataTable();
        var nrow = $('tr#profile_'+profile_id);
        oTable.fnDeleteRow(nrow, null, true);
        jQuery('#modal_profile_delete').modal('toggle');
    });

    $(document).on('click', '#addtags', function (){
        var selectedusers = getSelectedUsers();

        if (selectedusers.length>0) {
            jQuery('#modal_profile_bulk_tag').modal();
        }
        else{
            toastr.error('Please select atleast one user.','Error!');
            }
    });

    $(document).on('click', '#add_bulk_tag', function () {
        var TagValue = jQuery('#bulk_tag_value').val();
        var selectedusers = getSelectedUsers();
        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'add_bulk_tag',
            'profile_ids': JSON.stringify(selectedusers),
            'tag': TagValue
        };
        jQuery.post(ajax_url, data, function (response) {
            //jQuery("#edit_bulk_tags").html(response);
            jQuery('#modal_profile_bulk_tag').modal('toggle');
            jQuery("#bulk_tag_value").val('');
            var checkUser = $.inArray(currentProfileId, selectedusers);
            if(checkUser !== -1){
                getProfileDetailsBlock(currentProfileId);
            }
            toastr.success('Tag saved successfully.','Success!');
        });

    });

    $(document).on('click', '#addcustomfield', function (){
        var selectedusers = getSelectedUsers();

        if (selectedusers.length>0) {
            jQuery('#modal_profile_bulk_customfield').modal();
        }
        else{
            toastr.error('Please select atleast one user.','Error!');
        }
    });

    $(document).on('click', '#add_bulk_customfield', function () {
        var customfieldValue = jQuery('#bulk_customfield_value').val();
        var customfieldKey = jQuery('#bulk_customfield_key').val();
        var customKeyName = $( "#bulk_customfield_key option:selected" ).text();
        var selectedusers = getSelectedUsers();
        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'add_bulk_customfield',
            'profile_ids': JSON.stringify(selectedusers),
            'customfield_key': customfieldKey,
            'customfield_key_name':customKeyName,
            'customfield_value': customfieldValue
        };
        jQuery.post(ajax_url, data, function (response) {
            //jQuery("#edit_bulk_customfield").html(response);
            jQuery("#bulk_customfield_value").val('');
            if($.inArray(currentProfileId, selectedusers)!== -1){
                getProfileDetailsBlock(currentProfileId);
            }
            jQuery('#modal_profile_bulk_customfield').modal('toggle');
            toastr.success('Custom field value saved successfully.','Success!');
        });

    });


    $(document).on('click', '#delete_bulk_users', function (e){
        var selectedusers = getSelectedUsers();

        if (selectedusers.length>0) {
            e.preventDefault();
            jQuery('#profile_name').html();
            //jQuery('#modal_profile_bulk_delete').modal();
            modalConfirm("Are you sure you want to delete the selected user(s)",
                function(){
                    var selectedusers = getSelectedUsers();
                    var ajax_url='includes/admin-ajax.php';
                    var data={
                        'action':'delete_bulk_profile',
                        'profile_ids': JSON.stringify(selectedusers)
                    };
                    jQuery.post(ajax_url, data, function(response) {
                    });
                    for (var i=0;i<selectedusers.length;i++) {
                        var oTable = $('.table').dataTable();
                        var nrow = $('tr#profile_' + selectedusers[i]);
                        oTable.fnDeleteRow(nrow, null, true);
                    }
                    var slide = $('.table-slide');
                    var table = $( ".table-col" );
                    $("tbody tr").removeClass('selected');
                    slide.attr('data-inx','');
                    slide.css('display','none');
                    jQuery('#form_profile_id').val('');
                    slide.removeClass( "col-lg-4" );
                    table.removeClass( "col-lg-8" );
                    $('.overflow-name-td').removeClass('overflow-name-td-ellipsis');
                    table.addClass( "col-lg-12" );
                    used = 0;
                },
                function(){
                    //user clicked cancel
                });
        }
        else{
            modalAlert('Before you can delete, please make sure you selected at least one user');
        }
    });

    $(document).on('click', '.delete_bulk_users_yes', function (){
        var selectedusers = getSelectedUsers();

        var ajax_url='includes/admin-ajax.php';
        var data={
            'action':'delete_bulk_profile',
            'profile_ids': JSON.stringify(selectedusers)
        };
        jQuery.post(ajax_url, data, function(response) {
        });
        for (var i=0;i<selectedusers.length;i++) {
            var oTable = $('.table').dataTable();
            var nrow = $('tr#profile_' + selectedusers[i]);
            oTable.fnDeleteRow(nrow, null, true);
        }

        jQuery('#modal_profile_bulk_delete').modal('toggle');
    });

});

$('[data-toggle="tooltip"]').tooltip({
    'container': 'body'
});

$(document).on('mouseenter', 'td', function () {
    if ($(this).attr('data-toggle')=== 'tooltip')
    {

        $(this).tooltip({
            container: 'body',
            placement: 'right',
            trigger: 'hover'
        }).tooltip('show');
    }
});

function getSelectedUsers(){
    var searchIDs = new Array();
    var rows = $('.table').dataTable().fnGetNodes();
    $(rows).each(function () {
        if ( $(this).find(".action_single_check").prop( "checked" ) ) {
            var wid = $(this).find(".action_single_check").val();
            searchIDs.push(wid);
        }
    });
    return searchIDs;

}