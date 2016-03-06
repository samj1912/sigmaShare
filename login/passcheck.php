
 
<?php
session_start();
$userauth='';



if (isset($_SESSION['login_user'])){
$userauth =$_SESSION['login_user'];}

$user = $_GET['u'];


$file_path;
$auth =1;
$p = $_GET['p'];
$fname = $_GET['f'];

echo 
"<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form method='post' action='passcheck.php?u=$user&f=$fname&p=$p' enctype='multipart/form-data'>
	<p><label for='password'>Enter the password</label><br />
	<input type = 'password' name='password' value='' placeholder='*******' /></p>
	<input type='submit' name='submit' value='Submit' />
</form> 
</body>
</html>";

if($user=='')
{
  $file_path = "../uploads/common/$fname";
}
else if ($p==0)
{
  $file_path = "../uploads/$user/public/$fname";
}
else if ($p==1)
{
  $file_path = "../uploads/$user/unlisted/$fname";

}
else if($p==2)
{
  if ($userauth!=$user)
  {
    $auth = 0;
    echo "File Not available";
  }
  else 
  {
    $file_path = "../uploads/$user/private/$fname";

  }

}

 $arr = explode('_', $_GET['f']);
  
 $asfname=$arr[1];
 
if (isset($_POST['submit']))
{
	$pass = $_POST['password'];
	$connection = mysql_connect("localhost", "root", "pikachu");
	$db = mysql_select_db("cloud", $connection);
	$query = "select * from filepass where filename='$fname' and password='$pass'";
	if($query_run = mysql_query($query,$connection))
	{
		if (mysql_num_rows($query_run)==0)
		{
			$auth = 0;
			die ("Wrong Password $file_path");
		}
	}
	else
		die("Wrong query");


}

else {
	$auth=0;
	die();
}

if (isset($file_path) && $auth)
{
  if (!is_file($file_path)) {
    die("$file_path does not exist. Make sure you specified correct link to the file."); 
  }
  $fsize = filesize($file_path); 
  $fext = strtolower(substr(strrchr($fname,"."),1));
}



 
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename=' . "$asfname");
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file_path));
  ob_clean();
  flush();
  readfile($file_path);
  echo " mar ja bsdk";
  echo "<meta http-equiv = 'Refresh' content = '2; url =index.php'/>"; 
  exit;
  ?>
