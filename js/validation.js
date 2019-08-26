function MissingTitle(ItemId,ItemPart){
    jQuery("#"+ItemId).addClass("missing_title");
    jQuery("#"+ItemId).css({"background-color": "#f2dede", "color": "#a94442", "border-color": "#a94442"});
    jQuery('#'+ItemId).attr('placeholder', 'Please enter the '+ItemPart+' text first');
}

$(document).on('keyup', '.missing_title',function(){
    $(this).removeAttr("style");
    $(this).removeClass('missing_title');
    var ItemType = $(this).data('item-type');
    if(ItemType==="buttons"){$(this).attr('placeholder', 'Button text');}//for buttons
    if(ItemType==="title"){$(this).attr('placeholder', 'Title text');} //for titles
});