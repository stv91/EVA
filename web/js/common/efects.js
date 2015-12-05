function headerCollapse() {
	$(window).resize(function () {
		if(window.matchMedia('(min-width: 768px)').matches)
			$('#collapsable-links').slideDown(0, function () {
				$('#collapsable-links').css("display", "block");
			});
		else 
			$('#collapsable-links').slideUp(0, function () {
				$('#collapsable-links').css("display", "none");
			});
		
		
		$('#user-opcions').slideUp(0, function () {
			$('#user-opcions').css("display", "none");
		});
	});
	
	$('#user-opcions, #icon-user-mobile').click(function (params) {
		if($('#collapsable-links').css("display") == "block")
			$('#collapsable-links').slideUp(400, function () {
				$('#collapsable-links').css("display", "none");
			});
		if($('#user-opcions').css("display") == "block")
			$('#user-opcions').slideUp(400, function () {
				$('#user-opcions').css("display", "none");
			});
		else
			$('#user-opcions').slideDown(400, function () {
				$('#user-opcions').css("display", "block");
			});
	});
	
	$('#toggle-menu-btn').click(function () {
		if($('#user-opcions').css("display") == "block")
			$('#user-opcions').slideUp(400, function () {
				$('#user-opcions').css("display", "none");
			});
		if($('#collapsable-links').css("display") == "block")
			$('#collapsable-links').slideUp(400, function () {
				$('#collapsable-links').css("display", "none");
			});
		else
			$('#collapsable-links').slideDown(400, function () {
				$('#collapsable-links').css("display", "block");
			});
	});
}

$(document).ready(function () {
	headerCollapse();
});