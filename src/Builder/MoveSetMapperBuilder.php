<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\Pokemon;
use App\Exception\UnknownMapping;
use App\Helper\MoveSetHelper;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\PropertyAccess\PropertyAccess;

class MoveSetMapperBuilder
{
    private SpecificalMoveNodeBuilder $specificalMoveNodeBuilder;
    private GlobalMoveNodeBuilder $globalMoveNodeBuilder;

    public function __construct(SpecificalMoveNodeBuilder $specificalMoveNodeBuilder, GlobalMoveNodeBuilder $globalMoveNodeBuilder)
    {
        $this->specificalMoveNodeBuilder = $specificalMoveNodeBuilder;
        $this->globalMoveNodeBuilder = $globalMoveNodeBuilder;
    }

    public function getMapperCode($config): string
    {
        $stmts = $this->getClassAndMethodNodes($config);
        $prettyPrinter = new Standard;
        return $prettyPrinter->prettyPrintFile($stmts);
    }

    private function getClassAndMethodNodes($config): array
    {
        return [
            new Namespace_(new Name('App')),
            new Class_(
                'MoveMapper',
                [
                    'stmts' => [
                        new ClassMethod(
                            'mapMoves',
                            [
                                'params' => [
                                    new Param(new Variable('pokemon'), null, new Name\FullyQualified(Pokemon::class)),
                                    new Param(new Variable('move'), null, new Identifier('array')),
                                    new Param(new Variable('form'), null, new Identifier('string')),
                                    new Param(new Variable('gen'), null, new Identifier('int')),
                                    new Param(new Variable('format'), null, new Identifier('string')),
                                    new Param(new Variable('em'), null, new Name\FullyQualified(ObjectManager::class)),
                                ],
                                'stmts' =>
                                    [
                                        new Expression(
                                            new Assign(
                                                new Variable('propertyAccessor'),
                                                new StaticCall(new Name\FullyQualified(PropertyAccess::class), 'createPropertyAccessor'))),
                                        ...$this->getConditionalMoveNodes($config['moves'])
                                    ]
                            ]
                        )
                    ]
                ]
            ),
        ];
    }

    private function getConditionalMoveNodes($movesConfig): array
    {
        $mappingsNodes = [];

        $formattedMovesConfigurations = [];
        foreach ($movesConfig as $typeName => $type) {
            foreach ($type as $genNumber => $genMoves) {
                foreach ($genMoves as $formatName => $properties) {
                    $formattedMovesConfigurations[] = [
                        'type' => $typeName,
                        'gen' => $genNumber,
                        'format' => $formatName,
                        'properties' => $properties
                    ];
                }
            }
        }

        foreach ($formattedMovesConfigurations as $formattedMovesConfiguration) {
            $mappingsNodes[] = new If_(
                new BinaryOp\BooleanAnd(
                    new BinaryOp\BooleanAnd(
                        new BinaryOp\Identical(
                            new ArrayDimFetch(new Variable('move'), new String_('type')),
                            new String_($formattedMovesConfiguration['type'])
                        ),
                        new BinaryOp\Identical(
                            new Variable('gen'), new DNumber($formattedMovesConfiguration['gen']),
                        ),
                    ),
                    new BinaryOp\Identical(
                        new Variable('format'), new String_($formattedMovesConfiguration['format']),
                    )
                ),
                [
                    'stmts' => [
                        ...$this->getMoveNodesByFormatAndType($formattedMovesConfiguration['format'], $formattedMovesConfiguration['type'], $formattedMovesConfiguration['properties']),
                    ]
                ],

            );
        }
        $mappingsNodes[] = new Else_(
            [
                new Throw_(
                    new New_(new Name\FullyQualified(UnknownMapping::class),
                        [
                            new Arg(
                                new FuncCall(new Name('sprintf'), [
                                        new Arg(new String_('Unknown mapping format : %s / gen : %s ')),
                                        new Arg(new Variable('format')),
                                        new Arg(new Variable('gen'))
                                    ]
                                )
                            )
                        ]
                    )
                )
            ]
        );

        return $mappingsNodes;
    }

    private function getMoveNodesByFormatAndType(string $format, string $propertyType, array $propertiesData): array
    {
        if ($format === MoveSetHelper::MOVE_TYPE_GLOBAL) {
            $nodes = $this->globalMoveNodeBuilder->getGlobalMoveNodes($propertyType, $propertiesData);
        } elseif ($format === MoveSetHelper::MOVE_TYPE_SPECIFIC) {
            $nodes = $this->specificalMoveNodeBuilder->getSpecificMoveNodes($propertyType, $propertiesData);
        } else {
            throw new UnknownMapping('Unknown move type ' . $format);
        }

        return $nodes;
    }
}
