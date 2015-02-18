var MIN_DISTANCE = 10; // minimum distance to "snap" to a guide
var guides = []; // no guides available ... 
var innerOffsetX, innerOffsetY; // we'll use those during drag ... 

$( ".draggable-zone" ).draggable({
	containment: "parent",
	snap: true,
	stop: function(event, ui) {
		saveDetails(event, ui); 
	}
}).resizable({
	containment: "parent",
	handles: "all",
	minHeight: 100, 
	minWidth: 150, 
	maxHeight: 500,
	maxWidth: 500,
	snap: true,
	stop: function(event, ui) {
		saveDetails(event, ui); 
	}
});

function saveDetails(event, ui) {
	// if(ui.position) {
	// 	console.log("Left: " + ui.position.left);
	// 	console.log("Top: " + ui.position.top);
	// }

	// if(ui.size) {
	// 	console.log("Height: " + ui.size.height);
	// 	console.log("Width: " + ui.size.width);
	// }
		
}