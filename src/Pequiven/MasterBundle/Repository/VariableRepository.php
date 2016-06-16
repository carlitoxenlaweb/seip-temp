<?php

namespace Pequiven\MasterBundle\Repository;

use Pequiven\SEIPBundle\Doctrine\ORM\ContainerAwareEntityRepository;

/**
 * Description of variableRepository
 *
 * @author victor tortolero
 */
class VariableRepository extends ContainerAwareEntityRepository {

    function getVariablesByFormula($formulaId) {
        $qb = $this->getQueryBuilder();
        $qb
                ->innerJoin('v.formulas', 'f')
                ->andWhere('f.id=  :idFormula')
                ->setParameter('idFormula', $formulaId)
        ;
        return $qb->getQuery();
    }

    protected function getAlias() {
        return 'v';
    }

}
