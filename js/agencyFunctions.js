//global vars
var agencyDetails,agencyProducts,monthlyHtml,yearlyHtml,ionrange1,ionrange2,table;
FilePond.registerPlugin(
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginFileValidateType
);
const inputElement = document.querySelector("input.filepond");
const inputElementSquare = document.querySelector("input.filepond_square");
const inputElementFavi = document.querySelector("input.filepond_favi");
const inputElementEmail = document.querySelector("input.filepond_email");
const pond = FilePond.create(
    inputElement,
    {
        labelIdle: 'Drag & Drop your logo or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 400
    }
);
const pondSquare = FilePond.create(
    inputElementSquare,
    {
        labelIdle: 'Drag & Drop your square logo or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 400
    }
);
const pondFavi = FilePond.create(
    inputElementFavi,
    {
        labelIdle: 'Drag & Drop your favicon or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 400
    }
);
const pondEmail = FilePond.create(
    inputElementEmail,
    {
        labelIdle: 'Drag & Drop your email header image or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 400
    }
);
pond.on('addfile', (error, file) => {
    if (error) {
        toastr.error('Failed to upload. '+exception.detail,'Error!');
        return;
    }
    else{
        saveAgencyImage(pond,'.filepond_preview','nav_logo_top');
}
});
pondSquare.on('addfile', (error, file) => {
    if (error) {
        toastr.error('Failed to upload. '+exception.detail,'Error!');
        return;
    }
    else{
        saveAgencyImage(pondSquare,'.filepond_square_preview','nav_logo_left');
}
});
pondFavi.on('addfile', (error, file) => {
    if (error) {
        toastr.error('Failed to upload. '+exception.detail,'Error!');
        return;
    }
    else{
        saveAgencyImage(pondFavi,'.filepond_favi_preview','favicon');
}
});
pondEmail.on('addfile', (error, file) => {
    if (error) {
        toastr.error('Failed to upload. '+exception.detail,'Error!');
        return;
    }
    else{
        saveAgencyImage(pondEmail,'.filepond_email_preview','email_header_img');
}
});


$(document).ready(function () {

    getAgencyDetails();
    getAgencyProducts();
    getPurchaseInstance();
    $('#ionrange_1').ionRangeSlider({
        type: 'single',
        values: [],
        postfix: ' Subscribers',
        prettify_enabled: true,
        grid: true,
        onChange: function (data) {
            var slider = $("#ionrange_1").data("ionRangeSlider");
            ionrange1 = slider.result.from_value;
        }
    });
    $('#ionrange_2').ionRangeSlider({
        type: 'single',
        values: [],
        postfix: ' Subscribers',
        prettify_enabled: true,
        grid: true,
        onChange: function (data) {
            var slider = $("#ionrange_2").data("ionRangeSlider");
            ionrange2 = slider.result.from_value;
        }
    });

    <!-- iCheck -->
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        checkedClass: 'checked'
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
    table = $('.table').DataTable({

        "language": { "search":"" },
        "dom": 'T<"clear">lfrtip',
        "order": [],
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        },
        "serverSide": true,
        "ajax":"includes/datatablesSSP/agencyTable.php",
        'drawCallback': function(settings){
            //iCheck for checkbox
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green'
            });
        }

    });

    $("input.input-sm").attr("placeholder","Search...");
    $('.dataTables_filter label').append('<i class="search-mag fa icon-magnifier" aria-hidden="true"></i>');

});

$(document).on("click","#edit_agency_details",function(){
    $('#modal_agency_details').modal('toggle');
    getAgencyDetails();
});

$('#purchase_license_modal').on('click',function(){
    $('#modal_agency_purchase_licenses').modal('toggle');
});

$("input[name='sublicensecount']").TouchSpin();
$("#sublicensecount").on('change', function () {
    var lcount = $("#sublicensecount").val();
    var slab = calculateLicenseSlab(lcount);
    var price = slab.price;
    $("#sublicenseprice").html("$"+price);
    $("#purchaseLicenses").removeAttr('data-pk-is-inited data-pk-hash onclick');
    $("#purchaseLicenses").attr('href',slab.hash);
    var hash = slab.hash.replace('#pkmodal','');
    var fpdVal = slab.fpd;
    $("#purchaseLicenses").attr('data-qty',lcount);
    $("#purchaseLicenses").attr('data-agency-id',agencyDetails.id);
    initializePKSWidget(hash,fpdVal);
});

