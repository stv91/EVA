/* global $ */
function genPostLink(){
	$('a.post-link').each(function () {
		$(this).click(function (e) {
			e.preventDefault();
			$.post($(this).attr("href"));
		});
	});
}

function uploadFilesMask() {
	$('span[upload-file]').each(function(){
		var input = $(this).find("input");
		var label = $(this).find("label");
		input.hide();
		$(this).find("button").click(function(){
			input.trigger("click");
		});
		input.change(function () {
			label.text(input.val());
		});
	});
}

function dropdownNoClosable(){
	$(".noClosable ul input, .noClosable ul select").click(function(event) {
		event.stopPropagation();
	});
}

function changeDegree() {
	$("a.option-degree").each(function() {
		$(this).click(function (e) {
			e.preventDefault();
			var url = $(this).attr("href");
			var degree = $(this).attr("degree");
			$.post(url, {degree: degree}).done(function() {
				window.location.href = url;
			});
		});
	});
}

/****************************************************************/

$(function(){
	genPostLink();
	changeDegree();
	uploadFilesMask();
	dropdownNoClosable();
});