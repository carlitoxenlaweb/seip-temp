<?php

namespace Pequiven\SEIPBundle\Doctrine\ORM;

use Tecnocreaciones\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareEntityRepository extends EntityRepository implements ContainerAwareInterface
{
    /**
     * Container
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get Container
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}