app.controller('myController', ['$scope', '$window', '$http', '$timeout', function ($scope, $window, $http, $timeout) {

    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
    // VERSAO 1.1: FUTURAMENTE SERA NECESSARIO UM REFATORAMENTO DO CODIGO ABAIXO, POIS O MESMO
    // FOI FEITO EM CIMA DO CODIGO REALIZADO PELO DESENVOLVEDOR ANTERIOR E QUE COMECOU O SISTEMA.
    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////


    /////////////////////////////////
    // FUNCAO AO CLICAR NO LINK DO SUBMENU MAIS SISTEMAS: DESATIVADO NO MOMENTO
    /////////////////////////////////
    /*$timeout(function() {
      angular.element(document.getElementById('idLinkSistemasSubMenu'))[0].click();
      angular.element(document.getElementById('submenu-sistemas'))[0].style.display = 'none';
    }, 310);

    angular.element(document.getElementById('idLinkSistemasSubMenu'))[0].onclick = function() {
      if (angular.element(document.getElementById('submenu-sistemas'))[0].style.display == 'block') {
        angular.element(document.getElementById('submenu-sistemas'))[0].style.display = 'none';
      } else {
        angular.element(document.getElementById('submenu-sistemas'))[0].style.display = 'block';
      }

      return false;
    };*/


    ///////////////////////////////////
    // FUNCAO AO CARREGAR A PAGINA INICIAL
    // Desabilitar fundo branco; Clique no brasao volta para pagina inicial;
    ///////////////////////////////////
    $scope.loadPage = function() {
      $scope.pagina = 0;
      $scope.objNumRErrorSearch = 0;
      $scope.objStrTypeSearchENL = 'all';
      $scope.bolOnlyTodos = 1;
      $scope.objNumLastPageSearch = false;
      $scope.bolFimRadioElasticS = false;

      angular.element(document.getElementById('idRCarregandoBody'))[0].style.display = 'none';

      angular.element(document.querySelector('.brasaoResult'))[0].onmouseover = function() {
        angular.element(document.querySelector('.brasaoResult'))[0].style.cursor = 'pointer';
      };

      angular.element(document.querySelector('.brasaoResult'))[0].onclick = function() {
        $window.location.href = '/drupal_dev';
  
        return false;
      };
    }


    ////////////////////////////////////////////
    // FUNCAO AO ENVIAR O FORMULARIO E/OU PAGINACAO
    // Inicializacao de variaveis: textos de erros; Ao buscar algo resetar radiobuttons(no click e ao buscar); funcao para trazer resultados do elasticsearch, drupal e lexml
    ////////////////////////////////////////////
    $scope.submit = function(varStrType) {
      $scope.showErrorSearch = false;
      $scope.showErrorSearchIntro = false;
      $scope.showErrorSearchPag = false;
      var arrResultSearch = [];


      // RESETAR RADIO BUTTONS DA PAGINA INICIAL E DA SEGUNDA PAGINA AO BUSCAR...
      if (varStrType == 't_search' && ($scope.dataText != "" && typeof $scope.dataText != 'undefined')) {
        if ((typeof $scope.bolOnlyWiki != 'undefined' && $scope.bolOnlyWiki == 1) || (typeof $scope.bolOnlyRespostas != 'undefined' && $scope.bolOnlyRespostas == 1) || (typeof $scope.bolOnlySei != 'undefined' && $scope.bolOnlySei == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 0;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          if ((typeof $scope.bolOnlyWiki != 'undefined' && $scope.bolOnlyWiki == 1)) {
            $scope.bolOnlyTodos = 0;
            $scope.bolOnlyWiki = 0;
            $scope.bolOnlyRespostas = 0;
            $scope.bolOnlySei = 0;
            $scope.bolOnlyNoticias = 0;
            $scope.bolOnlyLexMl = 0;

            $scope.bolOnlyWiki2 = 1;
            $scope.objStrTypeSearchENL = 'wikiprf';
          } else if ((typeof $scope.bolOnlyRespostas != 'undefined' && $scope.bolOnlyRespostas == 1)) {
            $scope.bolOnlyTodos = 0;
            $scope.bolOnlyWiki = 0;
            $scope.bolOnlyRespostas = 0;
            $scope.bolOnlySei = 0;
            $scope.bolOnlyNoticias = 0;
            $scope.bolOnlyLexMl = 0;

            $scope.bolOnlyRespostas2 = 1;
            $scope.objStrTypeSearchENL = 'prfrespostas';
          } else if ((typeof $scope.bolOnlySei != 'undefined' && $scope.bolOnlySei == 1)) {
            $scope.bolOnlyTodos = 0;
            $scope.bolOnlyWiki = 0;
            $scope.bolOnlyRespostas = 0;
            $scope.bolOnlySei = 0;
            $scope.bolOnlyNoticias = 0;
            $scope.bolOnlyLexMl = 0;

            $scope.bolOnlySei2 = 1;
            $scope.objStrTypeSearchENL = 'sei';
          }
        } else if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1) || (typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1) || (typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 0;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1)) {
            $scope.bolOnlyTodos2 = 0;
            //$scope.bolOnlyWiki2 = 0;
            $scope.bolOnlyRespostas2 = 0;
            $scope.bolOnlySei2 = 0;
            $scope.bolOnlyNoticias2 = 0;
            $scope.bolOnlyLexMl2 = 0;

            $scope.objStrTypeSearchENL = 'wikiprf';
          } else if ((typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1)) {
            $scope.bolOnlyTodos2 = 0;
            $scope.bolOnlyWiki2 = 0;
            //$scope.bolOnlyRespostas2 = 0;
            $scope.bolOnlySei2 = 0;
            $scope.bolOnlyNoticias2 = 0;
            $scope.bolOnlyLexMl2 = 0;

            $scope.objStrTypeSearchENL = 'prfrespostas';
          } else if ((typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1)) {
            $scope.bolOnlyTodos2 = 0;
            $scope.bolOnlyWiki2 = 0;
            $scope.bolOnlyRespostas2 = 0;
            //$scope.bolOnlySei2 = 0;
            $scope.bolOnlyNoticias2 = 0;
            $scope.bolOnlyLexMl2 = 0;

            $scope.objStrTypeSearchENL = 'sei';
          }
        } else if ((typeof $scope.bolOnlyNoticias != 'undefined' && $scope.bolOnlyNoticias == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 1;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          $scope.bolOnlyTodos = 0;
          $scope.bolOnlyWiki = 0;
          $scope.bolOnlyRespostas = 0;
          $scope.bolOnlySei = 0;
          $scope.bolOnlyNoticias = 0;
          $scope.bolOnlyLexMl = 0;

          $scope.bolOnlyNoticias2 = 1;
          $scope.objStrTypeSearchENL = 'noticias';
        } else if ((typeof $scope.bolOnlyNoticias2 != 'undefined' && $scope.bolOnlyNoticias2 == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 1;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          //$scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'noticias';
        } else if ((typeof $scope.bolOnlyLexMl != 'undefined' && $scope.bolOnlyLexMl == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 1;
          $scope.objNumNoCallDrupal = 1;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          $scope.bolOnlyTodos = 0;
          $scope.bolOnlyWiki = 0;
          $scope.bolOnlyRespostas = 0;
          $scope.bolOnlySei = 0;
          $scope.bolOnlyNoticias = 0;
          $scope.bolOnlyLexMl = 0;

          $scope.bolOnlyLexMl2 = 1;
          $scope.objStrTypeSearchENL = 'lexml';
        } else if ((typeof $scope.bolOnlyLexMl2 != 'undefined' && $scope.bolOnlyLexMl2 == 1)) {
          $scope.objNumLastPageSearch = false;
          $scope.objNumNoCallElastic = 1;
          $scope.objNumNoCallDrupal = 1;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.pagina = 0;

          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 0;
          //$scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'lexml';
        } else {
          $scope.pagina = 0;
          $scope.objNumNoCallElastic = 0;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.objNumLastPageSearch = false;

          $scope.bolOnlyTodos = 0;
          $scope.bolOnlyWiki = 0;
          $scope.bolOnlyRespostas = 0;
          $scope.bolOnlySei = 0;
          $scope.bolOnlyNoticias = 0;
          $scope.bolOnlyLexMl = 0;

          $scope.bolOnlyTodos2 = 1;
          $scope.objStrTypeSearchENL = 'all';
        }
      } else if (varStrType == 't_search' && ($scope.dataText == "" || typeof $scope.dataText == 'undefined')) {
        if ((typeof $scope.bolOnlyLexMl != 'undefined' && $scope.bolOnlyLexMl == 1)) {
          $scope.objNumLastPageSearch = false;
        } else if ((typeof $scope.bolOnlyLexMl2 != 'undefined' && $scope.bolOnlyLexMl2 == 1)) {
          $scope.objNumLastPageSearch = false;
        }
      }


      // FUNCAO HTTPS PARA BUSCAR OS RESULTADOS DO ELASTICSEARCH...
      if ($scope.objNumNoCallElastic == 0 && $scope.objNumNoCallDrupal == 0 && $scope.objNumNoCallLexml == 0) {

        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $http({
            method: 'POST',
            url: 'https://homologacao.prf.gov.br/query_drupal/resultElastic.php',
            data: {
              'value' : $scope.dataText.replace(/ /g, '+'), 
              'page' : (varStrType == 't_search' ? 0 : $scope.pagina),
              'type' : $scope.objStrTypeSearchENL
            },
            headers: {'Content-Type': 'application/json'}
          })
          .then(function(response) {
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
            } else {
              $scope.objRElastic = 1;

              for (xj = 0; xj < response.data.length; xj++) {
                response.data[xj].sistema = response.data[xj].sistema.substr(2);
                response.data[xj].texto = response.data[xj].texto.substr(0, 500);
              }
            }

            if(JSON.stringify(response.data) != '{"quantity":0}'){
              $scope.showMsg = true;
              $scope.searchResultElastic = response.data;
              $scope.objNumPaginaLPS = 999999999999999;
              $scope.searchResultDrupal = [];
              $scope.searchResultLexml = [];
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';




              $http({
                method: 'POST',
                url: 'https://homologacao.prf.gov.br/query_drupal/resultElastic.php',
                data: {
                  'value' : $scope.dataText.replace(/ /g, '+'), 
                  'page' : (varStrType == 't_search' ? 0 : ($scope.pagina + 1)),
                  'type' : $scope.objStrTypeSearchENL

                },
                headers: {'Content-Type': 'application/json'}
              })
              .then(function(response) {
                if (JSON.stringify(response.data) == "\"\"") {
                  $scope.objNumPaginaLPS = $scope.pagina;
                } else if (JSON.stringify(response.data) == '{"quantity":0}') {
                  $scope.objNumPaginaLPS = $scope.pagina;
                }
              }, 
              function(response) { 
                $scope.showMsg = true;
              })




            }
            else{
              // Se a busca vier do radiobutton do elasticsearch e nao tiver resultados, termina. Se nao vai para noticas...
              if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1) || (typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1) || (typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1)) {
                $scope.objRLexml = 0;
                $scope.bolFimRadioElasticS = true;

                $scope.showMsg = true;
                $scope.showErrorSearchIntro = true;
                $scope.showErrorSearchPag = true;
                $scope.showErrorSearch = true;
                $scope.objNumNoCallElastic = 1;
                $scope.objNumNoCallDrupal = 1;
                $scope.objNumNoCallLexml = 1;
                $scope.searchResultElastic = [];
                $scope.searchResultDrupal = [];
                $scope.searchResultLexml = [];
                $scope.objNumLastPageSearch = true;
                $scope.objNumPaginaLPS = $scope.pagina;
                angular.element(document.getElementById('pageup'))[0].disabled = true;
                angular.element(document.getElementById('pageup2'))[0].disabled = true;
                angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
              } else {
                $scope.showMsg = true;
                $scope.searchResultElastic = [];
                $scope.objNumNoCallElastic = 1;
                $scope.objNumNoCallDrupal = 0;
                $scope.objNumNoCallLexml = 0;
                $scope.objNumLastPageSearch = false;
                $scope.objNumPaginaLPS = $scope.pagina;
                angular.element(document.getElementById('pageup'))[0].disabled = false;
                angular.element(document.getElementById('pageup2'))[0].disabled = false;
                $scope.submit('t_pagination_next');
              }
            }
          }, 
          function(response) { 
            $scope.showMsg = true;
          })
        }
      } else {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $scope.showMsg = true;
          $scope.searchResultElastic = [];
        }
      }


      // FUNCAO HTTPS PARA BUSCAR OS RESULTADOS DO DRUPAL/NOTICIAS...
      if ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 0 && $scope.objNumNoCallLexml == 0) {
        var urlDrupal = 'https://homologacao.prf.gov.br/drupal_dev/rest/views/artigos?titulo='+$scope.dataText+'&texto='+$scope.dataText;
        $http({
          method: 'GET',
          url: urlDrupal
        }).then(function successCallback(response) {
          arrResultSearch[1] = JSON.stringify(response.data);

          if (arrResultSearch[1] == '[]') {
            $scope.objRDrupal = 0;

            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          } else {
            $scope.objRDrupal = 1;
          }

          var newString;
          var newStringTexto;
          var strRE = /\<a\s+href\=\"(?:\\\&quot\;)*(\/*\w+\/*node\/\d+)(?:\\\&quot\;)*\".+\<\/a\>/i;
          var strREHTMLTags = /<(?:.|\n)*?>/gm;
          var transaction = [];
          $scope.restElastic = angular.fromJson(response.data);

          var i = 0;
          angular.forEach($scope.restElastic,function(num) {
            newString = num.link;
            newString = newString.replace(strRE, '$1');
            newStringTexto = num.texto;
            newStringTexto = newStringTexto.replace(strREHTMLTags, '');

            transaction[i] = {
              titulo: num.titulo,
              texto: ""+newStringTexto+"",
              sistema: "PAINEL PRF",
              link : ""+newString+""
            }
            i++;
          })

          if (!$scope.objNumLastPageSearch && JSON.stringify(response.data) != '[]') {
            $scope.showMsg = true;
            $scope.searchResultDrupal = transaction;
            $scope.searchResultElastic = [];
            $scope.searchResultLexml = [];
            $scope.objNumNoCallDrupal = 1;
            $scope.objNumNoCallLexml = 0;
            angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';

            if ((typeof $scope.bolOnlyNoticias != 'undefined' && $scope.bolOnlyNoticias == 1) || (typeof $scope.bolOnlyNoticias2 != 'undefined' && $scope.bolOnlyNoticias2 == 1)) {
              angular.element(document.getElementById('pageup'))[0].disabled = true;
              angular.element(document.getElementById('pageup2'))[0].disabled = true;
            }
          } else {
            if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1) || (typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1) || (typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1) || (typeof $scope.bolOnlyNoticias2 != 'undefined' && $scope.bolOnlyNoticias2 == 1)) {
              $scope.objRLexml = 0;
              $scope.bolFimRadioElasticS = true;

              $scope.showMsg = true;
              $scope.showErrorSearchIntro = true;
              $scope.showErrorSearchPag = true;
              $scope.showErrorSearch = true;
              $scope.objNumNoCallElastic = 1;
              $scope.objNumNoCallDrupal = 1;
              $scope.objNumNoCallLexml = 1;
              $scope.searchResultElastic = [];
              $scope.searchResultDrupal = [];
              $scope.searchResultLexml = [];
              $scope.objNumLastPageSearch = true;
              //$scope.objNumPaginaLPS = $scope.pagina;
              angular.element(document.getElementById('pageup'))[0].disabled = true;
              angular.element(document.getElementById('pageup2'))[0].disabled = true;
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
            } else {
              $scope.showMsg = true;
              $scope.searchResultElastic = [];
              $scope.searchResultDrupal = [];
              $scope.objNumNoCallElastic = 1;
              $scope.objNumNoCallDrupal = 1;
              $scope.objNumNoCallLexml = 0;
              $scope.objNumLastPageSearch = false;
              angular.element(document.getElementById('pageup'))[0].disabled = false;
              angular.element(document.getElementById('pageup2'))[0].disabled = false;
              $scope.submit('t_pagination_next');
            }
          }

        }, function errorCallback(response) {
          $scope.showMsg = true
        })
      } else {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $scope.showMsg = true;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
        }
      }


      // FUNCAO HTTPS PARA BUSCAR OS RESULTADOS DO LEXML...
      if ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 1 && $scope.objNumNoCallLexml == 0) {
        $http({
          method: 'POST',
          url: 'https://homologacao.prf.gov.br/query_drupal/resultLexml.php',
          data: {'value' : $scope.dataText },
          headers: {'Content-Type': 'application/json'}
        })
        .then(function(response) {
          arrResultSearch[2] = JSON.stringify(response.data);

          if (arrResultSearch[2] == '{"LexML":null}') {
            $scope.objRLexml = 0;

            if (varStrType == 't_search') {
              $scope.objNumRErrorSearch++;
            }
          } else {
            $scope.objRLexml = 1;
          }

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

          if (!$scope.objNumLastPageSearch && JSON.stringify(response.data) != '{"LexML":null}') {
            $scope.showMsg = true;
            $scope.searchResultLexml = transaction;
            $scope.searchResultElastic = [];
            $scope.searchResultDrupal = [];
            angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';

            $scope.objNumNoCallLexml = 1;
            $scope.objNumLastPageSearch = true;

            angular.element(document.getElementById('pageup'))[0].disabled = true;
            angular.element(document.getElementById('pageup2'))[0].disabled = true;
          } else {
            if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1) || (typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1) || (typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1) || (typeof $scope.bolOnlyNoticias2 != 'undefined' && $scope.bolOnlyNoticias2 == 1) || (typeof $scope.bolOnlyLexMl2 != 'undefined' && $scope.bolOnlyLexMl2 == 1)) {
              $scope.objRLexml = 0;
              $scope.bolFimRadioElasticS = true;

              $scope.showMsg = true;
              $scope.showErrorSearchIntro = true;
              $scope.showErrorSearchPag = true;
              $scope.showErrorSearch = true;
              $scope.objNumNoCallElastic = 1;
              $scope.objNumNoCallDrupal = 1;
              $scope.objNumNoCallLexml = 1;
              $scope.searchResultElastic = [];
              $scope.searchResultDrupal = [];
              $scope.searchResultLexml = [];
              $scope.objNumLastPageSearch = true;
              //$scope.objNumPaginaLPS = $scope.pagina;
              angular.element(document.getElementById('pageup'))[0].disabled = true;
              angular.element(document.getElementById('pageup2'))[0].disabled = true;
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
            } else {
              $scope.showMsg = true;
              $scope.searchResultElastic = [];
              $scope.searchResultDrupal = [];
              $scope.searchResultLexml = [];
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';

              $scope.objNumNoCallLexml = 1;
              $scope.objNumLastPageSearch = true;

              angular.element(document.getElementById('pageup'))[0].disabled = true;
              angular.element(document.getElementById('pageup2'))[0].disabled = true;
            }
          }

        }, 
        function(response) { 
          $scope.showMsg = true;
        })
      } else {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $scope.showMsg = true;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
        }
      }




      // VALIDACOES E MODO PARA RESETAR PROPRIEDADES DA BUSCA/PAGINACAO...
      if (varStrType == 't_search') {
        if ($scope.dataText == "" || typeof $scope.dataText == 'undefined') {       
          if ($scope.objNumLastPageSearch) {
            $scope.showErrorSearchIntro = true;
            $scope.showErrorSearchPag = true;
            $scope.showErrorSearch = true;
            $scope.pagina = 0;
            $scope.objNumNoCallElastic = 1;
            $scope.objNumNoCallDrupal = 1;
            $scope.objNumNoCallLexml = 1;
            $scope.searchResultElastic = [];
            $scope.searchResultDrupal = [];
            $scope.searchResultLexml = [];
          }

          alert("Por favor, digite algo para buscar...");
          return;
        } else {
          $scope.frmSubBusca.$setPristine();
          $scope.frmSubBusca.$setUntouched();
          $scope.frmSubBusca2.$setPristine();
          $scope.frmSubBusca2.$setUntouched();
          $scope.objRElastic = 1;
          $scope.objRDrupal = 1;
          $scope.objRLexml = 1;
          $scope.objNumRErrorSearch = 0;
          $scope.objNumNoCallElastic = 0;
          $scope.objNumNoCallDrupal = 0;
          $scope.objNumNoCallLexml = 0;
          $scope.searchResultElastic = [];
          $scope.searchResultDrupal = [];
          $scope.searchResultLexml = [];
          $scope.showErrorSearchIntro = false;
          $scope.showErrorSearch = false;
          $scope.objNumLastPageSearch = false;

          angular.element(document.getElementById('pageup'))[0].disabled = false;
          angular.element(document.getElementById('pageup2'))[0].disabled = false;
          angular.element(document.getElementById('idRCarregando'))[0].style.display = 'block';

          $timeout(function() {
            $scope.showErrorSearchIntro = false;
            $scope.showErrorSearch = false;

            if ($scope.objRLexml == 0) {
              $scope.pagina = 0;  
              $scope.objNumRErrorSearch = 0;
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';

              if ($scope.bolOnlyLexMl2 || $scope.bolFimRadioElasticS) {
                $scope.objNumNoCallElastic = 0;
                $scope.objNumNoCallDrupal = 0;
                $scope.objNumNoCallLexml = 0;
                $scope.searchResultElastic = [];
                $scope.searchResultDrupal = [];
                $scope.searchResultLexml = [];
                $scope.bolFimRadioElasticS = false;
                $scope.showErrorSearchIntro = true;
                $scope.showErrorSearch = true;
                $scope.objNumLastPageSearch = false;
              }
            } else if ($scope.objRDrupal == 0) {
              if ($scope.searchResultDrupal == '') {
                $scope.objNumRErrorSearch = 0;
                $scope.objNumNoCallElastic = 1;
                $scope.objNumNoCallDrupal = 1;
                $scope.objNumNoCallLexml = 1;
                $scope.objNumLastPageSearch = true;
                angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
              } else {
                $scope.objNumRErrorSearch = 0;
                $scope.objNumNoCallElastic = 1;
                $scope.objNumNoCallDrupal = 1;
                $scope.objNumNoCallLexml = 0;
                $scope.showErrorSearchIntro = false;
                $scope.objNumLastPageSearch = false;
                angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
              }
            } else if ($scope.objRElastic == 0) {
              $scope.objNumRErrorSearch = 0;
              $scope.objNumNoCallElastic = 1;
              $scope.objNumNoCallDrupal = 1;
              $scope.objNumNoCallLexml = 0;
              $scope.objNumLastPageSearch = false;
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
            }
          }, 310);
        }
      } else if (varStrType == 't_pagination_prev' || varStrType == 't_pagination_next') {
        $scope.objNumRErrorSearch = 0;

        $timeout(function() {        
          if ($scope.objNumLastPageSearch && ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 1 && $scope.objNumNoCallLexml == 1)) {
            if ($scope.searchResultLexml == '') {
              $scope.showErrorSearchIntro = true;
              $scope.showErrorSearchPag = true;
              $scope.showErrorSearch = true;
            } else {
              $scope.showErrorSearchIntro = false;
              $scope.showErrorSearchPag = false;
              $scope.showErrorSearch = false;
            }
          } else {
            $scope.showErrorSearchIntro = false;
            $scope.showErrorSearchPag = false;
            $scope.showErrorSearch = false;
          }
        }, 310);
      }



      // FUNCAO PARA RESETAR RADIOS NO MOMENTO DO CLICK NOS MESMOS...
      $scope.setResetRadios = function(strNameModelTypeRadio) {
        if (strNameModelTypeRadio == 'bolOnlyTodos2') {
          $scope.bolOnlyTodos2 = 1;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'all';
        } else if (strNameModelTypeRadio == 'bolOnlyWiki2') {
          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 1;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'wikiprf';
        } else if (strNameModelTypeRadio == 'bolOnlyRespostas2') {
          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 1;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'prfrespostas';
        } else if (strNameModelTypeRadio == 'bolOnlySei2') {
          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 1;
          $scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'sei';
        } else if (strNameModelTypeRadio == 'bolOnlyNoticias2') {
          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 1;
          $scope.bolOnlyLexMl2 = 0;

          $scope.objStrTypeSearchENL = 'noticias';
        } else if (strNameModelTypeRadio == 'bolOnlyLexMl2') {
          $scope.bolOnlyTodos2 = 0;
          $scope.bolOnlyWiki2 = 0;
          $scope.bolOnlyRespostas2 = 0;
          $scope.bolOnlySei2 = 0;
          $scope.bolOnlyNoticias2 = 0;
          $scope.bolOnlyLexMl2 = 1;

          $scope.objStrTypeSearchENL = 'lexml';
        }
      };


      // FUNCAO PARA PROXIMA PAGINA DA PAGINACAO...
      $scope.increment = function() {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $scope.objNumRErrorSearch = 0;
          angular.element(document.getElementById('idRCarregando'))[0].style.display = 'block';

          $scope.pagina++;


          if ($scope.objNumPaginaLPS <= $scope.pagina) {
          //if ($scope.objNumPaginaLPS != 999999999999999) {  
            $scope.objNumNoCallElastic = 1;

            // Nao mostrar noticias e lexml caso a busca seja via radiobuttons(wiki, resposta e sei)...
            if ((typeof $scope.bolOnlyWiki2 != 'undefined' && $scope.bolOnlyWiki2 == 1) || (typeof $scope.bolOnlyRespostas2 != 'undefined' && $scope.bolOnlyRespostas2 == 1) || (typeof $scope.bolOnlySei2 != 'undefined' && $scope.bolOnlySei2 == 1)) {
              $scope.objNumLastPageSearch = true;
              $scope.objNumNoCallElastic = 1;
              $scope.objNumNoCallDrupal = 1;
              $scope.objNumNoCallLexml = 1;
              $scope.searchResultElastic = [];
              $scope.searchResultDrupal = [];
              $scope.searchResultLexml = [];
              $scope.pagina = 0;
              angular.element(document.getElementById('idRCarregando'))[0].style.display = 'none';
            }
          }

          $timeout(function() {
            $scope.submit('t_pagination_next');
          }, 310);
        } else {
          alert('Digite o texto anterior na pesquisa para continuar ou faça uma nova pesquisa');
        }
      };


      // FUNCAO PARA PAGINA ANTERIOR DA PAGINACAO...
      $scope.decrement = function() {
        if ($scope.dataText != "" && typeof $scope.dataText != 'undefined') {
          $scope.objNumRErrorSearch = 0;
          angular.element(document.getElementById('pageup'))[0].disabled = false;
          angular.element(document.getElementById('pageup2'))[0].disabled = false;
          angular.element(document.getElementById('idRCarregando'))[0].style.display = 'block';

          $scope.pagina--;


          if ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 0) {
            $scope.objNumNoCallDrupal = 0;
            $scope.objNumNoCallLexml = 0;
            $scope.objNumLastPageSearch = false;
            
            $timeout(function() {
              if ($scope.objRDrupal == 0) {
                $scope.objNumNoCallDrupal = 1;

                if ($scope.searchResultDrupal == '') {
                  $scope.objNumNoCallElastic = 0;
                }
              }
            }, 310);
          } else if ($scope.objNumNoCallElastic == 1 && $scope.objNumNoCallDrupal == 1) {
            $scope.objNumNoCallDrupal = 0;
            $scope.objNumNoCallLexml = 0;
            $scope.objNumLastPageSearch = false;
            
            $timeout(function() {
              if ($scope.objRDrupal == 0) {
                $scope.objNumNoCallDrupal = 1;

                if ($scope.searchResultDrupal == '') {
                  $scope.objNumNoCallElastic = 0;
                  $scope.objNumNoCallDrupal = 0;
                }
              }
            }, 310);
          }

          if ($scope.objNumPaginaLPS >= $scope.pagina) {
            if ($scope.objNumNoCallElastic == 1) {
              $scope.objNumNoCallElastic = 0;
              $scope.objNumNoCallDrupal = 0;
              $scope.objNumNoCallLexml = 0;
              $scope.objNumLastPageSearch = false;
            }
          }


          $timeout(function() {
            $scope.submit('t_pagination_prev');
          }, 310);
        } else {
          alert('Digite o texto anterior na pesquisa para voltar ou faça uma nova pesquisa');
        }
      };

    }

}])






