<?php

namespace App\DependencyInjection;

use App\DataFixtures\AppFixtureInterface;
use App\DependencyInjection\CompilerPass\AppFixturesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(AppFixtureInterface::class)
                  ->addTag(AppFixturesCompilerPass::FIXTURE_TAG);
    }
}