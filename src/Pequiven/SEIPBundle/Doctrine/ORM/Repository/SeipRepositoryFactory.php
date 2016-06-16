<?php 

namespace Pequiven\SEIPBundle\Doctrine\ORM\Repository;

use Some\MyBundle\Doctrine\ORM\SeipEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeipRepositoryFactory extends DefaultRepositoryFactory
{
    /**
     * Container
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repository = parent::getRepository($entityManager, $entityName);

        if ($repository instanceof SeipEntityManager) {
            $repository->setContainer($this->container);
        }

        return $repository;
    }
}