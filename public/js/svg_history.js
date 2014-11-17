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
						.attr("x", place + 7.5 + "%")
						.attr("y", "60%")
						.text(this.value)
						.attr("font-size", "190px")
						.attr("fill", "white")
						.style("stroke-width", 3)
						.style("stroke", "#000000");

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

		function SVG_User(spot_id, zone_id)
		{
			this.svg_object;
			this.spot_id = spot_id;
			this.zone = zone_id;
			console.log("Created User with id:" + spot_id);
		}

		// method "create" to create an SVG object for a user at a specified zone
		SVG_User.prototype.create = function(zone_to, zones)
		{

			this.zone = zone_to;

			zones[this.zone-1].setCount(1);

			this.svg_object = d3
				.select("#zone_svg")
				.append("circle")
					.attr("cx", getXforZone(this.zone))
					.attr("cy", getYforZone())
					.attr("r", "25")
					.attr("visibility", "hidden")
					.style("stroke", "#000000")
					.style("stroke-width", 4)
					.style("fill", "#FFFFCC"); // "#FFFFCC" or getRandomColor()

			this.setColour(zone_to, zones);


// 			var text = svgContainer.selectAll("text")
// 13                        .data(circleData)
// 14                        .enter()
// 15                        .append("text");
		};

		// method "moveTo" to move the SVG object of a user at a specified zone
		SVG_User.prototype.moveTo = function(zone_to, zones, speed)
		{
			var zone_before = this.zone;

			zones[this.zone-1].setCount(-1);
			this.zone = zone_to;

			this.svg_object
				.style("fill", zones[zone_before-1].getColour());

			this.svg_object
				.transition()
				.attr("cx", getXforZone(this.zone))
				.attr("cy", getYforZone())
				.attr("visibility", "visible")
				.duration(speed)
				.ease("linear")
				.style("fill", zones[zone_to-1].getColour());

			var that = this;

			setTimeout(function()
				{
					that.svg_object
						.attr("visibility", "hidden");

					zones[that.zone-1].setCount(1);
					that.setColour(zone_to, zones);
				},speed+100);
		};

		SVG_User.prototype.getId = function()
		{
			return this.spot_id;
		};

		SVG_User.prototype.setColour = function(zone_id, zones)
		{
			document.getElementById("spot_" + this.spot_id + "_last_zone").innerHTML=zones[zone_id-1].getName();
			document.getElementById("spot_" + this.spot_id + "_last_zone").style.background=zones[zone_id-1].getColour();
		};

// 		function zoneCreate()
// 		{
// 			d3.select("#zone_2")
// 				.append("rect")
// 					.attr("x", "35%")
// 					.attr("width", 250)
// 					.attr("height", 250)
// 					.style("fill", "#56880A")
// 					.style("stroke-width", 3)
// 					.style("stroke", "#000000");

// 			d3.select("#zone_3")
// 				.append("rect")
// 					.attr("x", "70%")
// 					.attr("width", 250)
// 					.attr("height", 250)
// 					.style("fill", "#8F4308")
// 					.style("stroke-width", 3)
// 					.style("stroke", "#000000");

// 			d3
// 				.select("#zone_svg")

// // 			var text = svgContainer.selectAll("text")
// // 13                        .data(circleData)
// // 14                        .enter()
// // 15                        .append("text");
// 		}

		function getXforZone(zone)
		{
			if (zone == 1 )
			{
				return "12.5%"; //12.5 Middle or getRandomNumber(5, 20)
			}
			else if (zone == 2 )
			{
				return "47.5%"; //47.5 Middle or getRandomNumber(40, 55)
			}
			else if (zone == 3 )
			{
				return "82.5%"; //82.5 Middle or getRandomNumber(75, 90)
			}
		}

		function getYforZone()
		{
			return "40%"; // 40 Middle or getRandomNumber(15, 67.5)
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

		function live(zoneMovementHistory, users, zones)
		{
			document.getElementById('time').innerHTML = "Live";
			for(var i = 0; i < zoneMovementHistory.length; i++)
			{
				if(userExist(zoneMovementHistory[i].spot_id, users) == false)
				{
					var users_temp = [new SVG_User(zoneMovementHistory[i].spot_id, zoneMovementHistory[i].zone_id)];
					users_temp[0].create(zoneMovementHistory[i].zone_id, zones); // Create an SVG object for that user in a zone
					users = users.concat(users_temp);
				}
			}
		}

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

		function startTime(zoneMovementHistory, users, zones, progress)
		{
			var speed_option = document.getElementById("speed_option");
			var speed_option_value = speed_option.options[speed_option.selectedIndex].value;
			var speed = speed_option_value * 1000;
			var speed_for_movement = (speed_option_value * 1000) / 2;

			var date = d3.time.format("%Y-%m-%d %H:%M:%S");
			var date = date.parse(zoneMovementHistory[zoneMovementHistory.length-1].created_at);

			var day=date.getDate();
			var month=date.getMonth();
			var year=date.getFullYear();
			var h=date.getHours();
			var m=date.getMinutes();
			var s=date.getSeconds();
			m = checkTime(m);
			s = checkTime(s);
			document.getElementById('time').innerHTML = day+"."+(month + 1)+"."+year+" "+h+":"+m+":"+s;

			var anythingChanged = false;

			// if(date.getTime() >= movement_date.getTime())
			// {
				for(var i = 0; i < users.length; i++)
				{
					if(users[i].getId() == zoneMovementHistory[zoneMovementHistory.length-1].spot_id)
					{
						users[i].moveTo(zoneMovementHistory[zoneMovementHistory.length-1].zone_id, zones, speed_for_movement);
						setTimeout(function()
						{
							progress.update(zoneMovementHistory.length);
						},speed_for_movement+100);

						zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
						anythingChanged = true;
						break;
					}
				}

				if(anythingChanged === false)
				{
					var users_temp = [new SVG_User(zoneMovementHistory[zoneMovementHistory.length-1].spot_id, zoneMovementHistory[zoneMovementHistory.length-1].zone_id)];
					users_temp[0].create(zoneMovementHistory[zoneMovementHistory.length-1].zone_id, zones); // Create an SVG object for that user in a zone
					zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
					users = users.concat(users_temp);
					progress.update(zoneMovementHistory.length);
				}
			// }

			if(zoneMovementHistory.length > 0)
			{
				setTimeout(function(){startTime(zoneMovementHistory, users, zones, progress)}, speed);
			}
			else
			{
				setTimeout(function()
				{
					document.getElementById('time').innerHTML = "Finished!";
				},speed_for_movement+100);
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