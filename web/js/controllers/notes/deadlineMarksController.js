/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('deadlineMarksController', function ($scope, $http, $location, $alert) {
	
	var alert_mg = new AlertManager();
	function createMessages() {
		alert_mg.addAlert("error", "Error:", "Se ha producido un error guardando la nota.");
	}

	var currentValue = "";
	$scope.editMark = function(name) {
		var input = $('input[name='+name+']');
		var span = input.siblings('span');

		currentValue = input.val();

		input.show();
		input.focus();
		span.hide();
	}

	function changeValue(student, deadline, mark, cb) {
		var data = 'student='+student;
		data += '&deadline='+deadline,
		data +='&mark='+mark
		$http({
	        url: 'changedeadlinemark.html',
	        method: "POST",
	        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
	        data: data
	    }).success(function(data) {
	    	cb(data);
	    })
	    .error(function() {
	    	cb("ERROR");
	    });
	}

	$scope.validateMark = function(name) {
		var input = $('input[name='+name+']');
		var span = input.siblings('span');
		var value = input.val();

		var patt = new RegExp(/^\d{1,2}\.\d{1,2}$/);
    	if(patt.test(value)) {
    		changeValue(name, deadlineID, value, function(data) {
    			if(data == "ERROR"){
    				input.val(currentValue);
    				span.text(currentValue);
    				alert_mg.show($alert, 'error');
    			}
    			else {
    				input.val(value);
    				span.text(value);
    			}
    		})
    	}
    	else {
    		input.val(currentValue);
    		span.text(currentValue);
    	}
    	input.hide();
    	span.show()
	}

	function init() {
		$("table").tablesorter();
		createMessages();
	}
	init();
});