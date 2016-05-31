<?php

namespace Pequiven\SEIPBundle\Model\Admin;

use PDOException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseManager;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

/**
 * Description of ModelManager
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class ModelManager extends BaseManager
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
     * get preferred entity manager name to be used in ModelManager
     */
    public function getEntityManagerName(){
        return $this->emName;
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
    public function getEntityManager($class) {
        if (is_object($class)) {
            $class = get_class($class);
        }                
        if (isset($this->cache[$class]) === false) {
            //return fixed value if preferred entity manager name specified
            if (isset($this->emName) === true) {
                $this->cache[$class] = $this->registry->getEntityManager($this->emName);
            } else {
                $this->cache[$class] = parent::getEntityManager($class);
            }
        }

        return $this->cache[$class];
    }
    
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
        $class = get_class($object);
        if($class !== 'Pequiven\MasterBundle\Entity\RolUser'){
            try {
                $entityManager = $this->getEntityManager($object);
                $entityManager->persist($object);
                $entityManager->flush();
                if( $class == 'Pequiven\IndicatorBundle\Entity\Indicator' ||
                    $class == 'Pequiven\SEIPBundle\Entity\CEI\Plant')
                        $this->persistAssociations($object);
            } catch (PDOException $e) {
                throw new ModelManagerException('', 0, $e);
            }
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function update($object)
    {
        switch(get_class($object)) {
            case 'Pequiven\IndicatorBundle\Entity\Indicator':
            case 'Pequiven\SEIPBundle\Entity\CEI\Plant':
                try {
                    $entityManager = $this->getEntityManager($object);
                    $originaObjects = $entityManager->getRepository(get_class($object))->findBy(array(
                        'parent' => $object,
                    ));
                    
                    $childrensObject = $object->getChildrens();
                    foreach ($originaObjects as $indicatorChild) {
                        $find = false;
                        foreach ($childrensObject as $child) {
                            if($child === $indicatorChild){
                                $find = true;
                                break;
                            }
                        }
                        if($find === false){
                            $indicatorChild->setParent(null);
                            $entityManager->persist($indicatorChild);
                        }
                    }
                    
                    $entityManager->persist($object);
                    $entityManager->flush();
                    $this->persistAssociations($object);
                } catch (PDOException $e) {
                    throw new ModelManagerException('', 0, $e);
                }
                break;

            default:
                try {
                    $entityManager = $this->getEntityManager($object);
                    $entityManager->persist($object);
                    $entityManager->flush();
                } catch (\PDOException $e) {
                    throw new ModelManagerException(sprintf('Failed to update object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
                } catch (DBALException $e) {
                    throw new ModelManagerException(sprintf('Failed to update object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
                }
                break;
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
                    if($mapping['fieldName'] != 'childrens'){
                        continue;
                    }
                    
                    if ($owningObjects = $object->{'get' . ucfirst($mapping['fieldName'])}()) {
                        foreach ($owningObjects as $owningObject) {
                            $owningObject->{'set' . ucfirst($mapping['mappedBy']) }($object);
                            $entityManager->persist($owningObject);
                        }
                        $entityManager->flush();
                    }
                }
            }
        }
    }
}