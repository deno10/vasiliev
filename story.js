var svg_play = '<path d="M9.6 46.5c-1 0-2-.3-2.9-.8-1.8-1.1-2.9-2.9-2.9-5.1V7.3c0-2.1 1.1-4 2.9-5.1 1.9-1.1 4.1-1.1 5.9 0l30.1 17.6c1.5.9 2.3 2.4 2.3 4.1 0 1.7-.9 3.2-2.3 4.1L12.6 45.7c-.9.5-2 .8-3 .8z"></path>';
var svg_pause = '<path d="M15 1c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3zm18 0c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3z"></path>';

function funonload() {
	var videoEl = document.getElementsByTagName('video')[0];
	var playBtn = document.getElementsByClassName('story_header_content_icons_play')[0];
	var playBtnSVG = document.getElementsByClassName('story_header_content_icons_play_svg')[0];
	
	playBtn.addEventListener('click', function(){playpause(videoEl, playBtnSVG);}, false);
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