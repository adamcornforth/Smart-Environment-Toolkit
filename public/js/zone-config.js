var MIN_DISTANCE = 10; // minimum distance to "snap" to a guide
var guides = []; // no guides available ... 
var innerOffsetX, innerOffsetY; // we'll use those during drag ... 

$( ".draggable-zone" ).draggable({
	containment: "parent",
	snap: true,
	stop: function(event, ui) {
		saveDetails(event, ui, this);
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
		$(this).css('line-height', ui.size.height + "px");
	},
	stop:  function(event, ui) {
		saveDetails(event, ui, this);
	},
});

function saveDetails(event, ui, css) {
	var container_width = $('.zones-container').innerWidth();
	var attr = {};
	if(ui.position) {
		attr['left'] = (ui.position.left/container_width)*100; 
		attr['top'] = ui.position.top;
	}

	if(ui.size) {
		attr['height'] = ui.size.height;
		attr['width'] = (ui.size.width/container_width)*100;
	}

	var zone_id = $(css).attr('data-zone-id');

	$.ajax({
		url: "/zones/" + zone_id + "/updateZone", 
		data: attr,
		type: 'POST',
		async: true,
		success: function(data) {
		  console.log(data);
		}
	});
		
}