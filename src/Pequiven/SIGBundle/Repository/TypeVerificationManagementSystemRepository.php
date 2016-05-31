<?php

namespace Pequiven\SIGBundle\Repository;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio del tipo de verification (pequiven.repository.managementsystem_sig)
 *
 */
class TypeVerificationManagementSystemRepository extends EntityRepository
{
    protected function getAlias() {
        return 'tv';
    }
}