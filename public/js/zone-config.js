var MIN_DISTANCE = 10; // minimum distance to "snap" to a guide
var guides = []; // no guides available ... 
var innerOffsetX, innerOffsetY; // we'll use those during drag ... 

$(".draggable-zone").draggable({
	containment: "parent",
	snap: true,
	stop: function(event, ui) {
		saveDetails(event, ui, this, "updateZone", $('.zones-container').innerWidth());
	}
}).resizable({
	containment: "parent",
	handles: {
        'ne': '#negrip',
        'se': '#segrip',
        'sw': '#swgrip',
        'nw': '#nwgrip',
        'n': '#ngrip',
        'e': '#egrip',
        's': '#sgrip',
        'w': '#wgrip'
    },
	minHeight: 100, 
	minWidth: 150, 
	snap: true,
	resize: function(event, ui) {
		$(this).css('line-height', (ui.size.height*0.665) + "px");
	},
	stop:  function(event, ui) {
		saveDetails(event, ui, this, "updateZone", $('.zones-container').innerWidth());
	},
});

$(".draggable-object").draggable({
	containment: "parent",
	snap: true,
	stop: function(event, ui) {
		saveDetails(event, ui, this, "updateObject", $(this).parent('.draggable-zone').innerWidth());
	}
}).resizable({
	containment: "parent",
	handles: {
        'ne': '#negrip',
        'se': '#segrip',
        'sw': '#swgrip',
        'nw': '#nwgrip',
        'n': '#ngrip',
        'e': '#egrip',
        's': '#sgrip',
        'w': '#wgrip'
    },
	minHeight: 20, 
	minWidth: 100, 
	snap: true,
	resize: function(event, ui) {
		$(this).css('line-height', (ui.size.height - 7) + "px");
	},
	stop:  function(event, ui) {
		saveDetails(event, ui, this, "updateObject", $(this).parent('.draggable-zone').innerWidth());
	},
});

function saveDetails(event, ui, css, url, container_width) {
	var attr = {};
	if(ui.position) {
		attr['left'] = (ui.position.left/container_width)*100; 
		attr['top'] = ui.position.top;
	}

	if(ui.size) {
		attr['height'] = ui.size.height;
		attr['width'] = (ui.size.width/container_width)*100;
	}

	var zone_id = $(css).attr('data-object-id');

	$.ajax({
		url: "/zones/" + zone_id + "/" + url, 
		data: attr,
		type: 'POST',
		async: true,
		success: function(data) {
		  console.log(data);
		}
	});
		
}