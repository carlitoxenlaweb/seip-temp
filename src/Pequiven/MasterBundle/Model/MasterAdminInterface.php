<?php

namespace Pequiven\MasterBundle\Model;

interface MasterAdminInterface 
{
    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager);
    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection);
}