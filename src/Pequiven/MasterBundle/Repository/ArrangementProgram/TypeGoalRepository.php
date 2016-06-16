<?php

namespace Pequiven\MasterBundle\Repository\ArrangementProgram;

use Pequiven\SEIPBundle\Doctrine\ORM\ContainerAwareEntityRepository;

/**
 * Repositorio del tipo de meta
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class TypeGoalRepository extends ContainerAwareEntityRepository 
{
    function findByCategory($category)
    {
        $qb = $this->getQueryBuilder();
        $qb
                ->innerJoin('tg.categoryArrangementProgram', 'c')
                ->andWhere('c.id = :category')
                ->setParameter('category', $category)
        ;
        return $qb->getQuery()->getResult();
    }
    protected function getAlias() {
        return "tg";
    }
}
