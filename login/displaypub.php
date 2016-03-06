<?php
// include('session.php');
// include('dlsecure.php');
?>
<?php
$username = $_GET['u'];

if($username=='')
{
	$targetFolder="../uploads/public/";
	echo "<div class= 'container'><h1>All public files</h1>";
	displayAll($targetFolder);
	echo "</div>";
}
else
{
	$targetFolder="../uploads/$username/";

	// echo "$username's files\n";
	display($targetFolder."public/",$username);
}

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

function displayAll($targetFolder)
{
	$uploaded_files = "
<div class=\"container\">

  <table class=\"table table-hover table-responsive\">
    <thead>
      <tr>
        <th>File</th>
        <th>Uploaded By</th>
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
	// $dh = opendir($targetFolder);
	//LOOP through the files
	while (($file = readdir($dh)) !== false) 
	{
		if($file != '.' && $file != '..')
		{
			$filename = $targetFolder.$file;
			$parts = explode("_", $file);
			$user = $parts[0];
			$file_location="../uploads/$user/public/$parts[1]_$parts[2]_$parts[3]_$parts[4]";
			$size = formatBytes(filesize($file_location));
			$added = date("H:i:s d/m/Y", $parts[1]);
			$expirydate = date("H:i:s d/m/Y", $parts[4]);
			$origName = $parts[2];
			$filetype = getFileType(substr($file, strlen($file) - 3));
			$actualFile = "$parts[1]_$parts[2]_$parts[3]_$parts[4]	";
			// $uploaded_files .= "<li class=\"$filetype\"><a href=\"download.php?u=$user&f=$actualFile&p=0\" >$origName</a> $user $size $added</li>\n";
		
						if ($added!=$expirydate)

						$uploaded_files .= "<tr>
			        <td>$origName</td>
			        <td>$user</td>
			        <td>$size</td>
			        <td>$added</td>
			        <td>$expirydate</td>
			  
			        <td><a href = 'download.php?u=$user&f=$actualFile&p=0';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
			      </tr>";
			      else
			      		$uploaded_files .= "<tr>
			        <td>$origName</td>
			        <td>$user</td>
			        <td>$size</td>
			        <td>$added</td>
			        <td>No Expiry</td>

			        <td><a href = 'download.php?u=$user&f=$actualFile&p=0';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
			      </tr>";

		}

	}
	closedir($dh);

	if(strlen($uploaded_files) == 0)
	{
	    $uploaded_files = "<li><em>No files found</em></li>";
	}

	echo $uploaded_files; 

}

function display($targetFolder,$username)
{
	$uploaded_files = "
<div class=\"container\">
<h1>$username 's files</h1>
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
    $c=0;
	 // download('../uploads/test/private/1445520397_AmritapuriOnlineContest.pdf', 'Amrita');
	//Open directory for reading
	$dh = opendir($targetFolder);
	//LOOP through the files
	while (($file = readdir($dh)) !== false) 
	{
		if($file != '.' && $file != '..')
		{
			$filename = $targetFolder.$file;
			$parts = explode("_", $file);
			
			$c+=1;
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
  
        <td><a href = 'download.php?u=$username&f=$file&p=0';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
      </tr>";
      else
      		$uploaded_files .= "<tr>
        <td>$origName</td>
        <td>$size</td>
        <td>$added</td>
        <td>No Expiry</td>

        <td><a href = 'download.php?u=$username&f=$file&p=0';\" type=\"button\" class=\"btn btn-primary\">Download</a></td>
      </tr>";


			// $uploaded_files .= "<li class=\"$filetype\"><a href=\"download.php?u=$username&f=$file&p=0\" >$origName</a> $size $added</li>\n";
		}
	}
	closedir($dh);

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