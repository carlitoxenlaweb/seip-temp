<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Repository\DataLoad;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository;

/**
 * Repositorio de product report
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ProductReportRepository extends SeipEntityRepository {

    public function findByPlantReport($plantReport) {
        $qb = $this->getQueryBuilder();
        $qb
                ->innerJoin('pr.plantReport', 'pr_pr')
                ->andWhere('pr_pr.id = :plantReport')
                ->setParameter('plantReport', $plantReport)
        ;
        return $qb->getQuery()->getResult();
    }

    protected function getAlias() {
        return 'pr';
    }

}
