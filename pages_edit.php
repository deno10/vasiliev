<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'pages';
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
	$result = mysqli_query($link, "SELECT * FROM `pages` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="pages_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=id value="<?php echo $row['id']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Идентификатор:</td>
	<td><input type=text name=name value="<?php echo $row['name']; ?>" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td>Название:</td>
	<td><input type=text name=showname value="<?php echo $row['showname']; ?>" maxlength=50 required=required></input></td>
</tr>
<tr>
	<td>Адрес:</td>
	<td><input type=text name=address value="<?php echo $row['address']; ?>" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td style="padding-top: 0.5em;">Минимальная роль:<br/>
	<?php
	$trole = $row['minrole'];
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
		<select id=role1 name=minrole required=required <?php if ($norole) echo ('disabled=disabled style="display: none;"'); ?>>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>" <?php if (!$norole && $rowroles['value'] == $trole) echo "selected"?>><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
		<input id=role2 type=text name=minrole value="<?php echo $row['minrole']; ?>" maxlength=3 required=required <?php if (!$norole) echo('disabled=disabled style="display: none;"') ?>></input>
	</td>
</tr>
<tr>
	<td>Спецправа:</td>
	<?php
	$specroles = NULL;
	if ($row['specrole']) {
		$specroles = unserialize($row['specrole']);
	}
	?>
	<td>
		<select name="specrole[]" multiple>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>" 
			<?php
			if ($specroles)
				foreach ($specroles as $key => $value) {
					if ($value == $rowroles['value']) echo "selected";
				}
			?>
			><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
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
	$eid = $_POST["id"];
	$name = $_POST["name"];
	$showname = $_POST["showname"];
	$address = $_POST["address"];
	$minrole = $_POST["minrole"];
	if ($_POST["specrole"])
		$specrole = serialize($_POST["specrole"]);
	else
		$specrole = NULL;
	$result = mysqli_query($link, "UPDATE `pages` 	SET 	`name`='$name',
															`showname`='$showname', 
															`address`='$address', 
															`minrole`='$minrole', 
															`specrole`='$specrole'
													WHERE 	`id`='$eid';") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("pages.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showdelete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "SELECT * FROM `pages` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="pages_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=id value="<?php echo $row['id']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить страницу с идентификатором <br/> <?php echo $row["name"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "DELETE FROM `pages` WHERE `id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("pages.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showadd" && $paccess) {
?>
<form action="pages_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<table class=edittable>
<tr>
	<td>Идентификатор:</td>
	<td><input type=text name=name value="" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td>Название:</td>
	<td><input type=text name=showname value="" maxlength=50 required=required></input></td>
</tr>
<tr>
	<td>Адрес:</td>
	<td><input type=text name=address value="" maxlength=20 required=required></input></td>
</tr>
<tr>
	<td style="padding-top: 0.5em;">Минимальная роль:<br/>
	<div id="toggles">
		<input type="checkbox" name="checkbox1" id="checkbox3" class="ios-toggle" checked onChange="changerole();"></input>
		<label for="checkbox3" class="checkbox-label" data-off="Цифры" data-on="Список"></label>
	</div>
	</td>
	<td style="vertical-align: top;">
		<select id=role1 name=minrole required=required>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>"><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
		<input id=role2 type=text name=minrole value="<?php echo $row['role']; ?>" maxlength=3 required=required disabled=disabled style="display: none;"></input>
	</td>
</tr>
<tr>
	<td>Спецправа:</td>
	<td>
		<select name="specrole[]" multiple>
			<?php
			$result = mysqli_query($link, "SELECT * FROM `roles`") or die (mysqli_error($link));
			while ($rowroles = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			?>
			<option value="<?php echo $rowroles['value']; ?>"><?php echo $rowroles['name']; ?></option>
			<?php
			}
			?>
		</select>
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
	$name = $_POST["name"];
	$showname = $_POST["showname"];
	$address = $_POST["address"];
	$minrole = $_POST["minrole"];
	if ($_POST["specrole"])
		$specrole = serialize($_POST["specrole"]);
	else
		$specrole = NULL;
	$result = mysqli_query($link, "INSERT INTO `pages` 	(`name`, `showname`, `address`, `minrole`, `specrole`) VALUES 
														('$name', '$showname', '$address', '$minrole', '$specrole');") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("pages.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}
?>