
const modelParams = {
  flipHorizontal: true,   // flip e.g for video 
  imageScaleFactor: 3,  // reduce input image size for gains in speed.
  maxNumBoxes: 1,        // maximum number of boxes to detect
  iouThreshold: 0.5,      // ioU threshold for non-max suppression
  scoreThreshold: 0.5,    // confidence threshold for predictions.
}


navigator.getUserMedia = navigator.getUserMedia || navigator.webGetUserMedia || navigator.mozGetUserMedia;

const video = document.querySelector('video');
const canvas = document.querySelector('nuageDePoints');
//const context = canvas.getContext('2d');
let model;
var posx;
var posy;

handTrack.startVideo(video)
		.then(status => {
							if(status){
								//setInterval(runDetection, 100);
								
									navigator.getUserMedia({video:{}}, stream => {
										video.src = stream;
										//video.style.width = "0px";
										setInterval(runDetection, 100);

									},
								
								err => console.log(err)

								);

							}

		});

		function runDetection(){
			model.detect(video)
			.then(predictions => { 
				if(predictions.length> 0){
					let midval = predictions[0].bbox[0] + (predictions[0].bbox[2] / 2)
            		posx = document.body.clientWidth* (midval / video.width)
            		posx -= 100;
            		document.getElementById("positionMouseX").innerHTML = posx;
            		document.getElementById("positionMouseY").innerHTML = posy;
            		posy = document.body.clientHeight*(midval / video.height);
            		document.getElementById("infoTrack").innerHTML = "";
				}
				else{
					document.getElementById("infoTrack").innerHTML = "no detection";
				};
			
			});
		}

handTrack.load(modelParams)
	.then(lmodel => {
				model = lmodel;
	});