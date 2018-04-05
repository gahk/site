function drawMap(canvasId, roomId, roomnumber, direction, roomfloor, imageUrl) {
		var canvas = document.getElementById(canvasId);
		var context = canvas.getContext('2d');
		var imageObj = new Image();

		imageObj.src = imageUrl;
		imageObj.onload = function() {
			canvas.width = 342;
  		canvas.height = 189;
			context.drawImage(imageObj, 0, 0);

			var top = 0;
			if(direction == "mod gaden"){
				top = 148;
			} else {
				top = 43;
			}

			var roomPxWidth = 38;
			var left = 0;

			//STUEN
			if(roomfloor == "stuen"){
				left = roomId * roomPxWidth - 18;
				if(direction == "mod gaden" && roomId >= 5){
					left = left + 36;
				}
				if(direction == "mod gården"){
					left = roomPxWidth*2 + (roomId-8)*roomPxWidth - 18;
					if(roomId >= 10){
						left = roomPxWidth*5 + (roomId-8)*roomPxWidth - 18;		
					}
				}
			}

			//1. SAL
			var lastRoomIdBeforeFloor = 10;
			if(roomfloor == "1. sal"){
				left = (roomId-lastRoomIdBeforeFloor) * roomPxWidth - 18;

				if(direction == "mod gården"){
					left = roomPxWidth*2 + (roomId-(lastRoomIdBeforeFloor+9))*roomPxWidth - 18;
				}
			}

			//2. SAL
			var lastRoomIdBeforeFloor = 24;
			if(roomfloor == "2. sal"){
				left = (roomId-lastRoomIdBeforeFloor) * roomPxWidth - 18;

				if(direction == "mod gården"){
					left = roomPxWidth*2 + (roomId-(lastRoomIdBeforeFloor+9))*roomPxWidth - 18;
				}
			}

			//3. SAL
			var lastRoomIdBeforeFloor = 38;
			if(roomfloor == "3. sal"){
				left = (roomId-lastRoomIdBeforeFloor) * roomPxWidth - 18;

				if(direction == "mod gården"){
					left = roomPxWidth*2 + (roomId-(lastRoomIdBeforeFloor+9))*roomPxWidth - 18;
				}
			}

			//4. SAL
			var lastRoomIdBeforeFloor = 52;
			if(roomfloor == "4. sal"){
				left = (roomId-lastRoomIdBeforeFloor) * roomPxWidth - 18;

				if(direction == "mod gaden"){
						left =  (roomId-lastRoomIdBeforeFloor+3) * roomPxWidth - 19;//faengel and arest
					if(roomId == 53){
						left = (roomId-lastRoomIdBeforeFloor) * roomPxWidth; //atalie is big
					}
					if(roomId == 56){
						left = (roomId-lastRoomIdBeforeFloor+4) * roomPxWidth; //atalie is big
					}

				} else if(direction == "mod gården"){
					left = roomPxWidth*2 + (roomId-(lastRoomIdBeforeFloor+4))*roomPxWidth - 18;
				}
			}



			context.beginPath();
			context.arc(left,top,16,0,2*Math.PI);
			context.fillStyle = "#4da74d";
			context.fill();

			context.font = "16px Arial";
			context.fillStyle = "#FFFFFF";
			context.fillText(roomnumber,left-14,top+6);
		};
	}