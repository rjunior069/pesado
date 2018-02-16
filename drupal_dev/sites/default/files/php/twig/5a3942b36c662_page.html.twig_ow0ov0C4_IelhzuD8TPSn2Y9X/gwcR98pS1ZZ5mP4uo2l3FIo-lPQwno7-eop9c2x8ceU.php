<?php

/* themes/intranetprf/templates/system/page.html.twig */
class __TwigTemplate_b3259848c143579ea6dedf002e40f5ace207f870eb2a273e5feb3c72647291bc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'navbar' => array($this, 'block_navbar'),
            'main' => array($this, 'block_main'),
            'header' => array($this, 'block_header'),
            'sidebar_first' => array($this, 'block_sidebar_first'),
            'highlighted' => array($this, 'block_highlighted'),
            'breadcrumb' => array($this, 'block_breadcrumb'),
            'action_links' => array($this, 'block_action_links'),
            'help' => array($this, 'block_help'),
            'content' => array($this, 'block_content'),
            'sidebar_second' => array($this, 'block_sidebar_second'),
            'footer' => array($this, 'block_footer'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("set" => 1, "if" => 3, "block" => 4);
        $filters = array("clean_class" => 9, "t" => 21);
        $functions = array();

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array('set', 'if', 'block'),
                array('clean_class', 't'),
                array()
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 1
        $context["container"] = (($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", array()), "fluid_container", array())) ? ("container-fluid") : ("container"));
        // line 3
        if (($this->getAttribute(($context["page"] ?? null), "navigation", array()) || $this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array()))) {
            // line 4
            echo "  ";
            $this->displayBlock('navbar', $context, $blocks);
        }
        // line 107
        $this->displayBlock('main', $context, $blocks);
        // line 400
        if ($this->getAttribute(($context["page"] ?? null), "footer", array())) {
            // line 401
            echo "  ";
            $this->displayBlock('footer', $context, $blocks);
        }
    }

    // line 4
    public function block_navbar($context, array $blocks = array())
    {
        // line 5
        echo "    ";
        // line 6
        $context["navbar_classes"] = array(0 => "navbar", 1 => (($this->getAttribute($this->getAttribute(        // line 8
($context["theme"] ?? null), "settings", array()), "navbar_inverse", array())) ? ("navbar-inverse") : ("navbar-default")), 2 => (($this->getAttribute($this->getAttribute(        // line 9
($context["theme"] ?? null), "settings", array()), "navbar_position", array())) ? (("navbar-" . \Drupal\Component\Utility\Html::getClass($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", array()), "navbar_position", array())))) : (($context["container"] ?? null))));
        // line 12
        echo "    <header";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["navbar_attributes"] ?? null), "addClass", array(0 => ($context["navbar_classes"] ?? null)), "method"), "html", null, true));
        echo " id=\"navbar\" role=\"banner\">
      ";
        // line 13
        if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", array(0 => ($context["container"] ?? null)), "method")) {
            // line 14
            echo "        <div class=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["container"] ?? null), "html", null, true));
            echo "\">
      ";
        }
        // line 16
        echo "        <div class=\"navbar-header\">
          ";
        // line 17
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "navigation", array()), "html", null, true));
        echo "
          ";
        // line 19
        echo "          ";
        if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array())) {
            // line 20
            echo "            <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#navbar-collapse\">
              <span class=\"sr-only\">";
            // line 21
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Toggle navigation")));
            echo "</span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
              <span class=\"icon-bar\"></span>
            </button>
          ";
        }
        // line 27
        echo "        </div>

      ";
        // line 30
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array())) {
            // line 31
            echo "        <div id=\"navbar-collapse\" class=\"navbar-collapse collapse\">
          ";
            // line 32
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array()), "html", null, true));
            echo "
        </div>
      ";
        }
        // line 35
        echo "      ";
        if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", array(0 => ($context["container"] ?? null)), "method")) {
            // line 36
            echo "        </div>
      ";
        }
        // line 38
        echo "    </header>



    ";
        // line 42
        if (($context["logged_in"] ?? null)) {
            // line 43
            echo "    <header";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["navbar_attributes"] ?? null), "addClass", array(0 => ($context["navbar_classes"] ?? null)), "method"), "html", null, true));
            echo " id=\"navbar2\" role=\"banner\" style=\"background: #FFF !important; border-bottom: 1px solid #150958; z-index: 999 !important; min-height: 30px !important;\">
      ";
            // line 44
            if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", array(0 => ($context["container"] ?? null)), "method")) {
                // line 45
                echo "        <div class=\"";
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["container"] ?? null), "html", null, true));
                echo "\">
      ";
            }
            // line 47
            echo "        <div class=\"navbar-header\" id=\"idSubMenuResp\">
          
          ";
            // line 49
            if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array())) {
                // line 50
                echo "            <ul class=\"ulsubmenutopo-collapsible\">

              ";
                // line 52
                if ((((($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "admin") || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "NUCOM")) || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "ASCOM")) || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "CONHECIMENTO"))) {
                    // line 53
                    echo "                <li><a href=\"https://homologacao.prf.gov.br/drupal_dev/node/add/article\" style=\"color: #150958 !important;\">Adicionar Notícia</a></li>
              ";
                }
                // line 55
                echo "              <li><a href=\"https://homologacao.prf.gov.br/drupal_dev/mapa\" style=\"color: #150958 !important;\">Notícias</a></li>
              <li><a href=\"https://sei.prf.gov.br/sei/publicacoes/controlador_publicacoes.php?acao=publicacao_pesquisar&id_orgao_publicacao=0\" style=\"color: #150958 !important;\" target=\"_blank\">Boletim Eletrônico</a></li>
              <li><a href=\"http://www.prf.gov.br/arquivos/\" style=\"color: #150958 !important;\" target=\"_blank\">Drive</a></li>
              <li><a href=\"https://homologacao.prf.gov.br/drupal_dev/menu\" style=\"color: #150958 !important;\">Sistemas</a></li>
            </ul>
          ";
            }
            // line 61
            echo "
        </div>

      ";
            // line 65
            echo "      ";
            if ($this->getAttribute(($context["page"] ?? null), "navigation_collapsible", array())) {
                // line 66
                echo "        <div id=\"navbar-collapse\" class=\"navbar-collapse collapse\" style=\"text-align: center !important; position: relative !important;\">
          <ul class=\"ulsubmenutopo\">
            
            ";
                // line 69
                if ((((($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "admin") || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "NUCOM")) || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "ASCOM")) || ($this->getAttribute(($context["user"] ?? null), "displayname", array()) == "CONHECIMENTO"))) {
                    // line 70
                    echo "              <li><a href=\"https://homologacao.prf.gov.br/drupal_dev/node/add/article\" style=\"color: #150958 !important;\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_ad_noticia.png\" height=\"18\" alt=\"Adicionar Notícias\" border=\"0\"></a></li>
            ";
                }
                // line 72
                echo "            <li><a href=\"https://homologacao.prf.gov.br/drupal_dev/mapa\" style=\"color: #150958 !important;\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_noticias.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://sei.prf.gov.br/sei/publicacoes/controlador_publicacoes.php?acao=publicacao_pesquisar&id_orgao_publicacao=0\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_boletim_eletronico.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"http://www.prf.gov.br/arquivos/\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_drive.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://www.prf.gov.br/pdi/login\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_pdi.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://www.prf.gov.br/bat\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_bat.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"http://www.prf.gov.br/brcrime/index.xhtml\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_bop.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"http://www.prf.gov.br/multas/siscom.jsp\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_siscom.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://www.prf.gov.br/sipac/?modo=classico\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_sipac.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://sei.prf.gov.br/sip/login.php?sigla_orgao_sistema=PRF&sigla_sistema=SEI\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_sei.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://www2.prf.gov.br/webmail\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_webmail.png\" height=\"20\" border=\"0\"></a></li>
            <li><a href=\"https://www.prf.gov.br/sicop/login#/\" style=\"color: #150958 !important;\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_sicop.png\" height=\"20\" border=\"0\"></a></li>
            <li>
              <div id=\"submenu-sistemas\">
                <a href=\"https://www.prf.gov.br/pdi/login\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/PDI.png\" width=\"41\" height=\"74\" border=\"0\"></a>
                <a href=\"https://www.prf.gov.br/bat\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/BAT.png\" width=\"41\" height=\"55\" border=\"0\"></a>
                <a href=\"http://www.prf.gov.br/brcrime/index.xhtml\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/BOP.png\" width=\"41\" height=\"63\" border=\"0\"></a>
                <a href=\"http://www.prf.gov.br/multas/siscom.jsp\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/Siscom.png\" width=\"41\" height=\"71\" border=\"0\"></a>
                <a href=\"https://www2.prf.gov.br/webmail\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/Webmail.png\" width=\"41\" height=\"55\" border=\"0\"></a>
                <a href=\"https://www.prf.gov.br/sipac/?modo=classico\" target=\"_blank\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/Sipac.png\" width=\"41\" height=\"66\" border=\"0\"></a>
                <p align=\"center\"><a href=\"https://homologacao.prf.gov.br/drupal_dev/menu\">Mais...</a></p>
              </div>
              <a id=\"idLinkSistemasSubMenu222\" href=\"https://homologacao.prf.gov.br/drupal_dev/menu\" style=\"color: #150958 !important;\"><img src=\"https://homologacao.prf.gov.br/drupal_dev/themes/intranetprf/images/submenu_outros_sistemas.png\" height=\"20\" border=\"0\"></a>
            </li>
          </ul>
        </div>
      ";
            }
            // line 98
            echo "      ";
            if ( !$this->getAttribute(($context["navbar_attributes"] ?? null), "hasClass", array(0 => ($context["container"] ?? null)), "method")) {
                // line 99
                echo "        </div>
      ";
            }
            // line 101
            echo "    </header>
    ";
        }
        // line 103
        echo "
  ";
    }

    // line 107
    public function block_main($context, array $blocks = array())
    {
        // line 108
        echo "  <div role=\"main\" class=\"main-container ";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["container"] ?? null), "html", null, true));
        echo " js-quickedit-main-content\">
    <div class=\"row\" style=\"position: relative;\">
      <div id=\"idRCarregando\" style=\"position: absolute; left: 0px; top: 0px; display: none; width: 100%; height: 100%; background-color: rgba(255, 255, 255, .6) !important; z-index: 10; text-align: center;\"><p align=\"center\">Carregando...</p></div>

      ";
        // line 113
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "header", array())) {
            // line 114
            echo "        ";
            $this->displayBlock('header', $context, $blocks);
            // line 119
            echo "      ";
        }
        // line 120
        echo "
      ";
        // line 122
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_first", array())) {
            // line 123
            echo "        ";
            $this->displayBlock('sidebar_first', $context, $blocks);
            // line 128
            echo "      ";
        }
        // line 129
        echo "
      ";
        // line 131
        echo "      ";
        if (($context["is_front"] ?? null)) {
            // line 132
            echo "
      <div id=\"container\" ng-app=\"myModule\">
        <div ng-controller=\"myController\">
          <div id=\"idRCarregandoBody\" ng-init=\"loadPage()\" style=\"position: absolute; left: 0px; top: 0px; display: block; width: 100%; height: 120%; background-color: rgba(255, 255, 255, .9) !important; z-index: 1000; text-align: center; padding-top: 25%; font-size: 122px;\"><p align=\"center\">&nbsp;</p></div>

          <div ng-show=\"!showMsg\">
            <div class=\"assinatura\">
              <img width=\"80%\" height=\"50%\" src=\"themes/intranetprf/assinatura.svg\" alt=\"Brasao PRF\" style=\"margin-bottom: -15%; margin-left: 10%;\"/>
            </div>
              <div id=\"searchFull\" style=\"margin-bottom: 100px;\">
                <div class=\"inner-addon right-addon div-form\">
                  <form name=\"frmSubBusca\" ng-submit=\"submit('t_search')\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objRElastic\" ng-init=\"objRElastic=1;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallElastic\" ng-init=\"objNumNoCallElastic=0;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objRDrupal\" ng-init=\"objRDrupal=1;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallDrupal\" ng-init=\"objNumNoCallDrupal=0;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objRLexml\" ng-init=\"objRLexml=1;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallLexml\" ng-init=\"objNumNoCallLexml=0;\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumRErrorSearch\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objStrTypeSearchENL\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumLastPageSearch\">
                    <input type=\"text\" style=\"display:none;\" ng-model=\"objNumPaginaLPS\" ng-init=\"objNumPaginaLPS=999999999999999;\">

                    <input type=\"text\" class=\"form-control\" ng-model=\"dataText\" placeholder=\"Pesquisar\">
                    <p align=\"center\" style=\"text-align:center;\">
                      <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlyTodos\" ng-value=\"1\"> TODOS &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlyWiki\" ng-value=\"1\"> WikiPRF &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlyRespostas\" ng-value=\"1\"> PRFRespostas &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlySei\" ng-value=\"1\"> SEI &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlyNoticias\" ng-value=\"1\"> Notícias &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps\" ng-model=\"bolOnlyLexMl\" ng-value=\"1\"> LexML
                    </p>

                    <input type=\"hidden\" class=\"form-control\" id=\"currentPage\" ng-model=\"currentPage\">

                    <i class=\"glyphicon fa fa-keyboard-o\"></i>
                    <button type=\"submit\" class=\"btn btn-link\" ></button>

                  </form>
                </div>
              </div>
              <div id=\"blockFeed\" class=\"col-sm-11 col-md-11\" style=\"
    left: 55px;\">
                <div class=\"blockFeed conhecimento col-sm-1 col-md-1\" style=\"right: 45px;\">
                  <div id=\"conhecimento\">
                    <div class=\"blockFeedRepeat\" ng-repeat=\"news in feedNoticiaConhecimento | filter:{ field_posicao: '!0'} | orderBy:'field_posicao' | limitTo:1\">
                      <a href=\"(\$ news.view_node \$)\">
                        <!-- <span class=\"quickedit-field\">(\$ news.title \$)</span> -->
                        <div class=\"field field--name-body field--type-text-with-summary field--label-hidden field--item quickedit-field\">
                          <!-- <img src=\"(\$ news.field_image \$)\" width=\"200\" height=\"150\" > -->
                          <!--p>(\$ news.field_posicao \$)</p-->
                          <!--p>(\$ news.field_region \$)</p-->
                          <!--p>(\$ news.field_sector \$)</p-->
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
                <div class=\"blockFeed ascom col-sm-5 col-md-5\">
                  <div id=\"ascom\">
                    <div class=\"blockFeedRepeat col-sm-4 col-md-4\" ng-repeat=\"news in feedNoticiaAscom | filter:{ field_posicao: '!0'} | orderBy:'field_posicao'| unique:'field_posicao' | limitTo:3\">
                      <a href=\"(\$ news.view_node \$)\">
                        <!-- <span class=\"quickedit-field\">(\$ news.title \$)</span> -->
                        <div class=\"field field--name-body field--type-text-with-summary field--label-hidden field--item quickedit-field\">
                          <!-- <img src=\"(\$ news.field_image \$)\" width=\"200\" height=\"150\" > -->
                          <!--p>(\$ news.field_position \$)</p-->
                          <!--p>(\$ news.field_region \$)</p-->
                          <!--p>(\$ news.field_sector \$)</p-->
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
                <div class=\"blockFeed nucom col-sm-6 col-md-6\">
                  <div id=\"nucom\">
                    <div class=\"blockFeedRepeat col-sm-3 col-md-3\" ng-repeat=\"news in feedNoticiaNucom | filter:{ field_posicao: '!0'} | orderBy:'field_posicao'| limitTo:4\">
                      <a href=\"(\$ news.view_node \$)\">
                        <!-- <span class=\"quickedit-field\">(\$ news.title \$)</span> -->
                        <div class=\"field field--name-body field--type-text-with-summary field--label-hidden field--item quickedit-field\">
                          <!-- <img src=\"(\$ news.field_image \$)\" width=\"200\" height=\"150\"> -->
                          <!--p>(\$ news.field_posicao \$)</p-->
                          <!--p>(\$ news.field_region \$)</p-->
                          <!--p>(\$ news.field_sector \$)</p-->
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
            ";
            // line 219
            echo "            <!-- ";
            if (($this->getAttribute($this->getAttribute(($context["paragraph"] ?? null), "field_image_position", array()), "value", array()) == 2)) {
                // line 220
                echo "
            ";
            }
            // line 221
            echo " -->
            <div ng-show=\"showMsg\">
              <div>
                <div class=\"brasaoResult\"></div>   
                <div id=\"searchFullResult\">
                  <div class=\"inner-addon right-addon\">
                    <form name=\"frmSubBusca2\" ng-submit=\"submit('t_search')\">                  
                      <!-- <input type=\"hidden\" name=\"bolSearch\" ng-init=\"bolSearch='y';\"> -->
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objRElastic\" ng-init=\"objRElastic=1;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallElastic\" ng-init=\"objNumNoCallElastic=0;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objRDrupal\" ng-init=\"objRDrupal=1;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallDrupal\" ng-init=\"objNumNoCallDrupal=0;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objRLexml\" ng-init=\"objRLexml=1;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumNoCallLexml\" ng-init=\"objNumNoCallLexml=0;\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objStrTypeSearchENL\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumRErrorSearch\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumLastPageSearch\">
                      <input type=\"text\" style=\"display:none;\" ng-model=\"objNumPaginaLPS\" ng-init=\"objNumPaginaLPS=999999999999999;\">

                      <input type=\"text\" class=\"form-control\" ng-model=\"dataText\" placeholder=\"Pesquisar\">
                      <p align=\"center\" style=\"text-align:center;\">
                        <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlyTodos2\" ng-click=\"setResetRadios('bolOnlyTodos2')\" ng-value=\"1\"> TODOS &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlyWiki2\" ng-click=\"setResetRadios('bolOnlyWiki2')\" ng-value=\"1\"> WikiPRF &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlyRespostas2\" ng-click=\"setResetRadios('bolOnlyRespostas2')\" ng-value=\"1\"> PRFRespostas &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlySei2\" ng-click=\"setResetRadios('bolOnlySei2')\" ng-value=\"1\"> SEI &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlyNoticias2\" ng-click=\"setResetRadios('bolOnlyNoticias2')\" ng-value=\"1\"> Notícias &nbsp;&nbsp; <input type=\"radio\" name=\"rdOnlySelectSearchTps2\" ng-model=\"bolOnlyLexMl2\" ng-click=\"setResetRadios('bolOnlyLexMl2')\" ng-value=\"1\"> LexML
                      </p>

                      <i class=\"glyphicon fa fa-keyboard-o\"></i>
                            
                      <button type=\"submit\" class=\"btn btn-link\" ></button><br><br>

                    </form>
                     <!-- <form ng-submit=\"submit()\"> -->
                        <p align=\"center\" ng-show=\"showErrorSearchIntro\">Não foi possível encontrar resultados. Tente novamente!</p>
                        <p align=\"center\" ng-show=\"showErrorSearchPag\">
                          <!-- Ou clique ao lado para voltar a página anterior:

                          <button class=\"btn btn-link\" id =\"pagedown\" ng-model = \"pagina\" ng-disabled=\"pagina == 0\" ng-click=\"decrement()\">
                          Voltar
                          </button> -->
                        </p>

                        <span ng-show=\"!showErrorSearch\">
                          <button class=\"btn btn-link\" id =\"pagedown\" ng-model = \"pagina\" ng-disabled=\"pagina == 0\" ng-click=\"decrement()\">
                          Anterior
                          </button>
                          <span>
                          Página: (\$ pagina + 1 \$)
                          </span>
                          <button class=\"btn btn-link\" id =\"pageup\"  ng-model = \"pagina\" ng-click=\"increment()\">
                          Próximo
                          </button>
                        </span>
                    <!-- </form> -->
                  </div>
                </div>
              </div>
              <div class=\"blockResult col-md-8\" >
                
                <div class=\"elastic\">
                  <div ng-repeat=\"news in searchResultElastic\">
                    <div class=\"contentResult\">
                      <h3><a href=\"(\$ news.link \$)\" target=\"_blank\">(\$ news.titulo \$)</a></h3>        
                      <p>(\$ news.texto \$)</p>
                      <p class=\"link-color\">(\$ news.link \$)</p>
                      <div class=\"linkResult\">
                       <b> (\$ news.sistema \$)</b>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class=\"drupal\">
                  <div ng-repeat=\"news in searchResultDrupal\">
                    <div class=\"contentResult\">
                      <h3><a href=\"(\$ news.link \$)\" target=\"_blank\">(\$ news.titulo \$)</a></h3>        
                      <p>(\$ news.texto \$)</p>
                      <p class=\"link-color\">(\$ news.link \$)</p>
                      <div class=\"linkResult\">
                       <b> (\$ news.sistema \$)</b>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class=\"lexml\">
                  <div ng-repeat=\"news in searchResultLexml\">
                    <div class=\"contentResult\">
                      <h3><a href=\"(\$ news.link \$)\" target=\"_blank\">(\$ news.titulo \$)</a></h3>        
                      <p>(\$ news.texto \$)</p>
                      <p class=\"link-color\">(\$ news.link \$)</p>
                      <div class=\"linkResult\">
                       <b> (\$ news.sistema \$)</b>
                      </div>
                    </div>
                  </div>
                </div>
                 <!-- <form ng-submit=\"submit()\"> -->
                      <span ng-show=\"!showErrorSearch\">
                        <button class=\"btn btn-link\" id =\"pagedown2\" ng-model = \"pagina\" ng-disabled=\"pagina == 0\" ng-click=\"decrement()\">
                        Anterior
                        </button>
                          <span>
                          Página: (\$ pagina + 1 \$)
                          </span>
                        <button class=\"btn btn-link\" id =\"pageup2\"  ng-model = \"pagina\" ng-click=\"increment()\">
                        Próximo
                        </button> 
                      </span>  
                 <!-- </form> -->
              </div>

              <div class=\"blockRespostas col-md-3\">
                <div>
                 <a href=\"https://www.prf.gov.br/prfrespostas/\"target=\"_blank\"><img src=\"themes/intranetprf/images/001.png\" style=\"
    width: 130%;\"></a>
                </div>
              </div>
            </div>
            ";
            // line 338
            echo "      </div>



      ";
        }
        // line 343
        echo "
      ";
        // line 345
        $context["content_classes"] = array(0 => ((($this->getAttribute(        // line 346
($context["page"] ?? null), "sidebar_first", array()) && $this->getAttribute(($context["page"] ?? null), "sidebar_second", array()))) ? ("col-sm-6") : ("")), 1 => ((($this->getAttribute(        // line 347
($context["page"] ?? null), "sidebar_first", array()) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", array())))) ? ("col-sm-9") : ("")), 2 => ((($this->getAttribute(        // line 348
($context["page"] ?? null), "sidebar_second", array()) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_first", array())))) ? ("col-sm-9") : ("")), 3 => (((twig_test_empty($this->getAttribute(        // line 349
($context["page"] ?? null), "sidebar_first", array())) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", array())))) ? ("col-sm-12") : ("")));
        // line 352
        echo "      <section";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["content_attributes"] ?? null), "addClass", array(0 => ($context["content_classes"] ?? null)), "method"), "html", null, true));
        echo ">

        ";
        // line 355
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "highlighted", array())) {
            // line 356
            echo "          ";
            $this->displayBlock('highlighted', $context, $blocks);
            // line 359
            echo "        ";
        }
        // line 360
        echo "
        ";
        // line 362
        echo "        ";
        if (($context["breadcrumb"] ?? null)) {
            // line 363
            echo "          ";
            $this->displayBlock('breadcrumb', $context, $blocks);
            // line 366
            echo "        ";
        }
        // line 367
        echo "
        ";
        // line 369
        echo "        ";
        if (($context["action_links"] ?? null)) {
            // line 370
            echo "          ";
            $this->displayBlock('action_links', $context, $blocks);
            // line 373
            echo "        ";
        }
        // line 374
        echo "
        ";
        // line 376
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "help", array())) {
            // line 377
            echo "          ";
            $this->displayBlock('help', $context, $blocks);
            // line 380
            echo "        ";
        }
        // line 381
        echo "
        ";
        // line 383
        echo "        ";
        $this->displayBlock('content', $context, $blocks);
        // line 387
        echo "      </section>

      ";
        // line 390
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_second", array())) {
            // line 391
            echo "        ";
            $this->displayBlock('sidebar_second', $context, $blocks);
            // line 396
            echo "      ";
        }
        // line 397
        echo "    </div>
  </div>
