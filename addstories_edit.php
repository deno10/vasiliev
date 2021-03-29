<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'addstories';
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
	$result = mysqli_query($link, "SELECT * FROM `stories` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>

<form action="addstories_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=id value="<?php echo $row['id']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Изображение (превью):</td>
	<td><img src="uploads/<?php echo $row['image']; ?>" style="max-height: 250px;"/></td>
</tr>
<tr>
	<td>URL видео:</td>
	<td><input type=text name=video value="<?php echo $row['video']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Пин:</td>
	<td>
		<!--<input type=text name=pin value="" required=required></input>-->
		<select name=pin required=required>
		<?php
		$pinresult = mysqli_query($link, "SELECT * FROM `pins`") or die (mysqli_error($link));
		while ($pinrow = mysqli_fetch_array($pinresult, MYSQLI_ASSOC)) {
		?>
			<option value="<?php echo $pinrow['id']; ?>"<?php if ($pinrow['id'] == $row['pin']) echo (' selected=selected')?>><?php echo $pinrow['name']; ?></option>
		<?php
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td>Время</td>
	<td><input type=text name=time value="<?php echo $row['time']; ?>"></input></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input class="submitbutton" type=submit value="Сохранить изменения"></input></td>
</tr>
</table>
</form>
<?php
}

if ($_POST["action"] == "edit" && $paccess) {
	$eid = $_POST["id"];
	$time = $_POST["time"];
	$pin = $_POST["pin"];
	
	$result = mysqli_query($link, "UPDATE `stories` 	SET 	`time`='$time', 
																`pin`='$pin'
														WHERE 	`id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("addstories.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showdelete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "SELECT * FROM `stories` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="addstories_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=id value="<?php echo $row['id']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить сторис с id <br/> <?php echo $row["id"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "DELETE FROM `stories` WHERE `id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("addstories.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showadd" && $paccess) {
?>

<form action="addstories_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<table class=edittable>
<tr>
	<td>Видео:</td>
	<td><input type="file" id="js-file" onChange="imgupload();"></input><div id="result"></div></td>
</tr>
<tr>
	<td>URL видео:</td>
	<td><input type=text name=video id="video" value="" required=required></input></td>
</tr>
<tr>
	<td>URL превью:</td>
	<td><input type=text name=image id="image" value="" required=required></input></td>
</tr>
<tr>
	<td>Пин:</td>
	<td>
		<!--<input type=text name=pin value="" required=required></input>-->
		<select name=pin required=required>
		<?php
		$pinresult = mysqli_query($link, "SELECT * FROM `pins`") or die (mysqli_error($link));
		while ($pinrow = mysqli_fetch_array($pinresult, MYSQLI_ASSOC)) {
		?>
			<option value="<?php echo $pinrow['id']; ?>"><?php echo $pinrow['name']; ?></option>
		<?php
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td>Время:</td>
	<td><input type=text name=time value=""></input></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input class="submitbutton" type=submit value="Добавить"></input></td>
</tr>
</table>
</form>

<?php
}

if ($_POST["action"] == "add" && $paccess) {
	$video = $_POST["video"];
	$image = $_POST["image"];
	$pin = $_POST["pin"];
	$time = ($_POST["time"]) ? $_POST["time"] : NULL;

	$result = mysqli_query($link, "INSERT INTO `stories` (`video`, `image`, `pin`, `time`) VALUES ('$video', '$image', '$pin', '$time');") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("addstories.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}
?>