<?php

	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';

	$username=$_POST['username'];
	$password=$_POST['password'];
	$connection = mysql_connect("localhost", "root", "pikachu");
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	$db = mysql_select_db("cloud", $connection);
	$query = mysql_query("select * from users where password='$password' AND username='$username'", $connection);
	$rows = mysql_num_rows($query);
	if ($rows == 1)
	{
		session_start();
		$_SESSION['login_user']=$username;
		echo "OK";
	} 
	else 
	{
		echo "Username or Password is invalid";
	}
	mysql_close($connection); 
	echo '</response>';
?>