$("#purchaseLicenses").on("click",function(){
    $('#modal_agency_purchase_licenses').modal('toggle');
});

$("#add_client").on("click",function(){
    if(parseInt(agencyDetails.available_licenses)>0) {
        $('#addClientForm').find('input:text').val('');
        var range1 = $("#ionrange_1").data("ionRangeSlider");
        ionrange1 = agencyDetails.subscribersRange[0];
        range1.update({
            values: agencyDetails.subscribersRange,
            from:0
        });
        $('#modal_agency_add_client').modal('toggle');
    }
    else{
        toastr.error('You do not have any more licenses, please purchase licenses to add more clients.','Error!');
    }
});

$(document).on("click",".client_add_subscribers",function(){
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-user');
    if(parseInt(agencyDetails.available_subscribers)>999){
        var range2 = $("#ionrange_2").data("ionRangeSlider");
        ionrange2 = agencyDetails.subscribersRange[0];
        range2.update({
            values: agencyDetails.subscribersRange,
            from:0
        });
        $("#add-subs-client-name").html(name);
        $(".add-subs-confirm").attr('data-id',id);
        $('#modal_add_client_subscribers').modal('toggle');
    }
    else{
        toastr.error('You do not have enough subscriber credits, please purchase subscribers add more subscribers to your clients.','Error!');
    }
});

$(".add-subs-confirm").on("click",function(){
    var id = $(this).attr('data-id');
    upgradeClientSubs(id,ionrange2);
});

$("#sublicensecount").keydown(function (e) {
    if (e.keyCode == 13) {
        $('#modal_agency_purchase_licenses').modal('toggle');
        var inputs = $(this).parents("form").eq(0).find(":input");
        if (inputs[inputs.index(this) + 1] != null) {
            inputs[inputs.index(this) + 1].focus();
        }
        e.preventDefault();
        if($("#sublicensecount").val() > 0){
            $("#paymentmodaltitle").html("Upgrade Client Limits");
            $("#upgradetype").val("subusers");
        }

        return false;
    }
});

$(document).on("click",".enabledisablebutton",function(){
    var id = $(this).attr('data-client-id');
    var type = $(this).attr('data-action-type');
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'agency_client_change_status',
        'id' : id,
        'type': type
    };
    $.post(ajax_url, data, function (res) {
        if(res=='1') {
            toastr.success('changed client status to '+type+'d.', 'Success!');
            table.draw(false);
        }
        else{
            toastr.error(res,'Error!');
        }
    });
});

$("#delete_bulk_users").on("click",function(){
    var clientIds = getSelectedUsers();
    modalConfirm("Are you sure you want to delete the selected client(s)?",
        function(){
            deleteAgencyClients(clientIds);
        },
        function(){
            //user clicked cancel
        });
});

$("#saveAgencyDetails").on("click",function(){
    saveAgencyInfo();
});

$(".upgrade").click(function() {
    initializeUpgradeList();

    $('#modal_agency_upgrade_subscribers').modal('show');
});

$("#upgrade-qty").on('change',function(){
    var hash = $("#upgrade-qty :selected").val();
    var codeHash = hash.replace('#pkmodal','');
    var fpd = $("#upgrade-qty :selected").attr('data-fpd');
    $('#licensebutton').attr('href',hash);
    $("#licensebutton").attr('data-agency-id',agencyDetails.id);
    initializePKSWidget(codeHash,fpd);
});

$('#create_agency_user').on('click',function(){
    var firstName = $('#sub_user_first_name').val();
    var lastName = $('#sub_user_last_name').val();
    var email = $('#sub_user_email').val();
    var password = $('#sub_user_pass').val();
    var passwordConfirm = $('#sub_user_pass_confirm').val();
    var subs = ionrange1;
    if(password !== passwordConfirm){
        toastr.error('Passwords in both fields do not match.','Error!');
        return false;
    }
    else if(firstName ==='' || lastName ==='' || email ==='' || password ==='' || subs ===''){
        toastr.error('Please fill all fields.','Error!');
        return false;
    }
    createAgencyClient(firstName,lastName,email,password,subs);
});


