<?php


namespace App\Builder;


use App\Exception\UnknownMapping;
use App\Helper\NumberHelper;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;

class GlobalMoveNodeBuilder
{
    private CommonMoveSetBuilder $commonMoveSetBuilder;

    public function __construct(CommonMoveSetBuilder $commonMoveSetBuilder)
    {
        $this->commonMoveSetBuilder = $commonMoveSetBuilder;
    }

    public function getGlobalMoveNodes(string $propertyType, array $propertiesData): array
    {
        $nodes = [];
        $i = 1;
        $nodes = $this->commonMoveSetBuilder->getCommonMoveSet($propertyType);
        foreach ($propertiesData as $property) {
            $propertyType = key($property);
            $propertyName = $property[$propertyType];
            if ($propertyType === 'integer') {
                $nodes[] = new Expression(new MethodCall(
                        new Variable('propertyAccessor'),
                        new Name('setValue'),
                        [
                            new Arg(new Variable('moveEntity')),
                            new Arg(new String_($propertyName)),
                            new Arg(
                                new StaticCall(
                                    new Name\FullyQualified(
                                        NumberHelper::class
                                    ),
                                    'formatNumber',
                                    [
                                        new Arg(
                                            new ArrayDimFetch(
                                                new ArrayDimFetch(new Variable('move'), new String_('value')),
                                                new LNumber($i)
                                            )
                                        )
                                    ]
                                )
                            )
                        ]
                    )
                );
            } elseif ($propertyType === 'string') {
                $nodes[] = new Expression(new MethodCall(
                        new Variable('propertyAccessor'),
                        new Name('setValue'),
                        [
                            new Arg(new Variable('moveEntity')),
                            new Arg(new String_($propertyName)),
                            new Arg(
                                new ArrayDimFetch(
                                    new ArrayDimFetch(
                                        new Variable('move'), new String_('value')
                                    ),
                                    new LNumber($i)
                                )
                            )
                        ]
                    )
                );
            } else {
                throw new UnknownMapping('Unknown property type ' . $propertyType);
            }
            $i++;
        }
        $nodes[] = new Expression(new MethodCall(new Variable('em'), 'persist', [new Arg(new Variable('moveEntity'))]));

        return $nodes;
    }

}