";
    }

    // line 114
    public function block_header($context, array $blocks = array())
    {
        // line 115
        echo "          <div class=\"col-sm-12\" role=\"heading\">
            ";
        // line 116
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "header", array()), "html", null, true));
        echo "
          </div>
        ";
    }

    // line 123
    public function block_sidebar_first($context, array $blocks = array())
    {
        // line 124
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 125
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "sidebar_first", array()), "html", null, true));
        echo "
          </aside>
        ";
    }

    // line 356
    public function block_highlighted($context, array $blocks = array())
    {
        // line 357
        echo "            <div class=\"highlighted\">";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "highlighted", array()), "html", null, true));
        echo "</div>
          ";
    }

    // line 363
    public function block_breadcrumb($context, array $blocks = array())
    {
        // line 364
        echo "            ";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["breadcrumb"] ?? null), "html", null, true));
        echo "
          ";
    }

    // line 370
    public function block_action_links($context, array $blocks = array())
    {
        // line 371
        echo "            <ul class=\"action-links\">";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["action_links"] ?? null), "html", null, true));
        echo "</ul>
          ";
    }

    // line 377
    public function block_help($context, array $blocks = array())
    {
        // line 378
        echo "            ";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "help", array()), "html", null, true));
        echo "
          ";
    }

    // line 383
    public function block_content($context, array $blocks = array())
    {
        // line 384
        echo "          <a id=\"main-content\"></a>
          ";
        // line 385
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "content", array()), "html", null, true));
        echo "
        ";
    }

    // line 391
    public function block_sidebar_second($context, array $blocks = array())
    {
        // line 392
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 393
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "sidebar_second", array()), "html", null, true));
        echo "
          </aside>
        ";
    }

    // line 401
    public function block_footer($context, array $blocks = array())
    {
        // line 402
        echo "  <div class=\"col-sm-12\">
    <footer class=\"footer ";
        // line 403
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["container"] ?? null), "html", null, true));
        echo "\" role=\"contentinfo\">
      ";
        // line 404
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["page"] ?? null), "footer", array()), "html", null, true));
        echo "
    </footer>
    </div>
  ";
    }

    public function getTemplateName()
    {
        return "themes/intranetprf/templates/system/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  691 => 404,  687 => 403,  684 => 402,  681 => 401,  674 => 393,  671 => 392,  668 => 391,  662 => 385,  659 => 384,  656 => 383,  649 => 378,  646 => 377,  639 => 371,  636 => 370,  629 => 364,  626 => 363,  619 => 357,  616 => 356,  609 => 125,  606 => 124,  603 => 123,  596 => 116,  593 => 115,  590 => 114,  584 => 397,  581 => 396,  578 => 391,  575 => 390,  571 => 387,  568 => 383,  565 => 381,  562 => 380,  559 => 377,  556 => 376,  553 => 374,  550 => 373,  547 => 370,  544 => 369,  541 => 367,  538 => 366,  535 => 363,  532 => 362,  529 => 360,  526 => 359,  523 => 356,  520 => 355,  514 => 352,  512 => 349,  511 => 348,  510 => 347,  509 => 346,  508 => 345,  505 => 343,  498 => 338,  380 => 221,  376 => 220,  373 => 219,  285 => 132,  282 => 131,  279 => 129,  276 => 128,  273 => 123,  270 => 122,  267 => 120,  264 => 119,  261 => 114,  258 => 113,  250 => 108,  247 => 107,  242 => 103,  238 => 101,  234 => 99,  231 => 98,  203 => 72,  199 => 70,  197 => 69,  192 => 66,  189 => 65,  184 => 61,  176 => 55,  172 => 53,  170 => 52,  166 => 50,  164 => 49,  160 => 47,  154 => 45,  152 => 44,  147 => 43,  145 => 42,  139 => 38,  135 => 36,  132 => 35,  126 => 32,  123 => 31,  120 => 30,  116 => 27,  107 => 21,  104 => 20,  101 => 19,  97 => 17,  94 => 16,  88 => 14,  86 => 13,  81 => 12,  79 => 9,  78 => 8,  77 => 6,  75 => 5,  72 => 4,  66 => 401,  64 => 400,  62 => 107,  58 => 4,  56 => 3,  54 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "themes/intranetprf/templates/system/page.html.twig", "/var/www/html/drupal_dev/themes/intranetprf/templates/system/page.html.twig");
    }
}
