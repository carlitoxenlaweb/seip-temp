<?php

namespace Pequiven\SEIPBundle\Model\Politic;

use Doctrine\ORM\Mapping as ORM;

use Pequiven\SEIPBundle\Model\BaseModel;

/**
 * Modelo del círculo de estudio de trabajo
 *
 */
abstract class WorkStudyCircle  {
    
    const PHASE_ONE = 1;
    const PHASE_TWO = 2;
    const PHASE_THREE = 3;
    
    /**
     * Retorna las secciones de tipo de resultado del indicador
     * 
     * @staticvar array $typesOfResultSection
     * @return array
     */
    static function getPhaseLabels() {
        static $phaseLabels = array(
            self::PHASE_ONE => 'workStudyCircle.phase',
            self::PHASE_TWO => 'workStudyCircle.phase',
            self::PHASE_THREE => 'workStudyCircle.phase',
        );
        return $phaseLabels;
    }
    
}
