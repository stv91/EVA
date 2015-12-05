/* global app */
'use strict';

app.controller('doExamController', function($scope, $http, $location, $alert) {

	var alert_mg = new AlertManager();
	alert_mg.addAlert("error", "Error:", "Su examen no se ha podido validar.");

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

    function showMark(num) {
    	num = parseFloat(num);
    	if(num < 5) {
    		$scope.mark = { text: "Lamentablemente has sacado un " + num, class: "fail-exam" };
    	}
    	else {
    		$scope.mark = { text: "Enhorabuena, has sacado un " + num, class: "pass-exam" };
    	}
    }

    $scope.sendExam = function() {
    	var data = $("form").serialize();
    	var exam = $("form").attr("id");
    	$http({
	        url: 'correctexam.html?exam=' + exam,
	        method: "POST",
	        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
	        data: data
	    }).success(function(data) {
	    	if(data == 'Error'){
	    		alert_mg.showAlert($alert, "error");
	    	}
	    	else {
	    		showMark(data);
	    	}
	    })
	    .error(function() {
	    	alert_mg.showAlert($alert, "error");
	    });;
    }
});