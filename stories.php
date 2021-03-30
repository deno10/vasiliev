<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');
include('engine/basic.php');

$ThisPageName = 'stories';
$link = $GLOBALS['mysql_oldstyle_link'];
$basics = getbasics();

$pin = (isset($_GET['pin'])) ? $_GET['pin'] : '1'; //тут надо не 1, а SQL-запросом default. но зачем...

$result = mysqli_query($link, "SELECT * FROM `stories` WHERE `pin`='$pin'") or die (mysqli_error($link));
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if (!$row) header("Location: index.php");

$firstvideo = $row['video'];
$firstposter = $row['image'];

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
		<link rel="preload" href="story.css" as="style" type="text/css">
		<script src="../js/jquery-3.6.0.min.js" language="javascript" type="text/javascript"></script>
		<link rel="preload" href="story.js" as="script" type="text/javascript">
		<link rel="stylesheet" href="story.css" type="text/css">
		<script src="story.js"></script>
		<script language="javascript" type="text/javascript">
		<?php 
		$cstories = mysqli_query($link, "SELECT COUNT(*) FROM `stories` WHERE `pin`='$pin';") or die (mysqli_error($link));
		mysqli_data_seek($cstories, 0);
		$numstories = mysqli_fetch_row($cstories)[0]; ?>
		numstories = <?php echo $numstories; ?>;
		<?php
		do {
			echo ("videolinks.push("."'".$row['video'] ."');"."\n");
		} while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC));
		?>
		</script>
		<title>Истории • Instagram</title>
	</head>
	<body onload="funonload();">
		<div class="story_main">
			<section class="story_main_inner">
				<div class="story_content">
					<div class="story_container">
						<section class="story_wrapper">
							<div class="story_inner">
								<button class="story_button_left">
									<div class="story_button_left_inner"></div>
								</button>
								<div class="story_video_wrapper">
									<div class="story_video_outer">
										<div class="story_video_inner">
											<div class="story_video_loading"></div>
											<img class="story_video_image" src=""/>
											<video class="story_video" preload="auto" playsinline="" poster="uploads/<?php echo $firstposter; ?>" autoplay="autoplay" muted="muted">
												<source id="storysource" src="uploads/<?php echo $firstvideo; ?>">
											</video>
											<div class="story_video_mobnavigation">
												<div class="story_video_mobnavigation_outer">
													<div class="story_video_mobnavigation_inner">
														<button class="story_video_mobnavigation_leftsmall"></button>
														<button class="story_video_mobnavigation_left"></button>
														<button class="story_video_mobnavigation_right"></button>
														<button class="story_video_mobnavigation_rightsmall"></button>
														<div class="story_video_mobnavigation_play_outer">
															<button class="story_video_mobnavigation_play"></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="story_header">
									<div class="story_header_bar">
										<div class="story_header_bar_part" id="story0">
											<div class="story_header_bar_part_grey"></div>
											<div class="story_header_bar_part_white story_header_bar_part_current" style="width: 0%;"></div>
										</div>
										<?php 
											for ($i = 1; $i < $numstories; $i++) {
										?>
										<div class="story_header_bar_part" id="story<?php echo $i; ?>">
											<div class="story_header_bar_part_grey"></div>
										</div>
										<?php
											}
										?>
									</div>
									<div class="story_header_content">
										<div class="story_header_content_account">
											<div class="story_header_content_account_wrapper">
												<a class="story_header_content_account_link" href="index.php">
													<img class="story_header_content_account_img" src="<?php echo $basics['avatar']; ?>"/>
												</a>
												<div class="story_header_content_account_name">
													<div class="story_header_content_account_name_wrapper">
														<div class="story_header_content_account_name_inner">
															<a class="story_header_content_account_name_link" href="index.php"><?php echo $basics['account_name']; ?></a>
														</div>
														<time class="story_header_content_account_name_date">2 ч.</time>
													</div>
												</div>
											</div>
										</div>
										<div class="story_header_content_icons">
											<button class="story_header_content_icons_play" type="button">
												<div class="story_header_content_icons_play_inner">
													<svg class="story_header_content_icons_play_svg" fill="#ffffff" height="16" width="16" viewBox="0 0 48 48">
													<path d="M15 1c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3zm18 0c-3.3 0-6 1.3-6 3v40c0 1.7 2.7 3 6 3s6-1.3 6-3V4c0-1.7-2.7-3-6-3z"></path>
													</svg>
												</div>
											</button>
											<span>
												<svg class="story_header_content_icons_sound_svg" fill="#ffffff" height="16" width="16" viewBox="0 0 48 48">
												<path clip-rule="evenodd" d="M40.8 6.6c-.6-.6-1.6-.6-2.2 0L37.2 8c-.6.6-.6 1.6 0 2.2 0 0 5.7 5 5.7 13.8s-5.7 13.8-5.7 13.8c-.6.6-.6 1.6 0 2.2l1.4 1.4c.6.6 1.6.6 2.2 0 0 0 7.2-6 7.2-17.4S40.8 6.6 40.8 6.6zm-7.1 7.1c-.6-.6-1.6-.6-2.2 0l-1.4 1.4c-.6.6-.6 1.6 0 2.2 0 0 2.6 2 2.6 6.7s-2.6 6.7-2.6 6.7c-.6.6-.6 1.6 0 2.2l1.4 1.4c.6.6 1.6.6 2.2 0 0 0 4.1-3.5 4.1-10.3s-4.1-10.3-4.1-10.3zM23.1.4L10.2 13.3H1.5c-.8 0-1.5.7-1.5 1.5v18.4c0 .8.7 1.5 1.5 1.5h8.7l12.9 12.9c.9.9 2.5.3 2.5-1V1.4C25.5.2 24-.5 23.1.4z" fill-rule="evenodd"></path>
												</svg>
											</span>
											<a href="index.php"><button class="story_header_content_icons_close">
												<span class="story_header_content_icons_close_span"></span>
											</button></a>
										</div>
									</div>
								</div>
								<button class="story_button_right">
									<div class="story_button_right_inner"></div>
								</button>
							</div>
						</section>
					</div>
				</div>
				<div class="story_instalogo">
				</div>
				<div class="story_closebutton">
					<a href="index.php"><button class="story_closebutton_button">
						<div class="story_closebutton_inner">
							<svg class="story_closebutton_svg" fill="#ffffff" height="24" width="24" viewBox="0 0 48 48">
							<path clip-rule="evenodd" d="M41.8 9.8L27.5 24l14.2 14.2c.6.6.6 1.5 0 2.1l-1.4 1.4c-.6.6-1.5.6-2.1 0L24 27.5 9.8 41.8c-.6.6-1.5.6-2.1 0l-1.4-1.4c-.6-.6-.6-1.5 0-2.1L20.5 24 6.2 9.8c-.6-.6-.6-1.5 0-2.1l1.4-1.4c.6-.6 1.5-.6 2.1 0L24 20.5 38.3 6.2c.6-.6 1.5-.6 2.1 0l1.4 1.4c.6.6.6 1.6 0 2.2z" fill-rule="evenodd"></path>
							</svg>
						</div>
					</button></a>
				</div>
			</section>
		</div>
	</body>
</html>