$("#subscriberperiod_toggle").on('change',function(){
    initializeUpgradeList();
});

$(document).on('click','.delete-client',function(){
    var id = $(this).attr('data-id');
    var clientIds = [id];
    modalConfirm("Are you sure you want to delete the selected client?",
        function(){
            deleteAgencyClients(clientIds);
        },
        function(){
            //user clicked cancel
        });
});


function initializeUpgradeList(){
    if($("#subscriberperiod_toggle").is(':checked') === false) {
        $("#upgrade-qty").html(monthlyHtml);
    }
    else{
        $("#upgrade-qty").html(yearlyHtml);
    }
    var hash = $("#upgrade-qty :selected").val();
    var codeHash = hash.replace('#pkmodal','');
    var fpd = $("#upgrade-qty :selected").attr('data-fpd');
    $('#licensebutton').attr('href',hash);
    $("#licensebutton").attr('data-agency-id',agencyDetails.id);
    initializePKSWidget(codeHash,fpd);
}

function  getAgencyDetails(){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_agency_details'
    };
    jQuery.post(ajax_url, data, function(res) {
        agencyDetails = JSON.parse(res);
        agencyDetails.subscribersRange = generateIntervalsArray(parseInt(agencyDetails.available_subscribers),1000);
        $('#agency_name').val(agencyDetails.name);
        $('#agency_address').val(agencyDetails.address);
        $('.visual-agency-address').html(agencyDetails.address);
        $('.visual-agency-name').html(agencyDetails.name);

        $('.filepond_preview').attr('src',agencyDetails.logo);

        var elementExists = document.getElementsByClassName("filepond_square");
        if(elementExists.length>0){
            $('.filepond_square_preview').attr('src',agencyDetails.brand.nav_logo_left);
            $('.filepond_favi_preview').attr('src',agencyDetails.brand.favicon);
            $('.filepond_email_preview').attr('src',agencyDetails.email_header_img);

            $('#agency_domain').val(agencyDetails.domain);
            $('#agency_website').val(agencyDetails.brand.website);
            $('#agency_email').val(agencyDetails.email);
            $('#agency_link_support').val(agencyDetails.brand.link_support);

            $('#agency_smtp_host').val(agencyDetails.smtp.host);
            $('#agency_smtp_port').val(agencyDetails.smtp.port);
            $('#agency_smtp_username').val(agencyDetails.smtp.username);
            $('#agency_smtp_password').val(agencyDetails.smtp.password);
            $('#agency_enc_type').val(agencyDetails.smtp.encryption_type);
        }
        setLincensesUsageBar();
        setSubscribersUsageBar();
    });
}

function saveAgencyImage(pondObj,previewSelector,agencyImgRole){
    if(!pondObj.getFile()){
        toastr.error('please select an image'+previewSelector,'Error!');
        return false;
    }
    var blob = pondObj.getFile().file;
    var reader = new FileReader();
    reader.readAsDataURL(blob);
    reader.onloadend = function() {
        var base64data = reader.result;
        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'set_agency_brand_img',
            'imageBase64' : base64data,
            'agencyImgRole': agencyImgRole
        };
        $.post(ajax_url, data, function (response) {
            response = JSON.parse(response);
            if(response.status=='success') {
                pondObj.removeFile();
                toastr.success("Image saved.", "Success!");
                $(previewSelector).attr('src',response.imgUrl);
                if(agencyImgRole==='nav_logo_top'){
                    $('.visual-agency-logo').attr('src',response.imgUrl);
                }
            }
            else if(response.status=='error'){
                toastr.error(response.error, "Error!");
            }
            else{
                toastr.error('Could not upload, please contact support', "Error!");
            }
        });
    };
}

