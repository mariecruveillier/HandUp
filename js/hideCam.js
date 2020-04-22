document.getElementById("hideCam").addEventListener('click', function(event) {
	if(hiden)
		{
			camRight();
		}
	else{
			camLeft();
		}
			hiden = !hiden;
		});

function camLeft(){
	var cam = document.getElementById("video");
	var hideCam = document.getElementById("hideCam");
	cam.style.display = "none";
	hideCam.style.width = "3%";
	hideCam.style.backgroundImage = "url('img/video.png')";
	hideCam.style.marginLeft = "0.9%";
}

function camRight(){
	var cam = document.getElementById("video");
	var hideCam = document.getElementById("hideCam");
	cam.style.display = "flex";
	hideCam.style.width = "3%";
	hideCam.style.marginLeft = "11.2%";
	hideCam.style.backgroundImage = "url('img/no_video.png')";
}
