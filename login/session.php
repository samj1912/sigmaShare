
<?php
	$connection = mysql_connect("localhost", "root", "pikachu");
	$db = mysql_select_db("cloud", $connection);
	session_start();
	$user_check=$_SESSION['login_user'];
	$ses_sql=mysql_query("select username from users where username='$user_check'", $connection);
	$row = mysql_fetch_assoc($ses_sql);
	$login_session =$row['username'];
	if(!isset($login_session))
	{
		mysql_close($connection); 
		header('Location: index.php'); 
	}
?>