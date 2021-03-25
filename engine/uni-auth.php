<?php
include_once('connect.php');

##константы
define('USERS_TABLE', 'users');
define('SID', session_id());

##функции
//функция выхода
function logout()
{
	unset($_SESSION['uid']);
	session_destroy();
	die('<script>document.location.replace("'.$_SERVER['PHP_SELF'].'");</script>');		//ПЕРЕПИСАТЬ ЭТУ СТРОКУ! (сделать без хидеров) ОБЯЗАТЕЛЬНО! ДЕНИС, 17 СЕНТЯБРЯ   //19 сентября вроде переписал
}

//Проверка залогиненности пользователя
function check_user($uid)
{
	$link = $GLOBALS['mysql_oldstyle_link'];
	$result = mysqli_query($link, "SELECT `sid` FROM `".USERS_TABLE."` WHERE `uid`='$uid';")
		or die (mysql_error($link));
	mysqli_data_seek($result, 0);
	$sid = mysqli_fetch_row($result)[0];
	return $sid==SID ? true : false;
}

##Действие если пользователь авторизован
if (isset($_SESSION['uid'])) //если была произведена авторизация, то в сессии есть uid
{
	define('USER_LOGGED', true);
	$UserName = $_SESSION['username'];
	$UserShowName = $_SESSION['showname'];
	$UserPass = $_SESSION['password'];
	$UserID = $_SESSION['uid'];
	$UserRole = $_SESSION['role'];
}
else
{
	define('USER_LOGGED', false);
}

##Действие при попытке выхода
if (isset($_GET['logout']))
{
	logout();
}
?>