<?php

namespace Pequiven\SEIPBundle;

use Pequiven\SEIPBundle\DependencyInjection\Compiler\MainCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pequiven\CoreBundle\PequivenCoreBundle;

class PequivenSEIPBundle extends Bundle
{
    const VERSION = '2.0.0';
    const VERSION_DATE = 'Desde el 28-04-2016 11:00am';

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MainCompilerPass());
    }
}
