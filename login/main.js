window.onload = function(){

	var passwordBox = document.getElementById("passwordBox");
	var fileUpload = document.getElementById("fileUpload");
	var folderUpload = document.getElementById("folderUpload");
	passwordBox.style.display="none";
	folderUpload.style.display="none";
	expiryDateBox.style.display="none";
	expiryTimeBox.style.display="none";
	$('#loginAlert').hide();
	$('#signupAlert').hide();

	$('#expiryCheckbox').click(function(){
		if($(this).is(':checked'))
			{
				expiryDateBox.style.display="inline";
				expiryTimeBox.style.display="inline";
			}	
		else
			{
				expiryDateBox.style.display="none";
				expiryTimeBox.style.display="none";
			}
	});
	$('#passwordCheckbox').click(function(){
		if($(this).is(':checked'))
			passwordBox.style.display="inline";
		else
			passwordBox.style.display="none";
	});
	flag=1;
	$('#folderCheckbox').click(function(){
		if($(this).is(':checked'))
		{
			flag=2;
			fileUpload.style.display="none";
			folderUpload.style.display="inline";
		}
		else
		{
			flag=1;
			folderUpload.style.display="none";
			fileUpload.style.display="inline";
		}
	});

	$('#submitButton').click(function(){
		if(flag==2)
		{
			var x = document.getElementById('files');
			uploadFolder(x.files);
		}
		if(flag==1)
		{
			var x = document.getElementById('file');
			uploadFile(x.files);
		}
	});

}

function uploadFile(files)
{
	$password = $('#inputPassword').val();
	$time = $('#intime').val();
	$date= $('#indate').val();
	xhr = new XMLHttpRequest();
	data = new FormData();
	data.append(0, files[0]);
	data.append('password', $password);

	d = new Date();
	time = (d.getTime() - d.getMilliseconds())/1000;

	if(isNaN($time) && isNaN($date))
	{
		e = new Date($date + " " + $time);
		expiryTime = (e.getTime() - e.getMilliseconds())/1000;
	}
	else
		expiryTime=time;

	data.append('time', time);
	data.append('expiryTime', expiryTime);
	
	sanitiseName = sanitizeString(files[0]['name']);
	if($password == "")
		filename = time + "_" + sanitiseName + "_0_" +  expiryTime;
	else
		filename = time + "_" + sanitiseName + "_1_" +  expiryTime;
	
	$('#uploadAlert .msg').html("http://localhost/u2/login/download.php?u=&f=" + filename + "&p=");

	xhr.upload.addEventListener("progress", progressHandler, false);
	xhr.addEventListener("error", errorHandler, false);
	xhr.addEventListener("abort", abortHandler, false);

	xhr.onreadystatechange = function() {
	    if(xhr.readyState == 4 && xhr.status == 200) {
		    var return_data = xhr.responseText;
			$('#uploadAlert .msg').html(return_data);
	    }
    }
	xhr.open('POST', "fileUpload.php", true);
	xhr.send(this.data);
}



function uploadFolder(files)
{
	xhr = new XMLHttpRequest();
	data = new FormData();
	paths = "";
	$password = $('#inputPassword').val();
	$time = $('#intime').val();
	$date= $('#indate').val();
    
    xhr.onreadystatechange = function() {
	    if(xhr.readyState == 4 && xhr.status == 200) {
		    var return_data = xhr.responseText;
			$('#uploadAlert .msg').html(return_data);
	    }
    }
	
	for (var i in files){
		paths += files[i].webkitRelativePath+"###";
		data.append(i, files[i]);
	};
	data.append('paths', paths);
	d = new Date();
	time = (d.getTime() - d.getMilliseconds())/1000;

	if(isNaN($time) && isNaN($date))
	{
		e = new Date($date + " " + $time);
		expiryTime = (e.getTime() - e.getMilliseconds())/1000;
	}
	else
		expiryTime=time;

	var temp = files[0].webkitRelativePath.split("/");

	sanitiseName = sanitizeString(temp[0]);
	if($password == "")
		filename = time + "_" + sanitiseName + ".zip_0_" +  expiryTime;
	else
		filename = time + "_" + sanitiseName + ".zip_1_" +  expiryTime;
	
	var uploadMsg = "http://localhost/u2/login/download.php?u=&f=" + filename + "&p=" + "<br><br>Please wait while we upload and compress your files"; 
	$('#uploadAlert .msg').html(uploadMsg);


	data.append('time', time);
	data.append('expiryTime', expiryTime);
	data.append('password', $password);

	xhr.upload.addEventListener("progress", progressHandler, false);
	xhr.addEventListener("error", errorHandler, false);
	xhr.addEventListener("abort", abortHandler, false);
	xhr.open('POST', "folderUpload.php", true);
	xhr.send(this.data);
}

function _(el){
	return document.getElementById(el);
}

function progressHandler(event){
	var percent = (event.loaded / event.total) * 100;
	_("progressBar").style.width = Math.round(percent)+"%";
	_("status").innerHTML = Math.round(percent)+"% uploaded";
	if(Math.round(percent)==100)
	{
		$("#progressBar").removeClass("active");
		$("#progressBar").removeClass("progress-bar-striped");
	}
}
function completeHandler(event){
	_("progressBar").value = 100;
}
function errorHandler(event){
	_("status").innerHTML = "Upload Failed";
}
function abortHandler(event){
	_("status").innerHTML = "Upload Aborted";
}
function sanitizeString(str){
    	str = str.replace(/[\s]/gim,"-");
    	str = str.replace(/[_]/gim,"-");
    	str = str.replace(/[^a-z0-9 \.-]/gim,"");
    	str = str.replace(/[\s]/gim,"-");
    	return str.trim();
	}
function validateLogin()
{
	var loginUsername = document.querySelector('#loginUsername');
	var loginPassword = document.querySelector('#loginPassword');
	if(loginUsername.value=="" || loginPassword.value=="")
	{
		$('#loginAlert .msg').text('Both fields are required');
		$('#loginAlert').show();       
	}
	else
	{
		$.post('login.php',
			{
				username: loginUsername.value,
				password: loginPassword.value
			},
			function(data,status)
			{
				var responseMsg = $('response',data).text();
				if(responseMsg=='OK')
				{
					$('#loginAlert').hide();
					$('#loginModal').modal('hide');
					window.location = "profile.php";
				}
				else
				{
					$('#loginAlert .msg').text(responseMsg);
					$('#loginAlert').show();
				}                
			}
		);
	}
	return false;
}

function validateSignup()
{
	var signupUsername = document.querySelector('#signupUsername');
	var signupPassword = document.querySelector('#signupPassword');
	if(signupUsername.value=="" || signupPassword.value=="")
	{
		$('#signupAlert .msg').text('Both fields are required');
		$('#signupAlert').show();       
	}
	else
	{
		$.post('signup.php',
			{
				username: signupUsername.value,
				password: signupPassword.value
			},
			function(data,status)
			{
				var responseMsg = $('response',data).text();
				if(responseMsg=='OK')
				{
					$('#signupAlert').hide();
					$('#signupModal').modal('hide');
					window.location = "profile.php";
				}
				else
				{
					$('#signupAlert .msg').text(responseMsg);
					$('#signupAlert').show();
				}                
			}
		);
	}
	return false;
}
