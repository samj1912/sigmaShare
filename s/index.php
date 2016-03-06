<?php

if (isset($_GET['s']))
{
	
	$shortlink = $_GET['s'];
	echo $shortlink;
	$connection = mysql_connect("localhost", "root", "pikachu");
	$db = mysql_select_db("cloud", $connection);
	$query = "select * from shortner where short='$shortlink'";
	if($query_run = mysql_query($query,$connection))
	{
		if (mysql_num_rows($query_run)==0)
		{
		
			die ("No such Link");
		}
		else
		{
			$row = mysql_fetch_assoc($query_run);
			$link = $row['link'];
			header("Location: $link");
		}
	}
	else
		die("Wrong query");


}
else
{
die ("Invalid Link");
}

?>