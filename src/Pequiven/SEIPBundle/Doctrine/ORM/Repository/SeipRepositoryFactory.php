<?php 

namespace Pequiven\SEIPBundle\Doctrine\ORM\Repository;

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
        var_dump("factory");
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
var_dump("asd");
        $repository = parent::getRepository($entityManager, $entityName);

        if($repository instanceof ContainerAwareInterface){
            $repository->setContainer($this->container);
        }

        //var_dump($repository instanceof ContainerAwareInterface);
        return $repository;
    }

    /**
     * @inheritdoc
     */
    protected function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /*if ($entityName === Acme\Entities\User::class) {
            $metadata = $entityManager->getClassMetadata($entityName);
            return new ApplicationRepository($entityManager, $metadata);
        }*/
var_dump("asd");
        return parent::createRepository($entityManager, $entityName);
    }
}