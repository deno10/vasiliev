<?php
session_start();

include_once('engine/connect.php');
include('engine/uni-auth.php');

$ThisPageName = 'posts';
$link = $GLOBALS['mysql_oldstyle_link'];

if(USER_LOGGED)	{
		if (!check_user($UserID)) logout();
}

if (!USER_LOGGED) $UserRole = 63;
$paccess = false;
	
$result = mysqli_query($link, "SELECT * FROM `pages` WHERE `name`='$ThisPageName'") or die (mysqli_error($link));
$row = mysqli_fetch_array($result, MYSQL_ASSOC);
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
	$result = mysqli_query($link, "SELECT * FROM `posts` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQL_ASSOC);
?>

<form action="posts_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=id value="<?php echo $row['id']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Дата:</td>
	<td><input type=text name=date value="<?php echo $row['date']; ?>" required=required></input></td>
</tr>
<tr>
	<td>URL:</td>
	<td><input type=text name=url value="<?php echo $row['url']; ?>" required=required></input></td>
</tr>
<tr>
	<td>URL оригинала:</td>
	<td><input type=text name=url_original value="<?php echo $row['url_original']; ?>" required=required></input></td>
</tr>
<tr>
	<td>Описание:</td>
	<td><textarea name=description><?php echo $row['description']; ?></textarea></td>
</tr>
<tr>
	<td>Кем лайкнуто:</td>
	<td><textarea name=liked_by><?php echo $row['liked_by']; ?></textarea></td>
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
	$date = $_POST["date"];
	$url = $_POST["url"];
	$url_original = $_POST["url_original"];
	$description = $_POST["description"];
	$liked_by = NULL;
	if ($_POST["liked_by"])
		$liked_by = $_POST["liked_by"];
	
	$result = mysqli_query($link, "UPDATE `posts` 	SET 	`date`='$date', 
															`url`='$url', 
															`url_original`='$url_original',
															`description`='$description',
															`liked_by`='$liked_by'
													WHERE 	`id`='$eid'") or die (mysqli_error($link));
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("posts.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showdelete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysql_query("SELECT * FROM `sections` WHERE `id`='$eid'") or die (mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
?>
<form action="sections_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=id value="<?php echo $row['id']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить секцию с алиасом <br/> <?php echo $row["alias"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysql_query("DELETE FROM `sections` WHERE `id`='$eid'") or die (mysql_error());
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("sections.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}

if ($_POST["action"] == "showadd" && $paccess) {
?>

<form action="posts_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<table class=edittable>
<tr>
	<td>Изображение:</td>
	<td><input type="file" id="js-file" onChange="imgupload();"></input><div id="result"></div></td>
</tr>
<tr>
	<td>URL:</td>
	<td><input type=text name=url id="url" value="" required=required></input></td>
</tr>
<tr>
	<td>URL оригинала:</td>
	<td><input type=text name=url_original id="url_original" value="" required=required></input></td>
</tr>
<tr>
	<td>Дата:</td>
	<td><input type=text name=date value="" required=required></input></td>
</tr>
<tr>
	<td>Описание:</td>
	<td><textarea name=description></textarea></td>
</tr>
<tr>
	<td>Кем лайкнуто:</td>
	<td><textarea name=liked_by></textarea></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input type=submit value="Добавить"></input></td>
</tr>
</table>
</form>

<?php
}

if ($_POST["action"] == "add" && $paccess) {
	$url = $_POST["url"];
	$url_original = $_POST["url_original"];
	$date = $_POST["date"];
	$description = $_POST["description"];
	$liked_by = NULL;
	if ($_POST["liked_by"])
		$liked_by = $_POST["liked_by"];

	$result = mysqli_query($link, "INSERT INTO `posts` (`url`, `url_original`, `date`, `description`, `liked_by`) VALUES ('$url', '$url_original', '$date', '$description', '$liked_by');") or die (mysqli_error($link));
	
	if ($result) {
		echo "Удачно";
		$redirect = '<script type="text/javascript">
		location.replace("posts.php");
		</script>';
		echo $redirect;
	}
	else
		echo $result;
}
?>