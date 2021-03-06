<?php

namespace Pequiven\SEIPBundle\Repository\User;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de MOVIMIENTO DE EMPLEADOS
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FeeStructureRepository extends EntityRepository {

    function getChildren($idParent) {
        $qb = $this->getQueryBuilder();
        $qb
                ->Select('FeeStr')
                ->andWhere('FeeStr.parent= :Parent')
                ->orderBy('FeeStr.charge')
                ->setParameter('Parent', $idParent)
        ;
        return $qb->getQuery()->getResult();
    }

    function getGerente($idGerencia) {
        $qb = $this->getQueryBuilder();
        $qb
                ->Select('FeeStr')
                ->andWhere('FeeStr.gerencia= :Gerencia')
                ->andWhere('FeeStr.staff= :staff')
                ->andWhere($qb->expr()->isNull('FeeStr.gerenciasecond'))
                ->andWhere($qb->expr()->isNull('FeeStr.coordinacion'))
                ->orderBy('FeeStr.charge')
                ->setParameter('Gerencia', $idGerencia)
                ->setParameter('staff', 0)

        ;
        return $qb->getQuery()->getResult();
    }

    function getAllfeeStructure($idGerencia) {
        $qb = $this->getQueryBuilder();
        $qb
                ->Select('FeeStr')
                ->andWhere('FeeStr.gerencia= :Gerencia')
                ->andWhere('FeeStr.enabled= :enabled')
                ->orderBy('FeeStr.charge')
                ->setParameter('enabled', 1)
                ->setParameter('Gerencia', $idGerencia)
        ;
        return $qb;
    }

    function getAlias() {
        return 'FeeStr';
    }

}
