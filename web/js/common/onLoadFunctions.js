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
		$(this).find("button").click(function(e){
			e.preventDefault();
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

function initTimeSpinner() {
	$.widget( "ui.timespinner", $.ui.spinner, {
	    options: {
	      // seconds
	      step: 60 * 1000,
	      // hours
	      page: 60
	    },
	 
	    _parse: function( value ) {
	      if ( typeof value === "string" ) {
	        // already a timestamp
	        if ( Number( value ) == value ) {
	          return Number( value );
	        }
	        return +Globalize.parseDate( value );
	      }
	      return value;
	    },
	 
	    _format: function( value ) {
	      return Globalize.format( new Date(value), "t" );
	    }
	});
	Globalize.culture("es-ES");
}

/****************************************************************/

$(function(){
	genPostLink();
	changeDegree();
	uploadFilesMask();
	dropdownNoClosable();
});