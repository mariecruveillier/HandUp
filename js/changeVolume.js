
			function getVolume(){
				var volume = posYmouseMin * 100;
				volume = Math.round(volume);
				document.getElementById("displayedVolume").innerHTML = volume + " dB";
			}

			function setVolume(){
				posYmouse = document.getElementById("positionMouseX").innerText;
				posYmouseMin = parseInt(posYmouse,32);
				posYmouseMin *= 0.0001;
				sound.setVolume(posYmouseMin);
			}