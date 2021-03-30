<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'posts';
$link = $GLOBALS['mysql_oldstyle_link'];
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Список публикаций</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="richtext.min.css">
		<script src="../js/ajaxlogin.js" language="javascript" type="text/javascript"></script>
        <script src="../js/modals.js" language="javascript" type="text/javascript"></script>
		<script src="../js/jquery-3.6.0.min.js" language="javascript" type="text/javascript"></script>
		<script src="jquery.richtext.min.js" language="javascript" type="text/javascript"></script>
		<script language="javascript" type="text/javascript">
		function imgupload(){
			if (window.FormData === undefined) {
				alert('В вашем браузере FormData не поддерживается')
			} else {
				if ($('input[name=mediatype]:checked').val() == "image") {
					var formData = new FormData();
					formData.append('file', $("#js-file")[0].files[0]);
					$('#result').html('<img src="img/loading.gif"/>');
					$.ajax({
						type: "POST",
						url: '/img_upload.php',
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType : 'json',
						success: function(msg){
							if (msg.error == '') {
								$("#js-file").hide();
								$('#result').html('<img src="uploads/'+msg.url+'" style="max-height: 300px;"/>');
								$('#url').attr("value", msg.url);
								$('#url').attr("readonly", "readonly");
								$('#url_original').attr("value", msg.url_original);
								$('#url_original').attr("readonly", "readonly");
								$('#ratio').attr("value", msg.ratio);
							} else {
								$('#result').html(msg.error);
							}
						}
					});
				} else if ($('input[name=mediatype]:checked').val() == "video") {
					var formData = new FormData();
					formData.append('file', $("#js-file")[0].files[0]);
					$('#result').html('<img src="img/loading.gif"/>');
					$.ajax({
						type: "POST",
						url: '/video_upload.php',
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType : 'json',
						success: function(msg){
							if (msg.error == '') {
								$("#js-file").hide();
								$('#result').html('<img src="uploads/'+msg.url+'" style="max-height: 300px;"/><br/>Кадр из видео (Превью)');
								$('#url').attr("value", msg.url);
								$('#url').attr("readonly", "readonly");
								$('#url_original').attr("value", msg.url_original);
								$('#url_original').attr("readonly", "readonly");
								$('#ratio').attr("value", msg.ratio);
								$('#type').attr("value", "video");
							} else {
								$('#result').html(msg.error);
							}
						}
					});
				} else {
					var formData = new FormData();
					formData.append('file', $("#js-file")[0].files[0]);
					$('#result').html('<img src="img/loading.gif"/>');
					$.ajax({
						type: "POST",
						url: '/img_upload.php',
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType : 'json',
						success: function(msg){
							if (msg.error == '') {
								$("#js-file").hide();
								$('#result').html('<img src="uploads/'+msg.url+'" style="max-height: 250px;"/>');
								$('#url').attr("value", msg.url);
								$('#url').attr("readonly", "readonly");
								$('#url_original').attr("value", msg.url_original);
								$('#url_original').attr("readonly", "readonly");
								$('#ratio').attr("value", msg.ratio);
								$('#type').attr("value", "carousel");
								
								$('#mediatd').append('<br/><input type="file" id="js-file1" onChange="carouselupload(1);"></input><div id="result1">');
							} else {
								$('#result').html(msg.error);
							}
						}
					});
				}
			}
		}
		function carouselupload(imgnum){
			if (window.FormData === undefined) {
				alert('В вашем браузере FormData не поддерживается')
			} else {
				var formData = new FormData();
				formData.append('file', $("#js-file" + imgnum)[0].files[0]);
				$('#result' + imgnum).html('<img src="img/loading.gif"/>');
				$.ajax({
					type: "POST",
					url: '/img_upload.php',
					cache: false,
					contentType: false,
					processData: false,
					data: formData,
					dataType : 'json',
					success: function(msg){
						if (msg.error == '') {
							$("#js-file" + imgnum).hide();
							$('#result' + imgnum).html('<img src="uploads/'+msg.url+'" style="max-height: 250px;"/>');
							$('#mediaurl').append('<br/><input type=text name=url' + (imgnum) + ' id="url' + (imgnum) + '" value="" required=required></input>');
							$('#url' + imgnum).attr("value", msg.url);
							$('#url' + imgnum).attr("readonly", "readonly");
							
							$('#mediatd').append('<br/><input type="file" id="js-file' + (imgnum + 1) + '" onChange="carouselupload(' + (imgnum + 1) + ');"></input><div id="result' + (imgnum + 1) + '">');
						} else {
							$('#result').html(msg.error);
						}
					}
				});
			}
		}
		</script>
		<script language="javascript" type="text/javascript">
		//AJAX для редактирования
		var xmlHttpEDIT = new XMLHttpRequest();
		function callServerEDIT(eid)
		{
			var urlE = "posts_edit.php";

			xmlHttpEDIT.open("POST", urlE, true);
			xmlHttpEDIT.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xmlHttpEDIT.onreadystatechange = updatePageEDIT;

			var sendpostE = "action=showedit&id=" + eid;
			xmlHttpEDIT.send(sendpostE);
		}
		
		function updatePageEDIT()
		{
			//if (xmlHttpEDIT.readyState < 4)
				//document.getElementById("popupcontentEDIT").innerHTML = "11111";
			if (xmlHttpEDIT.readyState == 4)
			{
				var response = xmlHttpEDIT.responseText;
				document.getElementById("popupcontentEDIT").innerHTML = response;
				$('.description').richText({leftAlign: false, centerAlign: false, rightAlign: false, justify: false, ol: false, ul: false, fonts: false, fontSize: false, table: false, heading: false, imageUpload: false, fileUpload: false, videoEmbed: false, height: 200});
			}
		}
		
		//AJAX для удаления
		var xmlHttpDELETE = new XMLHttpRequest();
		function callServerDELETE(eid)
		{
			var urlD = "posts_edit.php";

			xmlHttpDELETE.open("POST", urlD, true);
			xmlHttpDELETE.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xmlHttpDELETE.onreadystatechange = updatePageDELETE;

			var sendpostD = "action=showdelete&id=" + eid;
			xmlHttpDELETE.send(sendpostD);
		}
		
		function updatePageDELETE()
		{
			//if (xmlHttpEDIT.readyState < 4)
				//document.getElementById("popupcontentEDIT").innerHTML = "11111";
			if (xmlHttpDELETE.readyState == 4)
			{
				var response = xmlHttpDELETE.responseText;
				document.getElementById("popupcontentDELETE").innerHTML = response;
			}
		}
		
		//AJAX для добавления
		var xmlHttpADD = new XMLHttpRequest();
		function callServerADD()
		{
			var urlA = "posts_edit.php";

			xmlHttpADD.open("POST", urlA, true);
			xmlHttpADD.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xmlHttpADD.onreadystatechange = updatePageADD;

			var sendpostA = "action=showadd";
			xmlHttpADD.send(sendpostA);
		}
		
		function updatePageADD()
		{
			//if (xmlHttpEDIT.readyState < 4)
				//document.getElementById("popupcontentEDIT").innerHTML = "11111";
			if (xmlHttpADD.readyState == 4)
			{
				var response = xmlHttpADD.responseText;
				document.getElementById("popupcontentADD").innerHTML = response;
				$('.description').richText({leftAlign: false, centerAlign: false, rightAlign: false, justify: false, ol: false, ul: false, fonts: false, fontSize: false, table: false, heading: false, imageUpload: false, fileUpload: false, videoEmbed: false, height: 200});
			}
		}
		</script>
	</head>
	<body>
	<div id=logdiv>
	<span id=ld1>Сайт «НазваниеСайта»</span>
	<span id=ld2>
	<?php
	if(USER_LOGGED)
	{
		if (!check_user($UserID)) logout();
	?>
		Здравствуйте, <?php echo $UserShowName; ?>! <br/>
		<button onClick="javascript:window.location=location.href + '?logout'">Выйти</button>
	<?php
	}
	else {
	?>
		<form method="POST" onsubmit="callServer(); return false;">
			Логин: <input type="text" name="login" id="login" size="20"/>
			Пароль: <input type="password" name="pass" id="pass" size="20"/><br/>
			<span id="imgload"></span>
			<input type="button" value="Войти" onClick = "callServer()"/>
		</form>
	<?php
	}
	?>
	</span>
	</div>
