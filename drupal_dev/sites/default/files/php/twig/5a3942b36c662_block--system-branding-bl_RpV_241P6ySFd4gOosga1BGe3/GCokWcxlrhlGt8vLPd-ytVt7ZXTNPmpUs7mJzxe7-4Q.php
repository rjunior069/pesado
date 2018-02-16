<?php

/* themes/intranetprf/templates/block/block--system-branding-block.html.twig */
class __TwigTemplate_fd49bab375ada3d1f8effd00db0da573cf31bf4fab0514e78c81c3b6603d3465 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("block--bare.html.twig", "themes/intranetprf/templates/block/block--system-branding-block.html.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "block--bare.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $tags = array("if" => 19);
        $filters = array("t" => 22);
        $functions = array("path" => 34);

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                array('if'),
                array('t'),
                array('path')
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

        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 18
    public function block_content($context, array $blocks = array())
    {
        echo "  
  ";
        // line 19
        if (($context["site_name"] ?? null)) {
            // line 20
            echo "    <div class=\"collapse navbar-collapse\" id=\"myNavbar\">
    <a class=\"name navbar-brand navbar-text\" href=\"https://homologacao.prf.gov.br/drupal_dev/\"><b class=\"prf\"><img width=\"70\" height=\"25\" src=\"https://www.prf.gov.br/design/assets/img/PRF-small.png\" style=\"margin:-3px 0px 16px 0px\"></b></a>
        <a class=\"name navbar-brand navbar-text\" href=\"https://homologacao.prf.gov.br/drupal_dev/\" title=\"";
            // line 22
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Início")));
            echo "\" rel=\"home\">Intranet
      </a>
      <ul class=\"nav navbar-nav\">
        <li><a href=\"https://www.facebook.com/prfoficial\" target=\"_blank\"><i class=\"fa fa-facebook\"></i></a></li>
        <li><a href=\"https://twitter.com/prfbrasil\" target=\"_blank\"><i class=\"fa fa-twitter\"></i></a></li>
        <li><a href=\"https://www.instagram.com/prfoficial/?hl=pt\" target=\"_blank\"><i class=\"fa fa-instagram\"></i></a></li>
        <li><a href=\"https://www.youtube.com/channel/UCC8hr_mloGmG7tnogjfuPoA\" target=\"_blank\"><i class=\"fa fa-youtube\"></i></a></li>
      </ul>
    </div>   

  ";
        }
        // line 33
        echo "  ";
        if (($context["site_logo"] ?? null)) {
            // line 34
            echo "    <a class=\"logo navbar-btn pull-left\" href=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("<front>")));
            echo "\" title=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("PAINEL")));
            echo "\" rel=\"home\">
      <img src=\"";
            // line 35
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["site_logo"] ?? null), "html", null, true));
            echo "\" alt=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("PAINEL")));
            echo "\" />
    </a>
  ";
        }
        // line 38
        echo "  ";
        if (($context["site_slogan"] ?? null)) {
            // line 39
            echo "    <p class=\"navbar-text\">";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["site_slogan"] ?? null), "html", null, true));
            echo "</p>
  ";
        }
    }

    public function getTemplateName()
    {
        return "themes/intranetprf/templates/block/block--system-branding-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 39,  95 => 38,  87 => 35,  80 => 34,  77 => 33,  63 => 22,  59 => 20,  57 => 19,  52 => 18,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "themes/intranetprf/templates/block/block--system-branding-block.html.twig", "/var/www/html/drupal_dev/themes/intranetprf/templates/block/block--system-branding-block.html.twig");
    }
}
