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

/* error/report_form.twig */
class __TwigTemplate_c35539afb8697d91f953ce7293baa7fa5b9f8c619e867cddfa9089e9a17ba031 extends \Twig\Template
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
        echo "<form action=\"error_report.php\" method=\"post\" name=\"report_frm\" id=\"report_frm\"
      class=\"ajax\">
    <fieldset style=\"padding-top:0\">

        <p>
            ";
        // line 6
        echo _gettext("This report automatically includes data about the error and information about relevant configuration settings. It will be sent to the phpMyAdmin team for debugging the error.");
        // line 9
        echo "        </p>

        <div class=\"label\"><label><strong>
                    ";
        // line 12
        echo _gettext("Can you tell us the steps leading to this error? It decisively helps in debugging:");
        // line 13
        echo "                </strong></label>
        </div>
        <textarea class=\"report-description\" name=\"description\"
                  id=\"report_description\"></textarea>

        <div class=\"label\"><label><p>
                    ";
        // line 19
        echo _gettext("You may examine the data in the error report:");
        // line 20
        echo "                </p></label></div>
        <pre class=\"report-data\">";
        // line 21
        echo ($context["report_data"] ?? null);
        echo "</pre>

        <input type=\"checkbox\" name=\"always_send\" id=\"always_send_checkbox\" />
        <label for=\"always_send_checkbox\">
            ";
        // line 25
        echo _gettext("Automatically send report next time");
        // line 26
        echo "        </label>

    </fieldset>

    ";
        // line 30
        echo ($context["hidden_inputs"] ?? null);
        echo "
    ";
        // line 31
        echo ($context["hidden_fields"] ?? null);
        echo "
</form>
";
    }

    public function getTemplateName()
    {
        return "error/report_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 31,  74 => 30,  68 => 26,  66 => 25,  59 => 21,  56 => 20,  54 => 19,  46 => 13,  44 => 12,  39 => 9,  37 => 6,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "error/report_form.twig", "C:\\Bitnami\\wampstack-7.1.14-0\\apache2\\htdocs\\phpMyAdmin\\templates\\error\\report_form.twig");
    }
}
