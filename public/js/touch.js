$(window).load(function(){
    /**
     * Touchscreen listeners
     */
    
    function touchHandler(event)
    {
        var touches = event.changedTouches,
            first = touches[0],
            type = "";
             switch(event.type)
        {
            case "touchstart": type = "mousedown"; break;
            case "touchmove":  type="mousemove"; break;        
            case "touchend":   type="mouseup"; break;
            default: return;
        }
     
        var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent(type, true, true, window, 1, 
                                  first.screenX, first.screenY, 
                                  first.clientX, first.clientY, false, 
                                  false, false, false, 0/*left*/, null);
        first.target.dispatchEvent(simulatedEvent);
        event.preventDefault();
    }
     
    function init() 
    {
        document.addEventListener("touchstart", touchHandler, true);
        document.addEventListener("touchmove", touchHandler, true);
        document.addEventListener("touchend", touchHandler, true);
        document.addEventListener("touchcancel", touchHandler, true);    
    }
    
    $('.draggable').draggable();
    
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