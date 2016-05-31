<?php

namespace Pequiven\SEIPBundle\Repository\Delivery;

use Pequiven\SEIPBundle\Doctrine\ORM\SeipEntityRepository as EntityRepository;

/**
 * Repositorio de product report
 *
 * @author Victor Tortolero <vart10.30@gmail.com>
 */
class ProductReportDeliveryRepository extends EntityRepository {

    protected function getAlias() {
        return 'prd';
    }

}
