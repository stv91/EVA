/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('createQuestionController', function ($scope, $http, $location, $alert) {
	
	var alert_mg = new AlertManager();

	function createMessages() {
		alert_mg.addAlert("questionError", "Error en \"Pregunta\":", "Este campo no puede estar vacio");
		alert_mg.addAlert("correctAnswerError", "Error en \"Respuesta correcta\":", "Este campo no puede estar vacio");
		alert_mg.addAlert("answerError", "Error en \"Otras respuestas\":", "Debe añadirse al menos una respuesta no correcta");
		alert_mg.addAlert("ok", "Resgistrado:", "Su pregunta ha sido registrada", "success");
		alert_mg.addAlert("error", "Error:", "Su pregunta no se ha podido registrar");
	}

	function orderAnswerValues() {
		if($scope.form.answer1.text == "" && $scope.form.answer2.text == "" && $scope.form.answer3.text == "")
			return;

		if($scope.form.answer1.text == ""){
			$scope.form.answer1.text = $scope.form.answer2.text;
			$scope.form.answer2.text = $scope.form.answer3.text;
			$scope.form.answer3.text = "";
			orderAnswerValues();
		}
		if($scope.form.answer2.text == "" && $scope.form.answer3.text != "") {
			$scope.form.answer2.text = $scope.form.answer3.text;
			$scope.form.answer3.text = "";
		}
	}

	function updateAddAnswer() {
		$scope.addAnswer = ($scope.form.answer1.text == "" || $scope.form.answer2.text == "" || $scope.form.answer3.text == "")
					&& (!$scope.form.answer1.editing && !$scope.form.answer2.editing && !$scope.form.answer3.editing);
	}


	$scope.edit = function(obj, index) {
		$("textarea").hide();

		if(obj == undefined){
			if($scope.form.answer1.text == "") {
				obj = $scope.form.answer1;
				index = 2;
			}
			else if ($scope.form.answer2.text == ""){
				obj = $scope.form.answer2;
				index = 3
			}
			else{
				obj = $scope.form.answer3;
				index = 4;
			}
		}

		obj.editing = true;
		updateAddAnswer();
		$($("textarea").get(index)).show();
		$($("textarea").get(index)).focus()
	};

	$scope.edited = function(obj, index) {
		obj.editing = false;
		$("textarea").hide();
		if(obj.text.replace(/\s/g, "").length == 0){
			obj.text = "";
		}
		orderAnswerValues();
		updateAddAnswer();
	}

	function validateQuestion() {
		if($scope.form.question.text == ""){
			alert_mg.showAlert($alert, "questionError");
			return false;
		}
		if($scope.form.correctAnswer.text == ""){
			alert_mg.showAlert($alert, "correctAnswerError");
			return false;
		}
		if($scope.form.answer1.text == ""){
			alert_mg.showAlert($alert, "answerError");
			return false;
		}
		return true;
	}

	function resetValues() {
		$scope.form.question.text = "";
		$scope.form.correctAnswer.text = "";
		$scope.form.answer1.text = "";
		$scope.form.answer2.text = "";
		$scope.form.answer3.text = "";
	}

	$scope.sendQuestion = function() {
		if(validateQuestion()) {
			var data = {
				"question": $scope.form.question.text,
				"correctAnswer": $scope.form.correctAnswer.text,
				"answer1": $scope.form.answer1.text,
				"answer2": $scope.form.answer2.text,
				"answer3": $scope.form.answer3.text
			};
			var params = "exam=" + exam;
			if(question){
				params += "&question=" + question;
			}
			$http.post("addquestion.html?"+params, data)
			.success(function(data) {
				if(data == "OK") {
					alert_mg.showAlert($alert, "ok");
					if(!question)
						resetValues();
					else
						send("questions.html", {'id': exam});
				}
				else {
					alert_mg.showAlert($alert, "error");
				}
			});
		}
	}

	function askQuestionData() {
		if(question) {
			$http({
		        url: 'getquestiondata.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+question
		    }).success(function(data) {
		    	console.log(data)
		    	$scope.form.question.text = data.question;
		    	$scope.form.correctAnswer.text = data.correct_answer;
		    	$scope.form.answer1.text = data.answer1;
		    	$scope.form.answer2.text = data.answer2? data.answer2 : "";
		    	$scope.form.answer3.text = data.answer3? data.answer3 : "";
		    });
		}
	}

	function init() {
		$("textarea").hide();
		createMessages();

		$scope.form = {
			"question" : {text: "", editing: false},
			"correctAnswer" : {text: "", editing: false},
			"answer1": {text: "", editing: false},
			"answer2": {text: "", editing: false},
			"answer3": {text: "", editing: false},
		};
		$scope.addAnswer = true;
		askQuestionData();
	}

	init();
});