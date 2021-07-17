<?php


namespace App\Builder;


use App\Entity\MoveName;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;

class GlobalMoveNodeBuilder
{

    public function getGlobalMoveNodes(int $generation, array $datas, string $type): array
    {
        $nodes = [];
        $i = 0;

        foreach ($datas as $versionGroupName => $moveData) {
            $entityName = sprintf('pokemonMoveEntity%s', $i);

            $moveData = array_flip($moveData);
            $nodes[] = new Expression(
                new Assign(
                    new Variable('moveName'),
                    new MethodCall(
                        new MethodCall(
                            new Variable('em'),
                            'getRepository',
                            [
                                new Arg(
                                    new ClassConstFetch(
                                        new Name\FullyQualified(MoveName::class),
                                        new Identifier('class')
                                    )
                                )
                            ]
                        ),
                        'findEnglishMoveNameByName',
                        [
                            new Arg(
                                new ArrayDimFetch(
                                    new ArrayDimFetch(
                                        new Variable('move'), new String_('value')
                                    ),
                                    new LNumber($moveData['move'])
                                )
                            ),
                            new Arg(new LNumber($generation))
                        ]
                    )
                )
            );
            $nodes[] = new Expression(
                new Assign(
                    new Variable('versionGroupEntity'),
                    new MethodCall(
                        new MethodCall(
                            new Variable('em'),
                            'getRepository',
                            [
                                new Arg(
                                    new ClassConstFetch(
                                        new Name\FullyQualified(VersionGroup::class),
                                        new Identifier('class')
                                    )
                                )
                            ]
                        ),
                        'findOneBy',
                        [
                            new Arg(new Array_(
                                    [
                                        new ArrayItem(
                                            new String_($versionGroupName),
                                            new String_('name')
                                        )
                                    ]
                                )
                            )
                        ]
                    )
                )
            );
            $nodes[] = new Expression(new Assign(new Variable('moveEntity'), new MethodCall(new Variable('moveName'), 'getMove')));
            $nodes[] = new  Expression(new Assign(new Variable($entityName), new New_(new Name\FullyQualified(PokemonMove::class))));
            $nodes[] = new Expression(new MethodCall(new Variable($entityName), 'setPokemon', [new Arg(new Variable('pokemon'))]));
            $nodes[] = new Expression(new MethodCall(new Variable($entityName), 'setLearnMethod', [new Arg(new Variable('learnMethod'))]));
            $nodes[] = new Expression(new MethodCall(new Variable($entityName), 'setMove', [new Arg(new Variable('moveEntity'))]));
            $nodes[] = new Expression(new MethodCall(new Variable($entityName), 'setVersionGroup', [new Arg(new Variable('versionGroupEntity'))]));

            if ($type === MoveSetHelper::BULBAPEDIA_LEVEL_WIKI_TYPE) {
                $nodes[] = new Expression(
                    new MethodCall(
                        new Variable($entityName),
                        'setLevel',
                        [
                            new Arg(
                                new StaticCall(new Name\FullyQualified(MoveSetHelper::class), 'convertLevel',
                                    [
                                        new Arg(new ArrayDimFetch(
                                                new ArrayDimFetch(
                                                    new Variable('move'),
                                                    new String_('value')
                                                ),
                                                new LNumber($moveData['level'])
                                            )
                                        )
                                    ]
                                )
                            )
                        ]
                    )
                );
            }

            $nodes[] = new Expression(new MethodCall(new Variable('em'), 'persist', [new Arg(new Variable($entityName))]));
            $i++;
        }
        return $nodes;
    }

}