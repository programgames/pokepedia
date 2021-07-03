<?php


namespace App\Builder;


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
        $nodes[] = new Expression(new Assign(new Variable('moveEntity'), new New_(new Name\FullyQualified(MoveSetHelper::getClassByType($format)))));
        $nodes[] = new Expression(new MethodCall(
                new Variable('propertyAccessor'),
                new Name('setValue'),
                [
                    new Arg(new Variable('moveEntity')),
                    new Arg(new String_('pokemon')),
                    new Variable('pokemon')
                ]
            )
        );
        $nodes[] = new Expression(new MethodCall(
                new Variable('propertyAccessor'),
                new Name('setValue'),
                [
                    new Arg(new Variable('moveEntity')),
                    new Arg(new String_('generation')),
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
        $nodes[] = new Expression(new MethodCall(
                new Variable('propertyAccessor'),
                new Name('setValue'),
                [
                    new Arg(new Variable('moveEntity')),
                    new Arg(new String_('form')),
                    new Arg(new Ternary(
                            new BinaryOp\Equal(
                                new Variable('form'),
                                new String_('noform')),
                            new ConstFetch(new Name('null')),
                            new Variable('form')
                        )
                    )
                ]
            )
        );

        return $nodes;
    }
}