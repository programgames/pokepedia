<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Exception\UnknownMapping;
use App\Helper\MoveSetHelper;
use Doctrine\Persistence\ObjectManager;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\PrettyPrinter\Standard;

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
                                    new Param(new Variable('generation'), null, new Name\FullyQualified(Generation::class)),
                                    new Param(new Variable('em'), null, new Name\FullyQualified(ObjectManager::class)),
                                    new Param(new Variable('learnMethod'), null, new Name\FullyQualified(MoveLearnMethod::class)),
                                ],
                                'stmts' =>
                                    [
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
                foreach ($genMoves as $formatName => $datas) {
                    $formattedMovesConfigurations[] = [
                        'type' => $typeName,
                        'generation' => $genNumber,
                        'format' => $formatName,
                        'datas' => $datas
                    ];
                }
            }
        }

        foreach ($formattedMovesConfigurations as $key => $formattedMovesConfiguration) {
            if ($key === 0) {
                $mappingsNodes[] = new If_(
                    new BinaryOp\BooleanAnd(
                        new BinaryOp\BooleanAnd(
                            new BinaryOp\Identical(
                                new ArrayDimFetch(new Variable('move'), new String_('type')),
                                new String_($formattedMovesConfiguration['type'])
                            ),
                            new BinaryOp\Identical(
                                new MethodCall(new Variable('generation'), 'getGenerationIdentifier'), new LNumber($formattedMovesConfiguration['generation']),
                            ),
                        ),
                        new BinaryOp\Identical(
                            new ArrayDimFetch(
                                new Variable('move'),
                                new String_('format')
                            ),
                            new String_($formattedMovesConfiguration['format']),
                        )
                    ),
                    [
                        'stmts' => [
                            ...$this->getMoveNodesByFormatAndType($formattedMovesConfiguration['generation'], $formattedMovesConfiguration['format'], $formattedMovesConfiguration['datas'], $formattedMovesConfiguration['type']),
                        ]
                    ],

                );
            } else {
                $mappingsNodes[] = new ElseIf_(
                    new BinaryOp\BooleanAnd(
                        new BinaryOp\BooleanAnd(
                            new BinaryOp\Identical(
                                new ArrayDimFetch(new Variable('move'), new String_('type')),
                                new String_($formattedMovesConfiguration['type'])
                            ),
                            new BinaryOp\Identical(
                                new MethodCall(new Variable('generation'), 'getGenerationIdentifier'), new LNumber($formattedMovesConfiguration['generation']),
                            ),
                        ),
                        new BinaryOp\Identical(
                            new ArrayDimFetch(
                                new Variable('move'),
                                new String_('format')
                            ),
                            new String_($formattedMovesConfiguration['format']),
                        )
                    ),
                    $this->getMoveNodesByFormatAndType($formattedMovesConfiguration['generation'], $formattedMovesConfiguration['format'], $formattedMovesConfiguration['datas'], $formattedMovesConfiguration['type']),
                );
            }
        }
        $mappingsNodes[] = new Else_(
            [
                new Throw_(
                    new New_(new Name\FullyQualified(UnknownMapping::class),
                        [
                            new Arg(
                                new FuncCall(new Name('sprintf'), [
                                        new Arg(new String_('Unknown mapping format : %s / gen : %s ')),
                                        new Arg(
                                            new ArrayDimFetch(
                                                new Variable('move'),
                                                new String_('format')
                                            ),
                                        ),
                                        new Arg(new MethodCall(new Variable('generation'), 'getGenerationIdentifier'))
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

    private function getMoveNodesByFormatAndType(int $generation, string $format, array $datas, string $type): array
    {
        if ($format === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
            $nodes = $this->globalMoveNodeBuilder->getGlobalMoveNodes($generation, $datas, $type);
        } elseif ($format === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_SPECIFIC) {
            $nodes = $this->specificalMoveNodeBuilder->getSpecificMoveNodes($datas);
        } else {
            throw new UnknownMapping('Unknown move type ' . $format);
        }

        return $nodes;
    }
}
