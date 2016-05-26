<?php

namespace Pequiven\MasterBundle\Admin\SIG;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\Admin\SonataBaseAdmin;

/**
 * Administrador de los procesos de los sistemas de gestión
 *
 */
class ProcessManagementSystemAdmin extends SonataBaseAdmin
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
            ->add('managementSystem')   
            ->add('levelProcess', null, array(),'choice', array(
                    "choices" => \Pequiven\SIGBundle\Entity\ProcessManagementSystem::getlevelProcessArray(), 
                    'translation_domain' => 'PequivenSIGBundle',                                       
                    'required' => false,
            )) 
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
                    ->add('managementSystem', null, array(
                        'em' => $this->modelManager->getEntityManagerName()
                    ))
                    ->add('levelProcess', 'choice', array(
                    "choices" => \Pequiven\SIGBundle\Entity\ProcessManagementSystem::getlevelProcessArray(),                    
                    'translation_domain' => 'PequivenSIGBundle',                                                           
                    'required' => false,
                    ))                    
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
