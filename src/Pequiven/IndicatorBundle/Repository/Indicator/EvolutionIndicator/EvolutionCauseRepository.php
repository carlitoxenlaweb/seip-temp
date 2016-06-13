<?php

namespace Pequiven\IndicatorBundle\Repository\Indicator\EvolutionIndicator;


use Pequiven\IndicatorBundle\Entity\Indicator;
use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de las freuencias de notificación de los indicadores
 */
class EvolutionCauseRepository extends EntityRepository {


    function getCausesByObject($id, $typeObject) {
        
        $queryBuilder = $this->getQueryBuilder();

        $queryBuilder
                ->addSelect('ec')                
                ->where('ec.idObject = :id')
                ->andWhere('ec.typeObject = :typeObj')
                ->orderBy('ec.causes')
                ->setParameter('id', $id)
                ->setParameter('typeObj', $typeObject)
        ;        
        
        return $queryBuilder;
    }

    protected function getAlias() {
        return 'ec';
    }
}