<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* start_and_number_of_rows_panel.twig */
class __TwigTemplate_e80c846ff6f6b5c42e8fb7b781b66e14b7e565796cf29ef62f73b55628b4f7e5 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<fieldset>
    <div>
        <label for=\"pos\">";
        // line 3
        echo _gettext("Start row:");
        echo "</label>
        <input type=\"number\" name=\"pos\" min=\"0\" required=\"required\"
            ";
        // line 5
        if ((($context["unlim_num_rows"] ?? null) > 0)) {
            // line 6
            echo "max=\"";
            echo twig_escape_filter($this->env, (($context["unlim_num_rows"] ?? null) - 1), "html", null, true);
            echo "\"";
        }
        // line 8
        echo "            value=\"";
        echo twig_escape_filter($this->env, ($context["pos"] ?? null), "html", null, true);
        echo "\" />

        <label for=\"session_max_rows\">";
        // line 10
        echo _gettext("Number of rows:");
        echo "</label>
        <input type=\"number\" name=\"session_max_rows\" min=\"1\"
               value=\"";
        // line 12
        echo twig_escape_filter($this->env, ($context["rows"] ?? null), "html", null, true);
        echo "\" required=\"required\" />
        <input type=\"submit\" name=\"submit\" class=\"Go\"
               value=\"";
        // line 14
        echo _gettext("Go");
        echo "\" />
        <input type=\"hidden\" name=\"sql_query\"
               value=\"";
        // line 16
        echo twig_escape_filter($this->env, ($context["sql_query"] ?? null), "html", null, true);
        echo "\" />
        <input type=\"hidden\" name=\"unlim_num_rows\"
               value=\"";
        // line 18
        echo twig_escape_filter($this->env, ($context["unlim_num_rows"] ?? null), "html", null, true);
        echo "\" />
    </div>
</fieldset>
";
    }

    public function getTemplateName()
    {
        return "start_and_number_of_rows_panel.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 18,  67 => 16,  62 => 14,  57 => 12,  52 => 10,  46 => 8,  41 => 6,  39 => 5,  34 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "start_and_number_of_rows_panel.twig", "C:\\edll_site\\site\\apache2\\htdocs\\phpMyAdmin\\templates\\start_and_number_of_rows_panel.twig");
    }
}
