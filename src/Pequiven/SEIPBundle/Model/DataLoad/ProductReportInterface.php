<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Model\DataLoad;

/**
 * Interfaz de producto de reporte
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ProductReportInterface 
{
    public function getTypeProduct();
    
    public function getProductPlannings();
}
