var current_cup_percent = -1;

function drink(percent) {

	var bottom_offset_px = 166;
	var cup_height_px = 155;
	var cup_full_ml = 330;
	var current_cup_ml = parseInt($('.water_level').html());
	var current_height_px = $('#CupOfCoffee #water').height();
	var new_ml = cup_full_ml * (percent / 100); 

	/**
	 * Decrements the ml counter
	 */
	$({someValue: current_cup_ml}).animate({someValue: new_ml}, {
		duration: 1000,
		easing:'swing', // can be anything
		step: function() { // called on every step
			// Update the element's text with rounded-up value:
			$('.water_level').html(Math.ceil(this.someValue) + "ml");
		}
	});

	/**
	 * Bit awkward to animate the last 5%
	 */
	if(percent <= 5) percent = 0;

	/**
	 * Work out how much to drag the water down, relative to cup height in px and percentage of water we're going to
	 */
	var new_height_px = cup_height_px * (percent / 100); 

	/**
	 * Bit hacky, but this is a way of making the water level drop slower above 70%, to account for extra cup width
	 */
	if(percent >= 90) new_height_px -= (10 * (percent/100));
	if(percent >= 80 && percent < 90) new_height_px -= (8 * (percent/100));
	if(percent >= 70 && percent < 80) new_height_px -= (5 * (percent/100));

	/**
	 * Calculate amount to roate the cup by
	 */
	var rotate = 90 - (80 * (percent / 100));
	if(percent <= 40) rotate -= 35 - 35 * (percent / 35);

	// console.log("Drank cup to " + percent + "% (" + new_ml + "ml, " + new_height_px + "px) angle: " + rotate);

	/**
	 * Animate our cup pouring out water
	 */
	$('#CupOfCoffee #water').stop().animate({queue: false, height: new_height_px + (bottom_offset_px + 6), rotate: rotate + "deg", }, 1000);
	$('#CupOfCoffee').stop().animate({queue: false, rotate: -(rotate) + "deg"}, 1000, function(){
		/**
		 * Rotate cup back into position
		 * Take water level back to right rotation
		 */
		$('#CupOfCoffee').animate({rotate: 0}, 1000); 
		$('#CupOfCoffee #water').animate({rotate: 0}, 1000).animate({height: new_height_px + (bottom_offset_px + 6)}, 1000);

	});

	return percent;
}

/**
 * Worker function to poll for water percent changes every 2.5s
 */
(function worker() {
  $.ajax({
    url: "/cup/percent", 
    async: true,
    success: function(data) {
      console.log("Data percent: " + data.percent + ", percent: " + current_cup_percent);
      if(data.percent != current_cup_percent) {
	      drink(data.percent);
	      current_cup_percent = data.percent;
	  }
    },
    complete: function() {
      // Schedule the next request when the current one's complete
      setTimeout(worker, 500);
    }
  });
})();