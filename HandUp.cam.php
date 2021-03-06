<!DOCTYPE html>
<html>
<head>
	<title>Hand Up</title>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet"> 
	<script src="js/three.min.js"></script>
	<script src="js/three.js"></script>
	<script src="js/OrbitControls.js"></script>
	<script src="js/LegacyJSONLoader.js"></script>
	<script src="js/ObjectLoader.js"></script>
	<script src="js/threex.domevents.js"></script>
	<script src="html2canvas.min.js" type="text/javascript"></script>

</head>
<meta charset=utf-8 />
	<body>
		<div id="panelInstructions">
			<div id="text">
				<div id="mouse">
					<img src="img/mouse.png">
					<p> 
					 	Depending on the position of the mouse, loudness of music, the colors and the opacity of the particles change. The rest of the interactions is triggered by keyboard keys.
					</p>
				</div>
				<div id="hand">
					<img src="img/hand.png">
					<p> 
						Depending on the position of the hand, loudness of music, the colors and the opacity of the particles change. The rest of the interactions is triggered by click on some buttons.
					</p>
				</div>
				<div id="rangeCircle">
					<img src="img/circles.png">
					<p> other interactions : 
						<br>
						look at around you by moving the virtual camera, make a screenshot of the render, change the number of particles, stop, play and change the music
					</p>
				</div>
			</div>
			<div id="close"> <img src="img/close.png"></div>
		</div>
		<div id="menu">
			<div id="positionMouseX">0</div>
			<div id="positionMouseY">0</div>
			<div id="button1"><p>
				<select name="musique" id="musique_selection">
				    <option value="">--Please choose a song--</option>
				    <option value="ACDC">ACDC</option>
				    <option value="Vivaldi">Vivaldi</option>
				    <option value="Prince">Prince</option>
				    <option value="Dave"> Dave Brubeck </option>
				    <option value="Daft"> Daft Punk </option>
				</select>
		</div>
			<div id="button2"> <img src="img/letsgo.png" > </div>
		</div>
		<div id="StopIMG"> <img src="img/pause.png"></div> 
		<div id="nuageDePoints">
			<div id="displayedVolume"></div>
			<div id="nameMusic"><p id="displayNameMusic" text-align="center"></p></div>
			<div id="panelMenu">
				<div>
					<video id="video" src="" opacity="0"></video>
					<div id="infoTrack"></div>
				</div>

				<div id="playerSound">
					<div id="previousSound"></div>
					<div id="manageSound"></div>
					<div id="nextSound"></div>			
				</div>
				<div id="nbPnts">
						<img id="imgCircles" src="img/circles.png" alt="nombre de points"/>
						<input type="range" id="DetailCircleRange" class='range_css'/>
				</div>
				<div id="instructions" class="buttonModeWebcam">
					<p> instructions </p>
				</div>
				<div id="screenshot" class="buttonModeWebcam">
					<p>screenshot</p>
				</div>
				<div id="mode">
					<div>
						<p> webcam </p>
					</div>
					<a href="./HandUp.mouse.php" class="otherMode">
						<p> mouse </p>
					</a>
				</div>
				<div id="rot">
					<div id="rotLeft"> <img id="imgRotLeft" src="img/arrow_left.png"></div>
					<div id="rotRight"> <img id="imgRotRight" src="img/arrow_white.png"></div>
					<div id="rotTop"> <img id="imgRotTop" src="img/arrow_top.png"></div>
					<div id="rotLow"> <img id="imgRotLow" src="img/arrow_low.png"></div>
				</div>
			</div>
				
			<script src="https://cdn.jsdelivr.net/npm/handtrackjs/dist/handtrack.min.js"> </script>
			<script type="text/javascript" src="js/app.js"></script>
			
			<script>
				const html2canvas = window.html2canvas;
				var ww = window.innerWidth, wh = window.innerHeight;
		  		var camera;
		  		var renderer;
		  		var factor = 3 ;
		  		var sound;
				var audioLoader;
				var listener;
				var tube;
				var geometry;
				var analyser, dataArray;
				var posXmouse = 0;
				var movementX =0;
				var movementY = 0;
				var hiden = false;
				var played = false; 
				var percentage = 0;
				var son = ["sound/acdc-back-in-black.mp3", "sound/daft.mp3", "sound/take-five-the-dave-brubeck-quartet-1959.mp3", "sound/les-quatre-saisons-vivaldi.mp3", "sound/prince-when-doves-cry.mp3" ];
				var nameSon = ["Back in black by ACDC", "Around the world by Daft Punk", "Take five by Dave Brubeck ", "The four Seasons by Vivaldi", "When doves cry by Prince"];
				var indexSon;
				renderer = new THREE.WebGLRenderer({antialias: true, preserveDrawingBuffer: true});
				renderer.setClearColor(0x000000, 0.0);

				scene = new THREE.Scene();
				scene.fog = new THREE.Fog(0xFFFFFF, 1, 2);

				// camera
				camera = new THREE.PerspectiveCamera(45, window.screen.width / window.screen.height, 0.001, 100);
				camera.position.z = 400;
				renderer.setSize( ww, wh,false);

				var scene = new THREE.Scene();

				controls = new THREE.OrbitControls( camera, renderer.domElement );

				loader = new THREE.ObjectLoader();
				var domEvents = new THREEx.DomEvents(camera, renderer.domElement);
				var audioData = [];
				var stream;

				// Define the precision of the finale tube, the amount of divisions
				var tubeDetail = 800;
				// Define the precision of the circles
				var circlesDetail = 100;

				// Define the radius of the finale tube
				var radius;

				var material = new THREE.PointsMaterial({
		 			size: 0.2,
		  			vertexColors: THREE.VertexColors // We specify that the colors must come from the Geometry
				});

				document.body.appendChild( renderer.domElement );
				

				//Array of points
				var points = [
				  [68.5, 185.5],
				  [1, 262.5],
				  [270.9, 281.9],
				  [345.5, 212.8],
				  [178, 155.7],
				  [240.3, 72.3],
				  [153.4, 0.6],
				  [52.6, 53.3],
				  [68.5, 185.5]
				];

				//Convert the array of points into vertices
				for (var i = 0; i < points.length; i++) {
				  var x = points[i][0];
				  var y = 0;
				  var z = points[i][1];
				  points[i] = new THREE.Vector3(x, y, z);
				}

				document.getElementById("manageSound").style.backgroundImage = "url('img/playBlanc.png')";
				document.getElementById("previousSound").style.backgroundImage = "url('img/lastBlanch.png')";
				document.getElementById("nextSound").style.backgroundImage = "url('img/nextBlanc.png')";
				

				var path = new THREE.CatmullRomCurve3(points);//Create a path from the points
				var frames = path.computeFrenetFrames(tubeDetail, true); // Get all the circles that will compose the tube


				/*
					//
					// Analyse le son
					//
				*/

				function splitFrequencyArray(arr, n) {
					var tab = Object.keys(arr).map(function(key) {
						return arr[key];
					});
				
					var len = tab.length,
					result = [],
					i = 0;

					while (i < len) {
						var size = Math.ceil((len - i) / n--);
						result.push(tab.slice(i, i + size));
						i += size;
					}
					return result;
				}

				function getAudioData(data) {
					// Split array into 3
					var frequencyArray = splitFrequencyArray(data, 1);
					// Make average of frenquency array entries
					for (var i = 0; i < frequencyArray.length; i++) {
						var average = 0;
						for (var j = 0; j < frequencyArray[i].length; j++) {
							average += frequencyArray[i][j];
						}
						audioData[i] = average / frequencyArray[i].length;
					}
				}


				/*
					//
					// Nuage de points
					//
				*/

				// Create an empty Geometry where we will put the particles
				function createGeometry()
				{
					geometry = new THREE.Geometry();
					// First loop through all the circles
					for (var i = 0; i < tubeDetail; i++) {
					  getAudioData(dataArray);
					  radius = Math.floor(audioData[0]/5);
					  var normal = frames.normals[i];
					  var binormal = frames.binormals[i]; // Get the binormal values
					  var index = i / tubeDetail; // Calculate the index of the circle (from 0 to 1)
					  var p = path.getPointAt(index);// Get the coordinates of the point in the center of the circle

					  posXmouse = document.getElementById("positionMouseX").innerText;
					  posXmouseMin = parseInt(posXmouse,32);
					  posXmouseMin *= 0.001;
					  posXmouseMin = Math.round(posXmouseMin);
					  setVolume();

					  if(radius > 0)
					  {
					  	getVolume();
					  	//document.getElementById("loading").style.display = "none";
					  	var color = new THREE.Color("hsl(" + (index * 360 * 4) + "," + (posXmouseMin*5) + "% ," +  (posXmouseMin*8) + "%)");

					  	// Loop for the amount of particles we want along each circle
						for (var j = 0; j < circlesDetail; j++) {
						    var position = p.clone();// Clone the position of the point in the center
						    var angle = (j / circlesDetail) * Math.PI * 2; // Calculate the angle for each particle along the circle (from 0 to Pi*2)
						    var sin = Math.sin(angle); // Calculate the sine of the angle
						    var cos = -Math.cos(angle); // Calculate the cosine from the angle
						    var normalPoint = new THREE.Vector3(0,0,0);// Calculate the normal of each point based on its angle
						    normalPoint.x = (cos * normal.x + sin * binormal.x);
						    normalPoint.y = (cos * normal.y + sin * binormal.y);
						    normalPoint.z = (cos * normal.z + sin * binormal.z);
						    normalPoint.multiplyScalar(radius + Math.random() * (radius/factor));// Multiple the normal by the radius
						    position.add(normalPoint) // We add the normal values for each point
						    geometry.vertices.push(position);
						    geometry.colors.push(color);
					   }
					}
				}
					tube = new THREE.Points(geometry, material);
					scene.add(tube);
				}

			/*
				//
				// menu : choisir la musique
				//
			*/
			function getChosenMusic()
			{
				var e = document.getElementById("musique_selection").value;
				switch(e){
					case "ACDC":
						stream = son[0];
						indexSon = 0;
					break;

					case "Daft": 
						stream = son[1];
						indexSon = 1;
					break;

					case "Prince": 
						stream = son[4];
						indexSon = 4;
					break;

					case "Dave": 
						stream = son[2];
						indexSon = 2;
					break;

					case "Vivaldi": 
						stream = son[3];
						indexSon = 3;
					break;

					default:
						stream = son[0];
						indexSon = 0;
				}
			}

			document.getElementById('button1').addEventListener('click', function(event) {
				getChosenMusic();
			});

		/*
			//
			// Permet de changer le nombre de points par cercle
			//
		*/
			document.getElementById('DetailCircleRange').addEventListener('click', function (event) {
				changeDetailCircles();
			});


			function changeDetailCircles()
			{
		  		circlesDetail = document.getElementById("DetailCircleRange").value;
		  		circlesDetail *= 2;
			}

		/*
			//
			// gestion de l'audio : mettre en pause, reprendre la lecture, changer de musique
			//
		*/
			document.getElementById("manageSound").addEventListener('click', function(event) {
				if(played)
				{
					stopMusic();
				}
				else {
					playMusic();
				}
				played = !played;
			});


			document.getElementById("nextSound").addEventListener('click', function(event) {
					if(indexSon < son.length - 1)
					{
						indexSon++;
						stream = son[indexSon]; 
						sound.stop();
						audioLoader.load(stream, function( buffer ) {
							sound.setBuffer( buffer );
							sound.setLoop(true);
							sound.play();
							displayNameMusic();
						});
						if(!played){
							played = true;
						}

					}
					else if(indexSon == son.length-1) {
						indexSon = 0;
						stream = son[0];
						sound.stop();
						audioLoader.load(stream, function( buffer ) {
							sound.setBuffer( buffer );
							sound.setLoop(true);
							sound.play();
							displayNameMusic();
						});
						if(!played){
							played = true;
						}
					}
					console.log(indexSon);
				});

			document.getElementById("previousSound").addEventListener('click', function(event) {
					if(indexSon > 0)
					{
						indexSon--;
						stream = son[indexSon];
						sound.stop();
						audioLoader.load(stream, function( buffer ) {
							sound.setBuffer( buffer );
							sound.setLoop(true);
							sound.play();
						});

						if(!played){
							played = true;
						}
						displayNameMusic();
					}
					else if(indexSon < 1)
					{
						indexSon = son.length - 1;
						stream = son[indexSon];
						sound.stop();
						audioLoader.load(stream, function( buffer ) {
							sound.setBuffer( buffer );
							sound.setLoop(true);
							sound.play();
						});
						if(!played){
							played = true;
						}
						displayNameMusic();
					}
					console.log(indexSon);
			});

			//affiche le nom de la musique jouée
			function displayNameMusic()
			{
				document.getElementById("displayNameMusic").innerHTML = nameSon[indexSon];
			}

		/*
			//
			// deplacement
			//
		*/

		document.getElementById('rotTop').addEventListener('click', function (event) {
			if(movementY < 10)
			{
				movementY++;
			}
		});

		document.getElementById('rotLow').addEventListener('click', function (event) {
			if(movementY> -10)
			{
				movementY--;
			}
		});

		document.getElementById('rotLeft').addEventListener('click', function (event) {
			if(movementX<40)
			{
				movementX++;
			}
		});

		document.getElementById('rotRight').addEventListener('click', function (event) {
			if(movementX > -40)
			{
				movementX--;
			}
		});


		/* 
			//
			//mettre en pause le son et faire afficher l'image de pause
			//
		*/
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

			function stopMusic(){
				var stopIMG = document.getElementById("StopIMG");
				sound.pause();
				stopIMG.style.display = "flex";
			}

			function playMusic(){
				sound.play();
				var stopIMG = document.getElementById("StopIMG");
				stopIMG.style.display = "none";
			}

		/*
			//
			// Instructions
			//
		*/

			document.getElementById('instructions').addEventListener('click', function(event)
			{
				document.getElementById('panelInstructions').style.display = "flex";
				document.getElementById('nuageDePoints').style.display = "none";
				sound.pause();
			});

			document.getElementById('close').addEventListener('click', function(event)
			{
				document.getElementById('panelInstructions').style.display = "none";
				document.getElementById('nuageDePoints').style.display = "flex";
				sound.play();
			});


		/*
			//
			// Initialisation, resizer, render et boucle de rendu 
			//
		*/

			// Initialisation scène, audio, caméra
			document.getElementById('button2').addEventListener('click', function (event) {
				audioLoader = new THREE.AudioLoader();
				listener = new THREE.AudioListener();
				camera.add(listener);
				sound = new THREE.Audio( listener );
				sound.crossOrigin = "anonymous";
				analyser = new THREE.AudioAnalyser(sound, 2048);
				analyser.analyser.maxDecibels = -3;
				analyser.analyser.minDecibels = -100;
				dataArray = analyser.data;
	  			animate();
	  			audioLoader.load(stream, function( buffer ) {
					sound.setBuffer( buffer );
					sound.setLoop(true);
					sound.play();
				});

				getAudioData(dataArray);

			});

			// resize la fenêtre
			window.addEventListener('resize', function ( )
				{
					ww = window.screen.width;
					wh = window.screen.height;
					renderer.setSize( ww, wh,false);
					camera.aspect = ww/ wh;
					camera.updateProjectionMatrix();
				});

			function render(){
				  analyser.getFrequencyData();
				  percentage += 0.0005;
				  var p1 = path.getPointAt(percentage % 1);
				  var p2 = path.getPointAt((percentage + 0.01) % 1);
				  camera.position.set(p1.x, p1.y, p1.z);
				  p2.x += movementX;
				  p2.y += movementY;
				  camera.lookAt(p2);
				  renderer.render(scene, camera);
			}

			function animate() {
			    createGeometry();
				render();
				scene.remove(tube);
			    requestAnimationFrame(animate);
			}

		</script>
		<script src="js/appear.js"></script>
		<script src="js/changeVolume.js"></script>
		<script src="js/screenshot.js"></script>
	</div>
	</body>
</html>