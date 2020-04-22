function lessTheOppacity(box)
	{
		var oppArray = ["0.9", "0.8", "0.7", "0.6", "0.5", "0.4", "0.3", "0.2", "0.1", "0.0"];
		var x = 0;
		(function next() {
			box.style.opacity = oppArray[x];
			if(box.style.opacity < "0.1")
				{
					document.getElementById('menu').style.display = 'none';
				}
				if(++x < oppArray.length) {
				    setTimeout(next, 100);
				}
			})();
	}
