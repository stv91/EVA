/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('teacherController', function ($scope, $http, $location, $alert) {

	$scope.showContent = function($event) {
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

	$scope.stdName = function (name) {
		if(!name)
			return "";
		var aux = name.split('.');
		var extension = aux[aux.length-1];
		return name.replace(/-\d+.\w{3,4}$/g, '') + '.' + extension;
	}

	function setModalClass() {
        var width = $(window).width();
        $scope.modalClass = "modal fade";
        if(width < 768){
            $scope.modalClass += " modal-fullscreen force-fullscreen";
        }
        else {
            $("#search-modal .modal-header button").trigger('click');
        }
    }

	$scope.createDeadline = function() {
		send('managedeadline.html');
	}

	$scope.editDeadline = function(item) {
		send('managedeadline.html', {'id': item.id});
	}

	$scope.updateDeleteItem = function(item) {
		$scope.deadlineToDelete = item;
	}

	$scope.deleteDeadline = function() {
		console.log()
		if($scope.deadlineToDelete != null) {
			$http({
		        url: 'deletedeadline.html',
		        method: "POST",
		        headers : {'Content-Type': 'application/x-www-form-urlencoded'} ,
		        data: "id="+$scope.deadlineToDelete.id
		    })
			.success(function(data) {
		    	if(data == 'ERROR'){
		    		alert_mg.showAlert($alert, "error");
		    	}
		    	else {
		    		askDeadlines();
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
		$http.get("getteacherdeadlines.html")
		.success(function(data) {
			$scope.deadlines = data;
			resizeTitleWidth();
		});
	}

	var alert_mg = new AlertManager();
	function createMessages() {
		alert_mg.addAlert("error", "Error:", "La entrega no se ha podido eliminar.");
		alert_mg.addAlert("ok", "Entrega eliminada:", "La entrega se ha eliminado correctamente.", "success");	
	}

	function init() {
		$scope.deadlineToDelete = null;
		askDeadlines();

		setModalClass();
		$(window).resize(function(event) {
			resizeTitleWidth();
			setModalClass();
            $scope.$apply();
		});

		createMessages();
	}
	
	init();
});

app.directive('afterLoad', function() {
    return function(scope) {
        uploadFilesMask();

    };
});