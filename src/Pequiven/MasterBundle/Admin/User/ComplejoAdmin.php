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
use Pequiven\MasterBundle\Model\MasterAdminInterface;

/**
 * Administracion de complejo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ComplejoAdmin extends Admin implements MasterAdminInterface
{
    private $asd;
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName($connection->getManagerName());
        $this->asd = $connection->getManagerName();
    }

    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) {
        $show
            ->add('id')
            ->add('description')
            ->add('ref')
            ->add('gerencias')
            ->add('createdAt')
            ->add('enabled')
            ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('description')
            ->add('ref')
            ->add('enabled')
            ;
    }
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('description')
            ->add('ref')
            ->add('enabled')
            ;
    }
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('description')
            ->add('ref')
            ->add('enabled')
            ;
    }
    
}
