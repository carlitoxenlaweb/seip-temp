<?php

namespace Pequiven\SEIPBundle\Doctrine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

class SeipManagerRegistry extends Registry
{
    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */

    public function getManager($name = null)
    {
        if (null === $name) {
            $name = $this->container->get('session')->get('connectionParameter', 'default');
        }

        $managers = $this->getManagerNames();

        if (!isset($managers[$name])) {
            throw new \InvalidArgumentException(sprintf('Doctrine %s Manager named "%s" does not exist.', $this->getName(), $name));
        }
        
        return $this->getService($managers[$name]);
    }
}