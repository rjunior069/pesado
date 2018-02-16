app.controller("autoInsc", ['$scope','$http', function ($scope, $http) {
  /*
    GET http for news conhecimento
  */ 
  $scope.feedNoticiaConhecimento = [
    {
      "title":"TESTE CONHECIMENTO",
      "field_image":"blabla",
      "field_position":"1",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"CONHECIMENTO"
    }
  ]
  /*end GET http for news */

  /*
    GET http for news ascom
  */
  $scope.feedNoticiaAscom = [
    {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"2",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
    {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"0",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
    {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"1",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
     {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"3",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
    {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"1",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
     {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"1",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    },
     {
      "title":"TESTE Ascom",
      "field_image":"blabla",
      "field_position":"4",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Ascom"
    }
 
  ]

  /*
    GET http for news nucom
  */
   $scope.feedNoticiaNucom = [
    {
      "title":"TESTE Nucom",
      "field_image":"blabla",
      "field_position":"4",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Nucom"
    },
    {
      "title":"TESTE Nucom",
      "field_image":"blabla",
      "field_position":"1",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Nucom"
    },
    {
      "title":"TESTE Nucom",
      "field_image":"blabla",
      "field_position":"3",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Nucom"
    },
    {
      "title":"TESTE Nucom",
      "field_image":"blabla",
      "field_position":"2",
      "field_region":"Distrito Federal",
      "view_node":"kkkkkkkkkkk",
      "field_setor":"Nucom"
    }
  ]
  /*end GET http for news */

}])