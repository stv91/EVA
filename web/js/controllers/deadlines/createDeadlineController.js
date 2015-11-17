/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('createDeadlineController', function ($scope, $http, $location, $alert) {

	$scope.deadline = {
		subject: "",
		name: "",
		date: "",
		description: ""
	}

	function getSubject(id) {
    	for(var i = 0; i < $scope.subjects.length; i++) {
    		if($scope.subjects[i].code == id){
    			return $scope.subjects[i];
    		}
    	}
    	return null;
    }

	function getDeadlineData() {
    	if(deadlineID) {
	    	$http({
		        url: 'getdeadline.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+deadlineID
		    }).success(function(data) {
		    	var parts = data.date.split('-');
		    	var date = parts[2] +'/'+ parts[1] +'/'+ parts[0];
		    	$scope.deadline = {
		    		subject: getSubject(data.subject),
					name: data.name,
					date: date,
					description: data.description
		    	};
		    	$('#description textarea').val(data.description);
		    	initDescTinyMCE();
		    });
		}
    }

	function getSubjects() {
		$http.get("/materials/getsubjects.html").then(function(subjects) {
			$scope.subjects = subjects.data;
			if($scope.deadline.subject == "") {
				$scope.deadline.subject = $scope.subjects[0];
				getDeadlineData();
			}
		});
	}

	var alert_mg = new AlertManager();
	function createMessages() {
		alert_mg.addAlert("empty", "Error:", "Todos los campos son obligatorios");
		alert_mg.addAlert("date", "Error:", "La fecha no es correcta");
		alert_mg.addAlert("error", "Error:", "La entrega no se ha podido guardar");
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

	function validate() {
		for (var key in $scope.deadline) {
			var val = $scope.deadline[key];
			if(typeof val == 'string') {
				val = val.replace(/\s/g, '');
				if(val.length == 0) {
					alert_mg.showAlert($alert, "empty");
					return false;
				}
			}
		}

		if(!isValidDate($scope.deadline.date)) {
			alert_mg.showAlert($alert, "date");
			return false;
		}

		return true;
	}

	$scope.createDeadline = function() {
		$scope.deadline.description = tinyMCE.activeEditor.getContent();
		if(validate()){
			var data = jQuery.extend({}, $scope.deadline);
			data.subject = data.subject.code;
			data.id = deadlineID;
			$http.post("updatedeadline.html", data)
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

	function initDescTinyMCE() {
        tinymce.init({
            selector: '#description textarea',
            menubar: false,
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        });
    }

	function init() {
		getSubjects();
		createMessages();
	}

	init();
});