<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pequiven\SEIPBundle\Menu\Template\Developer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of RequestVoter
 *
 * @author matias
 */
class RequestVoter implements VoterInterface {
    //put your code here
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function matchItem(ItemInterface $item){
        //var_dump($this->container->get('request')->getRequestUri());
        
        if($item->getUri() === $this->container->get('request')->getRequestUri()){
//            var_dump('alli<br>');
            return true;
        } else if($item->getUri() !== $this->container->get('request')->getBaseUrl().'/' && (substr($this->container->get('request')->getRequestUri(), 0,  strlen($item->getUri() === $item->getUri())))){
            
//            var_dump('aca<br>');
//            var_dump($item->getUri().'<br>');
//            var_dump($this->container->get('request')->getBaseUrl().'/<br>');
//            var_dump($this->container->get('request')->getRequestUri());
            //var_dump(substr($this->container->get('request')->getRequestUri(), 0,  strlen($item->getUri() === $item->getUri())));
            //die();
            return true;
        }
        return null;
    }
}