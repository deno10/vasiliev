var svg_play = '<path d="M9.6 46.5c-1 0-2-.3-2.9-.8-1.8-1.1-2.9-2.9-2.9-5.1V7.3c0-2.1 1.1-4 2.9-5.1 1.9-1.1 4.1-1.1 5.9 0l30.1 17.6c1.5.9 2.3 2.4 2.3 4.1 0 1.7-.9 3.2-2.3 4.1L12.6 45.7c-.9.5-2 .8-3 .8z"></path>';
var svg_pause = '<path d="M15 1c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3zm18 0c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3z"></path>';
var svg_nosound = '<path clip-rule="evenodd" d="M1.5 13.3c-.8 0-1.5.7-1.5 1.5v18.4c0 .8.7 1.5 1.5 1.5h8.7l12.9 12.9c.9.9 2.5.3 2.5-1v-9.8c0-.4-.2-.8-.4-1.1l-22-22c-.3-.3-.7-.4-1.1-.4h-.6zm46.8 31.4l-5.5-5.5C44.9 36.6 48 31.4 48 24c0-11.4-7.2-17.4-7.2-17.4-.6-.6-1.6-.6-2.2 0L37.2 8c-.6.6-.6 1.6 0 2.2 0 0 5.7 5 5.7 13.8 0 5.4-2.1 9.3-3.8 11.6L35.5 32c1.1-1.7 2.3-4.4 2.3-8 0-6.8-4.1-10.3-4.1-10.3-.6-.6-1.6-.6-2.2 0l-1.4 1.4c-.6.6-.6 1.6 0 2.2 0 0 2.6 2 2.6 6.7 0 1.8-.4 3.2-.9 4.3L25.5 22V1.4c0-1.3-1.6-1.9-2.5-1L13.5 10 3.3-.3c-.6-.6-1.5-.6-2.1 0L-.2 1.1c-.6.6-.6 1.5 0 2.1L4 7.6l26.8 26.8 13.9 13.9c.6.6 1.5.6 2.1 0l1.4-1.4c.7-.6.7-1.6.1-2.2z" fill-rule="evenodd"></path>';
var svg_sound = '<path clip-rule="evenodd" d="M40.8 6.6c-.6-.6-1.6-.6-2.2 0L37.2 8c-.6.6-.6 1.6 0 2.2 0 0 5.7 5 5.7 13.8s-5.7 13.8-5.7 13.8c-.6.6-.6 1.6 0 2.2l1.4 1.4c.6.6 1.6.6 2.2 0 0 0 7.2-6 7.2-17.4S40.8 6.6 40.8 6.6zm-7.1 7.1c-.6-.6-1.6-.6-2.2 0l-1.4 1.4c-.6.6-.6 1.6 0 2.2 0 0 2.6 2 2.6 6.7s-2.6 6.7-2.6 6.7c-.6.6-.6 1.6 0 2.2l1.4 1.4c.6.6 1.6.6 2.2 0 0 0 4.1-3.5 4.1-10.3s-4.1-10.3-4.1-10.3zM23.1.4L10.2 13.3H1.5c-.8 0-1.5.7-1.5 1.5v18.4c0 .8.7 1.5 1.5 1.5h8.7l12.9 12.9c.9.9 2.5.3 2.5-1V1.4C25.5.2 24-.5 23.1.4z" fill-rule="evenodd"></path>';
var current = 1;

function funonload() {
	
	var story_container = document.getElementsByClassName('story_container')[0];
	storysize(story_container);
	
	window.addEventListener('resize', function(){storysize(story_container);}, false);
	
	var videoEl = document.getElementsByTagName('video')[0];
	var playBtn = document.getElementsByClassName('story_header_content_icons_play')[0];
	var playBtnSVG = document.getElementsByClassName('story_header_content_icons_play_svg')[0];
	var muteBtn = document.getElementsByClassName('story_header_content_icons_sound_svg')[0];
	var muteBtnSVG = document.getElementsByClassName('story_header_content_icons_sound_svg')[0];
	
	var storybar = document.getElementsByClassName('story_header_bar_part_current')[0];
	
	playBtn.addEventListener('click', function(){playpause(videoEl, playBtnSVG);}, false);
	muteBtn.addEventListener('click', function(){mute(videoEl, muteBtnSVG);}, false);
	videoEl.addEventListener('timeupdate', function(){bartime(videoEl, storybar);}, false);
}

function playpause(videoEl, playBtnSVG) {
	if (videoEl.paused) {
		videoEl.play();
		playBtnSVG.innerHTML = svg_pause;
	} else {
		videoEl.pause();
		playBtnSVG.innerHTML = svg_play;
	}
}

function mute(videoEl, muteBtnSVG) {
	if (videoEl.muted == true) {
		videoEl.muted = false;
		muteBtnSVG.innerHTML = svg_sound;
	} else {
		videoEl.muted = true;
		muteBtnSVG.innerHTML = svg_nosound;
	}
}

function bartime(videoEl, storybar) {
	var wdth = videoEl.currentTime / videoEl.duration;
	storybar.style.width = (wdth * 100) + "%";
}

function storysize(container) {
	var hght = window.innerHeight * 0.96;
	var wdth = hght * 0.5625;
	container.style.width = wdth + "px";
	container.style.height = hght + "px";
}