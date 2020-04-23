function saveCanvas(){
	html2canvas(document.querySelector("canvas")).then(canvas => {
		var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
		window.location.href=image;		
	});
}

document.getElementById('screenshot').addEventListener('click', function (event) {
	saveCanvas();
});
