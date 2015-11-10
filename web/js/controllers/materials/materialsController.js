/* global app */
'use strict';

app.controller('materialsController', function($scope, $http, $location, $alert) {

    var ID = materialID;
    var tinyDesc = null;
    var alert_mg = new AlertManager();

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

    $scope.refreshComments = function() {
        if (ID != null) {
            $http.get('getcomments.html?id=' + ID).
            success(function(data, status, headers, config) {
                $scope.comments = data;
            });
        }
    }

    function initCommentTinyMCE(id, commentID, afterSave) {
        var toolbar = "save | cancel | undo redo";
        if(!commentID) {
            commentID = null;
            toolbar = "save | undo redo";
        }

        tinymce.init({
            selector: id + ' textarea',
            menubar: false,
            statusbar: false,
            toolbar: toolbar,
            setup: function(editor) {
                editor.addButton('save', {
                    image: "/images/save.png",
                    onclick: function() {
                        var content = editor.getContent();
                        editor.setContent("");
                        $http.post("savecomment.html", {
                            id: ID,
                            content: content,
                            reply: commentID
                        });
                        $scope.refreshComments();
                        if(afterSave)
                            afterSave();
                    }
                });
                editor.addButton('cancel', {
                    image: "/images/cancel.png",
                    onclick: function() {
                        if(afterSave)
                            afterSave();
                    }
                });
            }
        });
    }

    function initDescTinyMCE() {
        tinymce.init({
            selector: '#descriptionEdit textarea',
            menubar: false,
            toolbar: "save | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            setup: function(editor) {
                editor.addButton('save', {
                    image: "/images/save.png",
                    onclick: function() {
                        $scope.description = editor.getContent();
                        $http.post("savedescription.html", {
                            id: ID,
                            desc: $scope.description
                        });
                        $scope.editing = false;
                        $scope.$apply();
                    }
                });
            }
        });


        initCommentTinyMCE("#commentEdit");
    }

    $scope.searchMaterials = function() {
        $scope.material_prew = false;
        ID = null;
        $http.post('searchmaterials.html', $scope.search).
        success(function(data, status, headers, config) {
            $scope.results = data;
        }).
        error(function(data, status, headers, config) {
            alert_mg.showAlert($alert, "error");
        });
    }

    $scope.upload = function() {
        $("#upload-form").submit();
    }

    $scope.askMaterial = function(id) {
        ID = id;
        $http.post('getmaterial.html?id=' + id).
        success(function(data, status, headers, config) {
            $scope.material_prew = true;

            $scope.material = data.original_name;
            $scope.date = data.date;
            $scope.type = data.type;
            $scope.description = data.description;

            $http.get('getcurrentuser.html').success(function(user) {
                $scope.owner = data.user == user;
            });

            $scope.material_src = "/materials/" + data.course + "/" + data.subject + "/" + data.local_name;
            var viewer_src = "/js/ViewerJS/#" + $scope.material_src;
            $("#viewer-iframe").html("<iframe class=\"doc-viewer \" src=\"" + viewer_src + "\" allowfullscreen webkitallowfullscreen></iframe>")

            $scope.refreshComments();
        }).
        error(function(data, status, headers, config) {
            alert_mg.showAlert($alert, "error");
        });
    }

    $scope.replyComment = function(obj, $event) {
        $("#replyComment").remove();

        var mainComment =$("#"+obj.id).children().first();
        mainComment.after("<div id=\"replyComment\"><textarea></textarea></div>");
        
        initCommentTinyMCE("#replyComment", obj.id, function() {
            $($event.target).show();
            $("#replyComment").remove();
        });

        $($event.target).hide();
    }

    $scope.deleteMaterial = function() {
        if(ID != null) {
            $http.post('deletematerial.html?id='+ID)
            .success(function(data, status, headers, config) {
                if(data == "OK") {
                    window.location = window.location.pathname;
                }
                else {
                    alert_mg.showAlert($alert, "error borrar");
                }
            })
            .
            error(function(data, status, headers, config) {
                alert_mg.showAlert($alert, "error borrar");
            });
        }
        else {
            alert_mg.showAlert($alert, "error borrar");
        }
    }

    $scope.setTinyContent = function() {
        tinyMCE.get(0).setContent($scope.description);
    }

    function init() {
        alert_mg.addAlert("error borrar", "Error eliminando el material.", "No se ha podido eliminar el material. Intentelo más tarde.");
        alert_mg.addAlert("error", "Error.", "Se ha producido un error, lamentamos las molestias.");

        $scope.courses = ["2015-16", "2014-15", "2013-14", "2012-13", "2011-12", "2010-11",
            "2009-10", "2008-09", "2007-08", "2006-07", "2005-06", "2004-05"
        ];

        $scope.search = {
            text: "",
            oficials: true,
            noOficials: true,
            course: "2015-16",
            subject: "-1"
        };

        $http.get("getsubjects.html").then(function(subjects) {
            $scope.subjects = subjects.data;
        });

        setModalClass();
        initDescTinyMCE();

        if(ID != null) {
            $scope.askMaterial(ID);
        }
        else {
            $scope.searchMaterials();
        }

        $scope.material_prew = false;
        $scope.owner = false;
        $scope.editing = false;
        

        $(window).resize(function(event) {
            setModalClass();
            $scope.$apply();
        });
    }

    init();
    
});

app.directive('matetiasOffsetDirective', function() {
    return function(scope, iElement, iAttrs) {
        if (scope.$last) {
            if ((scope.$index + 1) % 3 == 1) {
                angular.element(iElement).addClass('col-md-offset-4 col-lg-offset-4');
            } else if ((scope.$index + 1) % 3 == 2) {
                angular.element(iElement).prev().addClass('col-md-offset-2 col-lg-offset-2');
            }
        }
    };
});

app.directive('afterLoad', function() {
    return function(scope) {
        $("#materialsController").show();
    };
});