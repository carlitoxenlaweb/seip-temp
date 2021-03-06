<?php

namespace Pequiven\SEIPBundle\Model\Admin;

use Pequiven\ObjetiveBundle\Entity\Objetive;
use Pequiven\IndicatorBundle\Entity\Indicator;
use Pequiven\ArrangementBundle\Entity\ArrangementRange;
use PDOException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseManager;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

/**
    * Description of ArrangementRangeManager
 *
 */
class ArrangementRangeManager extends BaseManager
{
    /* @var $emName stores the preferred entity manager name */
    protected $emName;
    
    /**
     * set preferred entity manager name to be used in ModelManager
     * @param string $name
     */
    public function setEntityManagerName($name){        
        $this->emName = $name;
    }

    /**
     * {@inheritdoc}
     */    
    public function createQuery($class, $alias = 'o') {
        //adding second parameter to getRepository method specifying entity manager name
        $repository = $this->getEntityManager($class)
                           ->getRepository($class, $this->emName);

        return new ProxyQuery($repository->createQueryBuilder($alias));
    }
    
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
        try {
            $entityManager = $this->getEntityManager($object);
            $entityManager->persist($object);
            $entityManager->flush();
            $this->persistAssociations($object);
        } catch (PDOException $e) {
            throw new ModelManagerException('', 0, $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function update($object)
    {       
        try {
            $entityManager = $this->getEntityManager($object);
            $this->persistAssociations($object);
            $entityManager->persist($object);
            $entityManager->flush();
        } catch (PDOException $e) {
            throw new ModelManagerException('', 0, $e);
        }
    }

    /**
     * Persist owning side associations
     */
    public function persistAssociations($object)
    {       
        $associations = $this
            ->getMetadata(get_class($object))
            ->getAssociationMappings();

        if ($associations) {
            $entityManager = $this->getEntityManager($object);

            foreach ($associations as $field => $mapping) {
                if ($mapping['isOwningSide'] == false) {
                    if($mapping['fieldName'] != 'indicator' && $mapping['fieldName'] != 'objetive'){
                        continue;
                    }
                    
                    if(($indicator = $object->getIndicator()) != null){
                        $indicator->setArrangementRange($object);
                        $entityManager->persist($indicator);
                    }
                    
                    if(($objetive = $object->getObjetive()) != null){
                        $objetive->setArrangementRange($object);
                        $entityManager->persist($objetive);
                    }
                    $entityManager->flush();
                }
            }
        }
    }
}