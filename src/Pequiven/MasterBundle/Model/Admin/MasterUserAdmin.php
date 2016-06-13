<?php

namespace Pequiven\MasterBundle\Model\Admin;

use Sonata\UserBundle\Admin\Entity\UserAdmin;
use Pequiven\MasterBundle\Model\Admin\MasterInterface;

class MasterUserAdmin extends UserAdmin implements MasterInterface
{
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName('master');
    }
}