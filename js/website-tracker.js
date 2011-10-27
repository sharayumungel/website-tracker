/*
 * website-tracker.js
 * 
 * Created by Frank on 2011-10-04
 */

// use jQuery to make sure DOM is ready and loaded
$(document).ready(function() {
	
	// when the only radio button is checked, do something...
	$('input:radio').change(function() {
		
		// for site relaunch: hide unnecessary form fields
		if ($('input:radio[name=status]:checked').val() == 'relaunch') {
			$('.forlaunch').hide('slow')
		}
		
		// if user changes mind, then reveal previously hidden form fields
		if ($('input:radio[name=status]:checked').val() == 'launch') {
			$('.forlaunch').show('slow')
		}	
		
	});

});
 







