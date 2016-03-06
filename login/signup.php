<?php

	header('Content-Type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

	echo '<response>';
	$username=$_POST['username'];
	$password=$_POST['password'];
	$connection = mysql_connect("localhost", "root", "pikachu");
	$db = mysql_select_db("cloud", $connection);
	$query=mysql_query("select username from users where username='$username'", $connection);
	$row = mysql_num_rows($query);
	if($row==0)
	{
		$query="insert into users (username,password) values('$username', '$password')";
		if(mysql_query($query))
		{
			session_start();
			$_SESSION['login_user']=$username;
			mkdir("../uploads/$username",0777);
			mkdir("../uploads/$username/public",0777);
			mkdir("../uploads/$username/private",0777);
			mkdir("../uploads/$username/unlisted",0777);
			echo "OK";
		}
	}
	else
	{
		echo 'Username already exists';
	}
	echo '</response>';
?>