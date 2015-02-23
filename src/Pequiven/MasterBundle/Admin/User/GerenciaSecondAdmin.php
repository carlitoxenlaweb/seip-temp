<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\MasterBundle\Admin\User;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Administrador de gerencia de segunda linea
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class GerenciaSecondAdmin extends Admin
{
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) {
        $show
            ->add('id')
            ->add('ref')
            ->add('abbreviation')
            ->add('description')
            ->add('gerencia')
            ->add('complejo')
            ->add('operationalObjectives')
            ->add('gerenciaVinculants')
            ->add('gerenciaSupports')
            ->add('createdAt')
            ->add('enabled')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('ref')
            ->add('abbreviation')
            ->add('description')
            ->add('gerencia')
            ->add('complejo','sonata_type_model_autocomplete',array(
                'property' => array('description')
            ))
            ->add('operationalObjectives','sonata_type_model_autocomplete',array(
                'required' => false,
                'property' => array('ref','description'),
                'multiple' => true
            ))
            ->add('gerenciaVinculants','sonata_type_model_autocomplete',array(
                'required' => false,
                'property' => array('ref','description'),
                'multiple' => true
            ))
            ->add('gerenciaSupports','sonata_type_model_autocomplete',array(
                'required' => false,
                'property' => array('ref','description'),
                'multiple' => true
            ))
            ->add('enabled')
            ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('id')
            ->add('ref')
            ->add('abbreviation')
            ->add('description')
            ->add('gerencia')
            ->add('enabled')
            ;
    }
    
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('ref')
            ->add('abbreviation')
            ->add('description')
            ;
    }
}