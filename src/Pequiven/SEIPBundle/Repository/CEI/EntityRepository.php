<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Repository\CEI;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository;

/**
 * Repositorio de entidad
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class EntityRepository extends SeipEntityRepository
{
    protected function getAlias() {
        return "e";
    }
}
