<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'basicblocks';
$link = $GLOBALS['mysql_oldstyle_link'];
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Базовые блоки</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="richtext.min.css">
		<script src="../js/ajaxlogin.js" language="javascript" type="text/javascript"></script>
        <script src="../js/modals.js" language="javascript" type="text/javascript"></script>
		<script src="../js/jquery-3.6.0.min.js" language="javascript" type="text/javascript"></script>
		<script src="jquery.richtext.min.js" language="javascript" type="text/javascript"></script>
		<script language="javascript" type="text/javascript">
		
		function showeditor() {
			$('.content').richText({leftAlign: false, centerAlign: false, rightAlign: false, justify: false, ol: false, ul: false, fonts: false, fontSize: false, table: false, heading: false, imageUpload: false, fileUpload: false, videoEmbed: false, height: 200});
			$('.showeditorbutton').css("display", "none");
			$('.showimguploadbutton').css("display", "none");
		}
		
		function showimgupload() {
			$('#tr_imgupload').css("display", "table-row");
			$('.showeditorbutton').css("display", "none");
			$('.showimguploadbutton').css("display", "none");
		}
		
		function imgupload(){
			if (window.FormData === undefined) {
				alert('В вашем браузере FormData не поддерживается')
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
							$('#result').html('<img src="uploads/'+msg.url+'" style="max-height: 150px;"/>');
							$('.content').html("uploads/" + msg.url_original);
						} else {
							$('#result').html(msg.error);
						}
					}
				});
			}
		}
		
		//AJAX для редактирования
		var xmlHttpEDIT = new XMLHttpRequest();
		function callServerEDIT(eid)
		{
			var urlE = "basicblocks_edit.php";

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
			}
		}
		
		//AJAX для удаления
		var xmlHttpDELETE = new XMLHttpRequest();
		function callServerDELETE(eid)
		{
			var urlD = "basicblocks_edit.php";

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
			var urlA = "basicblocks_edit.php";

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
		$result = mysqli_query($link, "SELECT * FROM `basicblocks`;") or die (mysqli_error($link));
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
				<td>Название</td>
				<td>Значение</td>
				<td>Описание</td>
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
			<td><?php echo $row['name']; ?></td>
			<td><?php echo $row['content']; ?></td>
			<td><?php echo $row['description']; ?></td>
		</tr>
		<?php
		}		
		?>
		<tr>
			<td colspan=5 style="text-align: center;"><img src="img/i_add.png" class="tableicon i_add" align=middle onClick='showModal("pwadd"); callServerADD();' style="cursor: pointer;"></img></td>
		</tr>
		</table>
	</body>
</html>