$(function() {
	'use strict'

	$('.select2').select2({
		placeholder: 'Choose one',
		width: '100%'
	});
	
	$('#generalForm').parsley();
	$('#enviosForm').parsley();
	
	

});