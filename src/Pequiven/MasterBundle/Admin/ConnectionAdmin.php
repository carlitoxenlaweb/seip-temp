<?php

namespace Pequiven\MasterBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\MasterAdminInterface;

class ConnectionAdmin extends Admin implements MasterAdminInterface
{
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName($connection->getManagerName());
    }

    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) {
        $show
            ->add('id')
            ->add('name')
            ->add('company', 'sonata_type_model', array(
                'required'      => true,
                'btn_add'       => false,
                'btn_list'      => false,
                'btn_delete'    => false,
                'btn_catalogue' => false
            ))
        ;
    }
    
    protected function configureFormFields(FormMapper $form) {
        $form
            ->add('name')
            ->add('company', 'sonata_type_model', array(
                'required'      => true,
                'btn_add'       => false,
                'btn_list'      => false,
                'btn_delete'    => false,
                'btn_catalogue' => false
            ))
        ;
    }
    protected function configureDatagridFilters(DatagridMapper $filter) {
        $filter
            ->add('id')
            ->add('name')
        ;
    }
    protected function configureListFields(ListMapper $list) {
        $list
            ->addIdentifier('name')
            ->add('company', 'sonata_type_model', array(
                'expanded' => true
            ))
        ;
    }
    
}
