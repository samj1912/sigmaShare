<?php
	
	include('session.php'); 
	if(sizeof($_FILES) > 0)
	{
		$fileUploader = new FileUploader($_FILES, $login_session);
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


	class FileUploader{
		public function __construct($uploads, $login_session){
			$target_path = "../uploads/";

			foreach($uploads as $current)
			{
				$pass=$_POST['password'];
				$perm=$_POST['perm'];
				$expiryTime=$_POST['expiryTime'];

				$fnname = sanitize(basename($current['name']));
				$filename = $_POST['time'] . '_' . $fnname;
				if($pass!="")
				{
					$connection = mysql_connect("localhost", "root", "pikachu");
					$db = mysql_select_db("cloud", $connection);
					$filename.="_1";
					$filename.="_".$expiryTime;
					$query = "insert into filepass (filename,password) values('$filename','$pass')";
					mysql_query($query,$connection);
					
				}
				else
				{
					$filename.="_0";
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

				$target_path = $target_path . $filename;
				$url="http://localhost/u2/login/download.php?u=$login_session&f=$filename&p=$p";
				$message = "If successful upload, your download link is:<br>"."<a>$url</a><br>";

				if($this->upload($current,$target_path))
				{
					if ($perm == 'unlisted')
					{
						symlink($target_path, "../uploads/common/$filename");
					}
					if ($perm == 'public')
					{
						symlink($target_path, "../uploads/public/$login_session"."_"."$filename");
					}
					$message .= "The shortlink is : <a href='http://localhost/u2/s/?s=".createShortLink($url)." '> Link</a>";
					$message = $message."The file $fnname has been successfully uploaded.";
				}
				else
					$message = "There was an error uploading the file, please try again!";
				echo $message;
			}
		}

		private function upload($current,$uploadFile){
			if(move_uploaded_file($current['tmp_name'],$uploadFile))
				return true;
			else 
				return false;
		}
	}
?>