////////////////////////////////////////////////
// FUNCAO PARA CRIAR UM NOVO FORMULARIO PARA RESULTADOS DO SEI: NÃO USADO NO MOMENTO
////////////////////////////////////////////////
/*function viewPageSEI(numLink) {
  var strURLSei = "https://homologacao.prf.gov.br/query_drupal/resultados_sei.php";
  var objIDTituloSei = document.getElementById('idTituloSei'+numLink).value;
  var objIDTextoSei = document.getElementById('idTextoSei'+numLink).value;

  var form = document.createElement("form");
  var element1 = document.createElement("input"); 
  var element2 = document.createElement("input");  

  form.action = strURLSei;
  form.method = "post";
  form.target = "_blank";
  form.id = "idFormSei";

  element1.value = objIDTituloSei;
  element1.name = "idTituloSeiFinal";
  element1.type = "hidden";
  form.appendChild(element1);  

  element2.value = objIDTextoSei;
  element2.name = "idTextoSeiFinal";
  element2.type = "hidden";
  form.appendChild(element2);

  document.body.appendChild(form);
  form.submit();
  document.getElementById('idFormSei').parentNode.removeChild(document.getElementById('idFormSei'));

  //document.getElementById('idRCarregando').innerHTML = "<p align='center'>Carregando...</p>";
}*/


////////////////////////////////////////////
// FUNCAO PARA EXECUTAR ALGO AO CARREGAR A PAGINA(FORA DO ANGULAR): NÃO USADO NO MOMENTO
////////////////////////////////////////////
window.onload = function() {
  //var bolTemClasse = document.querySelector('.content').classList.contains('content'); views-exposed-form
  var bolTemClasseAngular = (document.getElementsByClassName('assinatura').length > 0 ? true : false);
  //var bolTemClasse = (document.getElementsByClassName('content').length > 0 ? true : false);

  /*if (! bolTemClasseAngular) {
    document.getElementById('idLinkSistemasSubMenu').onclick = function() {
      if (document.getElementById('submenu-sistemas').style.display == 'block') {
        document.getElementById('submenu-sistemas').style.display = 'none';
      } else {
        document.getElementById('submenu-sistemas').style.display = 'block';
      }

      return false;
    };
  }*/
}