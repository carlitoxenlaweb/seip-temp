<?php

namespace Pequiven\IndicatorBundle\Repository\Indicator\EvolutionIndicator;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de los valores y observaciones de las acciones
 */
class EvolutionActionValueRepository extends EntityRepository {

    protected function getAlias() {
        return 'ev';
    }

}