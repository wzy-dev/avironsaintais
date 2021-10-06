$( document ).ready(function() {
	$('input[id$="imageFile_file"]').change(function(e){
		$('input[id$="imageData"]').val($(e.target).val().match(/[^\\/]*$/)[0]);
	});
});