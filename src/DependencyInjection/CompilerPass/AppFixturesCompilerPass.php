<?php


namespace App\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use App\Loader\AppFixturesLoader;

final class AppFixturesCompilerPass implements CompilerPassInterface
{
    public const FIXTURE_TAG = 'app.fixture.orm';

    public function process(ContainerBuilder $container) : void
    {
        $definition     = $container->getDefinition(AppFixturesLoader::class);
        $taggedServices = $container->findTaggedServiceIds(self::FIXTURE_TAG);

        $fixtures = [];
        foreach ($taggedServices as $serviceId => $tags) {
            $groups = [];
            foreach ($tags as $tagData) {
                if (! isset($tagData['group'])) {
                    continue;
                }

                $groups[] = $tagData['group'];
            }

            $fixtures[] = [
                'fixture' => new Reference($serviceId),
                'groups' => $groups,
            ];
        }

        $definition->addMethodCall('addFixtures', [$fixtures]);
    }


}
