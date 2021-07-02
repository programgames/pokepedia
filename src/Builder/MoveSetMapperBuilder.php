<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\LevelingUpMove;
use App\Entity\TutoringMove;
use App\Helper\MoveSetHelper;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\PrettyPrinter\Standard;

class MoveSetMapperBuilder
{
    public function getMapperCode($config)
    {
        $stmts = [
            new Namespace_(new Name('App')),
            new Class_(
                'MoveMapper',
                [
                    'stmts' => [
                        new ClassMethod(
                            'mapMoves',
                            [
                                'params' => [
                                    new Param(new Variable('pokemon')),
                                    new Param(new Variable('move')),
                                    new Param(new Variable('form')),
                                    new Param(new Variable('gen')),
                                ],
                                'stmts' =>
                                    [
                                        ...$this->getMappingNodes($config['moves'])
                                    ]
                            ]
                        )
                    ]
                ]
            ),
        ];

        $prettyPrinter = new Standard;
        return $prettyPrinter->prettyPrintFile($stmts);
    }

    private function getMappingNodes($movesConfig)
    {
        $mappingsNodes = [];

        $formattedMovesConfiguration = [];
        foreach ($movesConfig as $typeName => $type) {
            foreach ($type as $genNumber => $gen) {
                foreach ($gen as $formatName => $properties) {
                    $formattedMovesConfiguration[] = [
                        'type' => $typeName,
                        'gen' => $genNumber,
                        'format' => $formatName,
                        'columns' => $properties
                    ];
                }
            }
        }

        foreach ($formattedMovesConfiguration as $formattedMovesConfiguration) {
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
                )
                ,
                [
                    ...$this->getSpecificalNodes($formattedMovesConfiguration['format'],$formattedMovesConfiguration['type'])
                ]
            );
        }
        return $mappingsNodes;
    }

    private function getClassByType(string $type)
    {
        $mapping = [
            'tutoring' => TutoringMove::class,
            'leveling' => LevelingUpMove::class
        ];

        return $mapping[$type];
    }

    private function getSpecificalNodes(string $format, string $type): array
    {
        $nodes = [];

        if ($format === MoveSetHelper::MOVE_TYPE_SPECIFIC) {
            $nodes[] = new Expression(new Assign(new Variable('move'),new New_(new Name\FullyQualified($this->getClassByType($type)))));
        }

        return $nodes;
    }

}
