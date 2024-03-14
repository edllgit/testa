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

/* table/structure/display_structure.twig */
class __TwigTemplate_b03888269fdaed0105fbd05e604bf77a51c5e7243167e78cee96518cf610b422 extends \Twig\Template
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
        echo "<form method=\"post\" action=\"tbl_structure.php\" name=\"fieldsForm\" id=\"fieldsForm\"
    class=\"ajax";
        // line 2
        echo ((($context["hide_structure_actions"] ?? null)) ? (" HideStructureActions") : (""));
        echo "\">
    ";
        // line 3
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null), ($context["table"] ?? null));
        echo "
    <input type=\"hidden\" name=\"table_type\" value=";
        // line 5
        if (($context["db_is_system_schema"] ?? null)) {
            // line 6
            echo "\"information_schema\"";
        } elseif (        // line 7
($context["tbl_is_view"] ?? null)) {
            // line 8
            echo "\"view\"";
        } else {
            // line 10
            echo "\"table\"";
        }
        // line 11
        echo " />
    <div class=\"responsivetable\">
    <table id=\"tablestructure\" class=\"data topmargin\">
        ";
        // line 15
        echo "        ";
        $this->loadTemplate("table/structure/table_structure_header.twig", "table/structure/display_structure.twig", 15)->display(twig_to_array(["db_is_system_schema" =>         // line 16
($context["db_is_system_schema"] ?? null), "tbl_is_view" =>         // line 17
($context["tbl_is_view"] ?? null), "show_column_comments" =>         // line 18
($context["show_column_comments"] ?? null)]));
        // line 20
        echo "        <tbody>
        ";
        // line 22
        echo "        ";
        $context["rownum"] = 0;
        // line 23
        echo "        ";
        $context["columns_list"] = [];
        // line 24
        echo "        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["fields"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 25
            echo "            ";
            $context["rownum"] = (($context["rownum"] ?? null) + 1);
            // line 26
            echo "            ";
            $context["columns_list"] = twig_array_merge(($context["columns_list"] ?? null), [0 => $this->getAttribute($context["row"], "Field", [], "array")]);
            // line 27
            echo "            ";
            $context["field_charset"] = $this->getAttribute($context["row"], "Collation", [], "array");
            // line 28
            echo "
            ";
            // line 29
            $context["extracted_columnspec"] = PhpMyAdmin\Util::extractColumnSpec($this->getAttribute($context["row"], "Type", [], "array"));
            // line 30
            echo "            ";
            $context["attribute"] = $this->getAttribute(($context["extracted_columnspec"] ?? null), "attribute", [], "array");
            // line 31
            echo "            ";
            if ( !(strpos($this->getAttribute($context["row"], "Extra", [], "array"), "on update CURRENT_TIMESTAMP") === false)) {
                // line 33
                echo "                ";
                $context["attribute"] = "on update CURRENT_TIMESTAMP";
                // line 34
                echo "            ";
            }
            // line 35
            echo "
            ";
            // line 36
            if ((null === $this->getAttribute($context["row"], "Default", [], "array"))) {
                // line 37
                echo "                ";
                if (($this->getAttribute($context["row"], "Null", [], "array") == "YES")) {
                    // line 38
                    echo "                    ";
                    $context["row"] = twig_array_merge($context["row"], ["Default" => "<em>NULL</em>"]);
                    // line 39
                    echo "                ";
                }
                // line 40
                echo "            ";
            } else {
                // line 41
                echo "                ";
                $context["row"] = twig_array_merge($context["row"], ["Default" => twig_escape_filter($this->env, $this->getAttribute($context["row"], "Default", [], "array"))]);
                // line 42
                echo "            ";
            }
            // line 43
            echo "
            ";
            // line 44
            $context["field_name"] = twig_escape_filter($this->env, $this->getAttribute($context["row"], "Field", [], "array"));
            // line 45
            echo "            ";
            $context["displayed_field_name"] = ($context["field_name"] ?? null);
            // line 46
            echo "            ";
            // line 47
            echo "            ";
            $context["comments"] = "";
            // line 48
            echo "            ";
            // line 49
            echo "
            ";
            // line 50
            if ($this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute($context["row"], "Field", [], "array"), [], "array", true, true)) {
                // line 51
                echo "                ";
                ob_start(function () { return ''; });
                // line 52
                echo "<span class=\"commented_column\" title=\"";
                // line 53
                echo twig_escape_filter($this->env, $this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute($context["row"], "Field", [], "array"), [], "array"), "html", null, true);
                echo "\">";
                // line 54
                echo ($context["field_name"] ?? null);
                // line 55
                echo "</span>";
                $context["displayed_field_name"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 57
                echo "                ";
                $context["comments"] = $this->getAttribute(($context["comments_map"] ?? null), $this->getAttribute($context["row"], "Field", [], "array"), [], "array");
                // line 58
                echo "            ";
            }
            // line 59
            echo "
            ";
            // line 60
            if ((($context["primary"] ?? null) && $this->getAttribute(($context["primary"] ?? null), "hasColumn", [0 => ($context["field_name"] ?? null)], "method"))) {
                // line 61
                echo "                ";
                $context["displayed_field_name"] = (($context["displayed_field_name"] ?? null) . PhpMyAdmin\Util::getImage("b_primary", _gettext("Primary")));
                // line 64
                echo "            ";
            }
            // line 65
            echo "            ";
            if (twig_in_filter(($context["field_name"] ?? null), ($context["columns_with_index"] ?? null))) {
                // line 66
                echo "                ";
                $context["displayed_field_name"] = (($context["displayed_field_name"] ?? null) . PhpMyAdmin\Util::getImage("bd_primary", _gettext("Index")));
                // line 69
                echo "            ";
            }
            // line 70
            echo "        <tr>
            ";
            // line 71
            $this->loadTemplate("table/structure/table_structure_row.twig", "table/structure/display_structure.twig", 71)->display(twig_to_array(["row" =>             // line 72
$context["row"], "rownum" =>             // line 73
($context["rownum"] ?? null), "displayed_field_name" => preg_replace("/[\\x00-\\x1F]/", "&#x2051;",             // line 77
($context["displayed_field_name"] ?? null)), "type_nowrap" => PhpMyAdmin\Util::getClassForType($this->getAttribute(            // line 79
($context["extracted_columnspec"] ?? null), "type", [], "array")), "extracted_columnspec" =>             // line 80
($context["extracted_columnspec"] ?? null), "attribute" =>             // line 81
($context["attribute"] ?? null), "tbl_is_view" =>             // line 82
($context["tbl_is_view"] ?? null), "db_is_system_schema" =>             // line 83
($context["db_is_system_schema"] ?? null), "url_query" =>             // line 84
($context["url_query"] ?? null), "titles" =>             // line 85
($context["titles"] ?? null), "table" =>             // line 86
($context["table"] ?? null), "tbl_storage_engine" =>             // line 87
($context["tbl_storage_engine"] ?? null), "field_charset" =>             // line 88
($context["field_charset"] ?? null), "comments" =>             // line 89
($context["comments"] ?? null), "show_column_comments" =>             // line 90
($context["show_column_comments"] ?? null), "relation_commwork" =>             // line 91
($context["relation_commwork"] ?? null), "relation_mimework" =>             // line 92
($context["relation_mimework"] ?? null), "browse_mime" =>             // line 93
($context["browse_mime"] ?? null)]));
            // line 95
            echo "            ";
            if (( !($context["tbl_is_view"] ?? null) &&  !($context["db_is_system_schema"] ?? null))) {
                // line 96
                echo "                ";
                $this->loadTemplate("table/structure/actions_in_table_structure.twig", "table/structure/display_structure.twig", 96)->display(twig_to_array(["row" =>                 // line 97
$context["row"], "rownum" =>                 // line 98
($context["rownum"] ?? null), "extracted_columnspec" =>                 // line 99
($context["extracted_columnspec"] ?? null), "type" => (( !twig_test_empty($this->getAttribute(                // line 100
($context["extracted_columnspec"] ?? null), "print_type", [], "array"))) ? ($this->getAttribute(($context["extracted_columnspec"] ?? null), "print_type", [], "array")) : ("")), "tbl_storage_engine" =>                 // line 101
($context["tbl_storage_engine"] ?? null), "primary" =>                 // line 102
($context["primary"] ?? null), "field_name" =>                 // line 103
($context["field_name"] ?? null), "url_query" =>                 // line 104
($context["url_query"] ?? null), "titles" =>                 // line 105
($context["titles"] ?? null), "columns_with_unique_index" =>                 // line 106
($context["columns_with_unique_index"] ?? null), "is_in_central_columns" => ((twig_in_filter($this->getAttribute(                // line 107
$context["row"], "Field", [], "array"), ($context["central_list"] ?? null))) ? (true) : (false)), "central_columns_work" =>                 // line 108
($context["central_columns_work"] ?? null), "table" =>                 // line 109
($context["table"] ?? null), "hide_structure_actions" =>                 // line 110
($context["hide_structure_actions"] ?? null), "mysql_int_version" =>                 // line 111
($context["mysql_int_version"] ?? null)]));
                // line 113
                echo "            ";
            }
            // line 114
            echo "        </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 116
        echo "        </tbody>
    </table>
    </div>
    ";
        // line 119
        $this->loadTemplate("table/structure/check_all_table_column.twig", "table/structure/display_structure.twig", 119)->display(twig_to_array(["pma_theme_image" =>         // line 120
($context["pma_theme_image"] ?? null), "text_dir" =>         // line 121
($context["text_dir"] ?? null), "tbl_is_view" =>         // line 122
($context["tbl_is_view"] ?? null), "db_is_system_schema" =>         // line 123
($context["db_is_system_schema"] ?? null), "tbl_storage_engine" =>         // line 124
($context["tbl_storage_engine"] ?? null), "central_columns_work" =>         // line 125
($context["central_columns_work"] ?? null)]));
        // line 127
        echo "</form>
