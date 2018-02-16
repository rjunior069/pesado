app.controller('myController', ['$scope','$http', '$timeout', function ($scope, $http, $timeout) {

    console.log($scope);
    $scope.pagina = 0;
    $scope.objNumRErrorSearch = 0;
    $scope.objNumLastPageSearch = false;
    $scope.showMsg = false;



    $scope.submit = function(varStrType) {
      $scope.showMsg = false;
      $scope.showErrorSearch = false;
      $scope.showErrorSearchIntro = false;
      $scope.showErrorSearchPag = false;
      //$scope.frmSearchPainel.$setUntouched();
      //$scope.currentRecord={};
      var arrResultSearch = []; // JSON.stringify() > JSON.parse() ||||| Indices; 0 > elastic, 1 > drupal, 2 > lexml
      
      /*      POST http for ELASTIC     */
      $http({
        method: 'POST',
        url: 'https://homologacao.prf.gov.br/query_drupal/resultElastic.php',
        data: {
          'value' : $scope.dataText, 
          'page' : (varStrType == 't_search' ? 0 : $scope.pagina)
          // 'quantity' : $scope.pageSize,
          // 'current' : $scope.currentPage
          // string vazia OU sem resultados entao {"quantity":0}

        },
        headers: {'Content-Type': 'application/json'}
      })
      .then(function(response) {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          arrResultSearch[0] = JSON.stringify(response.data);

          if (arrResultSearch[0] == "\"\"") {
            $scope.objRElastic = 0;
            arrResultSearch[0] = '{"quantity":0}';
            response.data = JSON.parse('{"quantity":0}');

            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          } else if (arrResultSearch[0] == '{"quantity":0}') {
            $scope.objRElastic = 0;
            
            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          }

          if(JSON.stringify(response.data) != '{"quantity":0}' && $scope.objNumNoCallElastic == 0){
            $scope.showMsg = true;
            $scope.searchResultElastic = response.data;

            

                /*$http({
                  method: 'POST',
                  url: 'https://homologacao.prf.gov.br/query_drupal/resultElastic.php',
                  data: {
                    'value' : $scope.dataText, 
                    'page' : ($scope.pagina + 1)
                  },
                  headers: {'Content-Type': 'application/json'}
                })
                .then(function(response) {
                    if (JSON.stringify(response.data) == "\"\"" || JSON.stringify(response.data) == '{"quantity":0}') {
                      $scope.objNumNoCallElastic = 1;
                    }
                }, 
                function(response) { 
                  $scope.showMsg = true;
                })*/



          }
          else{
            $scope.showMsg = true;
            $scope.searchResultElastic = [];
            $scope.objNumNoCallElastic = 1;
          }

        }
      }, 
      function(response) { 
        $scope.showMsg = true;
      })
      /*  end post */


      /*      POST http for ELASTC     */
      var urlDrupal = 'https://homologacao.prf.gov.br/drupal_dev/rest/views/artigos?titulo='+$scope.dataText+'&texto='+$scope.dataText;
      $http({
        method: 'GET',
        url: urlDrupal
        // sem resultado entao []
      }).then(function successCallback(response) {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          arrResultSearch[1] = JSON.stringify(response.data);

          if (arrResultSearch[1] == '[]') {
            $scope.objRDrupal = 0;

            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          }

          var newString;
          var strRE = /\<a\s+href\=\"(?:\\\&quot\;)*(\/*\w+\/*node\/\d+)(?:\\\&quot\;)*\".+\<\/a\>/i;
          //var strRE = /\<a\s+href\=\"(\/*\w+\/*node\/\d+)\".+\<\/a\>/i;
          var transaction = [];
          $scope.restElastic = angular.fromJson(response.data);

          var i = 0;
          angular.forEach($scope.restElastic,function(num) {
            //newString = num.link.replace('<a href="','');
            //newString = newString.replace('" hreflang="en">view</a>','');
            newString = num.link;
            newString = newString.replace(strRE, '$1');

            transaction[i] = {
              titulo: num.titulo,
              texto: num.texto,
              sistema: "PAINEL PRF",
              link : ""+newString+""
            }
            i++;
          })

          if ($scope.objNumNoCallElastic == 1) {
            if (!$scope.objNumLastPageSearch && JSON.stringify(response.data) != '[]' && $scope.objNumNoCallDrupal == 0) {
              $scope.showMsg = true;
              $scope.searchResultDrupal = transaction;

              $scope.objRDrupal = 0;
              $scope.objNumNoCallDrupal = 1;
            } else {
              $scope.searchResultDrupal = [];
              $scope.objNumNoCallDrupal = 1;
            }
          } else {
            $scope.searchResultDrupal = [];
            $scope.objNumNoCallDrupal = 1;
          }

        }

      }, function errorCallback(response) {
        $scope.showMsg = true
      })
      /*  end post */


      /*      POST http for LEXML    */
      $http({
        method: 'POST',
        url: 'https://homologacao.prf.gov.br/query_drupal/resultLexml.php',
        data: {'value' : $scope.dataText },
        headers: {'Content-Type': 'application/json'}
        // sem resultados = {"LexML":null}
      })
      .then(function(response) {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          arrResultSearch[2] = JSON.stringify(response.data);

          if (arrResultSearch[2] == '{"LexML":null}') {
            $scope.objRLexml = 0;

            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          }

          if((JSON.stringify(response.data) != '{"LexML":null}')){
            var transaction = [];
            $scope.restLexm = angular.fromJson(response.data.LexML);

            var i = 0;
            angular.forEach($scope.restLexm,function(num) {
              transaction[i] = {
                titulo: num.title,
                texto: num.description,
                sistema: "LEXML - "+num.autoridade,
                link : num.url
              }
              i++
            })

            if ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 1) {
              if (!$scope.objNumLastPageSearch && $scope.objNumNoCallLexml == 0) {
                $scope.showMsg = true;
                $scope.searchResultLexml = transaction;

                $scope.objRLexml = 0;
                $scope.objNumLastPageSearch = true;
                $scope.objNumNoCallLexml = 1;
              } else {
                $scope.searchResultLexml = [];
                $scope.objNumLastPageSearch = true;
                $scope.objNumNoCallLexml = 1;
              }
            } else {
              $scope.searchResultLexml = [];
              $scope.objNumLastPageSearch = true;
              $scope.objNumNoCallLexml = 1;
            }

          }
          else{
            $scope.showMsg = true;
            $scope.searchResultLexml = [];
            $scope.objNumLastPageSearch = true;
            $scope.objNumNoCallLexml = 1;
          }

        }
      }, 
      function(response) { 
        $scope.showMsg = true;
      })



      $timeout(function() {
      }, 230);  
