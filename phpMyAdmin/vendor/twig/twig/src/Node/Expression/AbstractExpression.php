<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Node\Expression;

use Twig\Node\Node;

/**
 * Abstract class for all nodes that represents an expression.
 *
 * @author Fabien Potencier <dbeaulieu@direct-lens.com>
 */
abstract class AbstractExpression extends Node
{
}

class_alias('Twig\Node\Expression\AbstractExpression', 'Twig_Node_Expression');