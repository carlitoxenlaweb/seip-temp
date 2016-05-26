<?php

namespace Pequiven\MasterBundle\Admin\User;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\Admin\MasterBaseAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class RoleAdmin extends MasterBaseAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        var_dump("A");
        $form
            ->add('user', 'sonata_type_model_list', array(
                'label'        => 'pequiven_seip.user_label',
                'required'     => true,
                'btn_add'      => false,
                'btn_delete'   => false,
                'by_reference' => false
            ))
            ->add('rol', 'sonata_type_model_list', array(
                'label'        => 'pequiven_seip.rol_label',
                'required'     => true,
                'btn_delete'   => false,
                'by_reference' => false
            ))
            ->add('company', 'sonata_type_model', array(
                'label'         => 'pequiven_seip.comany_label',
                'required'      => true,
                'btn_add'       => false,
                'btn_list'      => false,
                'btn_delete'    => false,
                'btn_catalogue' => false,
                'by_reference'  => false
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('user', 'doctrine_orm_model_autocomplete', array(), null, array(
                'property'=>'username',
            ))
            ->add('rol', 'doctrine_orm_model_autocomplete', array(), null, array(
                'property'=>'name',
            ))
            ->add('company', 'doctrine_orm_model_autocomplete', array(), null, array(
                'property'=>'description',
            ))
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('user', 'many_to_one')
            ->addIdentifier('rol', 'many_to_one')
            ->addIdentifier('company', 'many_to_one')
        ;
    }
}
