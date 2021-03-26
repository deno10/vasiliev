<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'basicblocks';
$link = $GLOBALS['mysql_oldstyle_link'];

if(USER_LOGGED)	{
		if (!check_user($UserID)) logout();
}

if (!USER_LOGGED) $UserRole = 63;
$paccess = false;
	
$result = mysqli_query($link, "SELECT * FROM `pages` WHERE `name`='$ThisPageName'") or die (mysqli_error($link));
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if ($row['minrole'] == NULL) die ('Ошибка при получении доступа! Обратитесь к администратору для настройки базы');
if ($UserRole <= $row['minrole']) $paccess = true;
	
if ($row['specrole']) {
		$specroles = unserialize($row['specrole']);
		foreach ($specroles as $key => $value)
		{
			if ($value == $UserRole) $paccess = true;
		}
}
	
if (!$paccess)
	die ('Вы не имеете доступа к этой странице!<br/>Вероятно, вы не вошли в систему, или ваш уровень доступа не позволяет вам просматривать содержимое этой страницы.');

if ($_POST["action"] == "showedit" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "SELECT * FROM `basicblocks` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="basicblocks_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=id value="<?php echo $row['id']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Название:</td>
	<td><input type=text name=name value="<?php echo $row['name']; ?>" maxlength=30 required=required></input></td>
</tr>
<tr>
	<td>Значение:</td>
	<td>
		<textarea class=content name=content><?php echo $row['content']; ?></textarea>
		<br/>
		<input class=showeditorbutton type=button value="Показать редактор" onClick="showeditor();"></input>
		<input class=showimguploadbutton type=button value="Загрузить изображение" onClick="showimgupload();"></input>
	</td>
</tr>
<tr id="tr_imgupload" style="display: none;">
	<td>Медиафайл:</td>
	<td><input type="file" id="js-file" onChange="imgupload();"></input><div id="result"></div></td>
</tr>
<tr>
	<td>Описание:</td>
	<td><input type=text name=description value="<?php echo $row['description']; ?>"></input></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input type=submit value="Сохранить изменения"></input></td>
</tr>
</table>
</form>
<?php
}

if ($_POST["action"] == "edit" && $paccess) {
	$eid = $_POST["id"];
	$name = $_POST["name"];
	$content = ($_POST["content"]) ? $_POST["content"] : NULL;
	$description = ($_POST["description"]) ? $_POST["description"] : NULL;	
	
	$result = mysqli_query($link, "UPDATE `basicblocks` 	SET 	`name`='$name', 
																	`content`='$content', 
																	`description`='$description' 
															WHERE 	`id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("basicblocks.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showdelete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "SELECT * FROM `basicblocks` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="basicblocks_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=id value="<?php echo $row['id']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить блок с названием <br/> <?php echo $row["name"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "DELETE FROM `basicblocks` WHERE `id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("basicblocks.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showadd" && $paccess) {
?>
<form action="basicblocks_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<table class=edittable>
<tr>
	<td>Название:</td>
	<td><input type=text name=name value="" maxlength=30 required=required></input></td>
</tr>
<tr>
	<td>Значение:</td>
	<td>
		<textarea class=content name=content></textarea>
		<br/>
		<input class=showeditorbutton type=button value="Показать редактор" onClick="showeditor();"></input>
		<input class=showimguploadbutton type=button value="Загрузить изображение" onClick="showimgupload();"></input>
	</td>
</tr>
<tr id="tr_imgupload" style="display: none;">
	<td>Медиафайл:</td>
	<td><input type="file" id="js-file" onChange="imgupload();"></input><div id="result"></div></td>
</tr>
<tr>
	<td>Описание:</td>
	<td><input type=text name=description value=""></input></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input type=submit value="Добавить"></input></td>
</tr>
</table>
</form>
<?php
}

if ($_POST["action"] == "add" && $paccess) {
	$name = $_POST["name"];
	$content = ($_POST["content"]) ? $_POST["content"] : NULL;
	$description = ($_POST["description"]) ? $_POST["description"] : NULL;	

	$result = mysqli_query($link, "INSERT INTO `basicblocks` (`name`, `content`, `description`) VALUES ('$name', '$content', '$description');") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("basicblocks.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}
?>