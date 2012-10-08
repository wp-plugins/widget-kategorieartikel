jQuery(document).ready(function($) {
	// With Content
	$("input[name=\"with_content\"]").change(function() {
		if($(this).val() == 1) {
			$("p.for_with_content").show();
		} else {
			$("p.for_with_content").hide();
		}
	});
	
	// With Thumbnail
	$("input[name=\"with_thumbnail\"]").change(function() {
		if($(this).val() == 1) {
			$("p.for_with_thumbnail").show();
		} else {
			$("p.for_with_thumbnail").hide();
		}
	});
});