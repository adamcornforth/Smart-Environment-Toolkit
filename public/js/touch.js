// Gear 2 Swipe Gesture Tutorial
// ----------------------------------
//
// Copyright (c)2014 Dibia Victor, Denvycom
// Distributed under MIT license
//
// https://github.com/chuvidi2003/GearSwipeTutorial
$(window).load(function(){
    /**
     * Touchscreen listeners
     */
    
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


    $('.draggable').hammer(options).bind("tap", function(event) {
    	$('#textbox').html("Tap");
    }); 

    // Hold Gesture Enable
    $('.draggable').hammer(options).bind("hold", function(event) {
    	$('#textbox').html("Hold");
    });

    // Hold Gesture Enable
    $('.draggable').hammer(options).bind("dragright", function(event) {
    	$('#textbox').html("Drag Right");
    });

    $('.draggable').hammer(options).bind("dragleft", function(event) {
    	$('#textbox').html("Drag Left");
    });

    // Rotate Gesture Enable
    $('.draggable').hammer(options).bind("rotate", function(event) {
    	$('#textbox').html("Rotate");
    });

    // Swipe  Gesture
    $('.draggable').hammer(options).bind("swipeup", function(event) {
    	$('#textbox').html("Swipe up");
    });
    $('.draggable').hammer(options).bind("swipedown", function(event) {
    	$('#textbox').html("Swipe down");
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

    // Pinc gesture
    $('.draggable').hammer(options).bind("pinchin", function(event) {
    	$('#textbox').html("Pinch In");
    });
    $('.draggable').hammer(options).bind("pinchout", function(event) {
    	$('#textbox').html("Pinch Out");
    });

    /**
     * Brainsocket listeners
     */
    window.app = {};

    var baseUrl = $("body").data('bs-base-url');

    app.BrainSocket = new BrainSocket(
            new WebSocket('ws://' + baseUrl + ':8080'),
            new BrainSocketPubSub()
    );

    app.BrainSocket.Event.listen('app.zonechange',function(msg)
    {
        console.log(msg.client.data.event);
    });

    app.BrainSocket.Event.listen('app.heat',function(msg)
    {
        console.log(msg.client.data.event);
    });

    app.BrainSocket.Event.listen('app.light',function(msg)
    {
        console.log(msg.client.data.event);
    });

});