window.onload = function(){

	var passwordBox = document.getElementById("passwordBox");
	var fileUpload = document.getElementById("fileUpload");
	var folderUpload = document.getElementById("folderUpload");
	passwordBox.style.display="none";
	folderUpload.style.display="none";
	expiryDateBox.style.display="none";
	expiryTimeBox.style.display="none";

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

	$('#logoutButton').click(function(){
		window.location = "logout.php";
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
	$perm = $('input[name="perm"]:checked').val();

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
	data.append(0, files[0]);
	data.append('password', $password);
	data.append('perm', $perm);

	if($password == "")
		filename = time + "_" + files[0]['name'] + "_0_" +  expiryTime;
	else
		filename = time + "_" + files[0]['name'] + "_1_" +  expiryTime;

	var username = _('user').innerHTML;
	if($perm == "public")
		p=0;
	else if($perm == "unlisted")
		p=1;
	else 
		p=2;

	$('#uploadAlert .msg').html("http://localhost/u2/login/download.php?u=" + username + "&f=" + filename + "&p=" + p);

	xhr.upload.addEventListener("progress", progressHandler, false);
	xhr.addEventListener("error", errorHandler, false);
	xhr.addEventListener("abort", abortHandler, false);

	xhr.onreadystatechange = function() {
	    if(xhr.readyState == 4 && xhr.status == 200) {
		    var return_data = xhr.responseText;
			$('#uploadAlert .msg').html(return_data);
	    }
    }
	xhr.open('POST', "userFileUpload.php", true);
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
	$perm = $('input[name="perm"]:checked').val();
    
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

	if($password == "")
		filename = time + "_" + temp[0] + ".zip_0_" +  expiryTime;
	else
		filename = time + "_" + temp[0] + ".zip_1_" +  expiryTime;
	
	var username = _('user').innerHTML;
	if($perm == "public")
		p=0;
	else if($perm == "unlisted")
		p=1;
	else 
		p=2;

	var uploadMsg = "http://localhost/u2/login/download.php?u=" + username + "&f=" + filename + "&p=" + p + "<br><br>Please wait while we upload and compress your files"; 
	$('#uploadAlert .msg').html(uploadMsg);

	data.append('paths', paths);
	data.append('password', $password);
	data.append('perm', $perm);
	data.append('time', time);
	data.append('expiryTime', expiryTime);

	xhr.upload.addEventListener("progress", progressHandler, false);
	xhr.addEventListener("error", errorHandler, false);
	xhr.addEventListener("abort", abortHandler, false);
	xhr.open('POST', "userFolderUpload.php", true);
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