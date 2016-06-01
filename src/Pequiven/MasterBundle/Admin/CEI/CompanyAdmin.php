<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\MasterBundle\Admin\CEI;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Pequiven\MasterBundle\Admin\BaseAdminMaster;

/**
 * Administrador de compañia (Control estadistico e informacion)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class CompanyAdmin extends BaseAdminMaster
{
    protected function configureShowFields(\Sonata\AdminBundle\Show\ShowMapper $show) 
    {
        $show
            ->add('id')
            ->add('rif')
            ->add('description')
            ->add('typeOfCompany','choice',array(
                "choices" => \Pequiven\SEIPBundle\Entity\CEI\Company::getTypesOfCompanies(),
                "translation_domain" => "PequivenMasterBundle"
            ))
            ->add('affiliates')
            ->add('mixeds')
            ->add('region')
            ;
        parent::configureShowFields($show);
    }
    
    protected function configureFormFields(FormMapper $form) 
    {
        $object = $this->getSubject();
        
        $parameters = array(
            'em' => $this->modelManager->getEntityManagerName()
        );
        
        if($object !== null && $object->getId() > 0){
            $parameters['query_builder'] = function(\Doctrine\ORM\EntityRepository $repository) use ($object){
                return $repository->getQueryNotMe($object);
            };
        }
        $queryAllEnable = $this->getQueryAllEnable();
        $form
            ->add('rif')
            ->add('description')
            ->add('alias')
            ->add('typeOfCompany','choice',array(
                "choices" => \Pequiven\SEIPBundle\Entity\CEI\Company::getTypesOfCompanies(),
                "translation_domain" => "PequivenMasterBundle"
            ))
            ->add('affiliates',null,$parameters)
            ->add('mixeds',null,$parameters)
            ->add('region',null,array(
                "query_builder" => $queryAllEnable,
            ))
            ->add('base64image', 'file', array(
                'required' => false,
                'data_class' => null
            ))
        ;
        parent::configureFormFields($form);
    }

    public function prePersist($object)
    {
        $this->encodeBase64($object);
    }

    public function preUpdate($object)
    {
        $this->encodeBase64($object);
    }

    protected function encodeBase64($object)
    {
        $base64 = null;
        $image  = $object->getBase64Image();
        if($image !== null){
            $data   = file_get_contents($image->getPathname());
            $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode($data);
        } else {
            $entity = (object) $this->getModelManager()->getEntityManager($this->getClass())->getUnitOfWork()->getOriginalEntityData($object);
            $base64 = $entity->base64image;
        } $object->setBase64Image($base64);
    }
    
    protected function configureDatagridFilters(DatagridMapper $filter) 
    {
        $filter
            ->add('rif')
            ->add('description')
            ->add('typeOfCompany')
            ->add('region')
            ->add('enabled')
            ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('rif')
            ->add('description')
            ->add('alias')
            ;
        parent::configureListFields($list);
    }
}
