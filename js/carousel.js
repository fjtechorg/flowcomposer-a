$(document).on('click', '.carousel_next', function (e) {
    var MsgID  = jQuery(this).data('slide_id');
    var currentSlide = Number($('#'+MsgID+'_currentSlide').val());
    var NumSlides = $('#'+MsgID+'_num_items').val();
    if(currentSlide==NumSlides){
        currentSlide = 0; //if we reach the max number of cards and a new next is clicked we loop back to #1. 0 * -200 = 0 thus we have a margin left of 0
    }
    var NewSlide = currentSlide + 1;
    $('#'+MsgID+'_currentSlide').val(NewSlide);
    var MarginLeft = currentSlide * -200;
    $(".carouselslides").animate({'marginLeft': MarginLeft}, 500);

});

$(document).on('click', '.carousel_previous', function (e) {
    var MsgID  = jQuery(this).data('slide_id');
    var currentSlide = Number($('#'+MsgID+'_currentSlide').val());
    var NumSlides = $('#'+MsgID+'_num_items').val();
    var NewSlide = currentSlide - 1;
    if(NewSlide > 0){ //only do a previous if we are at #2 or higher
        $('#'+MsgID+'_currentSlide').val(NewSlide);
        var MarginLeft =( NewSlide - 1) * -200;
        $(".carouselslides").animate({    'marginLeft': MarginLeft}, 500);
    }
});

$(document).on('click', '.save_carousel_button', function (e) {

    var ItemId = jQuery('#current_item').val();
    jQuery('#'+ItemId+'_preview_buttons').show();
    for (i = 1; i < 4; i++) {
        var buttonType = jQuery('#'+ItemId+'_button_type_'+i).val();
        var buttonTitle = jQuery('#carousel_button_title'+i).val();
        jQuery('#'+ItemId+'_button_title_'+i).val(buttonTitle);
        if(buttonType!="share"){jQuery('#'+ItemId+'_preview_button'+i).html(buttonTitle);}
        jQuery('#carousel_button_title'+i).val('');
    }

});

function goToSlide(ThisSlideID,currentSlide,n) {

    var slides=[];

    $( '#slides_'+ThisSlideID+' li' ).each(function( index ) {

        var slideID=this.id;

        slides.push(slideID);

    });

    var SlidesLenght = slides.length;

    //need to set the class to just carouselslide

    var CurrentID = slides[currentSlide];

    jQuery('#slide_'+CurrentID).removeClass('carouselshowing');

    //lets see if it's previous that is clicked and if so if we are not at the first already. If so we go to the end of the array

    if(currentSlide===0){

        NewcurrentSlide = SlidesLenght - 1;

    }else{

        var NewcurrentSlide = (n+currentSlide);

    }



    //last line of deffence...we should not be higher then the lenght of the array

    if(NewcurrentSlide===SlidesLenght){

        NewcurrentSlide = 0;

    }



    var NewcurrentID = slides[NewcurrentSlide];

    //set the showing class name to the new current slide

    jQuery('#slide_'+NewcurrentID).addClass('carouselshowing');

    jQuery('#'+ThisSlideID+'_currentSlide').val(NewcurrentSlide);

    var slidesTxt = slides.toString();

}

function CarouselModalSetSelected(buttonType,Num){

    if(buttonType=="web_url"){$('#carousel_button_link_'+Num).addClass('sticky_menu_item_selected');}

    if(buttonType=="postback"){$('#carousel_button_msg_'+Num).addClass('sticky_menu_item_selected');}

    if(buttonType=="phone"){$('#carousel_button_phone_'+Num).addClass('sticky_menu_item_selected');}

    if(buttonType=="share"){$('#carousel_button_share_'+Num).addClass('sticky_menu_item_selected');}

}

function CarouselModalCleanSelected(){

    for (CurrentButton = 1; CurrentButton < 4; CurrentButton++){

        $('#carousel_button_title'+CurrentButton).removeClass('missing_title');
        $('#carousel_button_title'+CurrentButton).removeAttr("style");
        $('#carousel_button_title'+CurrentButton).attr('placeholder', 'Button text');

        $('#carousel_button_share_'+CurrentButton).removeClass('sticky_menu_item_selected');
        $('#carousel_button_phone_'+CurrentButton).removeClass('sticky_menu_item_selected');
        $('#carousel_button_msg_'+CurrentButton).removeClass('sticky_menu_item_selected');
        $('#carousel_button_link_'+CurrentButton).removeClass('sticky_menu_item_selected');

    }

}


