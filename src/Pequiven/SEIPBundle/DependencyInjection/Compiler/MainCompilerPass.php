<?php

namespace Pequiven\SEIPBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MainCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $repositoryFactory = $container->getDefinition('app.doctrine.orm.repository_factory');
        $container->findDefinition('doctrine.orm.configuration')->addMethodCall('setRepositoryFactory', [$repositoryFactory]);
    }
}