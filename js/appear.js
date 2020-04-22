function objAppear(obj){
	var oppArray = ["0.0", "0.1", "0.2", "0.3", "0.4", "0.5", "0.6", "0.7", "0.8", "0.9", "1.0"];
	var x = 0;
	(function next() {
		obj.style.opacity = oppArray[x];
		if(obj.style.opacity > "0.1")
		{
			document.getElementById('video').style.display = 'flex';
			obj.style.display = 'flex';
			document.getElementById('panelMenu').style.display = 'block';
		}
		if(++x < oppArray.length) {
			setTimeout(next, 100);
		}
	})();
	document.getElementById('panelMenu').style.display = 'block';
}

document.getElementById('button2').addEventListener('click', function (event) {
				var menu = document.getElementById("menu");
				var cam = document.getElementById("video");
				var previousSound = document.getElementById("previousSound");
				var nextSound = document.getElementById("nextSound");
				var manageSound = document.getElementById("manageSound");
				var hideCam = document.getElementById("hideCam");
				var infoTrack = document.getElementById("infoTrack");
				var nbPnts = document.getElementById("nbPnts");
				var panelMenu = document.getElementById("panelMenu");

				objAppear(panelMenu);
				objAppear(nbPnts);
				objAppear(manageSound);
				objAppear(cam);
				objAppear(previousSound);
				objAppear(nextSound);
				objAppear(hideCam);
				objAppear(infoTrack);
				lessTheOppacity(menu);
				displayNameMusic();
});
