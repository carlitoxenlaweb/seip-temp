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

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\MasterAdminInterface;

/**
 * Administrador de detalles de metas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class GoalDetailsAdmin extends Admin implements MasterAdminInterface
{
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName($connection->getManagerName());
    }
    
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('januaryPlanned')
            ->add('januaryReal')
            ->add('februaryPlanned')
            ->add('februaryReal')
            ->add('marchPlanned')
            ->add('marchReal')
            ->add('aprilPlanned')
            ->add('aprilReal')
            ->add('mayPlanned')
            ->add('mayReal')
            ->add('junePlanned')
            ->add('juneReal')
            ->add('julyPlanned')
            ->add('julyReal')
            ->add('augustPlanned')
            ->add('augustReal')
            ->add('septemberPlanned')
            ->add('septemberReal')
            ->add('octoberPlanned')
            ->add('octoberReal')
            ->add('novemberPlanned')
            ->add('novemberReal')
            ->add('decemberPlanned')
            ->add('decemberReal')
            ->add('goal')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('januaryPlanned')
            ->add('januaryReal')
            ->add('februaryPlanned')
            ->add('februaryReal')
            ->add('marchPlanned')
            ->add('marchReal')
            ->add('aprilPlanned')
            ->add('aprilReal')
            ->add('mayPlanned')
            ->add('mayReal')
            ->add('junePlanned')
            ->add('juneReal')
            ->add('julyPlanned')
            ->add('julyReal')
            ->add('augustPlanned')
            ->add('augustReal')
            ->add('septemberPlanned')
            ->add('septemberReal')
            ->add('octoberPlanned')
            ->add('octoberReal')
            ->add('novemberPlanned')
            ->add('novemberReal')
            ->add('decemberPlanned')
            ->add('decemberReal')
            ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('goal')
            ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('goal')
            ;
    }
    
    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection) {
        $collection->remove('create');
    }
}
