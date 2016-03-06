<?php

session_start();
$userauth='';
if (isset($_SESSION['login_user']))
{
  $userauth=$_SESSION['login_user'];
}

$user = $_GET['u'];
$p = $_GET['p'];
$fname = $_GET['f'];
$curr=time();


$file_path;
$auth=1;
$link_path;

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


// file size in bytes
  // $fsize = filesize($file_path); 

// file extension
  $fext = strtolower(substr(strrchr($fname,"."),1));

// check if allowed extension

// get mime type


// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.
  $arr = explode('_', $_GET['f']);
  $passcheck = $arr[2];
  $downloadTime = $arr[3];
  $uploadTime = $arr[0];
if (isset($file_path) && $auth)
{
  if($downloadTime<$curr && $downloadTime!=$uploadTime)
  {
    if(is_file($file_path))
    {
      unlink($file_path);
      if($p==0)
      {
        unlink("../uploads/public/$user"."_"."$fname");
        echo "../uploads/public/$user"."_"."$fname";
      }
    }
    die("The link has expired");
  }

  if (!is_file($file_path)) {
    die("$file_path does not exist. Make sure you specified correct link to the file."); 
  }
  if($passcheck==1)
  {
    header("Location: passcheck.php?u=$user&f=$fname&p=$p");
  }
  $asfname=$arr[1];
  // if($user=='')
  // {
  //     $asfname=$arr[2];
  // }
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
  exit;
  
}

?>
