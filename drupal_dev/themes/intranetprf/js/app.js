var app = angular.module("myModule", ['angular.filter'])


app.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('($')
    $interpolateProvider.endSymbol('$)')
})
