<?php

namespace Pequiven\MasterBundle\Model;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface MasterAdminInterface 
{
    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager);
    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection);
}