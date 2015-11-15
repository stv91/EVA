/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('manageExamController', function($scope, $http, $location, $alert, $filter) {

	$scope.exam = {
		subject: "",
		date: "",
		startTime: "",
		duration: "",
		numQuestions: "",
		description: "",
		studentQuestions: false
	};

	$scope.limit =  function(event, limit) {
		var value = $(event.target).val();
		if(value.length >= limit){
			$(event.target).val(value.substring(0, limit));
		}
	}

	function isValidDate(dateString) {
		if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
			return false;

		var parts = dateString.split("/");
		var day = parseInt(parts[0], 10);
		var month = parseInt(parts[1], 10);
		var year = parseInt(parts[2], 10);

		if (year < 1000 || year > 3000 || month == 0 || month > 12)
			return false;

		var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

		if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
			monthLength[1] = 29;

		return day > 0 && day <= monthLength[month - 1];
	}

	function isValidTime(timeString) {
		if (!/^\d{1,2}:\d{2}$/.test(timeString))
			return false;

		var parts = timeString.split(":");
		var hour = parseInt(parts[0], 10);
		var minutes = parseInt(parts[1], 10);

		return hour >= 0 &&  hour <= 12 && minutes >= 0 && minutes <= 59;
	}

	function isValidNumber(number) {
		return /^\d+$/.test(number);
	}

	function validate() {
		for (var key in $scope.exam) {
			var val = $scope.exam[key];
			if(typeof val == 'string') {
				val = val.replace(/\s/g, '');
				if(val.length == 0) {
					alert_mg.showAlert($alert, "empty");
					return false;
				}
			}
		}

		if(!isValidDate($scope.exam.date)) {
			alert_mg.showAlert($alert, "date");
			return false;
		}

		if(!isValidTime($scope.exam.startTime)) {
			alert_mg.showAlert($alert, "time");
			return false;
		}

		if(!isValidTime($scope.exam.duration)) {
			alert_mg.showAlert($alert, "duration");
			return false;
		}

		if(!isValidNumber($scope.exam.numQuestions)){
			alert_mg.showAlert($alert, "num-questions");
			return false;
		}

		return true;
	}

	function getSubjects() {
		$http.get("/materials/getsubjects.html").then(function(subjects) {
			$scope.subjects = subjects.data;
			if($scope.exam.subject == "") {
				$scope.exam.subject = $scope.subjects[0];
			}
		});
	}

	$scope.createExam = function() {
		$scope.exam.description = tinyMCE.activeEditor.getContent();
		if(validate()){
			var data = {
				exam : examID,
				subject : $scope.exam.subject.code,
				date: $scope.exam.date,
				startTime: $scope.exam.startTime,
				duration: $scope.exam.duration,
				numQuestions: $scope.exam.numQuestions,
				description: $scope.exam.description,
				studentQuestions: $scope.exam.studentQuestions
			}
			//send("updateexam.html", data);
			$http.post("updateexam.html", data)
			.success(function(data) {
				if(data == "ERROR"){
					alert_mg.showAlert($alert, "error");	
				}
				else {
					document.location.href = "index.html";
				}
			})
			.error(function() {
				alert_mg.showAlert($alert, "error");
			});
		}
	}

	var alert_mg = new AlertManager();
	function createMessages() {
		alert_mg.addAlert("empty", "Error:", "Todos los campos son obligatorios");
		alert_mg.addAlert("date", "Error:", "La fecha no es correcta");
		alert_mg.addAlert("time", "Error:", "La hora de inicio no es correcta");
		alert_mg.addAlert("duration", "Error:", "La duracción no es correcta");
		alert_mg.addAlert("num-questions", "Error:", "El número de preguntas no es correcto");
		alert_mg.addAlert("error", "Error:", "El examen no se ha podido guardar");
	}

	function initDescTinyMCE() {
        tinymce.init({
            selector: '#description textarea',
            menubar: false,
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        });
    }

	function init() {
		initDescTinyMCE();
		createMessages();
		getSubjects();
	}

	init();
});