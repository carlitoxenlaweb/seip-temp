<?php

namespace Pequiven\SEIPBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMException;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeipEntityManager extends EntityManager implements ContainerAwareInterface
{
    /**
     * Container
     * @var ContainerInterface
     */
    private $container;

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

    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case (is_array($conn)):
                $conn = \Doctrine\DBAL\DriverManager::getConnection(
                    $conn, $config, ($eventManager ?: new EventManager())
                );
                break;

            case ($conn instanceof Connection):
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                     throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new SeipEntityManager($conn, $config, $conn->getEventManager());
    }

	public function getRepository($entityName)
	{  
	    $entityName = ltrim($entityName, '\\');
	    if (isset($this->repositories[$entityName])) {
	        return $this->repositories[$entityName];
	    }

	    $metadata = $this->getClassMetadata($entityName);
	    $customRepositoryClassName = $metadata->customRepositoryClassName;

	    if ($customRepositoryClassName !== null) {
	        $repository = new $customRepositoryClassName($this, $metadata);
	    } else {
	        $repository = new EntityRepository($this, $metadata);
	    }

	    $this->repositories[$entityName] = $repository;

	    return $repository;
	}
}