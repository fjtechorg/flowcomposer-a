var table = $('.dataTables').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "ajax":"../includes/datatablesSSP/adminCategoriesTable.php",
    'createdRow': function( row, data, dataIndex ) {
        $(row).attr('id', 'profile_'+data[0]);
    },
    "language": { "search":"" },
    "dom": 'T<"clear">lfrtip'
});
$("input.input-sm").attr("placeholder","Search...");

var create = '<div id="status_sort" class="dataTables_length"><label class="btn btn-primary create_category" style="margin-left: 10px;padding:5px;">Create</label><label class="btn btn-primary regenerate_css" style="margin-left: 10px;padding:5px;">Regenerate CSS</label></div>';
$(".dataTables_length").after(create);

$('[data-toggle="tooltip"]').tooltip({
    'container': 'body'
});

$('.regenerate_css').on('click',function(){
    regenerateCss();
});

$('.create_category').on('click',function(){
    $('.category_confirm').attr('data-type','create');
    $("#catForm").find("input[type=text], textarea").val("");
    $('#modal_category_create').modal();
});

$(document).on('click','.edit_category',function(){
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    var icon = $(this).attr('data-icon');
    var color = $(this).attr('data-color');
    var background = $(this).attr('data-background');
    $('#cat_name').val(name);
    $('#cat_icon').val(icon);
    $('#cat_color').val(color);
    $('#cat_background').val(background);
    $('.category_confirm').attr('data-id',id);
    $('.category_confirm').attr('data-type','edit');
    $('#modal_category_create').modal();
});

$(document).on('click','.delete_category',function(){
    var id = $(this).attr('data-id');
    var res = confirm("Are you sure you want to delete this category?");
    if (res == true){
        deleteCategory(id);
    }
});

$('.category_confirm').on('click',function(){
    var type = $(this).attr('data-type');
    if(type === 'create'){
        createCategory();
    }
    else if(type === 'edit'){
       var id = $(this).attr('data-id');
        updateCategory(id);
    }
});

function createCategory(){
    var name = $('#cat_name').val();
    var icon = $('#cat_icon').val();
    var color = $('#cat_color').val();
    var background = $('#cat_background').val();
    if(name =='' || icon == '' || color == '' || background == ''){
        toastr.warning('Please enter all fields','Error!');
        return false;
    }
    var ajax_url='../includes/admin-ajax.php';
    var data={
        "action":"add_category",
        "name": name,
        "icon": icon,
        "color": color,
        "background": background
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            table.draw(false);
            toastr.success('Category created successfully','Success!');
        }
    });
}

function regenerateCss(){
    var ajax_url='../includes/admin-ajax.php';
    var data={
        "action":"regenerate_category_css"
    };
    jQuery.post(ajax_url, data, function(res) {
        toastr.success('template_categories.css regenerated.','Success!');
    });
}

function updateCategory(id){
    var name = $('#cat_name').val();
    var icon = $('#cat_icon').val();
    var color = $('#cat_color').val();
    var background = $('#cat_background').val();
    if(name =='' || icon == '' || color == '' || background == ''){
        toastr.warning('Please enter all fields','Error!');
        return false;
    }
    var ajax_url='../includes/admin-ajax.php';
    var data={
        "action":"edit_category",
        "id": id,
        "name": name,
        "icon": icon,
        "color": color,
        "background": background
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            table.draw(false);
            toastr.success('Category updated successfully','Success!');
        }
    });
}

function deleteCategory(id){
    var ajax_url='../includes/admin-ajax.php';
    var data={
        "action":"delete_category",
        "id": id
    };
    jQuery.post(ajax_url, data, function(res) {
        if(res==1){
            table.draw(false);
            toastr.success('Category delete successfully','Success!');
        }
    });
}