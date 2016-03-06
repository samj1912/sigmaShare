<?php

include('session.php'); 

	if(sizeof($_FILES)>0)
	{
		$fileUploader = new FileUploader($_FILES, $login_session);
	}
	class FileUploader{
		public function __construct($uploads,$login_session){
			$uploadDir='../uploads/temp/';
			$paths = explode("###",rtrim($_POST['paths'],"###"));
			$completePath = explode('/', $paths[0]);
			$uploadedFolder = $completePath[0];
			$flag=0;
			foreach($uploads as $key => $current)
			{
				$this->uploadFile=$uploadDir.rtrim($paths[$key],"/.");
				$this->folder = substr($this->uploadFile,0,strrpos($this->uploadFile,"/"));
				
				if(strlen($current['name'])!=1)
				{
					if($this->upload($current,$this->uploadFile))
						$flag=1;
					else 
					{
						$flag=0;
						break;
					}
				}
			}
			if($flag==1)
			{
				$message = "The folder $uploadedFolder has been successfully uploaded.<br>";
				$target_path = "../uploads/";

				$pass=$_POST['password'];
				$perm=$_POST['perm'];
				$expiryTime=$_POST['expiryTime'];

				$fnname = sanitize(basename($uploadedFolder));
				$zipname = $_POST['time'] . '_' . $fnname . '.zip';
				if($pass!="")
				{
					$connection = mysql_connect("localhost", "root", "pikachu");
					$db = mysql_select_db("cloud", $connection);
					$filename=$zipname."_1";
					$filename.="_".$expiryTime;
					$query = "insert into filepass (filename,password) values('$filename','$pass')";
					mysql_query($query,$connection);
					
				}
				else
				{
					$filename=$zipname."_0";
					$filename.="_".$expiryTime;
				}


				if($perm == 'public')
				{
					$target_path.="$login_session/public/";
					$p=0;
				}
				else if ($perm == 'private')
				{
					$target_path.="$login_session/private/";
					$p=2;
				}
				else {
					$target_path.="$login_session/unlisted/";
					$p=1;
				}

				$url="http://localhost/u2/login/download.php?u=$login_session&f=$filename&p=$p";
				$message .= "<br>Your download link is:<br>"."<a>$url</a><br>";

				if ($perm == 'unlisted')
					symlink($target_path, "../uploads/common/$filename");
				else if ($perm == 'public')
					symlink($target_path, "../uploads/public/$login_session"."_"."$filename");

				$targetFile = $target_path . $zipname;
				$this->compress($uploadDir.$uploadedFolder, $targetFile);
				rename($targetFile, $target_path.$filename);
				rrmdir($uploadDir.$uploadedFolder);
				$message .= "The shortlink is : <a href='http://localhost/u2/s/?s=".createShortLink($url)." '> Link</a>";
			}
			else
				$message = 'There was an error in uploading the folder';
			echo $message;	
		}
		
		private function upload($current,$uploadFile){
			if(!is_dir($this->folder)){
				mkdir($this->folder,0777,true);
			}
			if(move_uploaded_file($current['tmp_name'],$uploadFile))
				return true;
			else 
				return false;
		}

		private function compress($folderToCompress, $targetFile)
		{

			// Get real path for our folder
			$rootPath = realpath($folderToCompress);

			// Initialize archive object
			$zip = new ZipArchive();
			$zip->open($targetFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

			// Create recursive directory iterator
			/** @var SplFileInfo[] $files */
			$files = new RecursiveIteratorIterator(
			    new RecursiveDirectoryIterator($rootPath),
			    RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ($files as $name => $file)
			{
			    // Skip directories (they would be added automatically)
			    if (!$file->isDir())
			    {
			        // Get real and relative path for current file
			        $filePath = $file->getRealPath();
			        $relativePath = substr($filePath, strlen($rootPath) + 1);

			        // Add current file to archive
			        $zip->addFile($filePath, $relativePath);
			    }
			}

			// Zip archive will be created only after closing object
			$zip->close();

		}
	}


 	function sanitize($string, $force_lowercase = true, $anal = false) {
	    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
	                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
	                   "â€”", "â€“", ",", "<", ">", "/", "?");
	    $clean = trim(str_replace($strip, "", strip_tags($string)));
	    $clean = preg_replace('/\s+/', "-", $clean);
	    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	    return ($force_lowercase) ?
	        (function_exists('mb_strtolower')) ?
	            mb_strtolower($clean, 'UTF-8') :
	            strtolower($clean) :
	        $clean;
	}

	function generateRandomString($length = 5) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function createShortLink($filename)
	{
		$connection = mysql_connect("localhost", "root", "pikachu");
		$db = mysql_select_db("cloud", $connection);
		$shortlink = generateRandomString();
		$query = "insert into shortner (link,short) values('$filename','$shortlink')";
		mysql_query($query,$connection);
		return $shortlink;
	}


	function rrmdir($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	}
?>