<hr class=\"print_ignore\"/>
";
        // line 129
        $this->loadTemplate("table/structure/move_columns_dialog.twig", "table/structure/display_structure.twig", 129)->display(twig_to_array(["db" =>         // line 130
($context["db"] ?? null), "table" =>         // line 131
($context["table"] ?? null)]));
        // line 134
        echo "<div id=\"structure-action-links\">
    ";
        // line 135
        if ((($context["tbl_is_view"] ?? null) &&  !($context["db_is_system_schema"] ?? null))) {
            // line 136
            echo "        ";
            echo PhpMyAdmin\Util::linkOrButton("view_create.php", ["db" =>             // line 138
($context["db"] ?? null), "table" => ($context["table"] ?? null)], PhpMyAdmin\Util::getIcon("b_edit", _gettext("Edit view"), true));
            // line 140
            echo "
    ";
        }
        // line 142
        echo "    ";
        $this->loadTemplate("table/structure/optional_action_links.twig", "table/structure/display_structure.twig", 142)->display(twig_to_array(["url_query" =>         // line 143
($context["url_query"] ?? null), "tbl_is_view" =>         // line 144
($context["tbl_is_view"] ?? null), "db_is_system_schema" =>         // line 145
($context["db_is_system_schema"] ?? null), "table" =>         // line 146
($context["table"] ?? null), "is_active" =>         // line 147
($context["is_active"] ?? null), "mysql_int_version" =>         // line 148
($context["mysql_int_version"] ?? null), "is_mariadb" =>         // line 149
($context["is_mariadb"] ?? null)]));
        // line 151
        echo "</div>
