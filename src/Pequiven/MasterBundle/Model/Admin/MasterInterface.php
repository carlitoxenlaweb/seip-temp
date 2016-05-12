<?php

namespace Pequiven\MasterBundle\Model\Admin;

interface MasterInterface 
{
    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager);
    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection);
}