/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('studentController', function ($scope, $http, $location, $alert) {

	function askForExams() {
		$http.post("getexams.html").success(function(data, status, headers, config) {
            $scope.exams = data;
        });
        setTimeout(askForExams, 10000);
	}

	$scope.showContent = function($event, exam) {
		var arrow = $($event.target);
		var item = $(arrow.parents(".exam-list").get(0));

		if(arrow.hasClass('glyphicon-chevron-down')){
			arrow.removeClass('glyphicon-chevron-down');
			arrow.addClass('glyphicon-chevron-up');
			item.find(".exam-list-content").slideDown();
			item.find(".exam-list-header").addClass('no-bottom-border');
		}
		else {
			arrow.removeClass('glyphicon-chevron-up');
			arrow.addClass('glyphicon-chevron-down');
			item.find(".exam-list-content").slideUp();
			item.find(".exam-list-header").removeClass('no-bottom-border');
		}
	}

	function resizeTitleWidth() {
		$("#main-student .exam-list-header span.title").each(function(index, el) {
			var parentWidth = $(this).parent().width();
			var siblingsWidth = 0;
			$(this).siblings().each(function(index, el) {
				var right = parseInt(($(this).css("right")).replace('/px/g', ''));
				siblingsWidth += $(this).width() + right;
			});

			$(this).width(parentWidth - siblingsWidth);
		});
	}

	function init() {
		resizeTitleWidth();

		$(window).resize(function(event) {
			resizeTitleWidth();
		});

		askForExams();
	}

	$scope.propouseQuestions = function(item) {
		send("createquestions.html", {'id': item.id});
	}

	$scope.doExam = function(exam) {
		if(exam.open == 1) {
			send("doexam.html", {'exam': exam.id});
		}
	}

	init();
	
});