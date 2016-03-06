<?php 
include('session.php'); 
?>
	
<!DOCTYPE html>
<html>
<head>
	<title>Your Home Page</title>
	<meta charset="UTF-8">  
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">

            <div class="navbar-header">

                <li class="navbar-brand" style="display: flex;">
                    <a id="navTitle" href="">Welcome : <i><div id="user"><?php echo $login_session; ?></div></i></a>
                </li>
            </div>

            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right" >
                    <li><button id="logoutButton" type="button" class="btn btn-info btn-sm" >Logout</button></li>
                </ul>
            </div>

        </div>
    </nav>
    <br><br><br><br><br><br>

    <div class="container">
	<div class="jumbotron">
		<div class="row">
		<div class="col-md-4 col-md-offset-4">
	    	<div class="form-group">
	    		<input type="checkbox" id="folderCheckbox">Upload a folder?<br />
	    	</div>
	    	<div class="form-group">
	    		<div id="fileUpload">
	    			<input type="file" name="file" id="file"/>
	    		</div>
	    	</div>
	    	<div class="form-group">
	    		<div id="folderUpload">
	    			<input type="file" name="files" id="files" multiple="" webkitdirectory="">​
	    		</div>
	    	</div>
	    	<div class="form-group">
	    		<input type="radio" name="perm" value="private" checked/>Private
	    		<input type="radio" name="perm" value="public" />Public
	    		<input type="radio" name="perm" value="unlisted" />Unlisted
	    	</div>
	    	<div class="form-group">
	    		<input type="checkbox" id="passwordCheckbox">Want to set a password to your file?<br />
	    	</div>
	    	<div class="form-group">
	    		<div id="passwordBox">
	    			<input type = "password" name="password" id="inputPassword" value="" placeholder="*******" />
	    		</div>
	    	</div>
	    	<div class="form-group" >
		    		<input type="checkbox" id="expiryCheckbox">Want to set expiration date?<br />
		    </div>
	    	<div class="form-group">
	    		<div id="expiryDateBox">
	    			<input type = "date" name="expirydate" value=""  id = "indate"/>
	    		</div>
	    		<div id="expiryTimeBox">
	    			<input type = "time" name="expirytime" value=""  id = "intime"/>
	    		</div>
	    	</div>
	    	<button type="button" name="submit" id="submitButton">Start upload</button>
	    	<div class="progress">
	    	  <div class="progress-bar progress-bar-striped active" role="progressbar" id="progressBar" aria-valuenow="0"
	    	  aria-valuemin="0" aria-valuemax="100" >
	    	    <span class="sr-only"></span>
	    	  </div>
	    	</div>
	    	<h3 id="status"></h3>
		    <div class="alert alert-danger" role="alert" id="uploadAlert">
		    	<span class="msg"></span>
		    </div>
		    <button type = "button" id = "myFiles" onclick="location.href='displayfiles.php';"> View Uploaded Files</button> 
		</div>
		</div>
	</div>
	</div>

	<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"> ></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="profile.js" ></script>
</body>
</html>
