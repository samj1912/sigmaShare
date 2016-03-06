<?php
include('session.php');
// include('dlsecure.php');
?>
<?php
$username = $_SESSION['login_user'];

$targetFolder="../uploads/$username/";
// echo "Private \n";
echo "<div class=\"container\">
 
  <button style = \"margin-top:20px;float:right; margin-right=30px;clear:both;\"class = \"btn btn-primary\" name=\"profileButton\" type=\"button\" onclick=\"location.href='profile.php';\">Go Back to your Profile!</button> 
  
   <h1>Your Uploads</h1>";
display($targetFolder."private/",$username,2,"Private");
// echo "Public \n";
display($targetFolder."public/",$username,0,"Public");
// echo "Unlisted \n";
display($targetFolder."unlisted/",$username,1,"Unlisted");
echo "</div>";
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    
    $bytes /= pow(1024, $pow); 
    
    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
function getFileType($extension)
{
    $images = array('jpg', 'gif', 'png', 'bmp');
    $docs   = array('txt', 'rtf', 'doc');
    $apps   = array('zip', 'rar', 'exe');
     
    if(in_array($extension, $images)) return "Images";
    if(in_array($extension, $docs)) return "Documents";
    if(in_array($extension, $apps)) return "Applications";
    return "";
}

function display($targetFolder,$username,$per,$tname)
{
	$uploaded_files = "
<div class=\"container\">
  <h2>$tname</h2>
  
  <table class=\"table table-hover table-responsive\">
    <thead>
      <tr>
        <th>File</th>
        <th>Size</th>
        <th>Date Added</th>
        <th>Expiry Date</th>
        <th>Link</th>

      </tr>
    </thead>
    <tbody>";
	 // download('../uploads/test/private/1445520397_AmritapuriOnlineContest.pdf', 'Amrita');
	//Open directory for reading
	$dh = opendir($targetFolder);
	$c=0;
	//LOOP through the files
	while (($file = readdir($dh)) !== false) 
	{
		if($file != '.' && $file != '..')
		{
			$c+=1;
			$filename = $targetFolder.$file;
			$parts = explode("_", $file);
			$size = formatBytes(filesize($filename));
			$added = date("H:i:s d/m/Y", $parts[0]);
			$expirydate = date("H:i:s d/m/Y", $parts[3]);
			$origName = $parts[1];
			$filetype = getFileType(substr($file, strlen($file) - 3));
			// $url = "document.write('&lt?php download($filename,$origName)?&gt')";

			// $uploaded_files .= "<li class=\"$filetype\"><a href=\"download.php?u=$username&f=$file&p=$per\" >$origName</a> $size - $added</li>\n";
			if ($added!=$expirydate)

			$uploaded_files .= "<tr>
        <td>$origName</td>
        <td>$size</td>
        <td>$added</td>
        <td>$expirydate</td>
  
        <td><a href = 'download.php?u=$username&f=$file&p=$per';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
      </tr>";
      else
      		$uploaded_files .= "<tr>
        <td>$origName</td>
        <td>$size</td>
        <td>$added</td>
        <td>No Expiry</td>

        <td><a href = 'download.php?u=$username&f=$file&p=$per';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
      </tr>";

		}
	}
	

	if( $c == 0)
	{
	    $uploaded_files .= "<tr>
        <td>No Files</td>
        <td></td>
        <td></td>
        <td></td>
  
        <td></td>
      </tr>";
	}
$uploaded_files.="</tbody></table></div>";
	closedir($dh);
	echo $uploaded_files; 

}
?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

</head>
<body>



</body>
</html>	