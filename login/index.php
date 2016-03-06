<?php
	session_start();
	if(isset($_SESSION['login_user']))
		header('Location: profile.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">  
	<title>Our Cloud</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<!-- <link href="main.css" rel="stylesheet" type="text/css"> -->
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">

            <div class="navbar-header">

                <li class="navbar-brand" style="display: flex;">
                    <a id="navTitle" href="">Our Cloud</a>
                </li>
            </div>

            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right" >
                    <li><button style="margin-top:10px;width:80px;margin-right:10px;"id="loginButton" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#loginModal">Login</button></li>
                    <li><button style="margin-top:10px;width:80px;margin-right:20px;" id="signupButton" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#signupModal">Sign Up!</button></li>
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
	    			<input type="file" name="file" class="button"id="file"/>
	    		</div>
	    	</div>
	    	<div class="form-group">
	    		<div id="folderUpload">
	    			<input type="file" name="files" id="files" multiple="" webkitdirectory="">​
	    		</div>
	    	</div>
	    	<div class="form-group" >
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
	    	<!-- <progress  value="0" max="100" style="width:300px;"></progress> -->
	    	<h3 id="status"></h3>
	    	<div id="test"></div>
		    <div class="alert alert-danger" role="alert" id="uploadAlert">
		    	<span class="msg"></span>
		    </div>
		    <button id="searchPubFiles" type="button" onclick="location.href='displaypub.php?u=';">View Public Files!</button>
			<button id="searchUsers" type="button" onclick="location.href='userlist.php';">View Users!</button>
		</div>
		</div>
	</div>
	</div>

	<div class="modal fade" id="loginModal" tabindex='-1'>
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<strong>Login to view your profile</strong>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">
					<form role="form">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Username" id="loginUsername">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Password" id="loginPassword">
						</div>
					</form>
					<div class="alert alert-danger" role="alert" id="loginAlert">
				    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="false"></span>
				    	<span class="sr-only">Error:</span>
				    	<span class="msg"></span>
				    </div>
				</div>

				<div class="modal-footer" style="text-align: center;">
					<button type="submit" class="btn btn-primary" onclick="return validateLogin()">Log in</button>
				</div>

			</div>
		</div>
	</div>


	<div class="modal fade" id="signupModal" tabindex='-1'>
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<strong>Create a new account</strong>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">
					<form role="form">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Username" id="signupUsername">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Password" id="signupPassword">
						</div>
					</form>
					<div class="alert alert-danger" role="alert" id="signupAlert">
				    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="false"></span>
				    	<span class="sr-only">Error:</span>
				    	<span class="msg"></span>
				    </div>
				</div>

				<div class="modal-footer" style="text-align: center;">
					<button type="submit" class="btn btn-primary" onclick="return validateSignup()">Sign up</button>
				</div>

			</div>
		</div>
	</div>



	<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"> ></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="main.js" ></script>

</body>
</html>