";
        // line 152
        if (( !($context["tbl_is_view"] ?? null) &&  !($context["db_is_system_schema"] ?? null))) {
            // line 153
            echo "    ";
            $this->loadTemplate("table/structure/add_column.twig", "table/structure/display_structure.twig", 153)->display(twig_to_array(["columns_list" =>             // line 154
($context["columns_list"] ?? null), "db" =>             // line 155
($context["db"] ?? null), "table" =>             // line 156
($context["table"] ?? null)]));
        }
        // line 159
        echo "
";
        // line 161
        if ((( !($context["tbl_is_view"] ?? null) &&  !($context["db_is_system_schema"] ?? null)) && ("ARCHIVE" !=         // line 162
($context["tbl_storage_engine"] ?? null)))) {
            // line 163
            echo "    ";
            echo PhpMyAdmin\Index::getHtmlForDisplayIndexes();
            echo "
";
        }
        // line 165
        echo "
";
        // line 167
        if (($context["have_partitioning"] ?? null)) {
            // line 168
            echo "    ";
            // line 169
            echo "    ";
            if (( !twig_test_empty(($context["partition_names"] ?? null)) &&  !(null === $this->getAttribute(($context["partition_names"] ?? null), 0, [], "array")))) {
                // line 170
                echo "        ";
                $context["partitions"] = PhpMyAdmin\Partition::getPartitions(($context["db"] ?? null), ($context["table"] ?? null));
                // line 171
                echo "        ";
                $context["first_partition"] = $this->getAttribute(($context["partitions"] ?? null), 0, [], "array");
                // line 172
                echo "        ";
                $context["range_or_list"] = (((($this->getAttribute(($context["first_partition"] ?? null), "getMethod", [], "method") == "RANGE") || ($this->getAttribute(                // line 173
($context["first_partition"] ?? null), "getMethod", [], "method") == "RANGE COLUMNS")) || ($this->getAttribute(                // line 174
($context["first_partition"] ?? null), "getMethod", [], "method") == "LIST")) || ($this->getAttribute(                // line 175
($context["first_partition"] ?? null), "getMethod", [], "method") == "LIST COLUMNS"));
                // line 176
                echo "        ";
                $context["sub_partitions"] = $this->getAttribute(($context["first_partition"] ?? null), "getSubPartitions", [], "method");
                // line 177
                echo "        ";
                $context["has_sub_partitions"] = $this->getAttribute(($context["first_partition"] ?? null), "hasSubPartitions", [], "method");
                // line 178
                echo "        ";
                if (($context["has_sub_partitions"] ?? null)) {
                    // line 179
                    echo "            ";
                    $context["first_sub_partition"] = $this->getAttribute(($context["sub_partitions"] ?? null), 0, [], "array");
                    // line 180
                    echo "        ";
                }
                // line 181
                echo "
        ";
                // line 182
                $context["action_icons"] = ["ANALYZE" => PhpMyAdmin\Util::getIcon("b_search", _gettext("Analyze")), "CHECK" => PhpMyAdmin\Util::getIcon("eye", _gettext("Check")), "OPTIMIZE" => PhpMyAdmin\Util::getIcon("normalize", _gettext("Optimize")), "REBUILD" => PhpMyAdmin\Util::getIcon("s_tbl", _gettext("Rebuild")), "REPAIR" => PhpMyAdmin\Util::getIcon("b_tblops", _gettext("Repair")), "TRUNCATE" => PhpMyAdmin\Util::getIcon("b_empty", _gettext("Truncate"))];
                // line 190
                echo "        ";
                if (($context["range_or_list"] ?? null)) {
                    // line 191
                    echo "            ";
                    $context["action_icons"] = twig_array_merge(($context["action_icons"] ?? null), ["DROP" => PhpMyAdmin\Util::getIcon("b_drop", _gettext("Drop"))]);
                    // line 192
                    echo "        ";
                }
                // line 193
                echo "
        ";
                // line 194
                echo PhpMyAdmin\Util::getDivForSliderEffect("partitions", _gettext("Partitions"));
                echo "

        ";
                // line 196
                $context["remove_sql"] = (("ALTER TABLE " . PhpMyAdmin\Util::backquote(($context["table"] ?? null))) . " REMOVE PARTITIONING");
                // line 197
                echo "
        ";
                // line 198
                $this->loadTemplate("table/structure/display_partitions.twig", "table/structure/display_structure.twig", 198)->display(twig_to_array(["db" =>                 // line 199
($context["db"] ?? null), "table" =>                 // line 200
($context["table"] ?? null), "url_query" =>                 // line 201
($context["url_query"] ?? null), "partitions" =>                 // line 202
($context["partitions"] ?? null), "partition_method" => $this->getAttribute(                // line 203
($context["first_partition"] ?? null), "getMethod", [], "method"), "partition_expression" => $this->getAttribute(                // line 204
($context["first_partition"] ?? null), "getExpression", [], "method"), "has_description" =>  !twig_test_empty($this->getAttribute(                // line 205
($context["first_partition"] ?? null), "getDescription", [], "method")), "has_sub_partitions" =>                 // line 206
($context["has_sub_partitions"] ?? null), "sub_partition_method" => ((                // line 207
($context["has_sub_partitions"] ?? null)) ? ($this->getAttribute(($context["first_sub_partition"] ?? null), "getMethod", [], "method")) : ("")), "sub_partition_expression" => ((                // line 208
($context["has_sub_partitions"] ?? null)) ? ($this->getAttribute(($context["first_sub_partition"] ?? null), "getExpression", [], "method")) : ("")), "action_icons" =>                 // line 209
($context["action_icons"] ?? null), "range_or_list" =>                 // line 210
($context["range_or_list"] ?? null), "remove_url_params" => twig_array_merge(                // line 211
($context["url_params"] ?? null), ["sql_query" => ($context["remove_sql"] ?? null)])]));
                // line 213
                echo "    ";
            } else {
                // line 214
                echo "        ";
                $this->loadTemplate("table/structure/display_partitions.twig", "table/structure/display_structure.twig", 214)->display(twig_to_array(["db" =>                 // line 215
($context["db"] ?? null), "table" =>                 // line 216
($context["table"] ?? null)]));
                // line 218
                echo "    ";
            }
            // line 219
            echo "    ";
            // line 220
            echo "    </div>
";
        }
        // line 222
        echo "
