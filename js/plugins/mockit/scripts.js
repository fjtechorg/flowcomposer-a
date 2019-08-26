$(document).ready(function(){
						   
 $(window).resize(function(){

  $('.iphone-container').css({
   position:'absolute',
   left: ($('.light').width() 
	 - $('.iphone-container').outerWidth())/2,
   top: ($('.light').height() 
	 - $('.iphone-container').outerHeight())/2
  });

  $('.iphone-container').css({
   position:'absolute',
   left: ($('.dark').width() 
	 - $('.iphone-container').outerWidth())/2,
   top: ($('.dark').height() 
	 - $('.iphone-container').outerHeight())/2
  });

 });
 
 $(window).resize();

});
