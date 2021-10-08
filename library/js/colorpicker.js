$(document).ready(function(){
	var background_color ='';
	var elcolor = '';
    // change background color dynamically

		$('#change-color').colorpicker({ color: '#ffaa00', container: true	}
		
		).on('changeColor', function(e) {
		//console.log( e.color.toString('rgba'));
			background_color = e.color.toString('rgba');
			parent.document.getElementById("navbar").style.backgroundColor = background_color;	
			background_color = e.color.toHex();
			elcolor = invertColor(background_color);
			var cssVar = $('a', window.parent.document).css('color'); 
		   console.log(cssVar);
		   $('a', window.parent.document).css('color', elcolor); 
		   $('.dropdown-menu', window.parent.document).css('backgroundColor', background_color); 
//console.log(elcolor);

		}).on('hidePicker', function(){
			console.log( background_color);
			$.ajax({
				method: "POST",
				url: "save_change.php",
				data: { change_color:1, background: background_color}
			});		
			$("#showtext").show();
		}).on('showPicker', function(){
			$("#showtext").hide();
		});


	// Reset default background color
	$( "#reset-color" ).click(function() {
		parent.document.getElementById("navbar").style.backgroundColor = "";
		$.ajax({
			method: "POST",
			url: "save_change.php",
			data: {change_color:1, background: "#fff"}
		})
		.done(function(response) {			
		});
	});
	
});

function invertColor(hex) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    // invert color components
    var r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
        g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
        b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
}

function padZero(str, len) {
    len = len || 2;
    var zeros = new Array(len).join('0');
    return (zeros + str).slice(-len);
}