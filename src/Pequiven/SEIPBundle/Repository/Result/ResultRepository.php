<?php

namespace Pequiven\SEIPBundle\Repository\Result;

use Pequiven\SEIPBundle\Doctrine\ORM\ContainerAwareEntityRepository;

/**
 * Repositorio de resultado
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
class ResultRepository extends ContainerAwareEntityRepository
{
    /**
     * Retorna los resultados que sean de tipo resultado
     * @return type
     */
    function getQueryOfValidParents()
    {
        $qb = $this->getQueryBuilder();
        $qb
            ->andWhere('r.typeResult = :typeResult')
            ->setParameter('typeResult', \Pequiven\SEIPBundle\Entity\Result\Result::TYPE_RESULT_OF_RESULT)
            ;
        return $qb;
    }
    
    protected function getAlias() {
        return 'r';
    }
}
