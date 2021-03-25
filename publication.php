<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'posts';
$link = $GLOBALS['mysql_oldstyle_link'];

if (!isset($_GET['id'])) header("Location: index.php");
$eid = $_GET['id'];

$result = mysqli_query($link, "SELECT * FROM `posts` WHERE `id`='$eid'") or die (mysqli_error($link));
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if (!$row) header("Location: index.php");

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="preload" href="style.css" as="style">
		<link rel="stylesheet" href="style.css">
		<title>rektorkrut • Фото и видео в Instagram</title>
	</head>
	<body>
		<div class="nav_up">
			<div class="nav_up_filler"></div>
			<div class="nav_up_inner">
				<div class="nav_up_content">
					<div class="nav_up_logo">
						<a href="#">
							<div class="nav_up_logo_wrapper">
								<div class="nav_up_logo_inner">
									<img class="nav_up_logo_img" src="images/instagram.png"/>
								</div>
							</div>
						</a>
					</div>
					<div class="nav_up_icons">
						<div class="nav_up_icons_inner">
							<div class="nav_up_icon_outer">
								<a href="#">
									<svg class="nav_up_icon_svg" fill="#262626#" height="22" viewBox="0 0 48 48" width="22">
									<path d="M45.3 48H30c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2-4.6-4.6-4.6s-4.6 2-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.5-.6 2.1 0l21.5 21.5c.4.4.6 1.1.3 1.6 0 .1-.1.1-.1.2v22.8c.1.8-.6 1.5-1.4 1.5zm-13.8-3h12.3V23.4L24 3.6l-20 20V45h12.3V34.2c0-4.3 3.3-7.6 7.6-7.6s7.6 3.3 7.6 7.6V45z"></path>
									</svg>
								</a>
							</div>
							<div class="nav_up_icon_outer">
								<a href="#">
									<svg class="nav_up_icon_svg" fill="#262626#" height="22" viewBox="0 0 48 48" width="22">
									<path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
									</svg>
								</a>
							</div>
							<div class="nav_up_icon_outer">
								<a href="#">
									<svg class="nav_up_icon_svg" fill="#262626#" height="22" viewBox="0 0 48 48" width="22">
									<path clip-rule="evenodd" d="M24 0C10.8 0 0 10.8 0 24s10.8 24 24 24 24-10.8 24-24S37.2 0 24 0zm0 45C12.4 45 3 35.6 3 24S12.4 3 24 3s21 9.4 21 21-9.4 21-21 21zm10.2-33.2l-14.8 7c-.3.1-.6.4-.7.7l-7 14.8c-.3.6-.2 1.3.3 1.7.3.3.7.4 1.1.4.2 0 .4 0 .6-.1l14.8-7c.3-.1.6-.4.7-.7l7-14.8c.3-.6.2-1.3-.3-1.7-.4-.5-1.1-.6-1.7-.3zm-7.4 15l-5.5-5.5 10.5-5-5 10.5z" fill-rule="evenodd"></path>
									</svg>
								</a>
							</div>
							<div class="nav_up_icon_outer">
								<a href="#">
									<svg class="nav_up_icon_svg" fill="#262626#" height="22" viewBox="0 0 48 48" width="22">
									<path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>
									</svg>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
		<div class="main">
			<div class="bigpost_content">
				<div class="bigpost_outer">
					<article class="bigpost">
						<div class="bigpost_account">
							<div class="bigpost_account_logo_outer">
								<div class="bigpost_account_logo">
									<a class="bigpost_account_logo_inner">
										<img class="bigpost_account_logo_img" src="images/avatar.jpg"/>
									</a>
								</div>
							</div>
							<div class="bigpost_account_name">
								<div class="bigpost_account_name_inner">
									<div class="bigpost_account_name_name">
										<a class="bigpost_account_name_link" href="index.html">itmo.megabattle</a>
									</div>
									<div class="bigpost_account_name_following">
										<span class="bigpost_account_name_dot">•</span>
										<div class="bigpost_account_name_following_inner">Подписки</div>
									</div>
								</div>
							</div>
						</div>
						<!--div class="bigpost_ellipsis">
						</div-->
						<div class="bigpost_media">
							<div class="bigpost_media_wrapper">
								<?php if ($row['type'] == 'image') { ?><div class="bigpost_media_outer">
									<div class="bigpost_media_inner"<?php if ($row['ratio'] != 1) echo('style="padding-bottom: '.$row['ratio']*100 . '%;"');?>>
										<img class="bigpost_media_img" src="uploads/<?php echo $row['url']; ?>"/>
									</div>
								</div><?php } elseif ($row['type'] == 'video') { ?>
								<div class="bigpost_mediavideo_outer">
									<div class="bigpost_mediavideo_inner">
											<div class="bigpost_video_wrapper">
												<div class="bigpost_video_outer">
													<video class="bigpost_video" playsinline="" poster="uploads/<?php echo $row['url']; ?>" preload=none type="video/mp4" src="uploads/<?php echo $row['url_original']; ?>" loop="" controls="controls">
													</video>
												</div>
											</div>
									</div>
								</div><?php } else die('Контент неизвестного типа') ?>
							</div>
						</div>
						<div class="bigpost_description">
							<section class="bigpost_descr_icons">
								<span class="bigpost_descr_icon_like">
									<div class="bigpost_descr_icon_like_button">
										<div class="bigpost_descr_icon_like_wrapper">
											<span class="">
												<svg class="bigpost_descr_icon_like_svg" fill="#ed4956" height="24" width="24" viewBox="0 0 48 48">
												<path d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>
												</svg>
											</span>
										</div>
									</div>
								</span>
								<span class="bigpost_descr_icon_comm">
									<div class="bigpost_descr_icon_comm_button">
										<div class="bigpost_descr_icon_comm_wrapper">
											<svg class="bigpost_descr_icon_comm_svg" fill="#262626" height="24" width="24" viewBox="0 0 48 48">
											<path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"></path>
											</svg>
										</div>
									</div>
								</span>
								<span class="bigpost_descr_icon_direct">
									<div class="bigpost_descr_icon_direct_button">
										<div class="bigpost_descr_icon_direct_wrapper">
											<svg class="bigpost_descr_icon_direct_svg" fill="#262626" height="24" width="24" viewBox="0 0 48 48">
											<path d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z"></path>
											</svg>
										</div>
									</div>
								</span>
								<span class="bigpost_descr_icon_save">
									<div class="bigpost_descr_icon_save_button">
										<div class="bigpost_descr_icon_save_wrapper">
											<svg class="bigpost_descr_icon_save_svg" fill="#262626" height="24" width="24" viewBox="0 0 48 48">
											<path d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z"></path>
											</svg>
										</div>
									</div>
								</span>
							</section>
							<section class="bigpost_descr_liked">
								<div class="bigpost_descr_liked_outer">
									<div class="bigpost_descr_liked_inner">
										Нравится <span class="bigpost_descr_liked_account"> <a class="bigpost_descr_liked_account_link" href="#"><?php echo $row['liked_by']; ?></a> </span>и другим
									</div>
								</div>
							</section>
							<div class="bigpost_descr_description">
								<ul class="bigpost_descr_description_wrapper">
									<div class="bigpost_descr_description_outer">
										<li class="bigpost_descr_description_inner">
											<div class="bigpost_descr_description_text_outer">
												<div class="bigpost_descr_description_text_wrapper">
													<div class="bigpost_descr_description_text_icon">
														<div class="bigpost_descr_description_text_icon_wrapper">
															<span class="bigpost_descr_description_text_icon_inner">
																<img class="bigpost_descr_description_text_icon_img" src="images/avatar.jpg"/>
															</span>
														</div>
													</div>
													<div class="bigpost_descr_description_text_inner">
														<h2 class="bigpost_descr_description_text_account_outer">
															<div class="bigpost_descr_description_text_account">
																<span class="bigpost_descr_description_text_account_inner">
																	<a class="bigpost_descr_description_text_account_link" href="index.html">itmo.megabattle</a>
																</span>
															</div>
														</h2>
														<span class="bigpost_descr_description_text">
															<?php echo $row['description']; ?>
														</span>
														<div class="bigpost_descr_description_text_date_outer">
														</div>
													</div>
												</div>
											</div>
										</li>
									</div>
								</ul>
							</div>
							<div class="bigpost_descr_date">
								<a class="bigpost_descr_date_outer">
									<time class="bigpost_descr_date_inner"><?php echo $row['date']; ?></time>
								</a>
							</div>
							<section class="bigpost_desr_comments">
							</section>
						</div>
					</article>
				</div>
			</div>
		</div>
		<div class="footer"></div>
	</body>
</html>