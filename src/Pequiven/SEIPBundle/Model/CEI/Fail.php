<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pequiven\SEIPBundle\Model\CEI;

use Pequiven\SEIPBundle\Model\BaseModel;

/**
 * Modelo de Falla
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class Fail extends BaseModel
{
    /**
     * Tipo: Falla Interna
     */
    const TYPE_FAIL_INTERNAL = 0;
    /**
     * Tipo: Falla Externa
     */
    const TYPE_FAIL_EXTERNAL = 1;
    
    public static function getTypeFailsLabels()
    {
        return array(
            self::TYPE_FAIL_INTERNAL => "pequiven_seip.fail.type.internal",
            self::TYPE_FAIL_EXTERNAL => "pequiven_seip.fail.type.external",
        );
    }
    
    public static function getTypeFilLabelByType($type)
    {
        $label = "";
        $typeFailsLabels = self::getTypeFailsLabels();
        if(isset($typeFailsLabels[$type])){
            $label = $typeFailsLabels[$type];
        }
        return $label;
    }
}
