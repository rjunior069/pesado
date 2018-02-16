(function(angular) {
  'use strict';
angular.module('appModule', [])
  .controller('appController', ['$scope', 'filterFilter', function($scope, filterFilter) {
    
    $scope.names = [
      {name: 'Tobias', gender: 'm'},
      {name: 'Jeff', gender: 'm'},
      {name: 'Lisa', gender: 'f'},
      {name: 'Diana', gender: 'f'},
      {name: 'James', gender: 'm'},
      {name: 'Brad', gender: 'm'}
    ];
    
    $scope.filteredNames = filterFilter($scope.names, 'a'); // by text, it will check inside name and gender
    $scope.filteredNamesByFemale = filterFilter($scope.names, {gender: 'f'}); // by attribute
  
  }]);
})(window.angular);