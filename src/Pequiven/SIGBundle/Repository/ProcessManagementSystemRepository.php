<?php

namespace Pequiven\SIGBundle\Repository;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de los procesos de los sistemas de gestión
 *
 */
class ProcessManagementSystemRepository extends EntityRepository
{
	protected function getAlias() {
        return 'pms';
    }
}
