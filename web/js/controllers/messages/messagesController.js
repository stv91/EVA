
/* global doSubmit */
/* global AlertManager */
/* global angular */
/* global $ */
'use strict';

app.controller('messagesController', function ($scope, $http, $location, $alert) {

	var currentConversation = null;
	var alert_mg = new AlertManager();

	function createMessages() {
		alert_mg.addAlert("error", "Error:", "El mensaje no se ha podido enviar");
		alert_mg.addAlert("error-leave", "Error:", "No se pudó salir de la conversación");
		alert_mg.addAlert("ok-leave", "Conversación abandonarda:", "Ha abandonado la conversación con éxito.", "success");
	}

	$scope.showInfo = function(event, conversation) {
		var members = $(event.target).parent().find('.members');
		members.slideDown("slow", function() {
			setTimeout(function() {members.slideUp("slow");}, 3500);
		})
		event.stopPropagation();
	}

	$scope.slide = "left";
	$scope.togglePanel = function(event) {
		$scope.slide = $scope.slide == "left"? "right" : "left";
		$('#msg-panel>.content').animate({width: 'toggle'}, {
			progress: function() {
				var w = $('#msg-panel>.content').width();
				$('#msg-panel').width(w);
				var width = $(window).width();
				if(width >= 768){
					$('#write-panel').css('left', w+'px');
					$("#messages").css('left', w+'px');
					$("#new-conversation").css('left', w+'px');
				}
				else {
					$('#write-panel').css('left', '0');
					$('#messages').css('left', '0');
					$("#new-conversation").css('left', '0');
				}

			},
			duration: 800
		});
		if($scope.slide == "right") 
			$('#arrow-panel').animate({'margin-right': '-=19'}, 800);
		else
			$('#arrow-panel').animate({'margin-right': '+=19'}, 800);
	}

	function panelWidth() {
		var width = $(window).width();
		if(width < 768){
			$('#msg-panel>.content').width(width);
			if($('#msg-panel').width() > 0)
				$('#msg-panel').width(width);
		}
		else {
			$('#msg-panel>.content').width(300);
			if($('#msg-panel').width() > 0)
				$('#msg-panel').width(300);
		}
	}

	function contentLeft() {
		var width = $(window).width();
		if(width < 768){
			$('#write-panel').css('left', '0');
			$('#messages').css('left', '0');
			$("#new-conversation").css('left', '0');
		}
		else {
			if($scope.slide == "left") {
				$('#write-panel').css('left', '301px');
				$('#messages').css('left', '301px');
				$("#new-conversation").css('left', '301');
			}
			else {
				$('#write-panel').css('left', '0');
				$('#messages').css('left', '0');
				$("#new-conversation").css('left', '0');
			}
		}
	}

	function getTotalHeight(selector) {
		var height = $(selector).height();

		var mt = $(selector).css('margin-top');
		var mb = $(selector).css('margin-bottom');

		if(mt)
			height += parseInt(mt);
		if(mb)
			height += parseInt(mb);

		return height;
	}

	function listHeight() {
		var height = $(window).height();
		height -= 70;
		height -= getTotalHeight('#msg-panel .add-conversation-container');
		height -= getTotalHeight('#msg-panel .divider');
		height -= getTotalHeight('#msg-panel h4');

		var width = $(window).width();
		if(width < 768){
			$('#msg-panel .conversations').css('height', '100%');
		}
		else {
			$('#msg-panel .conversations').height(height);
		}
		
	}

	function setModalClass() {
        var width = $(window).width();
        $scope.modalClass = "modal fade";
        if(width < 768){
            $scope.modalClass += " modal-fullscreen force-fullscreen";
        }
    }

    var conversationToLeave = null;
    $scope.updateConversationToLeave = function(event, conversation) {
    	conversationToLeave = conversation.id;
    }
    $scope.leaveConversation = function() {
    	if(conversationToLeave != null) {
    		$http.get('leaveconversation.html?id=' + conversationToLeave)
    		.success(function(data) {
    			if(data == "ERROR") {
    				alert_mg.showAlert($alert, 'error-leave');
    			}
    			else {
    				alert_mg.showAlert($alert, 'ok-leave');
    				init();
    			}
    		})
    		.error(function() {
    			alert_mg.showAlert($alert, 'error-leave');
    		})
    	}
    }

	function askConversations() {
		$http.get('getconversations.html')
		.success(function(data) {
			$scope.conversations = data;
			if($scope.conversations.length > 0){
				$scope.selectConversation($scope.conversations[0]);
			}
		});
	}

	function askMessages() {
		$http.get('getmessages.html?id='+currentConversation.id)
		.success(function(data) {
			$scope.messages = data;
		})
	}

	$scope.selectConversation = function(conversation) {
		$scope.showMessages = true;
		currentConversation = conversation;
		$(".conversations>li").removeClass('selected');
		$("#"+conversation.id).addClass("selected");
		askMessages();
	}
	
	$scope.sendMessage = function() {
		var text = $("#write-panel textarea").val();
		var text = text.replace(/\n/g, "<br>");
		if(currentConversation != null && text != ""){
			$http.post("sendmessage.html", {conversation: currentConversation.id, text: text})
			.success(function(data) {
				if(data == "ERROR") {
					alert_mg.showAlert($alert, "error");
				}
				else {
					$("#write-panel textarea").val("");
					askMessages();
				}
			})
			.error(function() {
				alert_mg.showAlert($alert, "error");
			});
		}
	}

	function getSubjects() {
		$http.get("/materials/getsubjects.html").then(function(subjects) {
			$scope.subjects = subjects.data;
			if($scope.subjects.length > 0)
				$scope.subject = $scope.subjects[0];
				$scope.addedUsers = [];
				$scope.getUsers();
		});
	}

	$scope.getUsers = function () {
		$http.get("getusersbysubject.html?id="+$scope.subject.code)
		.then(function(users) {
			$scope.users = users.data;
			if($scope.users.length > 0)
				$scope.user = $scope.users[0].email;
		});
	}

	
	$scope.addUser = function() {
		var exists = false
		for(var i = 0; i < $scope.addedUsers.length; i++){
			if($scope.addedUsers[i].email == $scope.user){
				exists = true;
				break;
			}
		}
		if(!exists) {
			for(var i = 0; i < $scope.users.length; i++){
				if($scope.users[i].email == $scope.user){
					$scope.addedUsers.push($scope.users[i]);
					break;
				}
			}
		}
	}

	$scope.createConversation = function() {
		var data = {
			"subject": $scope.subject.code,
			"name": $scope.convName,
			"users": $scope.addedUsers
		}
		$http.post("createconversation.html", data);
	}

	function init() {
		$scope.showMessages = true;
		panelWidth();
		contentLeft()
		listHeight();
		setModalClass();

		$(window).resize(function(event) {
			panelWidth();
			contentLeft()
			listHeight();
			setModalClass();
        	$scope.$apply();
		});

		askConversations();
		createMessages();

		$scope.addedUsers = [];
		$scope.convName = "";
		getSubjects();	
	}

	init();
});

app.directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    $(".conversations>li:first-child").addClass('selected');
                });
            }
        }
    }
});