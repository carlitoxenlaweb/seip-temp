<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pequiven\MasterBundle\Repository;

use Pequiven\SEIPBundle\Doctrine\ORM\ContainerAwareEntityRepository;

/**
 * Description of FormulaRepository
 *
 * @author matias
 */
class FormulaRepository extends ContainerAwareEntityRepository {
    
    
    protected function getAlias() {
        return 'f';
    }
}
