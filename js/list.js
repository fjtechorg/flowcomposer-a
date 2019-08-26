$(document).on('click', '.AddListItem', function () {
    var ThisItemId = $(this).data('itemid');
    var NumItems = jQuery('#'+ThisItemId+'_num_items').val();
    if (NumItems===''){NumItems=Number("1");}else{NumItems=Number(NumItems);}
    if(NumItems<4){
        var NewItemsNum = AddNewItemsNum(ThisItemId,NumItems);
        if(NewItemsNum===4){
            jQuery('#'+ThisItemId+'_AddListItem').hide();
        }
        AddButtonMsgItem(ThisItemId,'list','','','','','','','','','');
    }
});