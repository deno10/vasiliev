<?php
session_start();

include_once('engine/connect.php');
include_once('engine/uni-auth.php');

$ThisPageName = 'index';

if(USER_LOGGED)
{
	if (!check_user($UserID)) logout();
?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf8">
	</head>
	<body>
	<h1>Здравствуйте, <?php echo $UserShowName; ?>!</h1>
	<h2>Ваш ID: <?php echo $UserID; ?>. </h2>
	<h3><a href="?logout">Выход</a></h3>
	<h4><a href="pages.php">Список всех исполняемых страниц</a></h4>
<?php
}
else
{
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf8">
	<script src="../js/ajaxlogin.js" language="javascript" type="text/javascript"></script>	
	</head>
	<body>
	Вам необходимо авторизоваться!
	
	<form method="POST" onsubmit="callServer(); return false;">
		<p>Логин: <input type="text" name="login" id="login" size="25"/></p>
		<p>Пароль: <input type="password" name="pass" id="pass" size="25"/></p>
		<p><input type="button" value="Войти" onClick = "callServer()"/></p>
		<div name="imgload" id="imgload"></div>
	</form>
	
	<!--
    <form method="POST" action="--><?php /*echo $_SERVER['PHP_SELF'];*/ ?><!--">
    <table>
    <tr>
    <td>Имя:</td><td><input type="text" name="user"></td>
    </tr>
    <tr>
    <td>Пароль:</td><td><input type="password" name="pass"></td>
    </tr>
    <tr>
    <td colspan="2"><input type="submit" name="login" value="Войти"></td>
    </tr>
    </table>
    </form-->
<?php
}
?>
</body>
</html>