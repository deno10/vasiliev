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
	$result = mysqli_query($link, "SELECT * FROM `posts` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>

<form action="posts_edit.php" method="post">
<input type=hidden name=action value="edit"></input>
<table class=edittable>
<tr>
	<td>ID:</td>
	<td><input type=text name=id value="<?php echo $row['id']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Изображение:</td>
	<td><img src="uploads/<?php echo $row['url']; ?>" style="max-height: 300px;"/></td>
</tr>
<tr>
	<td>URL оригинала:</td>
	<td><input type=text name=url_original value="<?php echo $row['url_original']; ?>" readonly=readonly></input></td>
</tr>
<tr>
	<td>Дата:</td>
	<td><input type=text name=date value="<?php echo $row['date']; ?>" required=required></input></td>
</tr>
<tr>
	<td>Описание:</td>
	<td><textarea class=description name=description><?php echo $row['description']; ?></textarea></td>
</tr>
<tr>
	<td>Кем лайкнуто:</td>
	<td><textarea name=liked_by><?php echo $row['liked_by']; ?></textarea></td>
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
	$date = $_POST["date"];
	$description = $_POST["description"];
	$liked_by = NULL;
	if ($_POST["liked_by"])
		$liked_by = $_POST["liked_by"];
	
	$result = mysqli_query($link, "UPDATE `posts` 	SET 	`date`='$date', 
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
	$result = mysqli_query($link, "SELECT * FROM `posts` WHERE `id`='$eid'") or die (mysqli_error($link));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
<form action="posts_edit.php" method="post">
<input type=hidden name=action value="delete"></input>
<input type=hidden name=id value="<?php echo $row['id']; ?>"></input>
<p style="text-align: center;">Вы уверены, что хотите удалить пост с id <br/> <?php echo $row["id"]; ?>?<br/></p>
<p style="text-align: center;"><input type=submit value="Удалить"></input></p>
</form>
<?php
}

if ($_POST["action"] == "delete" && $paccess) {
	$eid = $_POST["id"];
	$result = mysqli_query($link, "DELETE FROM `posts` WHERE `id`='$eid'") or die (mysqli_error($link));
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

if ($_POST["action"] == "showadd" && $paccess) {
?>

<form action="posts_edit.php" method="post">
<input type=hidden name=action value="add"></input>
<input type=hidden name=ratio id="ratio" value="1"></input>
<input type=hidden name=type id="type" value="image"></input>
<table class=edittable>
<tr>
	<td>Тип:</td>
	<td><input name="mediatype" id="mediatype" type="radio" value="image" checked=checked> Изображение<br/><input name="mediatype" id="mediatype2" type="radio" value="video"> Видео<br/><input name="mediatype" id="mediatype3" type="radio" value="carousel"> Карусель</td>
</tr>
<tr>
	<td>Медиафайл:</td>
	<td id="mediatd"><input type="file" id="js-file" onChange="imgupload();"></input><div id="result"></div></td>
</tr>
<tr>
	<td>URL:</td>
	<td id="mediaurl"><input type=text name=url id="url" value="" required=required></input></td>
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
	<td><textarea class=description name=description></textarea></td>
</tr>
<tr>
	<td>Кем лайкнуто:</td>
	<td><textarea name=liked_by></textarea></td>
</tr>
<tr>
	<td colspan=2 style="text-align: center;"><input class="submitbutton" type=submit value="Добавить"></input></td>
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
	$ratio = $_POST["ratio"];
	$type = $_POST["type"];
	$liked_by = NULL;
	if ($_POST["liked_by"])
		$liked_by = $_POST["liked_by"];
	
	if ($type == "carousel") {
		$urlnew = array($url);
		$i = 1;
		while (isset($_POST["url" . $i])) {
			array_push($urlnew, $_POST["url" . $i]);
			$i = $i + 1;
		}
		$url = json_encode($urlnew);
	}

	$result = mysqli_query($link, "INSERT INTO `posts` (`url`, `url_original`, `date`, `description`, `liked_by`, `ratio`, `type`) VALUES ('$url', '$url_original', '$date', '$description', '$liked_by', '$ratio', '$type');") or die (mysqli_error($link));
	
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