<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$link = $GLOBALS['mysql_oldstyle_link'];
$ThisPageName = 'users';

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
	$result = mysqli_query($link, "SELECT * FROM `users` WHERE `uid`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="users_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=uid value="<?php echo $row['uid']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Логин:</td>
	<td><input type=text name=username value="<?php echo $row['username']; ?>" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td>Пароль:</td>
	<td><input type=button id=pass1 value="Сменить" style="text-align: left;" onClick="changepass();"></input>
	<input type=text id=pass2 name=password value="" maxlength=20 disabled=disabled required=required style="display: none;"></input></td>
</tr>
<tr>
	<td>Имя:</td>
	<td><input type=text name=showname value="<?php echo $row['showname']; ?>" maxlength=30 required=required></input></td>
</tr>
<tr>
	<td style="padding-top: 0.5em;">Уровень доступа:<br/>
	<?php
	$trole = $row['role'];
	$resultR = mysqli_query($link, "SELECT * FROM `roles` WHERE `value`='$trole'") or die (mysqli_error($link));
	$rowR = mysqli_fetch_array($resultR, MYSQLI_ASSOC);
	if (!$rowR)
		$norole = true;
	else
		$norole = false;
	?>
	<div id="toggles">
		<input type="checkbox" name="checkbox1" id="checkbox3" class="ios-toggle" <?php if (!$norole) echo("checked"); ?> onChange="changerole();"></input>
		<label for="checkbox3" class="checkbox-label" data-off="Цифры" data-on="Список"></label>
	</div>
	</td>
	<td style="vertical-align: top;">
		<select id=role1 name=role required=required <?php if ($norole) echo ('disabled=disabled style="display: none;"'); ?>>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>" <?php if (!$norole && $rowroles['value'] == $trole) echo "selected"?>><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
		<input id=role2 type=text name=role value="<?php echo $row['role']; ?>" maxlength=3 required=required <?php if (!$norole) echo('disabled=disabled style="display: none;"') ?>></input>
	</td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input type=submit value="Сохранить изменения"></input></td>
</tr>
</table>
</form>
<?php
}

if ($_POST["action"] == "edit" && $paccess) {
	$eid = $_POST["uid"];
	echo $_POST["username"];
	$username = $_POST["username"];
	$showname = $_POST["showname"];
	$role = $_POST["role"];
	if ($_POST["password"])
		$password = md5($_POST["password"]);

	if (!$password)
		$result = mysqli_query($link, "UPDATE `users` 	SET 	`username`='$username', 
																`showname`='$showname', 
																`role`='$role' 
														WHERE 	`uid`='$eid'") or die (mysqli_error($link));
	else
		$result = mysqli_query($link, "UPDATE `users` SET `username`='$username', `showname`='$showname', `role`='$role', `password`='$password' WHERE `uid`='$eid'") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("users.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showdelete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "SELECT * FROM `users` WHERE `uid`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if ($UserID == $row['uid'])
		echo ("Вы не можете удалить свою учетную запись!");
	else {
?>
<form action="users_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=uid value="<?php echo $row['uid']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить пользователя с логином <br/> <?php echo $row["username"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
	}
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["uid"];
	$result = mysqli_query($link, "DELETE FROM `users` WHERE `uid`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("users.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showadd" && $paccess) {
?>
<form action="users_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<table class=edittable>
<tr>
	<td>Логин:</td>
	<td><input type=text name=username value="" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td>Пароль:</td>
	<td><input type=text name=password value="" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td>Имя:</td>
	<td><input type=text name=showname value="" maxlength=30 required=required></input></td>
</tr>
<tr>
	<td style="padding-top: 0.5em;">Уровень доступа:<br/>
	<div id="toggles">
		<input type="checkbox" name="checkbox1" id="checkbox3" class="ios-toggle" checked onChange="changerole();"></input>
		<label for="checkbox3" class="checkbox-label" data-off="Цифры" data-on="Список"></label>
	</div>
	</td>
	<td style="vertical-align: top;">
		<select id=role1 name=role required=required>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>"><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
		<input id=role2 type=text name=role value="" maxlength=3 required=required disabled=disabled style="display: none;"></input>
	</td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input type=submit value="Добавить"></input></td>
</tr>
</table>
</form>
<?php
}

if ($_POST["action"] == "add" && $paccess) {
	//$eid = $_POST["uid"];
	//echo $_POST["username"];
	$username = $_POST["username"];
	$showname = $_POST["showname"];
	$role = $_POST["role"];
	$password = md5($_POST["password"]);
	$result = mysqli_query($link, "INSERT INTO `users` (`username`, `showname`, `role`, `password`) VALUES ('$username', '$showname', '$role', '$password');") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("users.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}
?>