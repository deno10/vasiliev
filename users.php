<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$link = $GLOBALS['mysql_oldstyle_link'];
$ThisPageName = 'users';
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Все пользователи системы</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="../js/ajaxlogin.js" language="javascript" type="text/javascript"></script>
        <script src="../js/modals.js" language="javascript" type="text/javascript"></script>
		<script language="javascript" type="text/javascript">
		function changepass() {
			document.getElementById("pass1").style.display = "none";
			document.getElementById("pass2").style.display = "block";
			document.getElementById("pass2").disabled = false;
		}
		function changerole() {
			if (!document.getElementById("checkbox3").checked) {
				document.getElementById("role1").style.display = "none";
				document.getElementById("role2").style.display = "block";
				document.getElementById("role1").disabled = true;
				document.getElementById("role2").disabled = false;
				//document.getElementById("roleB").value = "Из списка";
			}
			else {
				document.getElementById("role2").style.display = "none";
				document.getElementById("role1").style.display = "block";
				document.getElementById("role2").disabled = true;
				document.getElementById("role1").disabled = false;
				//document.getElementById("roleB").value = "Цифрами";
			}
		}
		</script>
		<script language="javascript" type="text/javascript">
		//AJAX для редактирования
		var xmlHttpEDIT = new XMLHttpRequest();
		function callServerEDIT(eid)
		{
			var urlE = "users_edit.php";

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
			var urlD = "users_edit.php";

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
			var urlA = "users_edit.php";

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
		$result = mysqli_query($link, "SELECT * FROM `users`;") or die (mysqli_error($link));
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
				<td>Логин</td>
				<td>Имя пользователя</td>
				<td><abbr title="Уровень доступа отображается в том случае, если для него не указана роль">Роль (уровень доступа)</abbr></td>
			</tr>
		<?php
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
		?>
<tr>
			<td>
				<img src="img/i_edit.png" class="tableicon i_edit" align=middle onClick='showModal("pwedit"); callServerEDIT(<?php echo $row['uid']; ?>);' style="cursor: pointer;"/> 
				<img src="img/i_delete.png" class="tableicon i_delete" align=middle  onClick="showModal('pwdelete'); callServerDELETE(<?php echo $row['uid']; ?>);" style="cursor: pointer;"/>
			</td>
			<td><?php echo $row['uid']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $row['showname']; ?></td>
			<td><?php
			$temprole = $row['role'];
			$subresult = mysqli_query($link, "SELECT `name` FROM `roles` WHERE `value`='$temprole'") or die (mysqli_error($link));
			$subrow = mysqli_fetch_array($subresult, MYSQLI_ASSOC);
			if ($subrow['name'])
				echo $subrow['name'];
			else
				echo $row['role'];
			?></td>
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