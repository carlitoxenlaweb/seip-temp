<?php

namespace Pequiven\SEIPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tecnocreaciones\Bundle\ResourceBundle\Controller\ResourceController as baseController;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Pequiven\MasterBundle\Entity\Complejo;
use Pequiven\ObjetiveBundle\Entity\Objetive;
use Pequiven\MasterBundle\Model\ControllerFilters;

class DefaultController extends Controller {

    /** Página Principal del Sistema
     * @Route("/")
     */
    public function indexAction() {

        $groupsUsers = $this->getUser()->getGroups();
        $securityService = $this->getSecurityService();

        // $conn = $this->get('app.connection_service');
        
        // $connection = $conn->getConnection();        
        // $statement = $connection->prepare("SELECT description FROM seip_objetive WHERE id = :id");
        // $statement->bindValue('id', 1190);
        // $statement->execute();
        
        // $results = $statement->fetchAll();

        // $em = $this->getDoctrine()->getManager();
        // $results = $em->getRepository('PequivenObjetiveBundle:Objetive')->find(1190);

        // var_dump($results);
        // die();
            
        if ((count($groupsUsers) == 1) && ($securityService->isGranted(array("ROLE_WORKER_PQV"))) && !($securityService->isGranted(array("ROLE_DIRECTIVE","ROLE_GENERAL_COMPLEJO","ROLE_MANAGER_FIRST",'ROLE_MANAGER_SECOND','ROLE_SUPERVISER')))) {
            //CARGAN SOLO ITEMS
            $showButton = false;
        } else {
            //CARGA PANTALLA NORMAL Y OPCION PARA VER EL BOTON
            $showButton = true;
        }

        return $this->render('PequivenSEIPBundle:Default:index.html.twig', array(
            "buttonItems" => $showButton
        ));
    }

    /** Página de Seleccion de empresas
     * @Route("/selectCompany")
     */
    public function selectOptionAction(){
        $user_companies = array();
        $conn = $this->get('app.connection_service');
        $query = $conn->createQuery('SELECT c.enabled, c.id, c.rif, c.description, c.alias, c.base64image FROM PequivenSEIPBundle:CEI\Company c');
        $companies = $query->getResult();

        foreach ($this->getUser()->getConnections() as $item){
            if($item->getName() !== 'master'){
                array_push($user_companies, array(
                    "company" => $item->getCompany()->getId(),
                    "server"  => $item->getName()
                ));
            }
        }
        return $this->render('PequivenSEIPBundle:Default:switch.html.twig', array(
            "available" => $user_companies,
            "companies" => $companies,
            "lastUrl"   => $this->getRequest()->headers->get('referer')
        ));
        /*
        return count($connections) > 1 ? 
            $this->render('PequivenSEIPBundle:Default:switch.html.twig', array(
                "options" => $connections
            )) : 
            $this->render('PequivenSEIPBundle:Default:index.html.twig', array(
                "buttonItems" => $showButton
            ));
        */
    }

    /** SETEAR EMPRESA
     * @Route("/setCompany/{connection}", name="switch_server",
     *                      requirements={"url" = ".*\/$"}, methods={"GET"})
     */
    public function setOptionAction(Request $request, $connection){
        $MasterConn = $this->getDoctrine()
                           ->getRepository('PequivenMasterBundle:MasterConnection')
                           ->findOneByName($connection);
        if($MasterConn) {
            $this->get('session')->set('currentCompanyId', $MasterConn->getCompany()->getId());
            $this->get('session')->set('currentLogoCompany', $MasterConn->getCompany()->getBase64Image());
            $this->get('session')->set('companyAlias', $MasterConn->getCompany());
            $this->get('session')->set('connectionParameter', $connection);
        } else {
            throw new \Exception('Empresa no encontrada');
        } // return $this->redirect($request->query->get('url'));
        return $this->redirectToRoute('pequiven_seip_menu_home');
    }

    // /**
    //  * @Route("/testMultiEmpresa/{param}")
    //  */
    // public function testMultiEmpresaAction($param){
    //     $conn = $this->get('app.connection_service');

    //     switch($param){
    //         case 'query':
    //             $query = $conn->createQuery('SELECT c.id, c.description FROM PequivenMasterBundle:Complejo c');
    //             $data = $query->getResult();
    //             break;

    //         case 'repo':
    //             $array = array();

    //             $data = $conn->getRepository('PequivenMasterBundle:Complejo')
    //                          ->findAll();

    //             foreach ($data as $item)
    //                 array_push($array, $item->getId().": ".$item->getDescription());

    //             $data = $array;

    //             break;

    //         case 'insert':
    //             $complejo = new Complejo;

    //             $complejo->setCreatedAt(new \DateTime("now"));
    //             $complejo->setUpdatedAt(new \DateTime("now"));
    //             $complejo->setDescription("COMPLEJO SIN NOMBRE");
    //             $complejo->setEnabled(1);
    //             $complejo->setRef("CPAMC");

    //             $em = $conn->getManager();

    //             $em->persist($complejo);
    //             $em->flush();

    //             $data = "Registro Insertado";
    //             break;

    //         default:
    //             $data = $this->get('session')->get('connectionParameter');
    //             break;
    //     }
    //     exit(\Doctrine\Common\Util\Debug::dump($data));
    // }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @Route("/{period}/change-period",name="seip_change_period")
     */
    public function changePeriodAction(\Symfony\Component\HttpFoundation\Request $request) {
        $referer = $request->get('referer', null);
        $periodId = $request->get('period', null);

        $periodRepository = $this->get('pequiven.repository.period');
        $period = $periodRepository->find($periodId);
        $periodService = $this->getPeriodService();
        if ($period) {
            $periodService->setPeriodActive($period);
        }
        return $this->redirect($referer);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/test",name="seip_change_period_test")
     */
    public function testAction(\Symfony\Component\HttpFoundation\Request $request) {
        $sequenceGenerator = $this->getSequenceGenerator();
//        $indicator = $this->get('pequiven.repository.indicator')->find('14');
        $indicator = $this->get('pequiven.repository.indicator')->find('65');
        $next = $sequenceGenerator->getNextRefChildIndicator($indicator);
        var_dump($next);
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/testObjetive",name="seip_objetive_test")
     */
    public function testObjetiveAction(\Symfony\Component\HttpFoundation\Request $request) {
        $sequenceGenerator = $this->getSequenceGenerator();
//        $indicator = $this->get('pequiven.repository.objetive')->find('37');
        $indicator = $this->get('pequiven.repository.objetive')->find('20');
        $ref = $sequenceGenerator->getNextRefChildObjetive($indicator);
        var_dump($ref);
    }

    public function moduleAction(){
        return $this->render('PequivenSEIPBundle:Default:developr.html.twig');
    }

    /**
     * @return \Pequiven\SEIPBundle\Service\PeriodService
     */
    public function getPeriodService() {
        return $this->get('pequiven_seip.service.period');
    }

    /**
     * 
     * @return \Pequiven\SEIPBundle\Service\SequenceGenerator
     */
    private function getSequenceGenerator() {
        return $this->get('seip.sequence_generator');
    }

    protected function getSecurityService() {
        return $this->container->get('seip.service.security');
    }

}