$(document).on('click', '.button_carousel_msg', function (e) {

    var ItemId = $(this).data('item_id');
    jQuery('#current_item').val(ItemId);

    var ItemTitle = jQuery('#'+ItemId+'_carousel_title').val();
    if(ItemTitle!=""){
        var MsgItem = $(this).data('msg_id');

        jQuery('#current_msg').val(MsgItem)



        jQuery('#'+ItemId+'_preview_buttons').show();

        //clean the selected class from previous click

        CarouselModalCleanSelected();

        var buttonTitle1 = jQuery('#'+ItemId+'_button_title_1').val();

        var buttonTitle2 = jQuery('#'+ItemId+'_button_title_2').val();

        var buttonTitle3 = jQuery('#'+ItemId+'_button_title_3').val();

        jQuery('#carousel_button_title1').val(buttonTitle1);

        jQuery('#carousel_button_title2').val(buttonTitle2);

        jQuery('#carousel_button_title3').val(buttonTitle3);



        //build up the selected items again if there are any

        var buttonType1 = jQuery('#'+ItemId+'_button_type_1').val();if(buttonType1!=""){CarouselModalSetSelected(buttonType1,1);}

        var buttonType2 = jQuery('#'+ItemId+'_button_type_2').val();if(buttonType2!=""){CarouselModalSetSelected(buttonType2,2);}

        var buttonType3 = jQuery('#'+ItemId+'_button_type_3').val();if(buttonType3!=""){CarouselModalSetSelected(buttonType3,3);}

        jQuery('#modal_button_carousel_msg').modal();

    }else{

        var ItemId = ItemId+'_carousel_title';
        MissingTitle(ItemId,'Title');
    }
});

$(document).on('click', '.AddCarouselItem', function () {

    var ThisItemId = $(this).data('itemid');

    var NumItems = jQuery('#'+ThisItemId+'_num_items').val();

    if (NumItems===''){NumItems=Number("1");}
    else{NumItems=Number(NumItems);}

    if(NumItems<10){

        var randLetter = String.fromCharCode(65 + Math.floor(Math.random() * 26));
        AddButtonMsgItem(ThisItemId,'carousel',NumItems,'','','','','','','','','','');
        //is there any li item that is showing already?

        if(NumItems===0){

            //jQuery('#slide_'+uniqID).addClass('carouselshowing');

        }

        //let's hide the button items for a moment as that does not look well right now on an empty preview
        //jQuery('#'+uniqID+'_preview_buttons').hide();

        var NewNum = Number("1");

        NewItemsNum = NumItems+NewNum;

        jQuery('#'+ThisItemId+'_num_items').val(NewItemsNum);

        if(NewItemsNum==11){jQuery('#'+ThisItemId+' .AddCarouselItem').style("display:none;");}

    }

});


function ChangePreviewCarouselButtonTitle(ThisButton){

    var ItemID = jQuery('#current_item').val();

    var OrgText = jQuery('#carousel_button_title'+ThisButton).val();

    jQuery('#'+ItemID+'_preview_button_title'+ThisButton).html(OrgText);

    jQuery('#'+ItemID+'_button_title_'+ThisButton).val(OrgText);

}

$(document).on('click', '.carousel_img_item', function () {

    var ThisItemId = $(this).data('itemid');

});

function CarouselAddItem(itemID,uniqid,itemName){
//document.getElementById(itemName).id = uniqid+itemName;
//jQuery('#'+uniqid+itemName).attr('name', uniqid+itemName);
//jQuery('#'+itemName).attr('placeholder', '');
    jQuery('<input type="hidden" name="'+itemID+'_items['+uniqid+']['+itemName+']" id="'+itemID+'_'+uniqid+'_'+itemName+'" value=""/>').prependTo('#form');
}

function CarouselAddItemButtons(itemID,uniqid){
    for(var i = 1; i < 4; i++){
        CarouselAddItem(itemID,uniqid,'item_button'+i+'_title');
        CarouselAddItem(itemID,uniqid,'item_button'+i+'_type');
        CarouselAddItem(itemID,uniqid,'item_button'+i+'_url');
        CarouselAddItem(itemID,uniqid,'item_button'+i+'_phone');
        CarouselAddItem(itemID,uniqid,'item_button'+i+'_msg');
    }
}
