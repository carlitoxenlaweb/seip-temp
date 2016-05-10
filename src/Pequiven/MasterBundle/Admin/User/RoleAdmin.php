<?php

namespace Pequiven\MasterBundle\Admin\User;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Model\MasterAdminInterface;

use Sonata\AdminBundle\Route\RouteCollection;

class RoleAdmin extends Admin implements MasterAdminInterface
{
    private $tmpName;
    private $tmpCompnay;

    private $session;

    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        // $this->modelManager->setEntityManagerName($connection->getManagerName());
        $this->modelManager->setEntityManagerName('master');
        $this->tmpName = $connection->getManagerName();
    }

    public function setMasterSession(\Symfony\Component\HttpFoundation\Session\Session $session)
    {
        $this->session = $session;

        $tmpCompnay = $this->session->get('currentCompanyId');
        $tmpName    = $this->session->get('connectionParameter');

        $this->session->set('currentCompanyId', 1);
        $this->session->set('connectionParameter', 'master');
    }

    // public function getNewInstance()
    // {
    //     $instance = parent::getNewInstance();

    //     $entityManager = $this->getModelManager()->getEntityManager('Pequiven\SEIPBundle\Entity\User');
    //     $reference = $entityManager->getReference('Pequiven\SEIPBundle\Entity\User', 5879);
    //     $instance->setUser($reference);

    //     $entityManager = $this->getModelManager()->getEntityManager('Pequiven\MasterBundle\Entity\Rol');
    //     $reference = $entityManager->getReference('Pequiven\MasterBundle\Entity\Rol', 8);
    //     $instance->setRol($reference);

    //     $entityManager = $this->getModelManager()->getEntityManager('Pequiven\SEIPBundle\Entity\CEI\Company');
    //     $reference = $entityManager->getReference('Pequiven\SEIPBundle\Entity\CEI\Company', 1);
    //     $instance->setCompany($reference);

    //     return $instance;
    // }

    // protected function configureRoutes(RouteCollection $collection)
    // {
    //     $collection->remove('delete');
    // }

    protected function configureFormFields(FormMapper $form)
    {
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
            // ->add('user', 'doctrine_orm_callback', array(
            //     'callback'   => array($this, 'getUserFilter'),
            //     'field_type' => 'text'
            // ));
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

    public function postPersist($object)
    {
        $this->session->set('currentCompanyId', $this->tmpCompnay);
        $this->session->set('connectionParameter', $this->tmpName);
    }

    // public function getUserFilter($queryBuilder, $alias, $field, $value)
    // {
    //     if (!$value['value']) {
    //         return;
    //     }

    //     $queryBuilder->where('u.id = :value')
    //                  ->orWhere('u.firstname LIKE :value')
    //                  ->orWhere('u.lastname LIKE :value')
    //                  ->orWhere('u.username LIKE :value')
    //                  ->orWhere('u.numPersonal = :value')
    //                  ->setParameter('value', $value['value'])
    //                  ->add('orderBy', 'u.firstname ASC');

    //     // $queryBuilder->andWhere($queryBuilder->expr()->orX(
    //     //     $queryBuilder->expr()->like($alias.'.id', $queryBuilder->expr()->literal('%' . $value['value'] . '%')),
    //     //     $queryBuilder->expr()->like($alias.'.firstname', $queryBuilder->expr()->literal('%' . $value['value'] . '%')),
    //     //     $queryBuilder->expr()->like($alias.'.lastname', $queryBuilder->expr()->literal('%' . $value['value'] . '%'))
    //     // ));

    //     return true;
    // }
}