function saveAgencyInfo(){
    var name = $('#agency_name').val();
    if(name==''){
        toastr.error('please enter a name','Error!');
        return false;
    }
    var address = $('#agency_address').val();
    if(address==''){
        toastr.error('please enter an address','Error!');
        return false;
    }
    var elementExists = document.getElementsByClassName("filepond_square");

    if(elementExists.length>0){
        var website = $('#agency_website').val();
        var email = $('#agency_email').val();
        var support = $('#agency_link_support').val();

        //smtp
        var host = $('#agency_smtp_host').val();
        var port = $('#agency_smtp_port').val();
        var user = $('#agency_smtp_username').val();
        var pass = $('#agency_smtp_password').val();
        var encType = $('#agency_enc_type').val();

        if(website==''){
            toastr.error('please enter website','Error!');
            return false;
        }
        else if(email==''){
            toastr.error('please enter email','Error!');
            return false;
        }
        else if(support==''){
            toastr.error('please enter support link','Error!');
            return false;
        }
        else if(host==''){
            toastr.error('please enter SMTP host','Error!');
            return false;
        }
        else if(port==''){
            toastr.error('please enter SMTP port','Error!');
            return false;
        }
        else if(user==''){
            toastr.error('please enter SMTP Username','Error!');
            return false;
        }
        else if(pass==''){
            toastr.error('please enter SMTP Password','Error!');
            return false;
        }

        var data = {
            'action': 'update_agency_info',
            'name': name,
            'address': address,
            'website': website,
            'email': email,
            'support': support,
            'host': host,
            'port': port,
            'user': user,
            'pass': pass,
            'enc': encType
        };
    }
    else{
        var data = {
            'action': 'update_agency_info',
            'name': name,
            'address': address
        };

    }
    var ajax_url = 'includes/admin-ajax.php';
    $.post(ajax_url, data, function (response) {
        if(response=='success') {
            toastr.success("Agency details is saved.", "Success!");
            $('#modal_agency_details').modal('toggle');
            getAgencyDetails();
        }
        else{
            toastr.error(response, "Error!");
        }
    });

}

function setLincensesUsageBar(){
    var usedLics = parseInt(agencyDetails.used_licenses);
    var totalLics = parseInt(agencyDetails.total_licenses);
    var barWidth = (usedLics/totalLics)*100;
    barWidth = barWidth+'%';
    $('#licensesUsageBar').css('width',barWidth);
    $('#licensesavailable').html(totalLics.toLocaleString());
    $('#licensesused').html(usedLics.toLocaleString());
}

function setSubscribersUsageBar(){
    var usedSubs = parseInt(agencyDetails.used_subscribers);
    var totalSubs = parseInt(agencyDetails.total_subscribers);
    var barWidth = (usedSubs/totalSubs)*100;
    barWidth = barWidth+'%';
    $('#subscribersUsageBar').css('width',barWidth);
    $('#subscribersavailable').html(totalSubs.toLocaleString());
    $('#subscribersused').html(usedSubs.toLocaleString());
}

function getAgencyProducts(){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'get_agency_products'
    };
    jQuery.post(ajax_url, data, function(res) {
        agencyProducts = JSON.parse(res);
        agencyProducts.licenseQtyArray = [];
        agencyProducts.licenseDiscountQuantities = [];
        var qty;
        agencyProducts.license.forEach(function(license) {
            qty = parseInt(license.qty);
            agencyProducts.licenseQtyArray[qty] = license;
            agencyProducts.licenseDiscountQuantities.push(qty);
        });
        agencyProducts.licenseDiscountQuantities.sort(function(a, b){return b-a});
        monthlyHtml='';
        agencyProducts.upgradeMonthly.forEach(function(license) {
            monthlyHtml=monthlyHtml+'<option value="'+license.hash+'" data-fpd="'+license.fpd+'">'+parseInt(license.qty).toLocaleString()+' Subscribers $'+license.price+'/month</option>';
        });
        yearlyHtml='';
        agencyProducts.upgradeYearly.forEach(function(license) {
            yearlyHtml=yearlyHtml+'<option value="'+license.hash+'" data-fpd="'+license.fpd+'">'+parseInt(license.qty).toLocaleString()+' Subscribers $'+license.price+'/year</option>';
        });
    });
}

function createAgencyClient(firstName, lastName, email, password, subs){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'add_agency_client',
        'firstName':firstName,
        'lastName':lastName,
        'email':email,
        'password':password,
        'subs':subs
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res=="1"){
            toastr.success('Client created successfully.','Success!');
            table.draw(false);
            getAgencyDetails();

            $('#modal_agency_add_client').modal('toggle');
            $('#addClientForm input').val('');
        }
        else{
            toastr.error(res,'Error!');
        }
    });
}

