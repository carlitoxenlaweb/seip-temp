<?php

namespace Pequiven\SEIPBundle\Controller\User;

use Pequiven\SEIPBundle\Controller\SEIPController;
use Symfony\Component\HttpFoundation\Request;
use Pequiven\SEIPBundle\Entity\User;
use Pequiven\SEIPBundle\Entity\User\FeeStructure;
use Pequiven\SEIPBundle\Entity\User\MovementFeeStructure;
use Pequiven\MasterBundle\Model\Gerencia;
use Pequiven\SEIPBundle\Form\User\MovementFeeStructureInType;
use Pequiven\SEIPBundle\Form\User\MovementFeeStructureOutType;
use Pequiven\SEIPBundle\Form\User\CreateFeeStructureType;

/**
 * GESTION EN LA ESTRUCTURA DE CARGOS SEIP
 */
class FeeStructureController extends SEIPController {

    public function showAction(Request $request) {

        $array = array();
        $idGerente = 1;
        $array[] = $this->getRepository()->find($idGerente);
        $structure = $this->GenerateTree($idGerente, $array);

//OPCIONES PARA LOS FILTROS
        $em = $this->getDoctrine()->getManager();
        $gerencias = $em->getRepository('PequivenMasterBundle:Gerencia')->getgerencias();
        $gerencias2 = $em->getRepository('PequivenMasterBundle:GerenciaSecond')->getgerenciasSecond();

        return $this->render('PequivenSEIPBundle:User:FeeStructure/show.html.twig', array(
                    'gerencias' => $gerencias,
                    'gerencias2' => $gerencias2,
                    'user' => $this->getUser(),
                    'structure' => $structure,
        ));
    }

    /**
     * GENERA EL ARBOL DE SUBORDINADOS EN LA ESTRUCTURA ORGANIZATIVA
     * @param type $padre
     * @param type $array
     * @return type
     */
    public function GenerateTree($padre, $array) {

        $children = $this->get('pequiven_seip.repository.feestructure')->getChildren($padre);

        if ($children == null) {
            return $array;
        }

        foreach ($children as $child) {
            $array[] = $child;
            $array = $this->GenerateTree($child->getid(), $array);
        }

        return $array;
    }

    /**
     * Insert
     * @return type
     */
    public function assignAction(Request $request) {

        $period = $this->getPeriodService()->getPeriodActive(true);

        $movementFeeStructure = new MovementFeeStructure();
        $form = $this->createForm(new MovementFeeStructureInType(), $movementFeeStructure);

        $structure = $this->get('pequiven_seip.repository.feestructure')->find($request->get('id'));

        if (isset($request->get('fee_structure_add')["_token"])) {
            $em = $this->getDoctrine()->getManager();
            $form->bind($this->getRequest());
            $movementFeeStructure = $form->getData();

            $movementFeeStructure->setPeriod($period);
            $movementFeeStructure->setType('I');
            $movementFeeStructure->setCreatedBy($this->getUser());
            $movementFeeStructure->setFeestructure($structure);

            if ($request->get('fee_structure_add_encargado')) {
                $encargado = 1;
            } else {
                $encargado = 0;
            }

//Asignación de Usuario
            $UserAssigned = $request->get('fee_structure_add')["User"];
            $UserAssigned = $this->get('pequiven.repository.user')->find($UserAssigned);
            $structure->setUser($UserAssigned); //Actualización de Cargo
            $structure->setEncargado($encargado); //Actualización de Cargo

            $em->persist($movementFeeStructure);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Cargo Asignado Exitosamente.');
            die();
        } elseif ($structure->getUser() != null) {
            echo "El Cargo ya esta Asignado!";
            die();
        } else {
            $formAction = "form_fee_structure_assign";
            $check = $user = true;
            $view = $this
                    ->view()
                    ->setTemplate($this->config->getTemplate('_form.html'))
                    ->setTemplateVar($this->config->getPluralResourceName())
                    ->setData(array(
                'formAction' => $formAction,
                'user' => $user,
                'check' => $check,
                'form' => $form->createView(),
                    ))
            ;
            $view->getSerializationContext()->setGroups(array('id', 'api_list'));
            return $view;
        }
    }

