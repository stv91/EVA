/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('questionsController', function ($scope, $http, $location, $alert) {

	$scope.questionToDelete = null;

	$scope.updateQuestionToDelete = function(question) {
		$scope.questionToDelete = question;
	}

	$scope.deleteQuestion = function(){
		if($scope.questionToDelete) {
			$http({
		        url: 'deletequestion.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+$scope.questionToDelete.id
		    }).success(function(data) {
		    	if(data == "ERROR"){
		    		alert_mg.showAlert($alert, "error");
		    	}
		    	else {
		    		alert_mg.showAlert($alert, "ok");
		    		askQuestions();
		    	}
		    })
		    .error(function() {
		    	alert_mg.showAlert($alert, "error");
		    });
		}
	}

	$scope.editQuestion = function(question) {
		send("editquestion.html", {'id': question.id});
	}

	$scope.addQuestion = function() {
		send("createquestions.html", {'id' : examID});
	}

	var alert_mg = new AlertManager();
	function createMessages() {
		alert_mg.addAlert("error", "Error:", "La pregunta no se ha podido eliminar.");
		alert_mg.addAlert("ok", "Pregunta eliminada:", "La pregunta se ha eliminado correctamente.", "success");
	}

	function askQuestions() {
    	$http({
	        url: 'getquestions.html',
	        method: "POST",
	        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
	        data: "id="+examID
	    }).success(function(data) {
	    	$scope.questions = data;
	    });
	}

	function askQuestionsForever() {
		askQuestions();
		setTimeout(askQuestionsForever, 10000);
	}

	function setModalClass() {
        var width = $(window).width();
        $scope.modalClass = "modal fade";
        if(width < 768){
            $scope.modalClass += " modal-fullscreen force-fullscreen";
        }
    }

	function init() {
		setModalClass();
		askQuestionsForever();
		createMessages();
	}

	init();

});