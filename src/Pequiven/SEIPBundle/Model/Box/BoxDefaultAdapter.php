<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Model\Box;

use Tecnocreaciones\Bundle\BoxBundle\Model\Adapter\BoxBaseAdapter;

/**
 * Adaptador que devuele los boxes por defecto
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class BoxDefaultAdapter extends BoxBaseAdapter 
{
    public function getModelBoxes() {
        $boxActives = array();
        
        $boxActive = new \Tecnocreaciones\Bundle\BoxBundle\Model\BoxStaticLocked();
        $boxActive->setBoxName('pequiven_seip_box_my_arrangementprogram_summary');
        $boxActive->setArea(AreasBox::PRINCIPAL,array('position' => 1));
        $boxActives[] = $boxActive;
        
        if($this->isGranted('ROLE_WORKER_PLANNING')){
            $boxActive = new \Tecnocreaciones\Bundle\BoxBundle\Model\BoxStaticLocked();
            $boxActive->setBoxName('pequiven_seip_box_tactic_summaryindicatorcharged');
            $boxActive->setArea(AreasBox::PRINCIPAL,array('position' => 0));

            $boxActives[] = $boxActive;
        }
        if($this->isGranted(array('ROLE_DIRECTIVE','ROLE_DIRECTIVE_AUX','ROLE_WORKER_PLANNING'))){
            $boxActive = new \Tecnocreaciones\Bundle\BoxBundle\Model\BoxStaticLocked();
            $boxActive->setBoxName('pequiven_seip_box_dashboard_planningdashboard');
            $boxActive->setArea(AreasBox::DASHBOARD,array('position' => 0));

            $boxActives[] = $boxActive;
        }else{
            $boxActive = new \Tecnocreaciones\Bundle\BoxBundle\Model\BoxStaticLocked();
            $boxActive->setBoxName('pequiven_seip_box_operative_genericdashboard');
            $boxActive->setArea(AreasBox::DASHBOARD,array('position' => 0));

            $boxActives[] = $boxActive;
        }
        return $boxActives;
    }
}