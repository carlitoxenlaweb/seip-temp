<?php

namespace Pequiven\ArrangementProgramBundle\Repository;

use Tecnocreaciones\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Repositorio de programa de gestion
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArrangementProgramRepository extends EntityRepository
{
    protected function getAlias() {
        return 'ap';
    }
}