<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pequiven\SEIPBundle\Controller\DataLoad\RawMaterial;

use Pequiven\SEIPBundle\Controller\SEIPController;

/**
 * Controlador de detalles de consumo de materia prima
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DetailRawMaterialConsumptionController extends SEIPController 
{
    public function createNew() {
        $entity = parent::createNew();
        $request = $this->getRequest();
        $rawMaterialConsumptionPlanningId = $request->get("rawMaterialConsumption");
        if($rawMaterialConsumptionPlanningId > 0){
            $em = $this->getDoctrine()->getManager();
            $rawMaterialConsumptionPlanning = $em->getRepository("Pequiven\SEIPBundle\Entity\DataLoad\RawMaterial\RawMaterialConsumptionPlanning")->find($rawMaterialConsumptionPlanningId);
            $entity->setRawMaterialConsumptionPlanning($rawMaterialConsumptionPlanning);
        }
        return $entity;
    }
    
    public function createAction(\Symfony\Component\HttpFoundation\Request $request) 
    {
        $resource = $this->createNew();
        $form = $this->getForm($resource);

        if ($request->isMethod('POST') && $form->submit($request)->isValid()) {
            $resource = $this->domainManager->create($resource);

            return $this->redirectHandler->redirect($this->generateUrl("pequiven_raw_material_consumption_planning_show",array(
                "id" => $resource->getRawMaterialConsumptionPlanning()->getId(),
            )));
        }

        if ($this->config->isApiRequest()) {
            return $this->handleView($this->view($form));
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('create.html'))
            ->setData(array(
                $this->config->getResourceName() => $resource,
                'form'                           => $form->createView()
            ))
        ;

        return $this->handleView($view);
    }
    
    public function updateAction(\Symfony\Component\HttpFoundation\Request $request) 
    {
        $resource = $this->findOr404($request);
        $form = $this->getForm($resource);

        if (($request->isMethod('PUT') || $request->isMethod('POST')) && $form->submit($request)->isValid()) {

            $this->domainManager->update($resource);

            return $this->redirectHandler->redirect($this->generateUrl("pequiven_raw_material_consumption_planning_show",array(
                "id" => $resource->getRawMaterialConsumptionPlanning()->getId(),
            )));
        }

        if ($this->config->isApiRequest()) {
            return $this->handleView($this->view($form));
        }

        $view = $this
            ->view()
            ->setTemplate($this->config->getTemplate('update.html'))
            ->setData(array(
                $this->config->getResourceName() => $resource,
                'form'                           => $form->createView()
            ))
        ;

        return $this->handleView($view);
    }
}
