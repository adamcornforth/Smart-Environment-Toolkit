		/*
		** Class: Zone
		*/
		function SVG_Zone(zone_id, place, name, colour)
		{
			this.zone_id = zone_id;
			this.colour = colour;
			this.name = name;

			this.zone = d3
				.select("#zone_" + zone_id)
					.append("rect")
						.attr("x", place + "%")
						.attr("width", 250)
						.attr("height", 250)
						.style("fill", this.colour)
						.style("stroke-width", 3)
						.style("stroke", "#000000");

			this.value = 0;

			this.count = d3
				.select("#zone_" + zone_id)
					.append("text")
						.attr("x", place + 1 + "%")
						.attr("y", "10%")
						.text(this.value)
						.attr("font-size", "30px")
						.attr("fill", "white")
						// .style("stroke-width", 0)
						// .style("stroke", "#000000");

			console.log("Created Zone with id:" + zone_id);
		}

		SVG_Zone.prototype.setCount = function(number)
		{
			this.value = this.value + number;

			this.count
				.text(this.value);
		};

		SVG_Zone.prototype.getName = function()
		{
			return this.name;
		};

		SVG_Zone.prototype.getColour = function()
		{
			return this.colour;
		};

		/*
		** Class: User
		*/
		function SVG_User(spot_id, zone_id)
		{
			this.svg_object;
			this.spot_id = spot_id;
			this.zone = zone_id;
			this.colour = getRandomColor();
			this.setMovingStatus(false);
			console.log("Created User with id:" + spot_id);
		}

		// method "create" to create an SVG object for a user at a specified zone
		SVG_User.prototype.create = function(zone_to, zones, seats, date_string)
		{
			this.zone = zone_to;
			var seat = seats.takeSeat();
			this.seat_X = seat[0];
			this.seat_Y = seat[1];
			zones[this.zone-1].setCount(1);

			this.svg_object = d3
				.select("#zone_svg")
				.append("circle")
					.attr("cx", getXforZone(this.zone, this.seat_X))
					.attr("cy", getYforZone(this.seat_Y))
					.attr("r", "25")
					// .attr("visibility", "hidden")
					.style("stroke", "#000000")
					.style("stroke-width", 4)
					.style("fill", this.colour); // "#FFFFCC" or getRandomColor()

			this.printColourForZone(zone_to, zones);
			this.printColourForName();
			this.printTime(date_string);
		};

		// method "moveTo" to move the SVG object of a user at a specified zone
		SVG_User.prototype.moveTo = function(zone_to, zones, speed, date_string)
		{
			this.setMovingStatus(true);

			var zone_before = this.zone;

			zones[this.zone-1].setCount(-1);
			this.zone = zone_to;

			// this.svg_object
			// 	.style("fill", zones[zone_before-1].getColour());

			this.svg_object
				.transition()
				.attr("cx", getXforZone(this.zone, this.seat_X))
				// .attr("cy", getYforZone(1))
				.attr("visibility", "visible")
				.duration(speed)
				.ease("linear")
				// .style("fill", zones[zone_to-1].getColour());

			var that = this;

			setTimeout(function()
				{
					// that.svg_object
					// 	.attr("visibility", "hidden");

					zones[that.zone-1].setCount(1);
					that.printColourForZone(zone_to, zones);
					that.printTime(date_string);
					that.setMovingStatus(false);
				},speed+100);
		};

		SVG_User.prototype.getId = function()
		{
			return this.spot_id;
		};

		SVG_User.prototype.getColour = function()
		{
			return this.colour;
		};

		SVG_User.prototype.setMovingStatus = function(moving)
		{
			this.moving = moving;
		};

		SVG_User.prototype.isMoving = function()
		{
			return this.moving;
		};

		SVG_User.prototype.printColourForZone = function(zone_id, zones)
		{
			document.getElementById("spot_" + this.spot_id + "_last_zone").innerHTML=zones[zone_id-1].getName();
			document.getElementById("spot_" + this.spot_id + "_last_zone").style.background=zones[zone_id-1].getColour();
		};

		SVG_User.prototype.printColourForName = function()
		{
			document.getElementById("spot_" + this.spot_id + "_name").style.background=this.getColour();
		};

		SVG_User.prototype.printTime = function(date_string)
		{
			document.getElementById("spot_" + this.spot_id + "_time_of_change").innerHTML=date_string;
		};

		/*
		** Class: Progress
		*/
		function SVG_Progress(number)
		{
			this.number = number;
			document.getElementById("zone_change_progress").style.width="0%";
			document.getElementById("zone_change_progress_parent").style.visibility="visible";
		}

		SVG_Progress.prototype.update = function(number)
		{
			document.getElementById("zone_change_progress").style.width= 100-((number/this.number) *100) + "%";
		};

		/*
		** Class: Seat System
		*/
		function SVG_Seats(seat_per_Y, number_of_Y)
		{
			this.seat_per_Y = seat_per_Y;
			this.seats = new Array();

			for(var i_Y = 1; i_Y <= number_of_Y; i_Y++)
			{
				for(var i_X = 1; i_X <= this.seat_per_Y; i_X++)
				{
					this.seats.push([i_X, i_Y]);
				}
			}
		}

		SVG_Seats.prototype.takeSeat = function()
		{
			var seat = this.seats[0];
			this.seats.shift();
			return seat;
		};

		/*
		** Class: General functions
		*/
		function getXforZone(zone, seat_X)
		{
			var difference = 15/seat_X;
			if (zone == 1 )
			{
				return 5 + difference + "%"; //12.5 Middle or getRandomNumber(5, 20)
			}
			else if (zone == 2 )
			{
				return 40 + difference + "%"; //47.5 Middle or getRandomNumber(40, 55)
			}
			else if (zone == 3 )
			{
				return 75 + difference + "%"; //82.5 Middle or getRandomNumber(75, 90)
			}
		}

		function getYforZone(rowNumber)
		{
			return (rowNumber * 20) + "%"; // 40 Middle or getRandomNumber(15, 67.5)
		}

		function getRandomY()
		{
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ )
			{
				color += letters[Math.floor(Math.random() * 16)];
			}
    		return color;
		}

		function userExist(spot_id, users)
		{
			for(var i = 0; i < users.length; i++)
			{
				if(users[i].getId() == spot_id)
				{
					return true;
				}
			}

			return false;
		}

		function live(zoneMovementHistory, users, zones, seats)
		{
			document.getElementById('time').innerHTML = "Live";
			for(var i = 0; i < zoneMovementHistory.length; i++)
			{
				if(userExist(zoneMovementHistory[i].spot_id, users) == false)
				{
					var users_temp = [new SVG_User(zoneMovementHistory[i].spot_id, zoneMovementHistory[i].zone_id)];
					users_temp[0].create(zoneMovementHistory[i].zone_id, zones, seats); // Create an SVG object for that user in a zone
					users = users.concat(users_temp);
				}
			}
		}

		function dateToString(date_old)
		{
			var date_new = d3.time.format("%Y-%m-%d %H:%M:%S");
			var date_new = date_new.parse(date_old);

			var day=date_new.getDate();
			var month=date_new.getMonth();
			var year=date_new.getFullYear();
			var h=date_new.getHours();
			var m=date_new.getMinutes();
			var s=date_new.getSeconds();
			m = checkTime(m);
			s = checkTime(s);

			return day+"."+(month + 1)+"."+year+" "+h+":"+m+":"+s;
		}

		function startTimer(zoneMovementHistory, users, zones, seats, progress)
		{
			var speed_slider_value = 4 - document.getElementById('speed_option_slider').value;
			var speed = speed_slider_value * 1000;
			var speed_for_movement = (speed_slider_value * 1000) / 2;

			var date_string = dateToString(zoneMovementHistory[zoneMovementHistory.length-1].created_at);
			document.getElementById('time').innerHTML = date_string;

			var current_user = zoneMovementHistory[zoneMovementHistory.length-1].spot_id;

			var anythingChanged = false;
			var skiping_due_to_moving_status = false;

			for(var i = 0; i < users.length; i++)
			{
				if(users[i].getId() == zoneMovementHistory[zoneMovementHistory.length-1].spot_id)
				{
					if(users[i].isMoving() == false)
					{
						users[i].moveTo(zoneMovementHistory[zoneMovementHistory.length-1].zone_id, zones, speed_for_movement, date_string);
						setTimeout(function()
						{
							progress.update(zoneMovementHistory.length);
						},speed_for_movement+100);

						zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
						anythingChanged = true;
						break;
					}
					else
					{
						skiping_due_to_moving_status = true;
						setTimeout(function(){startTimer(zoneMovementHistory, users, zones, seats, progress)}, 100);
					}
				}
			}

			if(skiping_due_to_moving_status == false)
			{
				if(anythingChanged === false)
				{
					var users_temp = [new SVG_User(zoneMovementHistory[zoneMovementHistory.length-1].spot_id, zoneMovementHistory[zoneMovementHistory.length-1].zone_id)];
					users_temp[0].create(zoneMovementHistory[zoneMovementHistory.length-1].zone_id, zones, seats, date_string); // Create an SVG object for that user in a zone
					zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
					users = users.concat(users_temp);
					progress.update(zoneMovementHistory.length);
				}

				if(zoneMovementHistory.length > 0)
				{
					if(current_user == zoneMovementHistory[zoneMovementHistory.length-1].spot_id) // zoneMovementHistory[0].spot_id is the future user
					{
						setTimeout(function(){startTimer(zoneMovementHistory, users, zones, seats, progress)}, speed);
					}
					else
					{
						setTimeout(function(){startTimer(zoneMovementHistory, users, zones, seats, progress)}, speed * 0.2); // Make it faster
					}
				}
				else
				{
					setTimeout(function()
					{
						document.getElementById('time').innerHTML = "Finished!";
					},speed_for_movement+100);
				}
			}
		}

		function checkTime(i)
		{
			if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
			return i;
		}

		// from http://stackoverflow.com/questions/1484506/random-color-generator-in-javascript
		function getRandomColor()
		{
			var letters = '0123456789ABCDEF'.split('');
			var color = '#';
			for (var i = 0; i < 6; i++ )
			{
				color += letters[Math.floor(Math.random() * 16)];
			}
    		return color;
		}

		function getRandomNumber(min,max)
		{
			return Math.floor(Math.random()*(max-min+1)+min);
		}