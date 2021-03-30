<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');
include('engine/basic.php');

$ThisPageName = 'posts';
$link = $GLOBALS['mysql_oldstyle_link'];
$basics = getbasics();

$result = mysqli_query($link, "SELECT * FROM `posts` ORDER BY `id` DESC;") or die (mysqli_error($link));
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
		<link rel="preload" href="style.css" as="style">
		<link rel="stylesheet" href="style.css">
		<title><?php echo $basics['account_name']; ?> • Фото и видео в ITMOgram</title>
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
									<img class="nav_up_logo_img" src="images/itmogram.png"/>
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
			<div class="content">
				<div class="header">
					<div class="avatar">
						<div class="avatar_inner">
						<a href="stories.php"><span class="avatar_span" style="width: 150px; height: 150px;"><img class="avatar_image" src="<?php echo $basics['avatar']; ?>"/></span></a>
						</div>
					</div>
					<div class="header_content">
						<div class="header_account">
							<h2 class="header_account_name"><?php echo $basics['account_name']; ?></h2>
						</div>
						<ul class="header_numbers">
							<li class="header_number">
								<span class="header_number_number"><?php echo $basics['count1']; ?></span> <?php echo $basics['count1_name']; ?>
							</li>
							<li class="header_number">
								<span class="header_number_number"><?php echo $basics['count2']; ?></span> <?php echo $basics['count2_name']; ?>
							</li>
							<li class="header_number">
								<span class="header_number_number"><?php echo $basics['count3']; ?></span> <?php echo $basics['count3_name']; ?>
							</li>
						</ul>
						<div class="header_description">
							<h1 class="header_description_title"><?php echo $basics['account_name2']; ?></h1>
							<br/>
							<span><?php echo $basics['account_description']; ?></span>
							<span class="header_description_followers">
								<span class="header_description_followers_inner">Подписаны <span class="header_description_followers_follower"><?php echo $basics['followers']; ?></span> и ещё <?php echo $basics['followers_num']; ?></span>
							</span>
						</div>
					</div>
				</div>
				<div class="header_description_mobile">
					<h1 class="header_description_title"><?php echo $basics['account_name2']; ?></h1>
					<br/>
					<span><?php echo $basics['account_description']; ?></span>
					<span class="header_description_followers">
						<span class="header_description_followers_inner">Подписаны <span class="header_description_followers_follower"><?php echo $basics['followers']; ?></span> и ещё <?php echo $basics['followers_num']; ?></span>
					</span>
				</div>
				<div class="stories">
					<div class="stories_inner">
						<?php $cstories = mysqli_query($link, "SELECT COUNT(*) FROM `pins` WHERE `alias` NOT IN ('default');") or die (mysqli_error($link));
						mysqli_data_seek($cstories, 0);
						$numstories = mysqli_fetch_row($cstories)[0]; ?>
						<div class="stories_inner2<?php if ($numstories > 7) echo(' stories_inner2_wide'); ?>">
							<div class="stories_content">
								<ul class="stories_content_inner">
									<li class="filler"></li>
									<?php $rstories = mysqli_query($link, "SELECT * FROM `pins` WHERE `alias` NOT IN ('default');") or die (mysqli_error($link));
									while ($strow = mysqli_fetch_array($rstories, MYSQLI_ASSOC)) {
									?>
									<li class="stories_story_outer">
										<div class="stories_story">
											<a class="stories_story_link" href="stories.php?pin=<?php echo $strow['id']; ?>"><div class="stories_story_inner">
												<div class="stories_story_img">
													<div class="stories_story_img_inner">
														<img class="stories_story_image" src="uploads/<?php echo $strow['image']; ?>"/>
													</div>
												</div>
												<div class="stories_story_name"><?php echo $strow['name']; ?></div>
											</div></a>
										</div>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<ul class="header_numbers_mobile">
					<li class="header_number_mobile">
						<span class="header_number_inner_mobile"><span class="header_number_number_mobile"><?php echo $basics['count1']; ?></span> <?php echo $basics['count1_name']; ?></span>
					</li>
					<li class="header_number_mobile">
						<span class="header_number_inner_mobile"><span class="header_number_number_mobile"><?php echo $basics['count2']; ?></span> <?php echo $basics['count2_name']; ?></span>
					</li>
					<li class="header_number_mobile">
						<span class="header_number_inner_mobile"><span class="header_number_number_mobile"><?php echo $basics['count3']; ?></span> <?php echo $basics['count3_name']; ?></span>
					</li>
				</ul>
				<div class="nav">
					<a class="nav_item nav_selected" href="#">
						<span class="nav_inner">
							<svg class="nav_svg" fill="#262626" height="12" viewBox="0 0 48 48" width="12">
							<path clip-rule="evenodd" d="M45 1.5H3c-.8 0-1.5.7-1.5 1.5v42c0 .8.7 1.5 1.5 1.5h42c.8 0 1.5-.7 1.5-1.5V3c0-.8-.7-1.5-1.5-1.5zm-40.5 3h11v11h-11v-11zm0 14h11v11h-11v-11zm11 25h-11v-11h11v11zm14 0h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11zm14 28h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11z" fill-rule="evenodd"></path>
							</svg>
							<span class="nav_text">Публикации</span>
						</span>
					</a>
					<a class="nav_item" href="#">
						<span class="nav_inner">
							<svg class="nav_svg" fill="#8e8e8e" height="12" viewBox="0 0 48 48" width="12">
							<path d="M41 10c-2.2-2.1-4.8-3.5-10.4-3.5h-3.3L30.5 3c.6-.6.5-1.6-.1-2.1-.6-.6-1.6-.5-2.1.1L24 5.6 19.7 1c-.6-.6-1.5-.6-2.1-.1-.6.6-.7 1.5-.1 2.1l3.2 3.5h-3.3C11.8 6.5 9.2 7.9 7 10c-2.1 2.2-3.5 4.8-3.5 10.4v13.1c0 5.7 1.4 8.3 3.5 10.5 2.2 2.1 4.8 3.5 10.4 3.5h13.1c5.7 0 8.3-1.4 10.5-3.5 2.1-2.2 3.5-4.8 3.5-10.4V20.5c0-5.7-1.4-8.3-3.5-10.5zm.5 23.6c0 5.2-1.3 7-2.6 8.3-1.4 1.3-3.2 2.6-8.4 2.6H17.4c-5.2 0-7-1.3-8.3-2.6-1.3-1.4-2.6-3.2-2.6-8.4v-13c0-5.2 1.3-7 2.6-8.3 1.4-1.3 3.2-2.6 8.4-2.6h13.1c5.2 0 7 1.3 8.3 2.6 1.3 1.4 2.6 3.2 2.6 8.4v13zM34.6 25l-9.1 2.8v-3.7c0-.5-.2-.9-.6-1.2-.4-.3-.9-.4-1.3-.2l-11.1 3.4c-.8.2-1.2 1.1-1 1.9.2.8 1.1 1.2 1.9 1l9.1-2.8v3.7c0 .5.2.9.6 1.2.3.2.6.3.9.3.1 0 .3 0 .4-.1l11.1-3.4c.8-.2 1.2-1.1 1-1.9s-1.1-1.2-1.9-1z"></path>
							</svg>
							<span class="nav_text">IGTV</span>
						</span>
					</a>
					<a class="nav_item" href="#">
						<span class="nav_inner">
							<svg class="nav_svg" fill="#8e8e8e" height="12" viewBox="0 0 48 48" width="12">
							<path d="M41.5 5.5H30.4c-.5 0-1-.2-1.4-.6l-4-4c-.6-.6-1.5-.6-2.1 0l-4 4c-.4.4-.9.6-1.4.6h-11c-3.3 0-6 2.7-6 6v30c0 3.3 2.7 6 6 6h35c3.3 0 6-2.7 6-6v-30c0-3.3-2.7-6-6-6zm-29.4 39c-.6 0-1.1-.6-1-1.2.7-3.2 3.5-5.6 6.8-5.6h12c3.4 0 6.2 2.4 6.8 5.6.1.6-.4 1.2-1 1.2H12.1zm32.4-3c0 1.7-1.3 3-3 3h-.6c-.5 0-.9-.4-1-.9-.6-5-4.8-8.9-9.9-8.9H18c-5.1 0-9.4 3.9-9.9 8.9-.1.5-.5.9-1 .9h-.6c-1.7 0-3-1.3-3-3v-30c0-1.7 1.3-3 3-3h11.1c1.3 0 2.6-.5 3.5-1.5L24 4.1 26.9 7c.9.9 2.2 1.5 3.5 1.5h11.1c1.7 0 3 1.3 3 3v30zM24 12.5c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.6 9.6 9.6 9.6-4.3 9.6-9.6-4.3-9.6-9.6-9.6zm0 16.1c-3.6 0-6.6-2.9-6.6-6.6 0-3.6 2.9-6.6 6.6-6.6s6.6 2.9 6.6 6.6c0 3.6-3 6.6-6.6 6.6z"></path>
							</svg>
							<span class="nav_text">Отметки</span>
						</span>
					</a>
				</div>
				<div class="nav_mobile">
					<a class="nav_item_mobile">
						<svg class="nav_item_mobile_svg" fill="#0095f6" height="24" width="24" viewBox="0 0 48 48">
						<path clip-rule="evenodd" d="M45 1.5H3c-.8 0-1.5.7-1.5 1.5v42c0 .8.7 1.5 1.5 1.5h42c.8 0 1.5-.7 1.5-1.5V3c0-.8-.7-1.5-1.5-1.5zm-40.5 3h11v11h-11v-11zm0 14h11v11h-11v-11zm11 25h-11v-11h11v11zm14 0h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11zm14 28h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11z" fill-rule="evenodd"></path>
						</svg>
					</a>
					<a class="nav_item_mobile">
						<span class="nav_item_mobile_img"></span>
					</a>
					<a class="nav_item_mobile">
						<svg class="nav_item_mobile_svg" fill="#8e8e8e" height="24" width="24" viewBox="0 0 48 48">
						<path d="M41 10c-2.2-2.1-4.8-3.5-10.4-3.5h-3.3L30.5 3c.6-.6.5-1.6-.1-2.1-.6-.6-1.6-.5-2.1.1L24 5.6 19.7 1c-.6-.6-1.5-.6-2.1-.1-.6.6-.7 1.5-.1 2.1l3.2 3.5h-3.3C11.8 6.5 9.2 7.9 7 10c-2.1 2.2-3.5 4.8-3.5 10.4v13.1c0 5.7 1.4 8.3 3.5 10.5 2.2 2.1 4.8 3.5 10.4 3.5h13.1c5.7 0 8.3-1.4 10.5-3.5 2.1-2.2 3.5-4.8 3.5-10.4V20.5c0-5.7-1.4-8.3-3.5-10.5zm.5 23.6c0 5.2-1.3 7-2.6 8.3-1.4 1.3-3.2 2.6-8.4 2.6H17.4c-5.2 0-7-1.3-8.3-2.6-1.3-1.4-2.6-3.2-2.6-8.4v-13c0-5.2 1.3-7 2.6-8.3 1.4-1.3 3.2-2.6 8.4-2.6h13.1c5.2 0 7 1.3 8.3 2.6 1.3 1.4 2.6 3.2 2.6 8.4v13zM34.6 25l-9.1 2.8v-3.7c0-.5-.2-.9-.6-1.2-.4-.3-.9-.4-1.3-.2l-11.1 3.4c-.8.2-1.2 1.1-1 1.9.2.8 1.1 1.2 1.9 1l9.1-2.8v3.7c0 .5.2.9.6 1.2.3.2.6.3.9.3.1 0 .3 0 .4-.1l11.1-3.4c.8-.2 1.2-1.1 1-1.9s-1.1-1.2-1.9-1z"></path>
						</svg>
					</a>
					<a class="nav_item_mobile">
						<svg class="nav_item_mobile_svg" fill="#8e8e8e" height="24" width="24" viewBox="0 0 48 48">
						<path d="M41.5 5.5H30.4c-.5 0-1-.2-1.4-.6l-4-4c-.6-.6-1.5-.6-2.1 0l-4 4c-.4.4-.9.6-1.4.6h-11c-3.3 0-6 2.7-6 6v30c0 3.3 2.7 6 6 6h35c3.3 0 6-2.7 6-6v-30c0-3.3-2.7-6-6-6zm-29.4 39c-.6 0-1.1-.6-1-1.2.7-3.2 3.5-5.6 6.8-5.6h12c3.4 0 6.2 2.4 6.8 5.6.1.6-.4 1.2-1 1.2H12.1zm32.4-3c0 1.7-1.3 3-3 3h-.6c-.5 0-.9-.4-1-.9-.6-5-4.8-8.9-9.9-8.9H18c-5.1 0-9.4 3.9-9.9 8.9-.1.5-.5.9-1 .9h-.6c-1.7 0-3-1.3-3-3v-30c0-1.7 1.3-3 3-3h11.1c1.3 0 2.6-.5 3.5-1.5L24 4.1 26.9 7c.9.9 2.2 1.5 3.5 1.5h11.1c1.7 0 3 1.3 3 3v30zM24 12.5c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.6 9.6 9.6 9.6-4.3 9.6-9.6-4.3-9.6-9.6-9.6zm0 16.1c-3.6 0-6.6-2.9-6.6-6.6 0-3.6 2.9-6.6 6.6-6.6s6.6 2.9 6.6 6.6c0 3.6-3 6.6-6.6 6.6z"></path>
						</svg>
					</a>
				</div>
				<div class="posts">
					<article class="posts_outer">
						<div class="posts_inner">
						<?php while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
							<div class="posts_row">
								<div class="post_outer"><?php if ($row) { ?>
									<a href="publication.php?id=<?php echo $row['id']; ?>">
										<div class="post">
											<div class="post_inner">
												<img class="post_img" src="uploads/<?php echo ($row['type'] == 'carousel') ? $row['url_original'] : $row['url']; ?>"/>
											</div>
										</div>
										<?php if ($row['type'] != 'image') { ?><div class="post_mediatype">
											<span class="post_mediatype_<?php echo $row['type']; ?>"></span>
										</div><?php } ?>
									</a> <?php } $row = mysqli_fetch_array($result, MYSQLI_ASSOC); ?>
								</div>
								<div class="post_outer"><?php if ($row) { ?>
									<a href="publication.php?id=<?php echo $row['id']; ?>">
										<div class="post">
											<div class="post_inner">
												<img class="post_img" src="uploads/<?php echo ($row['type'] == 'carousel') ? $row['url_original'] : $row['url']; ?>"/>
											</div>
										</div>
										<?php if ($row['type'] != 'image') { ?><div class="post_mediatype">
											<span class="post_mediatype_<?php echo $row['type']; ?>"></span>
										</div><?php } ?>
									</a> <?php } $row = mysqli_fetch_array($result, MYSQLI_ASSOC); ?>
								</div>
								<div class="post_outer"><?php if ($row) { ?>
									<a href="publication.php?id=<?php echo $row['id']; ?>">
										<div class="post">
											<div class="post_inner">
												<img class="post_img" src="uploads/<?php echo ($row['type'] == 'carousel') ? $row['url_original'] : $row['url']; ?>"/>
											</div>
										</div>
										<?php if ($row['type'] != 'image') { ?><div class="post_mediatype">
											<span class="post_mediatype_<?php echo $row['type']; ?>"></span>
										</div><?php } ?>
									</a> <?php } ?>
								</div>
							</div>
						<?php } ?>
						</div>
					</article>
				</div>
			</div>
		</div>
		<div class="footer"></div>
	</body>
</html>