/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('studentController', function ($scope, $http, $location, $alert) {

	$scope.showContent = function($event, exam) {
		var arrow = $($event.target);
		var item = $(arrow.parents(".deadline-list").get(0));

		if(arrow.hasClass('glyphicon-chevron-down')){
			arrow.removeClass('glyphicon-chevron-down');
			arrow.addClass('glyphicon-chevron-up');
			item.find(".deadline-list-content").slideDown();
			item.find(".deadline-list-header").addClass('no-bottom-border');
		}
		else {
			arrow.removeClass('glyphicon-chevron-up');
			arrow.addClass('glyphicon-chevron-down');
			item.find(".deadline-list-content").slideUp();
			item.find(".deadline-list-header").removeClass('no-bottom-border');
		}
	}

	$scope.uploadFile = function(item) {
		var form = $("#"+item.id);
		form.submit();
		/*;
		$http({
	        url: 'uploadfile.html',
	        method: "POST",
	        headers : {'Content-Type': 'multipart/form-data; charset=UTF-8'} ,
	        data: form.serialize()
	    })
	    .success(function(data) {

	    }).
	    error(function(){

	    });*/
	}

	$scope.stdName = function (name) {
		if(!name)
			return "";
		var aux = name.split('.');
		var extension = aux[aux.length-1];
		return name.replace(/-\d+.\w{3,4}$/g, '') + '.' + extension;
	}

	function resizeTitleWidth() {
		$("#main-deadlines .deadline-list-header span.title").each(function(index, el) {
			var parentWidth = $(this).parent().width();
			var siblingsWidth = 0;
			$(this).siblings().each(function(index, el) {
				var right = parseInt(($(this).css("right")).replace('/px/g', ''));
				siblingsWidth += $(this).width() + right;
			});

			$(this).width(parentWidth - siblingsWidth);
		});
	}

	function askDeadlines() {
		$http.get("getstudentdeadlines.html")
		.success(function(data) {
			$scope.deadlines = data;
			resizeTitleWidth();
		});
	}

	function init() {
		askDeadlines();

		$(window).resize(function(event) {
			resizeTitleWidth();
		});
	}
	
	init();
});

app.directive('afterLoad', function() {
    return function(scope) {
        uploadFilesMask();
    };
});