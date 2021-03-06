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
		numQuestions: "10",
		description: "",
		studentQuestions: false
	};

	function getSubject(id) {
    	for(var i = 0; i < $scope.subjects.length; i++) {
    		if($scope.subjects[i].code == id){
    			return $scope.subjects[i];
    		}
    	}
    	return null;
    }

    function getExamData() {
    	if(examID) {
	    	$http({
		        url: 'getexam.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+examID 
		    }).success(function(data) {
		    	var date = data.date.split(' ')[0];
		    	var startTime = data.date.split(' ')[1];

		    	var parts = date.split('-');
		    	date = parts[2] +'/'+ parts[1] +'/'+ parts[0];

		    	parts = startTime.split(':');
		    	startTime = parts[0] +':'+parts[1];

		    	parts = data.duration.split(':');
		    	var duration = parts[0] +':'+parts[1];
		    	$scope.exam = {
		    		subject: getSubject(data.subject),
					date: date,
					startTime: startTime,
					duration: duration,
					numQuestions: data.num_questions,
					description: data.description,
					studentQuestions: data.student_questions == 1
		    	};
		    	$("#num-questions").spinner().spinner("value", data.num_questions);
		    	$("#duration").val(data.duration.replace(/:00$/, ''));
		    	$("#time").val(startTime);
		    	$('#description textarea').val(data.description);
		    	initDescTinyMCE();
		    });
		}
		else {
			initDescTinyMCE();
		}
    }

    function getSubjects() {
		$http.get("/materials/getsubjects.html")
		.then(function(subjects) {
			$scope.subjects = subjects.data;
			if($scope.exam.subject == "") {
				$scope.exam.subject = $scope.subjects[0];
				
			}
			getExamData();
		});
	}

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

		return hour >= 0 &&  hour <= 24 && minutes >= 0 && minutes <= 59;
	}

	function isValidNumber(number) {
		return /^\d+$/.test(number);
	}

	function validate() {
		console.log($scope.exam);
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

	function time2String(id){
		var value = $("#"+id).timespinner("value");
		if(value) {
			var date = new Date(value);
			var hour = date.getHours();
			var min = date.getMinutes();
			return (hour < 10? "0"+hour : hour) +":"+ (min < 10? "0"+min : min);
		}
		return "";
	}


	$scope.createExam = function() {
		$scope.exam.description = tinyMCE.activeEditor.getContent();
		$scope.exam.startTime = time2String("time");
		$scope.exam.duration = time2String("duration");
		$scope.exam.numQuestions = $("#num-questions").spinner().spinner("value");
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

    function initJqueryUIWidget() {
    	$( "#date" ).datepicker({
    		showOn: "both",
    		dateFormat: "dd/mm/yy",
    		minDate: new Date(new Date().getTime() + 24 * 60 * 60 * 1000),
    		buttonText: "<span class='glyphicon glyphicon-calendar'></span>"
		});
		$("#date").next('button').addClass('input-group-addon');

		initTimeSpinner();
		$("#time").timespinner();
		$("#duration").timespinner();
		$("#num-questions").spinner({
			min: 0,
			max: 100
		});
    }

	function init() {
		getSubjects();
		createMessages();
		initJqueryUIWidget();
	}

	init();
});