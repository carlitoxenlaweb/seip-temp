<?php

namespace Pequiven\MasterBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;

class MasterConnection
{
	private $connectionParam;

	private $doctrine;

	private $entityManager;

    public function __construct(Session $session, Registry $doctrine)
    {
        $this->connectionParam = $session->get('connectionParameter');
        // $this->connectionParam = $session->get('connectionParameter', 'default');

        $this->doctrine = $doctrine;
        $this->entityManager = $doctrine->getManager($this->connectionParam);
    }

    public function getRepository($repositoryName)
    {
        $data = $this->doctrine
                     ->getRepository($repositoryName, $this->connectionParam);
     	return $data;
    }

    public function getQueryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    public function createQuery($query)
    {
    	return $this->entityManager->createQuery($query);
    }

    public function getManager()
    {
    	return $this->entityManager;
    }

    public function getEntityManager() {
        return $this->getManager();
    }

    public function getManagerName()
    {
        return $this->connectionParam;
    }

    public function getConnection()
    {
        return $this->entityManager->getConnection();
    }
    
    //abstract function getTable();
}