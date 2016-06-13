<?php

namespace Pequiven\SEIPBundle\Repository\Delivery;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de grupos de productos de despacho
 *
 * @author Victor Tortolero <vart10.30@gmail.com>
 */
class ProductGroupDeliveryRepository extends EntityRepository {

    protected function getAlias() {
        return 'pgr';
    }

}
