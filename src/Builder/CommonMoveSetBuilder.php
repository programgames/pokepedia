<?php


namespace App\Builder;


use App\Entity\Move;
use App\Entity\MoveLearnMethod;
use App\Entity\PokemonMove;
use App\Helper\MoveSetHelper;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\BinaryOp;

class CommonMoveSetBuilder
{
    public function getCommonMoveSet(string $format): array
    {
        $nodes[] = new Expression(new Assign(new Variable('pokemonMoveEntity'), new New_(new Name\FullyQualified(PokemonMove::class))));
        $nodes[] = new Expression(new MethodCall(
                new Variable('pokemonMoveEntity'),
                new Name('setPokemon'),
                [
                    new Arg(new Variable('pokemon'))
                ]
            )
        );
        $nodes[] = new Expression(new MethodCall(
                new Variable('pokemonMoveEntity'),
                new Name('setLearnMethod'),
                [
                    new Arg(new Variable('learnMethod'))
                ]
            )
        );
        $nodes[] = new Expression(new MethodCall(
                new Variable('propertyAccessor'),
                new Name('setValue'),
                [
                    new Arg(new Variable('pokemonMoveEntity')),
                    new Arg(new String_('versionGroup')),
                    new Variable('gen')
                ]
            )
        );
        $nodes[] = new Expression(new MethodCall(
                new Variable('propertyAccessor'),
                new Name('setValue'),
                [
                    new Arg(new Variable('moveEntity')),
                    new Arg(new String_('type')),
                    new Arg(new ClassConstFetch(
                            new Name\FullyQualified(MoveSetHelper::class),
                            new Name('MOVE_TYPE_GLOBAL'))
                    )
                ]
            )
        );
        return $nodes;
    }
}