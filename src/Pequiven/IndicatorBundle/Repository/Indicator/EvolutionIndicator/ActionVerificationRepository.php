<?php

namespace Pequiven\IndicatorBundle\Repository\Indicator\EvolutionIndicator;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de la Verificacion de la Acciones (pequiven.repository.sig_action_verification)
 */
class ActionVerificationRepository extends EntityRepository {

    protected function getAlias() {
        return 'av';
    }

}