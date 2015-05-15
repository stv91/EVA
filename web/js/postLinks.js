function genPostLink(){
	$('a.post-link').each(function () {
		$(this).click(function (e) {
			e.preventDefault();
			$.post($(this).attr("href"));
		});
	})
}

$(function(){
	genPostLink();
});