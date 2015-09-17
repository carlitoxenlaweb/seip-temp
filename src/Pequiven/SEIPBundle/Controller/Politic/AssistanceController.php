<?php

namespace Pequiven\SEIPBundle\Controller\Politic;

use DateTime;
use Pequiven\SEIPBundle\Controller\SEIPController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controlador Asistencia
 *
 */
class AssistanceController extends SEIPController {

    public function editAction(Request $request) {
        $user_id = $request->get("user_id");
        $valueAssistance = $request->get("value");
        $valueAssistance = boolval(!$valueAssistance);
        
        $meetingId = $request->get("meeting");

        $assistance = $this->get("pequiven.repository.assistance")->findOneBy(array("user" => $user_id, "meeting" => $meetingId));

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        $assistance->setAssistance($valueAssistance);
        $em->persist($assistance);
        $em->flush();

        try {
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }

        $response = new JsonResponse();
        $data = array();
        $data["url"] = true;
        $response->setData($data);
        return $response;
    }

}
