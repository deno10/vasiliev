<?php
	session_start();
	include_once('connect.php');
	include('auth.php');
	$link = $GLOBALS['mysql_oldstyle_link'];
	if (isset($_POST['user']) && isset($_POST['pass']))
	{
		$Luser = mysqli_real_escape_string($link, stripslashes($_POST['user']));
		$Lpass = md5(mysqli_real_escape_string($link, stripslashes($_POST['pass'])));
		if (login($Luser, $Lpass))
		{
			//header('Refresh: 3');
			die('1');
		}
		else
		{
			//header('Refresh: 3');
			die('2');
		}
	}
?>