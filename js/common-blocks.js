/* scroll function code for slide in div*/
resize_slidein_divobject();
$(window).resize(resize_slidein_divobject);

function resize_slidein_divobject(){
    var a = $('#side-menu').height(), b = $('.nav-header').height(), c = $('.footer').height();
    b= b+c;
    //a=a-20;
    a=a-b;
    $(".table-slide").css("height",a);
}

resize_slidein_divobject2();
$(window).resize(resize_slidein_divobject2);

function resize_slidein_divobject2(){
    var a = $('#side-menu').height(), b = $('.nav-header').height(), c = $('.footer').height();
    b= b+c;
    //a=a-20;
    a=a-b;
    $(".table-col").css("height",a);
}
/* /scroll function code for slide in div*/