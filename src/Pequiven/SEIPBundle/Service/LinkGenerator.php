<?php

namespace Pequiven\SEIPBundle\Service;

use LogicException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Exception;

/**
 * Generador de links por objeto
 *
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
abstract class LinkGenerator implements ContainerAwareInterface,  LinkGeneratorInterface
{
    /**
     * Contenedor de dependencias
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Tipo de link por defecto o categoria (Se usa para crear links diferentes del mismo objeto)
     */
    const TYPE_LINK_DEFAULT = 'default';
    
    /**
     * Configuraciones de las entitades
     * @var array
     */
    private $configsObjects = array();
    
    /**
     * Se usa para evaluar si ya se inicializo el generador
     * @var boolean
     */
    private $init = false;

    /**
     * Genera la configuracion de los objetos agreagados
     * @throws LogicException
     */
    private function boot (){
        $this->init = true;
        
        $configsObjectsDeft = $this->getConfigObjects();
        $defaultConfig = array(
            'type' => self::TYPE_LINK_DEFAULT,
            'icon' => 'fa fa-2x fa-flag',
            'method' => 'renderDefault',
            'routeParameters' => array(),
            'labelMethod' => null,
            'translation_domain' => null,
        );
        $configsObjects = array();
        foreach ($configsObjectsDeft as $key => $configObject)
        {
            $config = array_merge($defaultConfig,$configObject);
            if(!isset($config['class'])){
                throw new LogicException(sprintf('The class for item "%s" not defined',$key));
            }
            $class = $config['class'];
            if(!isset($configsObjects[$class])){
                $configsObjects[$class] = array(
                    'type' => array(),
                );
            }
            $type = $config['type'];
            if(isset($configsObjects[$class]['type'][$type])){
                throw new LogicException(sprintf('The type "%s" for the class "%s" in item "%s" is already defined (Change type o remove definition)',$type,$class,$key));
            }
            if($defaultConfig['method'] == $config['method'] && !isset($configObject['route'])){
                throw new LogicException(sprintf('The route for the class "%s" in item "%s" is required (optional with custom method)',$class,$key));
            }
            
            $configsObjects[$class]['type'][$type] = $config;
        }
        
        $this->configsObjects = $configsObjects;
    }
    
    /**
     * Metodo que renderiza el link por defecto
     * 
     * @param type $entity
     * @param type $entityConfig
     * @param type $type
     * @return type
     */
    protected function renderDefault($entity,$entityConfig,$type = self::TYPE_LINK_DEFAULT,array $parameters = array())
    {
        $route = $entityConfig['route'];
        $routeParameters = $entityConfig['routeParameters'];
        $labelMethod = $entityConfig['labelMethod'];
        
        if($labelMethod !== null){
            $label = call_user_func_array($entity, $labelMethod,array());
        }else{
            $label = (string)$entity;
        }
        if($entityConfig["translation_domain"] !== null){
            $label = $this->trans($label,array(),$entityConfig["translation_domain"]);
        }
        $originalLabel = $label;
        $truncate = 0;
        $addTitle = false;
        if(isset($parameters['truncate'])){
            $truncate = (int) $parameters['truncate'];
            if(strlen($label) > $truncate){
                $label = mb_substr($label, 0, $truncate,'UTF-8').'...';
                $addTitle = true;
            }
        }
        
        if(isset($parameters['_onlyIcon']) && $parameters['_onlyIcon'] === true){
            return $entityConfig['icon'];
        }
        
        $icon = sprintf('<i class="%s"></i>',$entityConfig['icon']);
        $href = $this->buildUrl($entity, $entityConfig);
        $entityConfig['url'] = $href;
        if(isset($parameters['_onlyUrl']) && $parameters['_onlyUrl'] === true){
            return $href;
        }
        if($href != ''){
            $extraParameters = '';
            if($addTitle === true){
                $extraParameters .= 'title = "'.$originalLabel.'"';
            }
            $link = sprintf('<a href="%s" %s>%s&nbsp;&nbsp;%s</a>',$href,$extraParameters,$icon,$label);
        }else{
            $link = sprintf('%s&nbsp;&nbsp;%s',$icon,$label);
        }
        
        if(isset($parameters['_onlyConf']) && $parameters['_onlyConf'] === true){
            return $entityConfig;
        }
        return $link;
    }
    
    private function buildUrl($entity,$entityConfig) {
        $route = $entityConfig['route'];
        $routeParameters = $entityConfig['routeParameters'];
        $href = '';
        if($route != null){
            $href = $this->generateUrl($route,array_merge(array('id' => $entity->getId()),$routeParameters));
        }
        return $href;
    }
    
    /**
     * Retorna la configuracion de una entidad u objeto
     * @param type $entity
     * @return type
     * @throws Exception
     */
    protected function getEntityConf($entity)
    {
        $entityClass = get_class($entity);
        if($this->init === false){
            $this->boot();
        }
        if(preg_match('/'. \Doctrine\Common\Persistence\Proxy::MARKER .'/',$entityClass)){
            $entityClass = \Doctrine\Common\Util\ClassUtils::getRealClass($entityClass);
        }
        if(!isset($this->configsObjects[$entityClass])){
            throw new Exception(sprintf('The config for entity "%s", not defined',$entityClass));
        }
        return $this->configsObjects[$entityClass];
    }
    
    /**
     * Genera un link a partir de la configuracion de ese objeto
     * @param type $entity
     * @param array $entityConfig
     * @param type $type
     * @return type
     */
    private function generateFromConfig($entity,array $entityConfig,$type,$parameters = array())
    {
        $method = $entityConfig['type'][$type]['method'];
        return call_user_func_array(array($this,$method), array($entity,$entityConfig['type'][$type],$type,$parameters));
    }

    /**
     * Genera el link del objeto
     * 
     * @param type $entity
     * @param type $type
     * @return type
     */
    public function generate($entity,$type = self::TYPE_LINK_DEFAULT,$parameters = array())
    {
        if($type === null){
            $type = self::TYPE_LINK_DEFAULT;
        }
        $entityConfig = $this->getEntityConf($entity);
        $link = '';
        if($entityConfig){
            $link = $this->generateFromConfig($entity,$entityConfig,$type,$parameters);
        }
        return $link;
    }
    
    public function generateOnlyUrl($entity,$type = self::TYPE_LINK_DEFAULT,$parameters = array())
    {
        $parameters['_onlyUrl'] = true;
        return $this->generate($entity,$type,$parameters);
    }
    
    public function getUnicodeIcon($entity,$type = self::TYPE_LINK_DEFAULT,$parameters = array())
    {
        $parameters['_onlyIcon'] = true;
        $icon = $this->generate($entity,$type,$parameters);
        $iconsDefinition = $this->getIconsDefinition();
        if(!isset($iconsDefinition[$icon])){
            throw new \InvalidArgumentException('The icon definition "%s", not found!',$icon);
        }
        $unicode = $iconsDefinition[$icon]['unicode'];
        return $unicode;
    }
    
    /**
     * Retornal la configuracion de una entidad
     * @param type $entity
     * @return type
     */
    public function getConfigFromEntity($entity) {
        $parameters = array();
        $parameters['_onlyConf'] = true;
        return $this->generate($entity,null,$parameters);
    }
    
    /**
     * Generates a URL from the given parameters.
     *
     * @param string         $route         The name of the route
     * @param mixed          $parameters    An array of parameters
     * @param bool|string    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
    
    protected function trans($id, $parameters = array(), $domain = 'message')
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
}
