/*
** Class: Zone
*/
function SVG_Zone(zone_id, place, name, colour)
{
	this.zone_id = zone_id;
	this.colour = colour;
	this.name = name;
	this.place = place;
	this.zone = d3
		.select("#zone_" + zone_id)
			.append("rect")
				.attr("x", place + "%")
				.attr("y", "10%")
				.attr("width", 257.5)
				.attr("height", 257.5)
				.style("fill", this.colour)
				.style("stroke-width", 3)
				.style("stroke", "#000000");
	this.value = 0;
	var that = this;

	this.circle_lable_size_difference_small = 12;
	this.circle_lable_size_difference_big = 7.5;
	this.count_isbig = false;
	this.count = d3
		.select("#zone_" + zone_id)
			.append("text")
				.attr("x", this.place + this.circle_lable_size_difference_small + "%")
				.attr("y", "9%")
				.attr("font-size", "30px")
				// .attr("x", this.place + this.circle_lable_size_difference + "%")
				// .attr("y", "60%")
				// .attr("font-size", "190px")
				.text(this.value)
				.attr("fill", "black")
				.on("click", function() { that.switchCountSize() });
				// .style("stroke-width", 0)
				// .style("stroke", "#000000");
	console.log("Created Zone with id:" + zone_id);
}
SVG_Zone.prototype.setCount = function(number)
{
	this.value = this.value + number;
	if(this.value == 10)
	{
		this.circle_lable_size_difference_small = this.circle_lable_size_difference_small / 1.1;
		this.circle_lable_size_difference_big = this.circle_lable_size_difference_big / 2;
		if(this.count_isbig)
		{
			this.count
				.attr("x", this.place + this.circle_lable_size_difference_big + "%")
				// .attr("x", this.place + this.circle_lable_size_difference_small + "%");
		}
		else
		{
			this.count
				.attr("x", this.place + this.circle_lable_size_difference_small + "%")

		}
	}
	this.count
		.text(this.value);
};
SVG_Zone.prototype.switchCountSize = function()
{
	if(this.count.style("font-size") != "190px")
	{
		this.count_isbig = true;
		this.count
			.attr("x", this.place + this.circle_lable_size_difference_big + "%")
			.attr("y", "70%")
			.attr("font-size", "190px");
	}
	else
	{
		this.count_isbig = false;
		this.count
			.attr("x", this.place + this.circle_lable_size_difference_small + "%")
			.attr("y", "9%")
			.attr("font-size", "30px");
	}
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
	// this.setMovingStatus(false);
	console.log("Created User with id:" + spot_id);
}
// method "create" to create an SVG object for a user at a specified zone
SVG_User.prototype.create = function(zone_to, zones, seats, date_string)
{
	this.zone = zone_to;
	this.moving = false;
	var seat = seats.takeSeat();
	if(seat != null)
	{
		this.seat_X = seat[0];
		this.seat_Y = seat[1];
		zones[this.zone-1].setCount(1);
		this.svg_object = d3
			.select("#zone_svg")
			.append("g")

		this.svg_object
			.append("circle")
				.attr("cx", getXforZone(this.zone, this.seat_X))
				.attr("cy", getYforZone(this.seat_Y))
				.attr("r", circle_size)
				// .attr("visibility", "hidden")
				.style("stroke", "#000000")
				.style("stroke-width", circle_stroke_size + "px")
				.style("fill", this.colour); // "#FFFFCC" or getRandomColor()

		this.svg_object
			.append("text")
				// .attr("x", (this.svg_object.select("circle").attr("cx").replace("%", "") / 1.4) + "%")
				// .attr("y", (this.svg_object.select("circle").attr("cy").replace("%", "") * 1.3) + "%")
				.attr("x", (this.svg_object.select("circle").attr("cx").replace("%", "") - text_position_difference_X) + "%")
				.attr("y", (this.svg_object.select("circle").attr("cy").replace("%", "") - text_position_difference_Y) + "%")
				.attr("font-size", circle_label_size + "px")
				.attr("fill", "white")
				.text(document.getElementById("spot_" + this.spot_id + "_full_name").innerHTML.charAt(0));

		this.printColourForZone(zone_to, zones);
		this.printColourForName();
		this.printTime(date_string);
		return true;
	}
	else
	{
		return false;
	}
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
	this.svg_object.select("circle")
		.transition()
		.attr("cx", getXforZone(this.zone, this.seat_X))
		// .attr("cy", getYforZone(1))
		.attr("visibility", "visible")
		.duration(speed)
		.ease("linear");

	this.svg_object.select("text")
		.transition()
		.attr("x", (getXforZone(this.zone, this.seat_X).replace("%", "") - text_position_difference_X) + "%")
		.duration(speed)
		.ease("linear");

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
SVG_User.prototype.shrink = function()
{
	var seat = seats.takeSeat();
	this.seat_X = seat[0];
	this.seat_Y = seat[1];

	this.svg_object.select("circle")
		.attr("cx", getXforZone(this.zone, this.seat_X))
		.attr("cy", getYforZone(this.seat_Y))
		.attr("r", circle_size)
		.style("stroke-width", circle_stroke_size + "px");

	this.svg_object.select("text")
		.attr("font-size", circle_label_size + "px")
		.attr("x", (this.svg_object.select("circle").attr("cx").replace("%", "") - text_position_difference_X) + "%")
		.attr("y", (this.svg_object.select("circle").attr("cy").replace("%", "") - text_position_difference_Y) + "%");
		// .attr("x", (this.svg_object.select("circle").attr("cx").replace("%", "") - 1) + "%")
		// .attr("y", (this.svg_object.select("circle").attr("cy").replace("%", "") - (-2.5)) + "%");
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
	something_is_moving = moving;
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
	this.setMax(number);
	this.current = 0;
	document.getElementById("zone_change_progress").style.width="0%";
	document.getElementById("zone_change_progress_parent").style.visibility="visible";
}
SVG_Progress.prototype.update = function(number)
{
	this.current = this.current + number;
	document.getElementById("zone_change_progress").style.width= ((this.current/this.max) *100) + "%";
};
SVG_Progress.prototype.setMax = function(number)
{
	this.max = number;
};
SVG_Progress.prototype.getMax = function()
{
	return this.max;
};
/*
** Class: Seat System
*/
function SVG_Seats(seat_per_Y, number_of_Y)
{
	this.seat_per_Y = seat_per_Y;
	this.number_of_Y = number_of_Y;
	this.set(seat_per_Y, number_of_Y);
}
SVG_Seats.prototype.takeSeat = function()
{
	var seat = this.seats[0];
	this.seats.shift();

	if(seat == null && !isAnythingMoving())
	{
		while(seat == null)
		{
			if((seat_decreaser / 1.075) > 1.13)
			{
				seat_decreaser = seat_decreaser / 1.075;
			}
			this.seat_per_Y = this.seat_per_Y * seat_decreaser;
			this.number_of_Y = this.number_of_Y * seat_decreaser;
			// console.log("seat_decreaser: " + seat_decreaser);
			// console.log("seat_per_Y: " + Math.floor(this.seat_per_Y));
			// console.log("number_of_Y: " + Math.floor(this.number_of_Y));
			this.set(this.seat_per_Y, this.number_of_Y);
			shrinkAll();
			seat = this.seats[0];
			this.seats.shift();
		}
	}
	return seat;
};
// SVG_Seats.prototype.takeSeatAndShrink = function(seat)
// {
// 	console.log(isAnythingMoving());
// 	if(isAnythingMoving())
// 	{
// 		setTimeout(function(){
// 			this.takeSeatAndShrink();
// 		}, 100);
// 	}
// 	else
// 	{

// 	}

// 	return seat;
// };
SVG_Seats.prototype.set = function(seat_per_Y, number_of_Y)
{
	this.seat_per_Y = seat_per_Y;
	this.seats = new Array();
	for(var i_Y = 1; i_Y < number_of_Y+1; i_Y++)
	{
		for(var i_X = 0; i_X < this.seat_per_Y; i_X++)
		{
			this.seats.push([i_X, i_Y]);
		}
	}
};
/*
** Class: General functions
*/
function isAnythingMoving()
{
	return something_is_moving;
};
function shrinkAll()
{
	if((circle_size_decreaser / 1.725) > 1)
	{
		circle_size_decreaser = circle_size_decreaser / 1.725;
	}
	if((difference_in_position_Y_increaser * 1.0275) < 1.4)
	{
		difference_in_position_Y_increaser = difference_in_position_Y_increaser * 1.0275;
	}
	console.log("circle_size_decreaser: " + circle_size_decreaser);
	difference_in_position_X = difference_in_position_X / circle_size_decreaser;
	difference_in_position_Y = difference_in_position_Y / circle_size_decreaser;
	X_start_position_for_zone = X_start_position_for_zone / circle_size_decreaser;
	console.log("circle_size_decreaser: " + circle_size_decreaser);
	circle_size = circle_size / circle_size_decreaser;
	console.log("circle_size: " + circle_size);
	circle_stroke_size = circle_stroke_size / circle_size_decreaser;
	circle_label_size = circle_label_size / circle_size_decreaser;
	text_position_difference_X = text_position_difference_X / circle_size_decreaser;
	text_position_difference_Y = text_position_difference_Y / circle_size_decreaser;
	difference_in_position_Y = difference_in_position_Y * 1.01;

	if(difference_in_position_Y_constant < 0)
	{
		difference_in_position_Y_constant = difference_in_position_Y_constant / 1.25;
	}
	if(difference_in_position_Y_constant < 0 && difference_in_position_Y_constant > -17.5)
	{
		difference_in_position_Y_constant = 0.25
	}
	if(difference_in_position_Y_constant >= 0 && (difference_in_position_Y_constant * difference_in_position_Y_increaser) <= 10)
	{
		difference_in_position_Y_constant = difference_in_position_Y_constant * difference_in_position_Y_increaser;
	}
	console.log("difference_in_position_Y_constant: " + difference_in_position_Y_constant);
	// console.log("difference_in_position_Y_constant: " + difference_in_position_Y_constant);

	for(var i = 0; i < users.length; i++)
	{
		users[i].shrink();
	}
	return false;
}

function getXforZone(zone, seat_X)
{
	var difference = difference_in_position_X * seat_X;
	if (zone == 1 )
	{
		return X_start_position_for_zone + difference + "%"; //12.5 Middle or getRandomNumber(5, 20)
	}
	else if (zone == 2 )
	{
		return ((X_start_position_for_zone + 35) + difference) + "%"; //47.5 Middle or getRandomNumber(40, 55)
	}
	else if (zone == 3 )
	{
		return ((X_start_position_for_zone + 70) + difference) + "%"; //82.5 Middle or getRandomNumber(75, 90)
	}
}
function getYforZone(rowNumber)
{
	if(difference_in_position_Y == null)
	{
		difference_in_position_Y = 25;
	}

	// if( rowNumber == 1)
	// {
	// 	return ((rowNumber * difference_in_position_Y) + difference_in_position_Y_constant) + "%";
	// }
	// else
	// {
	// 	return ((rowNumber * difference_in_position_Y) + 4) + "%";
	// }

	return ((rowNumber * difference_in_position_Y) + difference_in_position_Y_constant) + "%"; // 40 Middle or getRandomNumber(15, 67.5)
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
function live(zoneMovementHistory)
{
	var is_live = true;
	document.getElementById('time').innerHTML = "Live";
	if(zoneMovementHistory[0].id > last_id)
	{
		if(users.length < 1)
		{
			for(var i = 0; i < zoneMovementHistory.length; i++)
			{
				if(userExist(zoneMovementHistory[i].spot_id, users) == false)
				{
					progress.setMax(progress.getMax() + 1);
					progress.update(1);
					var users_temp = [new SVG_User(zoneMovementHistory[i].spot_id, zoneMovementHistory[i].zone_id)];
					var date_string = dateToString(zoneMovementHistory[i].created_at);
					users_temp[0].create(zoneMovementHistory[i].zone_id, zones, seats, date_string); // Create an SVG object for that user in a zone
					users = users.concat(users_temp);
				}
			}
		}
		else
		{
			var zoneMovementHistory_filtered = [];
			for(var i = 0; i < zoneMovementHistory.length; i++)
			{
				if(zoneMovementHistory[i].id > last_id)
				{
					zoneMovementHistory_filtered = zoneMovementHistory_filtered.concat(zoneMovementHistory[i]);
				}
			}
			progress.update(-(progress.getMax()));
			progress.setMax(zoneMovementHistory_filtered.length);
			setTimeout(function()
			{
				startTimer(zoneMovementHistory_filtered, is_live);
			},1000);
		}
	}
	last_id = zoneMovementHistory[0].id; // ID of the last change
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
function startTimer(zoneMovementHistory, is_live)
{
	var speed_slider_value = document.getElementById('speed_option_slider').value;
	var speed = speed_slider_value * 1000;
	var speed_for_movement = (speed_slider_value * 1000) / 2;
	var date_string = dateToString(zoneMovementHistory[zoneMovementHistory.length-1].created_at);
	if(!is_live)
	{
		document.getElementById('time').innerHTML = date_string;
	}
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
					// zoneMovementHistory sometimes drops to 1 element
					progress.update(1);
				},speed_for_movement+100);
				zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
				anythingChanged = true;
				break;
			}
			else
			{
				skiping_due_to_moving_status = true;
				setTimeout(function(){startTimer(zoneMovementHistory, is_live)}, 100);
			}
		}
	}

	if(skiping_due_to_moving_status == false)
	{
		if(anythingChanged === false)
		{
			var users_temp = [new SVG_User(zoneMovementHistory[zoneMovementHistory.length-1].spot_id, zoneMovementHistory[zoneMovementHistory.length-1].zone_id)];
			if(users_temp[0].create(zoneMovementHistory[zoneMovementHistory.length-1].zone_id, zones, seats, date_string)) // Create an SVG object for that user in a zone
			{
				zoneMovementHistory = zoneMovementHistory.splice(0, zoneMovementHistory.length-1); // Remove 1 object at the end
				users = users.concat(users_temp);
				progress.update(1);
			}
			// else
			// {
			// 	// skiping_due_to_moving_status = true;
			// 	// setTimeout(function(){startTimer(zoneMovementHistory, is_live)}, 100);
			// }
		}
		if(skiping_due_to_moving_status == false)
		{
			if(zoneMovementHistory.length > 0)
			{
				if(current_user == zoneMovementHistory[zoneMovementHistory.length-1].spot_id) // zoneMovementHistory[0].spot_id is the future user
				{
					setTimeout(function(){startTimer(zoneMovementHistory, is_live)}, speed);
				}
				else
				{
					setTimeout(function(){startTimer(zoneMovementHistory, is_live)}, speed * 0.2); // Make it faster
				}
			}
			else
			{
				if(!is_live)
				{
					setTimeout(function()
					{
						document.getElementById('time').innerHTML = "Finished!";
					},speed_for_movement+100);
				}
			}
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