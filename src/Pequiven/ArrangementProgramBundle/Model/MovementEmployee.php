<?php

namespace Pequiven\ArrangementProgramBundle\Model\MovementEmployee;

use Pequiven\SEIPBundle\Model\BaseModel;

abstract class MovementEmployee extends BaseModel implements MovementEmployeeInterface {

    /**
     * MAESTROS DE ENTRADA
     */
    const ASIGNACION = 1;
    const SUPLENCIA = 2;

    /**
     * MAESTROS DE SALIDA
     */
    const CAMBIO = 1;
    const RETIRO = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="causein", type="integer")
     */
    protected $causein;

    /**
     * @var integer
     *
     * @ORM\Column(name="causeout", type="integer")
     */
    protected $causeout;

    /**
     * 
     * @param type $causein
     */
    function setCausein($causein) {
        $this->causein = $causein;
    }

    /**
     * 
     * @param type $causeout
     */
    function setCauseout($causeout) {
        $this->causeout = $causeout;
    }

    /**
     * 
     * @staticvar array $array
     * @return string
     */
    static function getCausein() {
        static $array = [
            self::ASIGNACION => 'Asignación',
            self::SUPLENCIA => 'Suplencia',            
        ];
        return $array;
    }
    
    /**
     * 
     * @staticvar array $array
     * @return string
     */
    static function getCauseout() {
        static $array = [
            self::CAMBIO => 'Cambio',
            self::RETIRO => 'Retiro',            
        ];
        return $array;
    }

}
