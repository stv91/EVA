var app = angular.module('EVA', ['ngSanitize', 'mgcrea.ngStrap']);

app.filter('html', ['$sce', function ($sce) { 
    return function (text) {
        return $sce.trustAsHtml(text);
    };    
}])