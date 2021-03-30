//import smoothscroll from 'smoothscroll-polyfill';
var numpictures = 3;

function funonload() {
	//smoothscroll.polyfill();
	var leftbutton = document.getElementsByClassName('bigpost_carousel_leftbutton')[0];
	var rightbutton = document.getElementsByClassName('bigpost_carousel_rightbutton')[0];
	var carousel = document.getElementsByClassName('bigpost_carousel_outer')[0];
	leftbutton.addEventListener('click', function() {prevpicture();}, false);
	rightbutton.addEventListener('click', function() {nextpicture();}, false);
	carousel.addEventListener('scroll', function() {scrollcarousel();}, {passive: true});
}

function nextpicture() {
	var wdth = $('.bigpost_media_wrapper').width();
	var scr = $('.bigpost_carousel_outer').scrollLeft();
	
	if (scr >= ((numpictures - 1) * wdth)) {
		$('.bigpost_carousel_rightbutton').hide();
		$('.bigpost_carousel_leftbutton').show();
		return 0;
	}
	
	var carousel = document.getElementsByClassName('bigpost_carousel_outer')[0];
	
	carousel.scrollTo({left: wdth+scr, behavior: "smooth"});
	
	if ((wdth + scr) > ((numpictures - 2) * wdth))
		$('.bigpost_carousel_rightbutton').hide();
	if ((wdth + scr) >= wdth)
		$('.bigpost_carousel_leftbutton').show();
}

function prevpicture() {
	var wdth = $('.bigpost_media_wrapper').width();
	var scr = $('.bigpost_carousel_outer').scrollLeft();
	
	if (scr < wdth) {
		$('.bigpost_carousel_leftbutton').hide();
		$('.bigpost_carousel_rightbutton').show();
		return 0;
	}
	
	var carousel = document.getElementsByClassName('bigpost_carousel_outer')[0];
	
	carousel.scrollTo({left: scr-wdth, behavior: "smooth"});
	
	if ((scr - wdth) < wdth)
		$('.bigpost_carousel_leftbutton').hide();
	if ((scr - wdth) < ((numpictures - 1) * wdth))
		$('.bigpost_carousel_rightbutton').show();
}

function scrollcarousel() {
	var scr = $('.bigpost_carousel_outer').scrollLeft();
	var wdth = $('.bigpost_media_wrapper').width();
	if (scr > 0) {
		$('.bigpost_carousel_leftbutton').show();
	} else {
		$('.bigpost_carousel_leftbutton').hide();
	}
	if (scr < ((numpictures - 1) * wdth)) {
		$('.bigpost_carousel_rightbutton').show();
	} else {
		$('.bigpost_carousel_rightbutton').hide();
	}
}