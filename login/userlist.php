<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<form action = "userlist.php" method="post" >
Search Users:<input type="text" name="name" >
 <input type="submit" name="submit" value="Submit"> 

</form>

</body>
</html>

 <?php 
 $search = '';

 if (isset($_POST['submit']))
 {
 	$search = $_POST['name'];
 
 $dir = "../uploads/$search*";

// 	// Open a known directory, and proceed to read its contents
	

	$count = 0;	
	echo "<br>Search Results:<br>";
		foreach(glob($dir,GLOB_ONLYDIR) as $file) 
	{

		$r = explode('/', $file);
		$disp = $r[2];
		if ($disp!='common' && $disp!='public')
		{echo " <a href = displaypub.php?u=$disp>"."\t$disp"."</a>"."<br />";
		
		$count++;}
	}
	// echo $count;
	if (!$count)
		echo "No users found";	}
	?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<br>
<br>
<br>
<br>
<br>
<br>

				<button name="loginButton" type="button" onclick="location.href='index.php';">Go Back to Login!</button>


</form>

</body>
</html>	