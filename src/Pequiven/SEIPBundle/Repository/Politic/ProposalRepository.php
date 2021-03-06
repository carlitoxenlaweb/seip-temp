<?php

namespace Pequiven\SEIPBundle\Repository\Politic;

use Pequiven\SEIPBundle\Entity\Politic\Proposal;
use Pequiven\SEIPBundle\Entity\Period;
use Pequiven\SEIPBundle\Entity\User;
use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de la Propuesta del Círculo de Estudio de Trabajo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProposalRepository extends EntityRepository {

    /**
     * Crea un paginador para los indicadores de acuerdo al nivel del mismo
     * 
     * @param array $criteria
     * @param array $orderBy
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    function createPaginatorProposal(array $criteria = null, array $orderBy = null) {
        return $this->createPaginator($criteria, $orderBy);
    }
    
    function createPaginatorInheritedByWorkStudyCircle(array $criteria = null, array $oderBy = null){
        $criteria['inheritedByWorkStudyCircle'] = true;
        return $this->createPaginator($criteria, $oderBy);
    }

    protected function applyCriteria(\Doctrine\ORM\QueryBuilder $queryBuilder, array $criteria = null) {
        $criteria = new \Doctrine\Common\Collections\ArrayCollection($criteria);

        $queryBuilder->innerJoin('pr.workStudyCircle', 'wsc');
        
        if (($phase = $criteria->remove('phase'))) {
            $queryBuilder
                    ->andWhere('wsc.phase = :phase')
                    ->setParameter('phase', $phase)
                ;
        }
        
        //Filtro por descripción
        if(($description = $criteria->remove('description')) !== null){
            $queryBuilder->andWhere($queryBuilder->expr()->like('pr.description', "'%".$description."%'"));
        }

        //Filtro Localidad
        if (($complejo = $criteria->remove('complejo')) !== null) {
            $queryBuilder
                    ->andWhere('wsc.complejo = :complejo')
                    ->setParameter('complejo', $complejo)
            ;
        }

        if (($gerencia = $criteria->remove('firstLineManagement'))) {
            $queryBuilder
                    ->innerJoin('wsc.gerencias', 'g')
                    ->andWhere('g.id = :gerencia')
                    ->setParameter('gerencia', $gerencia)
            ;
        }

        if (($workStudyCircle = $criteria->remove('workStudyCircle'))) {
            $queryBuilder
                    ->andWhere('wsc.id = :workStudyCircle')
                    ->setParameter('workStudyCircle', $workStudyCircle)
            ;
        }

        if (($lineStrategic = $criteria->remove('lineStrategic'))) {
            $queryBuilder
                    ->andWhere('pr.lineStrategic = :lineStrategic')
                    ->setParameter('lineStrategic', $lineStrategic)
            ;
        }
        
        if(($inheritedByWorkStudyCircle = $criteria->remove('inheritedByWorkStudyCircle')) != null){
            if($inheritedByWorkStudyCircle == true){
                $queryBuilder->innerJoin('wsc.parent', 'wscp');
                if(($workStudyCircleParent = $criteria->remove('workStudyCircleParent')) != null){
                    $queryBuilder
                        ->andWhere('wscp.id = :workStudyCircleParent')
                        ->setParameter('workStudyCircleParent', $workStudyCircleParent)
                            ;
                }
            }
        }

        parent::applyCriteria($queryBuilder, $criteria->toArray());
    }

    /**
     * Propuestas por Complejo
     *
     *
     */
    function findQueryProposalComplejo($complejo) {

        //$queryBuilder->innerJoin('pr.workStudyCircle', 'wsc');

        $qb = $this->getQueryBuilder();
        $qb
                ->innerJoin('pr.workStudyCircle', 'wsc')
                ->andWhere('wsc.complejo = :complejo')
                ->setParameter('complejo', $complejo)
        ;

        return $qb->getQuery()->getResult();
    }

    protected function getAlias() {
        return 'pr';
    }

}
