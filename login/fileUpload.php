<?php


	if(sizeof($_FILES) > 0)
	{
		$fileUploader = new FileUploader($_FILES);
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
		public function __construct($uploads){
			$target_path = "../uploads/common/";

			foreach($uploads as $current)
			{
				$pass=$_POST['password'];
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

				$target_path = $target_path . $filename;
				$url = "http://localhost/u2/login/download.php?u=&f=$filename&p=";
				$message = "Your download link is:<br>"."<a href='$url'>Link</a><br>";

				if($this->upload($current,$target_path))
				{
					$message .= "The file $fnname has been successfully uploaded.";
					$message.="<br><br>Your short link is : <br> <a href='http://localhost/u2/s/?s=".createShortLink($url)."'>Link</a>";
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