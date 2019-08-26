$('.main-table').dataTable({
    responsive: true,
    "dom": 'T<"clear">lfrtip',
    "bAutoWidth": false,
    "processing": true,
    "serverSide": true,
    "ajax":"../includes/datatablesSSP/adminUsersTable.php",
    'createdRow': function( row, data, dataIndex ) {
        $(row).attr('id', 'profile_'+data[0]);
    }
});

$(document).on('click', '.edit_profile', function (){
    var ajax_url='../includes/admin-ajax.php';
    var profile_id = $(this).data('profile_id');
    jQuery('#edit_user_id').val(profile_id);
    var data = {'action': 'get_user_profile',
        'profile_id': profile_id
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery("#edit_profile").html(response);
        jQuery('#modal_user_edit').modal();
    });
});

$(document).on('click', '.edit_details', function (){
    var ajax_url='../includes/admin-ajax.php';
    var id = $(this).data('id');
    var data = {'action': 'get_user_details_with_id',
        'id': id
    };
    jQuery.post(ajax_url, data, function(response) {
        console.log(response);
        var res = JSON.parse(response);
        $('#modal_edit_user_details .first_name').val(res.first_name);
        $('#modal_edit_user_details .last_name').val(res.last_name);
        $('#modal_edit_user_details .user_name').val(res.user_name);
        $('#modal_edit_user_details .email').val(res.email);
        $('.save_user_details').data('id',res.id);
        jQuery('#modal_edit_user_details').modal();
    });
});

$(document).on('click', '.save_user_details', function (){
    var ajax_url='../includes/admin-ajax.php';
    var id = $(this).data('id');
    var firstName = $('#modal_edit_user_details .first_name').val();
    var lastName = $('#modal_edit_user_details .last_name').val();
    var userName = $('#modal_edit_user_details .user_name').val();
    var email = $('#modal_edit_user_details .email').val();
    var data = {'action': 'save_user_details_with_id',
        'id': id,
        'first_name': firstName,
        'last_name': lastName,
        'user_name': userName,
        'email': email
    };
    jQuery.post(ajax_url, data, function(response) {
        if(response==1) {
            jQuery('#modal_edit_user_details').modal('toggle');
            toastr.success('User details changed successfully', 'Success!');
        }
    });
});

$(document).on('click', '.edit_profile', function (){
    var ajax_url='../includes/admin-ajax.php';
    var profile_id = $(this).data('profile_id');
    jQuery('#edit_user_id').val(profile_id);
    var data = {'action': 'get_user_profile',
        'profile_id': profile_id
    };
    jQuery.post(ajax_url, data, function(response) {
        jQuery("#edit_profile").html(response);
    });
    jQuery('#modal_user_edit').modal();
});

$(document).on('click', '.takeover', function (){
    var ajax_url='../includes/admin-ajax.php';
    var profile_username = $(this).data('profile_username');
    var data = {'action': 'session_takeover',
        'profile_id': profile_username
    };
    jQuery.post(ajax_url, data, function() {
        jQuery("#takeoversession").html("user takeover selected, redirecting...");
        $(location).attr("href", "../index.php");
    });
});

$(document).on('click', '.delete_profile', function (){
    var profile_id = $(this).data("profile_id");
    var profile_name = $(this).data("profile_name");
    jQuery('#profile_name').html(profile_name);
    $('.delete_profile_yes').attr('id', 'delete_'+profile_id);
    jQuery('#modal_user_delete').modal();
});

$(document).on('click', '.delete_profile_yes', function (){
    var profile_id = jQuery(this).attr('id').replace('delete_', '');
    var ajax_url='../includes/admin-ajax.php';
    var data={
        'action':'delete_user',
        'user_id': profile_id
    };
    jQuery.post(ajax_url, data, function(response) {
    });

    $('#profile_'+profile_id).remove();
    jQuery('#modal_user_delete').modal('toggle');
    toastr.success("User account is deleted", "Success!");
});

$(document).on('click', '#delete_login', function (){
    var login_id = $(this).data("login_id");
    var ajax_url='../includes/admin-ajax.php';
    var data={
        'action':'delete_login_session',
        'login_id': login_id
    };
    jQuery.post(ajax_url, data, function() {
        toastr.success("Login session deleted.", "Success!");
    });
    $('#last_login').remove();
});

$(document).on('click', '#change_password', function (){
    var user_id = jQuery('#edit_user_id').val();
    var password_value = jQuery('#password_value').val();
    if(password_value!==""){
        var ajax_url='../includes/admin-ajax.php';
        var data={
            'action':'change_password',
            'user_id': user_id,
            'password':password_value
        };
        jQuery.post(ajax_url, data, function() {
            toastr.success('Password changed successfully','Success!');
        });
    }
});
$(document).on('click', '#clear_account', function (){
    var user_id = jQuery('#edit_user_id').val();
    if(user_id!==""){
        var ajax_url='../includes/admin-ajax.php';
        var data={
            'action':'clean_account',
            'user_id': user_id};
        jQuery.post(ajax_url, data, function(response) {

        });
        jQuery('#modal_user_edit').modal('hide');
        toastr.success("User account cleaned.", "Success!");
    }
});

$(document).on('click', '.delete_membership', function (){
    var membership_id = $(this).data("membership_id");
    var user_id = jQuery('#edit_user_id').val();
    if(membership_id!==""){
        var ajax_url='../includes/admin-ajax.php';
        var data={
            'action':'delete_membership',
            'user_id': user_id,
            'membership_id':membership_id
        };
        jQuery.post(ajax_url, data, function(response) {
            toastr.success("User account membership deleted successfully.", "Success!");
        });
        $('#level_'+membership_id).remove();
    }
});


$(document).on('click', '#add_member_level', function (){
    var membershipId = jQuery('#member_level').val();
    var userId = jQuery('#edit_user_id').val();
    var invoiceId = jQuery('#invoice_id_value').val();
    if(membershipId!==""){
        var ajax_url='../includes/admin-ajax.php';
        var data={
            'action':'add_membership',
            'user_id': userId,
            'invoice_id': invoiceId,
            'membership_id':membershipId
        };
        jQuery.post(ajax_url, data, function(response) {
            //jQuery('#member_level_result').append(response);
            if(response==''){
                toastr.error("Could not add membership.", "Error!");
            }
            else {
                $('#member_level_result tbody').html(response);
                toastr.success("User account membership added.", "Success!");
            }
        });
    }
});

$(document).on('click', '.user_login_history', function (){
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    var ajax_url='../includes/admin-ajax.php';
    var data={
        'action':'user_login_history',
        'id': id
    };
    jQuery.post(ajax_url, data, function(res) {
        res = JSON.parse(res);
        console.log(res);
        var html = '<table class="login-history-table table table-striped table-bordered table-hover"><thead><th>IP</th><th>Time</th><th>Browser</th><th>OS</th></thead><tbody>';
        for(var i=0;i<res.length;i++){
            html = html+'<tr>'+'<td>'+res[i].ip+'</td>'+'<td>'+res[i].time+'</td>'+'<td>'+res[i].browser+'</td>'+'<td>'+res[i].os+'</td>'+'</tr>'
        }
        html = html+'</tbody></table>';
        $('#user-history-name').html(name);
        $('#login-history-container').html(html);
        $('.login-history-table').dataTable({
            responsive: true,
            "dom": 'T<"clear">lfrtip',
            "bAutoWidth": true,
            "processing": true
        });
        $('#modal_login_history').modal();
    });
});