    /**
     * Remove
     * @return type
     */
    public function removeAction(Request $request) {

        $period = $this->getPeriodService()->getPeriodActive(true);

        $movementFeeStructure = new MovementFeeStructure();
        $form = $this->createForm(new MovementFeeStructureOutType(), $movementFeeStructure);

        $structure = $this->get('pequiven_seip.repository.feestructure')->find($request->get('id'));

        if (isset($request->get('fee_structure_add')["_token"])) {
            $em = $this->getDoctrine()->getManager();

            $form->bind($this->getRequest());
            $movementFeeStructure = $form->getData();

            $movementFeeStructure->setPeriod($period);
            $movementFeeStructure->setType('O');
            $movementFeeStructure->setCreatedBy($this->getUser());
            $movementFeeStructure->setFeestructure($structure);

//Asignación de Usuario
            $UserAssigned = NULL;
            $structure->setUser($UserAssigned); //Actualización de Cargo
            $structure->setEncargado($UserAssigned); //

            $em->persist($movementFeeStructure);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Cargo Removido Exitosamente.');
            die();
        } elseif ($structure->getUser() === null) {
            echo "El Cargo no esta Asignado!";
            die();
        } else {
            $formAction = "form_fee_structure_remove";
            $check = $user = false;
            $view = $this
                    ->view()
                    ->setTemplate($this->config->getTemplate('_form.html'))
                    ->setTemplateVar($this->config->getPluralResourceName())
                    ->setData(array(
                'formAction' => $formAction,
                'user' => $user,
                'check' => $check,
                'form' => $form->createView(),
                    ))
            ;
            $view->getSerializationContext()->setGroups(array('id', 'api_list'));
            return $view;
        }
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function CreateAction(Request $request) {

        $array = array();
/*
        //DATOS AUDITORIA
        $login = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        $em = $this->getDoctrine()->getManager();
        $securityService = $this->getSecurityService();

        $feestructure = new FeeStructure();
        $formCreate = $this->createForm(new CreateFeeStructureType(), $feestructure);
        $formCreate->handleRequest($request);

        $em->getConnection()->beginTransaction();

        if ($formCreate->isSubmitted()) {
            //DATOS DEL FORMULARIO
            $gerenciaform = $request->get("feestructurecreate")["gerencia"];
            if ($request->get("feestructurecreate")["gerenciasecond"] == '') {
                $gerenciaform2 = null;
            } else {
                $gerenciaform2 = $request->get("feestructurecreate")["gerenciasecond"];
            }
            $charge = $request->get("feestructurecreate")["charge"];
            $parent = $request->get("feestructurecreate")["parent"];
            $staff = $request->get("feestructurecreate")["staff"];
            $repeat = $request->get('repeat');

            for ($i = 1; $i <= $repeat; $i++) {
                $feestructure = new FeeStructure();
                $feestructure->setCreatedBy($login);
                $feestructure->setGerencia($gerenciaform);
                $feestructure->setGerenciasecond($gerenciaform2);
                $feestructure->setCharge($charge);
                $feestructure->setParent($parent);
                $feestructure->setStaff($staff);
            }
        }*/

        $gerencia = 14;
        $Gte = $this->get('pequiven_seip.repository.feestructure')->getGerente($gerencia);
        foreach ($Gte as $g) {
            $Gerente = $g;
        }

        $array[] = $this->getRepository()->find($Gerente->getid());
        $structure = $this->GenerateTree($Gerente->getid(), $array);



//FORMULARIO DE CREACIÓN
        $feeStructure = new FeeStructure();
        $formCreate = $this->createForm(new CreateFeeStructureType(), $feeStructure);

        return $this->render('PequivenSEIPBundle:User:FeeStructure/create.html.twig', array(                    
                    'user' => $this->getUser(),
                    'structure' => $structure,
                    'form_create' => $formCreate->createView(),
        ));
    }

    /**
     *  Period
     *
     */
    protected function getPeriodService() {
        return $this->container->get('pequiven_seip.service.period');
    }

}
