<?php

namespace Pequiven\IndicatorBundle\Repository\Indicator\EvolutionIndicator;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de las freuencias de notificación de los indicadores
 */
class EvolutionCauseAnalysisRepository extends EntityRepository {

    protected function getAlias() {
        return 'ca';
    }

}