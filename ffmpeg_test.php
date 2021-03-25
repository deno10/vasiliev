<?php
require_once '/usr/bin/vendor/autoload.php';

$sec = 0;
$path = __DIR__;
$movie = $path.'/videos/itmo.megabattle_10000000_1983450141797961_2454407776336995913_n.mp4';
$thumbnail = $path.'/videos/thumbnail.png';

$ffmpeg = FFMpeg\FFMpeg::create();
$video = $ffmpeg->open($movie);
$frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
$frame->save($thumbnail);
echo '<img src="/videos/thumbnail.png">';

?>