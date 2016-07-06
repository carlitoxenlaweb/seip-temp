<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Repository\PrePlanning;

use Pequiven\SEIPBundle\Doctrine\ORM\ContainerAwareEntityRepository;

/**
 * Repositorio de pre planificacion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class PrePlanningRepository extends ContainerAwareEntityRepository
{
    public function findIn(array $ids) {
        $qb = $this->getQueryBuilder();
        $qb->andWhere($qb->expr()->in('p.id', $ids));
        return $qb->getQuery()->getResult();
    }
    
    protected function getAlias() {
        return 'p';
    }
}
