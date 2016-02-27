jQuery(document).ready(function() {
	$(".bottom").hide();
});

jQuery(document).on("click", ".top", function(){
	$(event.target).next().toggleSlide();
	$(event.target).next().next().toggleSlide();
});
