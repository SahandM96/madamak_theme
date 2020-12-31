$(function(){"use strict";$('[data-toggle="offcanvas"]').on("click",function(){$(".offcanvas-collapse").toggleClass("open")})}),$(window).scroll(function(){$(this).scrollTop()>20?($("nav.navbar").addClass("sticky"),$(".navbar>.container").addClass("sticky")):($("nav.navbar").removeClass("sticky"),$(".navbar>.container").removeClass("sticky"))}),$(document).ready(function(){$(".back-to-top").click(function(){return $("body,html").animate({scrollTop:0},400),!1})}),$(document).ready(function(){$(window).on("load",function(){preloaderFadeOutTime=500,$(".spinner-wrapper").fadeOut(preloaderFadeOutTime)})});

$(document).ready(function(){
	var $carousel = $('#SingleProductCarousel');
	/*$carousel.on( 'staticClick.flickity', function( event, pointer, cellElement, cellIndex ) {
  		// dismiss if cell was not clicked
  		if ( !cellElement ) {
    		return;
  		}
  		// change cell background with .is-clicked
  		$carousel.find('.is-clicked').removeClass('is-clicked');
  		$( cellElement ).addClass('is-clicked');
  		console.log( 'Cell ' + ( cellIndex + 1 )  + ' clicked' );
		
	});
	
	$carousel.on( 'pointerDown.flickity', function( event, pointer ) {
		  $carousel.flickity('toggleFullscreen')
	});*/
});