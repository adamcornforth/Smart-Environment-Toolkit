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

    function touchBind(element) {
        $(element).hammer().bind("swipeleft", function(ev) {
            console.log(ev);
            $(ev.target).find('.nav-tabs li.active').next().find('a').click();
            console.log('swipeleft'); 
        });

        $(element).hammer().bind("swiperight", function(ev) {
            console.log(ev);
            $(ev.target).find('.nav-tabs li.active').prev().find('a').click();
            console.log('swiperight'); 
        }); 

        $(element).hammer().bind("swipeup", function(ev) {
            console.log(ev);
            $(ev.target).find('.minimisable').slideUp();
            console.log('swipeup'); 
        }); 

        $(element).hammer().bind("swipedown", function(ev) {
            console.log(ev);
            $(ev.target).find('.minimisable').slideDown();
            console.log('swipedown'); 
        }); 
    }
     
    function init() 
    {
        document.addEventListener("touchstart", touchHandler, true);
        document.addEventListener("touchmove", touchHandler, true);
        document.addEventListener("touchend", touchHandler, true);
        document.addEventListener("touchcancel", touchHandler, true);    

        $('.draggable').each(function(index, element) {
            touchBind(element);  
        });    
    }

    function draggable() {
        console.log("Init draggables");
        $('.draggable').draggable({
                                snap:".snappable", 
                                opacity: 0.7, 
                                handle: ".handle, .dock", 
                                snapMode: "inner",
                                zIndex: 2000
                            }); 

         //This listens for the back button press
        document.addEventListener('tizenhwkey', function(e) {
            if(e.keyName == "back")
                tizen.application.getCurrentApplication().exit();
        });

        $('.draggable').each(function(index, element) {
            if($(element).data("hammer") === undefined) {
                touchBind(element);
            }
        });
    }

$(window).load(function(){
    /**
     * Touchscreen listeners
     */
    window.draggable = draggable;
    init(); 
    window.draggable();

    $('.snappable').droppable({ 
                                accept: '.draggable',
                                tolerance: 'pointer',
                                activate: function(event, ui) {
                                    $('.snappable').animate({'background-color': '#fefefe', 'border-color': '#666'}, 200);
                                },
                                deactivate: function(event, ui) {
                                    $('.snappable').animate({'background-color': 'transparent', 'border-color': 'transparent'}, 200);
                                },
                                drop: function(event, ui) {
                                    // reset this snappable area's panel left/right
                                    $(this).find('.panel').css('top', '').css('left', '');

                                     // If we're dragging from snappable-min area
                                    if($(ui.draggable).parent().hasClass('snappable-min')) {
                                        $(this).find('.panel > div, .panel > table, .panel > ul').hide();
                                        $(this).find('.panel > div.dock').show();
                                    }

                                    // make dropped item's parent's html equal this snappable area's html
                                    $(ui.draggable).parent().html($(this).html());

                                    // insert dropped item into snappable area
                                    $(this).html(ui.draggable);

                                    // reset dropped item top/left as it is snapping
                                    $(ui.draggable).css('top', '-10px').css('left', '').find('> div, > table, > ul').show();
                                    $(ui.draggable).animate({'top' : '0px'}).find('div.dock').hide();

                                    // reinit draggables
                                    setTimeout(function() {
                                        window.draggable();
                                    }, 250);

                                    
                                }
                            });

                            $('.snappable-min').droppable({ 
                                accept: '.draggable',
                                tolerance: 'pointer',
                                activate: function(event, ui) {
                                    $('.snappable-min').animate({'background-color': '#fefefe', 'border-color': '#666'}, 200);
                                },
                                deactivate: function(event, ui) {
                                    $('.snappable-min').animate({'background-color': 'transparent', 'border-color': 'transparent'}, 200);
                                },
                                drop: function(event, ui) {
                                    // reset this snappable area's panel left/right
                                    $(this).find('.panel').css('top', '').css('left', '');

                                    // If we're dragging from snappable area
                                    if($(ui.draggable).parent().hasClass('snappable')) {
                                        $(this).find('.panel > div, .panel > table, .panel > ul').show();
                                        $(this).find('.panel > div.dock').hide();
                                    }

                                    // make dropped item's parent's html equal this snappable area's html
                                    $(ui.draggable).parent().html($(this).html());

                                    // insert dropped item into snappable area
                                    $(this).html(ui.draggable);

                                    $(ui.draggable).find('> div, > table, > ul').hide();
                                    $(ui.draggable).find('div.dock').show();

                                    // reset dropped item top/left as it is snapping
                                    $(ui.draggable).css('top', '-10px').css('left', '');
                                    $(ui.draggable).animate({'top' : '0px'});

                                    // reinit draggables
                                    setTimeout(function() {
                                        window.draggable();
                                    }, 250);

                                    
                                }
                            });

});