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
	die(header('Location: '.$_SERVER['PHP_SELF']));
}

//функция входа
function login($username, $password)
{
	$link = $GLOBALS['mysql_oldstyle_link'];
	$result = mysqli_query($link, "SELECT * FROM `".USERS_TABLE."` WHERE `username`='$username' AND `password`='$password';")
		or die(mysqli_error($link));
	$USER = mysqli_fetch_array($result);
	if (!empty($USER)) //если существует такая пара логин-пароль
	{
		//$_SESSION = array_merge($_SESSION, $USER); //добавляем массив с пользователем к массиву сессии
		$_SESSION['uid'] = $USER['uid'];
		$_SESSION['username'] = $USER['username'];
		$_SESSION['showname'] = $USER['showname'];
		$_SESSION['password'] = $USER['password'];
		$_SESSION['role'] = $USER['role'];
		$_SESSION['sid'] = $USER['sid'];
		mysqli_query($link, "UPDATE `".USERS_TABLE."` SET `sid`='".SID."' WHERE `uid`='".$USER['uid']."';")
			or die(mysqli_error($link));
		return true;
	}
	else
	{
		return false;
	}
}

//Проверка залогиненности пользователя
function check_user($uid)
{
	$link = $GLOBALS['mysql_oldstyle_link'];
	$result = mysqli_query($link, "SELECT `sid` FROM `".USERS_TABLE."` WHERE `uid`='$uid';")
		or die (mysqli_error());
	mysqli_data_seek($result, 0);
	$sid = mysqli_fetch_row($result)[0];
	return $sid==SID ? true : false;
}

##Действие если пользователь авторизован
if (isset($_SESSION['uid'])) //если была произведена авторизация, то в сессии есть uid
{
	define('USER_LOGGED', true);
	$UserName = $_SESSION['username'];
	$UserPass = $_SESSION['password'];
	$UserID = $_SESSION['uid'];
}
else
{
	define('USER_LOGGED', false);
}

##Действия при попытке входа

if (isset($_POST['login']))
{
	if (get_magic_quotes_gpc())
	{
		$_POST['user'] = stripslashes($_POST['user']);
		$_POST['pass'] = stripslashes($_POST['pass']);
	}
	$user = mysqli_real_escape_string($_POST['user']);
	$pass = md5(mysqli_real_escape_string($_POST['pass']));
	if (login($user, $pass))
	{
		header('Refresh: 3');
		die('<html><head><meta charset="utf8"></head><body>Вы успешно авторизовались!');
	}
	else
	{
		header('Refresh: 3');
		die('<html><head><meta charset="utf8"></head><body>Логин или пароль неправильные!');
	}
}

##Действие при попытке выхода
if (isset($_GET['logout']))
{
	logout();
}
?>