$(window).load(function(){
    /**
     * Touchscreen listeners
     */
    
    var percent = 100;
    var interval = 10;
    
	//This listens for the back button press
	document.addEventListener('tizenhwkey', function(e) {
        if(e.keyName == "back")
            tizen.application.getCurrentApplication().exit();
    });

	// Tap Gesture Enable
    var options = {
    	  tapHighlightColor: "rgba(5,0,0,0.9)" ,
    		  showTouches: true
    }; 
    
    $('.draggable').css('left', 0); 
    $('.draggable').css('top', 0); 

    $('.draggable').hammer(options).bind("pan", function(event) {
        var touch = event.gesture;
        var obj = $(event.target[0]);
        console.log(obj);

        // Place element where the finger is
        obj.css('left', parseInt(obj.css('left')) + touch.deltaX-25 + "px");
        obj.css('top', parseInt(obj.css('top')) + touch.deltaY-25 + "px");
        event.preventDefault();
    });

    $('.draggable').hammer(options).bind("swipeleft", function(event) {
    	$('#textbox').html("Swipe left");
        console.log(event);
        $(event.target).find('.nav-tabs li.active').next().find('a').click();
    });

    $('.draggable').hammer(options).bind("swiperight", function(event) {
    	$('#textbox').html("Swipe right");
        console.log(event);
        $(event.target).find('.nav-tabs li.active').prev().find('a').click();
    });

});