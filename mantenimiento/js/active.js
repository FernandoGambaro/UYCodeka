$(function(){

	var this_js_script = $('script[src*=active]'); // or better regexp to get the file name..

var inicio = this_js_script.attr('data-inicio');   
if (typeof inicio === "undefined" ) {
   var inicio = 'some_default_value';
}

	
	var note = $('#note'),
		ts = new Date(2019, 0, 1),
		newYear = true;
	
	if((new Date()) > ts){
		// The new year is here! Count towards something else.
		// Notice the *1000 at the end - time must be in milliseconds
		ts = (new Date(inicio)).getTime() + 60*60*1000;
		newYear = false;
	}
		
	$('#countdown').countdown({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			
			var message = "";
			
			message += days + " dias" + ( days==1 ? '':'s' ) + ", ";
			message += hours + " hora" + ( hours==1 ? '':'s' ) + ", ";
			message += minutes + " minuto" + ( minutes==1 ? '':'s' ) + " and ";
			message += seconds + " segundo" + ( seconds==1 ? '':'s' ) + " <br />";
			
			if(newYear){
				message += "faltan para año nuevo!";
			}
			
			
			note.html(message);
		}
	});
	
});