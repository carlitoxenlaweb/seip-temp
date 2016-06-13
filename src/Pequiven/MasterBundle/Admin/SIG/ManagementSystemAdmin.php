<?php

namespace Pequiven\MasterBundle\Admin\SIG;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\Admin\SonataBaseAdmin;

/**
 * Administrador de los sistemas de gestión
 *
 */
class ManagementSystemAdmin extends SonataBaseAdmin
{
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) 
    {
        $show
            ->add('id')
            ->add('description')
            ->add('enabled')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) 
    {
        $form
            ->tab('General')
                    ->add('description')
                    ->add('enabled',null,array(
                        'required' => false,
                    ))
                ->end()
            ->end()
            ->tab('Details')
                    ->add('politicManagementSystem')
                ->end()
            ->end()
        ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) 
    {
        $filter
            ->add('description')
            ->add('enabled')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('description')
            ->add('enabled')
            ->add('createdAt')
            ;
    }
}
