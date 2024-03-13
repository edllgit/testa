<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <dbeaulieu@direct-lens.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\Node\Node;

/**
 * Represents an already parsed expression.
 *
 * @author Fabien Potencier <dbeaulieu@direct-lens.com>
 */
class ParsedExpression extends Expression
{
    private $nodes;

    /**
     * @param string $expression An expression
     * @param Node   $nodes      A Node representing the expression
     */
    public function __construct($expression, Node $nodes)
    {
        parent::__construct($expression);

        $this->nodes = $nodes;
    }

    public function getNodes()
    {
        return $this->nodes;
    }
}
