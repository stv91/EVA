/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('teacherController', function ($scope, $http, $location, $alert) {

	$scope.examToDelete = null;

	var alert_mg = new AlertManager();
	alert_mg.addAlert("error", "Error:", "El exámen no se ha podido eliminar.");
	alert_mg.addAlert("ok", "Examen eliminado:", "El examen se ha eliminado correctamente.", "success");

	function setModalClass() {
        var width = $(window).width();
        $scope.modalClass = "modal fade";
        if(width < 768){
            $scope.modalClass += " modal-fullscreen force-fullscreen";
        }
    }

    setModalClass();
    $(window).resize(function(event) {
        setModalClass();
        $scope.$apply();
    });

    function askForExams() {
    	$http.post("getexams.html").success(function(data, status, headers, config) {
            $scope.exams = data;
        });
    }

	function askForExamsForever() {
		askForExams();
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

	$scope.manageQuestions = function(item) {
		send("questions.html", {'id': item.id});
	}

	$scope.editExam = function(item) {
		if(item) {
			send("manageexam.html", {'id': item.id});	
		}
		else {
			send("manageexam.html");	
		}
	}

	$scope.updateDeleteItem = function(item) {
		$scope.examToDelete = item;
	}

	$scope.deleteExam = function() {
		if($scope.examToDelete != null) {
			$http({
		        url: 'deleteexam.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+$scope.examToDelete.id
		    })
			.success(function(data) {
		    	if(data == 'ERROR'){
		    		alert_mg.showAlert($alert, "error");
		    	}
		    	else {
		    		askForExams();
		    		alert_mg.showAlert($alert, "ok");
		    	}
		    })
		    .error(function() {
		    	alert_mg.showAlert($alert, "error");
		    });
		}
		else {
			alert_mg.showAlert($alert, "error");
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

		askForExamsForever();
	}

	init();
});