<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\MasterBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Pequiven\SEIPBundle\Model\Common\CommonObject;
use Pequiven\MasterBundle\Model\Admin\MasterUserAdmin;

/**
 * Administrador de usuario
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
class UserAdmin extends MasterUserAdmin
{    
    protected function configureFormFields(\Sonata\AdminBundle\Form\FormMapper $formMapper) {
        $formMapper
            ->tab('General')
                ->with('Datos Básicos')
                    ->add('username')
                    ->add('email')
                    ->add('statusWorker','choice',array(
                        'choices' => CommonObject::getLabelsStatusWorker(),
                        'label' => 'pequiven_seip.status_worker',
                        'translation_domain' => 'PequivenSEIPBundle',
                    ))
                    ->add('affiliatedWorker', null, array(
                        'label' => 'Trabajador de Empresa Filial o Mixta',
                        'required' => false,
                    ))
                ->end()
                ->with('Empresa / Accesos')
                    ->add('defaultConnection', 'sonata_type_model', array(
                        'label'         => 'pequiven_seip.default_connection',
                        'required'      => true,
                        'btn_add'       => false,
                        'btn_list'      => false,
                        'btn_delete'    => false,
                        'btn_catalogue' => false
                    ))
                    ->add('company', 'sonata_type_model', array(
                        'label'         => 'pequiven_seip.company',
                        'required'      => true,
                        'btn_add'       => false,
                        'btn_list'      => false,
                        'btn_delete'    => false,
                        'btn_catalogue' => false
                    ))
                    ->add('connections', 'sonata_type_model', array(
                        'label'         => 'pequiven_seip.user_connection',
                        'multiple'      => true,
                        'required'      => true,
                        'btn_add'       => false,
                        'btn_list'      => false,
                        'btn_delete'    => false,
                        'btn_catalogue' => false
                    ))
                    ->add('userCompanies', 'sonata_type_model', array(
                        'label'         => 'pequiven_seip.user_companies',
                        'multiple'      => true,
                        'required'      => true,
                        'btn_add'       => false,
                        'btn_list'      => false,
                        'btn_delete'    => false,
                        'btn_catalogue' => false
                    ))
                ->end()
            ->end()
            ->tab('Detalles')
                ->with('Datos Personales')
                    ->add('dateOfBirth', 'birthday', array('required' => false))
                    ->add('firstname', null, array('required' => false))
                    ->add('lastname', null, array('required' => false))
                    ->add('website', 'url', array('required' => false))
                    ->add('biography', 'text', array('required' => false))
                    ->add('numPersonal', null, array('required' => false, 'label' => 'Número de Personal'))
                    ->add('cellphone', null, array('required' => false, 'label' => 'Número de Celular'))
                    ->add('gender', 'sonata_user_gender', array(
                        'required' => true,
                        'translation_domain' => $this->getTranslationDomain()
                    ))
                    ->add('locale', 'locale', array('required' => false))
                    ->add('timezone', 'timezone', array('required' => false))
                    ->add('phone', null, array('required' => false))
                ->end()
                ->with('Social')
                    ->add('facebookUid', null, array('required' => false))
                    ->add('facebookName', null, array('required' => false))
                    ->add('twitterUid', null, array('required' => false))
                    ->add('twitterName', null, array('required' => false))
                    ->add('gplusUid', null, array('required' => false))
                    ->add('gplusName', null, array('required' => false))
                ->end()
                ->with('Localidad')
                    ->add('complejo', 'sonata_type_model_autocomplete', array(
                        'required' => false,
                        'property' => array('description'),
                        'label' => 'Complejo'
                    ))
                    ->add('gerencia', 'sonata_type_model_autocomplete', array(
                        'required' => false,
                        'property' => array('description')
                    ))
                    ->add('gerenciaSecond', 'sonata_type_model_autocomplete', array(
                        'required' => false,
                        'property' => array('description')
                    ))
                ->end()
            ->end()
        ;
        
        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('Management')
                    ->with('Management')
                        // ->add('realRoles', 'sonata_security_roles', array(
                        //     'label'    => 'form.label_roles',
                        //     'expanded' => true,
                        //     'multiple' => true,
                        //     'required' => false,
                        //     'translation_domain' => $this->getTranslationDomain()
                        // ))
                        ->add('locked', null, array('required' => false))
                        ->add('expired', null, array('required' => false))
                        ->add('enabled', null, array('required' => false))
                        ->add('credentialsExpired', null, array('required' => false))
                    ->end()
                ->end()
            ;
        }

        $formMapper
            ->tab('Seguridad')
                ->with('Security')
                    ->add('plainPassword', 'text', array(
                        'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                    ))
                    ->add('token', null, array('required' => false))
                    ->add('twoStepVerificationCode', null, array('required' => false))
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('username')
                ->add('email')
                ->add('groups')
                ->add('enabled', null, array('editable' => true))
                ->add('createdAt', null, array(
                    'format' => 'Y-m-d h:i:s a'
                ))
        ;

        if ($this->isGranted('ROLE_SEIP_UNLOCKED_USER')) {
            $listMapper->add('locked', null, array('editable' => true));
        }

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                    ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
            ;
        }

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper->add('quarterToLoadOperationProduction', null, array('editable' => true));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(\Sonata\AdminBundle\Datagrid\DatagridMapper $filterMapper) {
        $filterMapper
                ->add('id')
                ->add('username')
                ->add('firstname')
                ->add('lastname')
                ->add('numPersonal')
                ->add('locked')
                ->add('email')
                ->add('groups')
        ;
    }

    public function preUpdate($user) {
        parent::preUpdate($user);
        if ($user->getConfiguration() == null) {
            $user->setConfiguration(new \Pequiven\SEIPBundle\Entity\User\Configuration());
        }
    }

}