<?php

if (!USER_LOGGED) $UserRole = 63;
	$paccess = false;
	
	$result = mysqli_query($link, "SELECT * FROM `pages` WHERE `name`='$ThisPageName'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if ($row['minrole'] == NULL) die ('Ошибка при получении доступа! Обратитесь к администратору для настройки базы');
	if ($UserRole <= $row['minrole']) $paccess = true;
	
	if ($row['specrole'])
	{
		$specroles = unserialize($row['specrole']);
		foreach ($specroles as $key => $value)
		{
			if ($value == $UserRole) $paccess = true;
		}
	}
	
	if (!$paccess) die ('Вы не имеете доступа к этой странице!<br/>Вероятно, вы не вошли в систему, или ваш уровень доступа не позволяет вам просматривать содержимое этой страницы.');
	
	echo ('<p id=showname><span class=whitebox>' . $row['showname'] . '</span></p>');
?>
		<?php
		$result = mysqli_query($link, "SELECT * FROM `posts`;") or die (mysqli_error($link));
		?>
		
        <!-- МОДАЛЬНОЕ ОКНО -->
		<div class=popup-wrapper>
		<a href="" class="overlay" id="act" onClick="hideModal();"></a>
        <div class="popup" id="pwedit" id="pw">
			<div id=popupcontentEDIT>
            	Загрузка...<br/>
				<img src="img/loading.gif" align=middle /> <br/>
            </div>
			<a class="close" title="Закрыть" href="" onClick="hideModal();"></a>
        </div>
		</div>
        
        <div class=popup-wrapper>
		<a href="" class="overlay" id="act" onClick="hideModal();"></a>
        <div class="popup" id="pwdelete" id="pw">
			<div id=popupcontentDELETE>
            	Загрузка...<br/>
				<img src="img/loading.gif" align=middle />
			</div>
			<a class="close" title="Закрыть" href="" onClick="hideModal();"></a>
        </div>
		</div>
        
		<div class=popup-wrapper>
		<a href="" class="overlay" id="act" onClick="hideModal();"></a>
        <div class="popup" id="pwadd" id="pw">
			<div id=popupcontentADD>
            	Загрузка...<br/>
				<img src="img/loading.gif" align=middle /> <br/>
            </div>
			<a class="close" title="Закрыть" href="" onClick="hideModal();"></a>
        </div>
		</div>
        <!-- КОНЕЦ МОДАЛЬНЫХ ОКОН -->
		
		<table class="whitebox whitebox-table whitebox-table-colors whitebox-table-first whitebox-table-secondcenter">
			<tr>
				<td></td>
				<td>id</td>
				<td>Дата</td>
				<td>URL</td>
				<td>URL оригинала</td>
				<td>Описание</td>
				<td>Кем лайкнуто</td>
			</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
		?>
		<tr>
			<td>
				<img src="img/i_edit.png" class="tableicon i_edit" align=middle onClick='showModal("pwedit"); callServerEDIT(<?php echo $row['id']; ?>);' style="cursor: pointer;"/> 
				<img src="img/i_delete.png" class="tableicon i_delete" align=middle  onClick="showModal('pwdelete'); callServerDELETE(<?php echo $row['id']; ?>);" style="cursor: pointer;"/>
			</td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['date']; ?></td>
			<td>
				<?php
				if ($row['type'] == 'carousel') {
					$urlarray = json_decode($row['url']);
					foreach ($urlarray as &$tURL) { ?>
				<img src="uploads/<?php echo $tURL; ?>" style="max-height:200px;"/><br/>
				<?php	}
				} else {
				?>
				<img src="uploads/<?php echo $row['url']; ?>" style="max-height: 200px;"/><?php if ($row['type'] == 'video') echo '<br/>Кадр из видео (превью)'; }?>
			</td>
			<td><a href="uploads/<?php echo $row['url_original']; ?>">link...</a></td>
			<td><?php echo $row['description']; ?></td>
			<td><?php echo $row['liked_by']; ?></td>
		</tr>
		<?php
		}		
		?>
		<tr>
			<td colspan=7 style="text-align: center;"><img src="img/i_add.png" class="tableicon i_add" align=middle onClick='showModal("pwadd"); callServerADD();' style="cursor: pointer;"></img></td>
		</tr>
		</table>
	</body>
</html>