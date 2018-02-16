app.controller("result", ['$scope','$http', function ($scope, $http) {

  $scope.showMsg = false
  $scope.submit = function() {
    /*      POST http for ELASTC     */
    $http({
      method: 'POST',
      url: 'resultElastic.php',
      data: {'value' : $scope.form.data },
      headers: {'Content-Type': 'application/json'}
    })
    .then(function(response) {
      if(response.data != null ){
        $scope.showMsg = true
        $scope.searchResultElastic = response.data
      }
      else{
        $scope.showMsg = true
      }
    }, 
    function(response) { 
      $scope.showMsg = true
    })
  /*  end post */
  /*      POST http for ELASTC     */
  $http({
    method: 'GET',
    url: 'http://localhost/drupal/rest/views/artigos?titulo='
  }).then(function successCallback(response) {
      var newString
      var transaction = []
      $scope.feedNoticiaConhecimento = angular.fromJson(response.data)

      var i = 0
      angular.forEach($scope.feedNoticiaConhecimento,function(num) {
        newString = num.link.replace('<a href="','');
        newString = newString.replace('" hreflang="en">view</a>','');

        transaction[i] = {
          titulo: num.titulo,
          texto: num.texto,
          sistemas: num.sistemas,
          link : newString
        }
        i++
      })

      $scope.showMsg = true
      $scope.searchResultDrupal = transaction

    }, function errorCallback(response) {
      $scope.showMsg = true
    })
  /*  end post */
}


}])