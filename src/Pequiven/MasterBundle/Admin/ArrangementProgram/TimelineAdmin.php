<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\MasterBundle\Admin\ArrangementProgram;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\Admin\SonataBaseAdmin;

/**
 * Administrador del programa de gestion
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TimelineAdmin extends SonataBaseAdmin
{
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) 
    {
        $show
            ->add('id')
            ->add('goals')
            ->add('arrangementProgram')
        ;
    }
    
    protected function configureFormFields(FormMapper $form) 
    {
         $form
            ->add('goals','sonata_type_model_autocomplete',array(
                'property' => 'name',
                'multiple' => true,
            ))
            ->add('arrangementProgram',null,array(
                'disabled' => true,
                'em' => $this->modelManager->getEntityManagerName()
            ))
        ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) 
    {
        $filter
            ->add('arrangementProgram')
        ;
    }
    
    protected function configureListFields(ListMapper $list) 
    {
        $list
            ->addIdentifier('id')
            ->add('arrangementProgram')
            ->add('goals')
        ;
    }
    
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) 
    {
        $collection->remove('create');
    }
}