//alert('Num Erros Search: '+$scope.objNumRErrorSearch+' -Elastic: '+$scope.objRElastic+' -Drupal: '+$scope.objRDrupal+' -Lexml: '+$scope.objRLexml);
alert('Last Elastic: '+$scope.objNumNoCallElastic+' Last Drupal: '+$scope.objNumNoCallDrupal+' Last Lexml: '+$scope.objNumNoCallLexml);
      if (varStrType == 't_search') {
        if ($scope.dataText == "" || typeof $scope.dataText == 'undefined') {
          alert("Por favor, digite algo para buscar...");
          return;
        } else {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 0;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;
          //$scope.formData = {};
          //$scope.frmSearchPainel.$setPristine();
          //$scope.frmSearchPainel2.$setPristine();
          //$scope.submitted = false;

          $timeout(function() {
            if ($scope.objNumRErrorSearch == 3 && ($scope.objRElastic == 0 && $scope.objRDrupal == 0 && $scope.objRLexml == 0)) {
              $scope.objNumRErrorSearch = 0;
              $scope.showErrorSearchIntro = true;
              $scope.showErrorSearch = true;
            } else {
              $scope.showErrorSearchIntro = false;
              $scope.showErrorSearch = false;
            }
          }, 210);
        }
      } else if (varStrType == 't_pagination') {
        $scope.objNumRErrorSearch = 0;
        $scope.objRElastic = 1;
        $scope.objRDrupal = 1;
        $scope.objRLexml = 1;

        if ($scope.objNumLastPageSearch && ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 1 && $scope.objNumNoCallLexml == 1)) {
          $scope.showErrorSearchIntro = true;
          $scope.showErrorSearchPag = true;
          $scope.showErrorSearch = true;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
        } else {
          $scope.showErrorSearchIntro = false;
          $scope.showErrorSearchPag = false;
          $scope.showErrorSearch = false;
        }
      }



      $scope.increment = function() { 
      $scope.pagina++;
      $scope.objNumRErrorSearch = 0;
      $scope.objRElastic = 1;
      $scope.objRDrupal = 1;
      $scope.objRLexml = 1;

      console.log($scope.pagina);
      //$scope.page = $scope.pagina;
      $scope.submit('t_pagination')
      };


      $scope.decrement = function() { 
      $scope.pagina--;
      $scope.objNumRErrorSearch = 0;
      $scope.objRElastic = 1;
      $scope.objRDrupal = 1;
      $scope.objRLexml = 1;

      console.log($scope.pagina);
     //$scope.page = $scope.pagina;
      $scope.submit('t_pagination') 
      };

      /*  end post */

  /*
    GET http for news conhecimento
  */
  // $http({
  //   method: 'GET',
  //   url: 'https://homologacao.prf.gov.br/drupal_dev/rest/views/feed_noticia?sector=CONHECIMENTO'
  // }).then(function successCallback(response) {
  //     var newString
  //     var transaction = []
  //     $scope.feedNoticiaConhecimento = angular.fromJson(response.data)

  //     var i = 0
  //     angular.forEach($scope.feedNoticiaConhecimento,function(num) {
  //       newString = num.view_node.replace('<a href="','');
  //       newString = newString.replace('" hreflang="en">view</a>','');

  //       transaction[i] = {
  //         title: num.title,
  //         field_image: num.field_image,
  //         field_posicao: num.field_posicao,
  //         field_region: num.field_region,
  //         field_sector: num.field_sector,
  //         view_node : newString
  //       }
  //       i++
  //     })

  //     $scope.feedNoticiaConhecimento = transaction

  //   }, function errorCallback(response) {
  //     console.log('falha ao caregar noticias '+ response)
  //   })
  // /*end GET http for news */

  // /*
  //   GET http for news ascom
  // */
  // $http({
  //   method: 'GET',
  //   url: 'https://homologacao.prf.gov.br/drupal_dev/rest/views/feed_noticia?sector=ASCOM'
  // }).then(function successCallback(response) {
  //     var newString
  //     var transaction = []
  //     $scope.feedNoticiaAscom = angular.fromJson(response.data)

  //     var i = 0
  //     angular.forEach($scope.feedNoticiaAscom,function(num) {

  //       newString = num.view_node.replace('<a href="','');
  //       newString = newString.replace('" hreflang="en">view</a>','');

  //       transaction[i] = {
  //         title: num.title,
  //         field_image: num.field_image ,
  //         field_posicao: num.field_posicao,
  //         field_region: num.field_region,
  //         field_sector: num.field_sector,
  //         view_node : newString
  //       } 
  //       i++
  //     })

  //     $scope.feedNoticiaAscom = transaction
  //   }, function errorCallback(response) {
  //     console.log('falha ao caregar noticias '+ response)
  //   })
  // /*end GET http for news */

  // /*
  //   GET http for news nucom
  // */
  // $http({
  //   method: 'GET',
  //   url: 'https://homologacao.prf.gov.br/drupal_dev/rest/views/feed_noticia?sector=NUCOM'
  // }).then(function successCallback(response) {
  //     var newString
  //     var transaction = []
  //     $scope.feedNoticiaNucom = angular.fromJson(response.data)

  //     var i = 0
  //     angular.forEach($scope.feedNoticiaNucom,function(num) {

  //       newString = num.view_node.replace('<a href="','');
  //       newString = newString.replace('" hreflang="en">view</a>','');

  //       transaction[i] = {
  //         title: num.title,
  //         field_image: num.field_image ,
  //         field_posicao: num.field_posicao,
  //         field_region: num.field_region,
  //         field_sector: num.field_sector,
  //         view_node : newString
  //       } 
  //       i++
  //     })

  //     $scope.feedNoticiaNucom = transaction
  //   }, function errorCallback(response) {
  //     console.log('falha ao caregar noticias '+ response)
  //   })
  /*end GET http for news */




    }

}])