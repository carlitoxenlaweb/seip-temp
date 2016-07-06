<?php

namespace Pequiven\SEIPBundle\Doctrine\ORM;

use Tecnocreaciones\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class ContainerAwareEntityRepository extends EntityRepository
{
    public function __construct($em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->container = $em->getEventManager()->getContainer(); //Quitar Cable!
    }
}