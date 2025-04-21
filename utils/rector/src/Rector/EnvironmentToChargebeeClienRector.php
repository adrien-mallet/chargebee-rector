<?php

declare(strict_types=1);

namespace ChargebeeRector\Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Rector\AbstractRector;

final class EnvironmentToChargebeeClienRector extends AbstractRector implements ConfigurableRectorInterface
{
    private const ENVIRONMENT_CLASS = 'ChargeBee\ChargeBee\Models\Environment';
    private const ENVIRONMENT_METHOD = 'configure';
    private const CHARGEBEE_CLIENT_CLASS = 'Chargebee\ChargebeeClient';

    private ?Array_ $options = null;

    public function getNodeTypes(): array
    {
        return [
            FileWithoutNamespace::class,
            Class_::class,
            Expression::class,
        ];
    }

    public function refactor(Node $node): null|int|Node|array
    {
        if ($node instanceof Class_ || $node instanceof FileWithoutNamespace) {
            $this->options = null;

            return null;
        }

        $expr = $node->expr;
        $class = get_class($expr);

        return match($class) {
            StaticCall::class => $this->updateStaticCall($node, $expr),
            default => null,
        };
    }

    public function configure(array $configuration): void
    {
    }

    private function updateStaticCall(Node $node, Node $expr): int|Expression|array
    {
        if (!$this->isName($expr->class, self::ENVIRONMENT_CLASS)) {
            return null;
        }

        $options = $this->initOptions();

        if (!$this->isName($expr->name, self::ENVIRONMENT_METHOD)) {
            match ($this->getName($expr->name)) {
                'updateConnectTimeoutInSecs' => $this->addOptions(new String_('connectTimeoutInMillis'), $this->convertSecToMillis($expr->args[0])->value),
                'updateRequestTimeoutInSecs' => $this->addOptions(new String_('requestTimeoutInMillis'), $this->convertSecToMillis($expr->args[0])->value),
                'setUserAgentSuffix' => $this->addOptions(new String_('userAgentSuffix'), $expr->args[0]->value),
                default => null,
            };

            return null !== $options ? $options : NodeVisitor::REMOVE_NODE;
        }

        $this->addOptions(new String_('site'), $expr->args[0]->value);
        $this->addOptions(new String_('apiKey'), $expr->args[1]->value);

        $newClient = new Expression(new Assign(
            new Variable('chargebee'), // TODO configure var name.
            new New_(new FullyQualified(self::CHARGEBEE_CLIENT_CLASS), [
                new Arg(new Variable('options'))
            ])
        ), $node->getAttributes());

        return null !== $options ? [$options, $newClient] : $newClient;
    }

    private function addOptions(String_ $key, Variable|String_|Mul $value): void
    {
        $this->options->items[] = new ArrayItem($value, $key);
    }

    private function convertSecToMillis(Arg $argument): Arg
    {
        $value = $argument->value;
        $argument->value = new Mul($value, new Int_(1000));

        return $argument;
    }

    private function initOptions(): ?Node
    {
        if (null !== $this->options) {
            return null;
        }

        $this->options = new Array_([]);

        return new Expression(
            new Assign(
                new Variable('options'),
                $this->options
            )
        );
    }
}