";
        // line 224
        if (($context["show_stats"] ?? null)) {
            // line 225
            echo "    ";
            echo ($context["table_stats"] ?? null);
            echo "
";
        }
        // line 227
        echo "<div class=\"clearfloat\"></div>
";
    }

    public function getTemplateName()
    {
        return "table/structure/display_structure.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  415 => 227,  409 => 225,  407 => 224,  404 => 222,  400 => 220,  398 => 219,  395 => 218,  393 => 216,  392 => 215,  390 => 214,  387 => 213,  385 => 211,  384 => 210,  383 => 209,  382 => 208,  381 => 207,  380 => 206,  379 => 205,  378 => 204,  377 => 203,  376 => 202,  375 => 201,  374 => 200,  373 => 199,  372 => 198,  369 => 197,  367 => 196,  362 => 194,  359 => 193,  356 => 192,  353 => 191,  350 => 190,  348 => 182,  345 => 181,  342 => 180,  339 => 179,  336 => 178,  333 => 177,  330 => 176,  328 => 175,  327 => 174,  326 => 173,  324 => 172,  321 => 171,  318 => 170,  315 => 169,  313 => 168,  311 => 167,  308 => 165,  302 => 163,  300 => 162,  299 => 161,  296 => 159,  293 => 156,  292 => 155,  291 => 154,  289 => 153,  287 => 152,  284 => 151,  282 => 149,  281 => 148,  280 => 147,  279 => 146,  278 => 145,  277 => 144,  276 => 143,  274 => 142,  270 => 140,  268 => 138,  266 => 136,  264 => 135,  261 => 134,  259 => 131,  258 => 130,  257 => 129,  253 => 127,  251 => 125,  250 => 124,  249 => 123,  248 => 122,  247 => 121,  246 => 120,  245 => 119,  240 => 116,  233 => 114,  230 => 113,  228 => 111,  227 => 110,  226 => 109,  225 => 108,  224 => 107,  223 => 106,  222 => 105,  221 => 104,  220 => 103,  219 => 102,  218 => 101,  217 => 100,  216 => 99,  215 => 98,  214 => 97,  212 => 96,  209 => 95,  207 => 93,  206 => 92,  205 => 91,  204 => 90,  203 => 89,  202 => 88,  201 => 87,  200 => 86,  199 => 85,  198 => 84,  197 => 83,  196 => 82,  195 => 81,  194 => 80,  193 => 79,  192 => 77,  191 => 73,  190 => 72,  189 => 71,  186 => 70,  183 => 69,  180 => 66,  177 => 65,  174 => 64,  171 => 61,  169 => 60,  166 => 59,  163 => 58,  160 => 57,  157 => 55,  155 => 54,  152 => 53,  150 => 52,  147 => 51,  145 => 50,  142 => 49,  140 => 48,  137 => 47,  135 => 46,  132 => 45,  130 => 44,  127 => 43,  124 => 42,  121 => 41,  118 => 40,  115 => 39,  112 => 38,  109 => 37,  107 => 36,  104 => 35,  101 => 34,  98 => 33,  95 => 31,  92 => 30,  90 => 29,  87 => 28,  84 => 27,  81 => 26,  78 => 25,  73 => 24,  70 => 23,  67 => 22,  64 => 20,  62 => 18,  61 => 17,  60 => 16,  58 => 15,  53 => 11,  50 => 10,  47 => 8,  45 => 7,  43 => 6,  41 => 5,  37 => 3,  33 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "table/structure/display_structure.twig", "C:\\edll_site\\site\\apache2\\htdocs\\phpMyAdmin\\templates\\table\\structure\\display_structure.twig");
    }
}