function upgradeClientSubs(id,qty){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'agency_client_subs_upgrade',
        'clientId':id,
        'qty':qty
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res=="1"){
            toastr.success('Upgraded Client subscribers successfully.','Success!');
            table.draw(false);
            getAgencyDetails();
            $("#add-subs-client-name").html('');
            $(".add-subs-confirm").attr('data-id','');
            $('#modal_add_client_subscribers').modal('toggle');
            $('#addClientForm input').val('');
        }
        else{
            toastr.error(res,'Error!');
        }
    });
}

$(document).on('click','#purchaseLicenses',function(){
    var prod = "License(s)";
    var qty = $(this).data('qty');
    recordPurchaseInstance(prod,qty);
});

$(document).on('click','#licensebutton',function(){
    var prod = "Subscriber(s)";
    var qty = $('#upgrade-qty :selected').html();
    recordPurchaseInstance(prod,qty);
});

$(document).on('click','.client_takeover',function(){
    var id = $(this).attr('data-id');
    clientTakeover(id);
});

function deleteAgencyClients(clientIds){

    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'delete_agency_clients',
        'clientIds': JSON.stringify(clientIds)
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res.length==1){
            toastr.success('Client(s) deleted successfully.','Success!');
            table.draw(false);
            getAgencyDetails();
        }
        else{
            toastr.error(res,'Error!');
        }
    });
}

function calculateLicenseSlab(num){
    var qty = agencyProducts.licenseDiscountQuantities;
    var count = qty.length;
    var itrCnt = count-1;
    var res = {};
    res.price = 0;
    res.hash = '';
    for(var i=0;i<=itrCnt;i++){
        if(num>=qty[i]){
            res.price = agencyProducts.licenseQtyArray[qty[i]].price;
            res.hash = agencyProducts.licenseQtyArray[qty[i]].hash;
            res.prodId = agencyProducts.licenseQtyArray[qty[i]].product_id;
            res.fpd = agencyProducts.licenseQtyArray[qty[i]].fpd;
            break;
        }
    }
    return res;
}

function recordPurchaseInstance(prod,qty){
    var ajax_url='includes/admin-ajax.php';
    var data={
        'action':'record_agency_checkout',
        'prod':prod,
        'qty':qty
    };
    jQuery.post(ajax_url, data, function() {

    });
}

function getPurchaseInstance(){
    var params = getSearchParameters();
    if(params.event==='purchase-successful') {
        var ajax_url = 'includes/admin-ajax.php';
        var data = {
            'action': 'get_agency_checkout'
        };
        jQuery.post(ajax_url, data, function (res) {
            if (res !== '') {
                res = JSON.parse(res);
                getAgencyDetails();
                modalAlert('Purchase Successful - '+res.name+' - '+res.qty);
            }
        });
    }
}

function clientTakeover(id){
    var ajax_url = 'includes/admin-ajax.php';
    var data = {
        'action': 'agency_client_takeover',
        'id':id
    };
    jQuery.post(ajax_url, data, function (res) {
        if (res == 'success') {
            window.location.replace("index.php");
        }
        else{
            toastr.error(res,'Error!');
        }
    });
}

function getSelectedUsers(){
    var searchIDs = new Array();
    var rows = $(".table").dataTable().fnGetNodes();
    $(rows).each(function () {
        if ( $(this).find(".action_single_check").prop( "checked" ) ) {
            var wid = $(this).find(".action_single_check").val();
            searchIDs.push(wid);
        }
    });
    return searchIDs;
}

function initializePKSWidget(hash,fpdVal) {
    window.PKWidgetsData = window.PKWidgetsData || {}, window.PKWidgetsData.hasOwnProperty(hash) || (window.PKWidgetsData[hash] = {host: 'https://app.paykickstart.com', fpd: fpdVal }),window.PKWIDGET.init()
}

function generateIntervalsArray(limit,interval){
    var count = Math.trunc(limit/interval);
    var res = [];
    for(var i=1;i<=count;i++){
        res.push(i*interval);
    }
    if(res.length===0){
        res=[0,0];
    }
    else if(res.length===1){
        res=[0,res[0]];
    }
    return res;
}
