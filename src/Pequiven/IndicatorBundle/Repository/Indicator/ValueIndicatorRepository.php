<?php

namespace Pequiven\IndicatorBundle\Repository\Indicator;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de las freuencias de notificación de los indicadores
 */
class ValueIndicatorRepository extends EntityRepository {

    protected function getAlias() {
        return 'vi';
    }

}
