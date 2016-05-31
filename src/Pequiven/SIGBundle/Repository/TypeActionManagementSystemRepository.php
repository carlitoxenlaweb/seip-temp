<?php

namespace Pequiven\SIGBundle\Repository;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de los tipos de Acción (pequiven.repository.managementsystem_sig)
 *
 */
class TypeActionManagementSystemRepository extends EntityRepository
{
    protected function getAlias() {
        return 'ta';
    }
}