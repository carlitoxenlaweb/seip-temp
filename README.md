SEIP Multi-Empresa
========================

Id del usuario de pruebas: *5879*

Privilegios y Roles: *Todos*

#### TODO:

- [x] Actualizar a Symfony 2.8 + dependencias
- [x] Crear servcio para cambiar entre entidades de forma automática
- [x] Cargar y mostrar solo las conexiones permitidas del usuario
- [x] Cambiar logo de la cabecera según empresa
- [x] Agregar el soporte "*MultipleEntidad*" a Sonata
- [x] Agregar al usuario las entidades de *conexión y empresa*
- [x] Crear interface para las clases *ADMIN* de SONATA
- [ ] Cargar logo de la empresa por defecto
- [x] Modificar los *roles* del sistema para multiples empresas
- [ ] Comprobar cada rol por empresa
- [ ] Sobreescribir metodo para auditar entidades
- [x] Crear script de sincronización

#### Enlaces:

- [Dependencias](#dependencias)
- [Base de Datos](#base-de-datos)
- [Cambios Significativos](#cambios-significativos)
- [Posibles Errores](#posibles-errores)
- [Modulos Sonata](#modulos-sonata)
- [Cambios de 2.4 a 2.8](#cambios-de-2.4-a-2.8)

| Para cambiar de empresa utilizar la URL '/setCompany/{nombre_de_la_conexion_en_parametros}'

Dependencias
----------------------------------

```json
php: >=5.3.3,
symfony/symfony: 2.8,
doctrine/orm: ^2.4.8,
doctrine/doctrine-bundle: ~1.4,
doctrine/doctrine-fixtures-bundle: 2.3.*,
stof/doctrine-extensions-bundle: ~1.1@dev,
twig/extensions: ~1.0,
symfony/assetic-bundle: ~2.3,
symfony/swiftmailer-bundle: ~2.3,
symfony/monolog-bundle: ~2.4,
sensio/distribution-bundle: ~4.0,
sensio/framework-extra-bundle: ^3.0.2,
sensio/generator-bundle: ~2.3,
incenteev/composer-parameter-handler: ~2.0,
tecnocreaciones/resource-bundle: dev-master,
tecnocreaciones/vzla-government-bundle: dev-master,
tecnocreaciones/ajax-fos-user-bundle: 1.*@dev,
tecnocreaciones/install-bundle: dev-master,
tecnocreaciones/tools-bundle: v0.1,
tecnocreaciones/vzla-entity-bundle: dev-master,
tecnocreaciones/vzla-fixtures-bundle: dev-master,
friendsofsymfony/rest-bundle: @stable,
friendsofsymfony/user-bundle: ~1.3,
friendsofsymfony/jsrouting-bundle: 1.5.3,
friendsofsymfony/oauth-server-bundle: 1.4.2,
jms/translation-bundle: ~1.1@dev,
jms/serializer-bundle: @stable,
knplabs/knp-menu-bundle: 2.1.1,
knplabs/knp-menu: 2.1.1,
white-october/pagerfanta-bundle: 1.0.*,
sylius/resource-bundle: 0.10.0,
phpoffice/phpexcel: 1.8.1,
tecnocreaciones/extjs-bundle: dev-master,
sonata-project/admin-bundle: ~2.4@dev,
sonata-project/core-bundle: 2.3.*@dev,
sonata-project/doctrine-orm-admin-bundle: 2.4.*@dev,
sonata-project/easy-extends-bundle: ~2.1@dev,
sonata-project/doctrine-extensions: ~1@dev,
sonata-project/block-bundle: ~2.2@dev,
sonata-project/user-bundle: ~2.2@dev,
sonata-project/cache-bundle: dev-master,
whiteoctober/tcpdf-bundle: dev-master,
simplethings/entity-audit-bundle: 0.*@dev,
genemu/form-bundle: 2.2.*@dev,
shtumi/useful-bundle: dev-master,
phpunit/phpunit: 4.8.*@dev,
tecnocreaciones/console-bundle: dev-master,
symfony/doctrine-bridge: ~3.0
os/doctrine-query-builder: ~1.0
```

Base de Datos
-------------------------------------

#### seip_user_connection

```sql
CREATE TABLE IF NOT EXISTS `seip_user_connection` (
  `user_id` int(11) NOT NULL,
  `connection_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `seip_user_connection` (`user_id`, `connection_id`) VALUES
(5879, 1),
(5879, 2),
(5879, 3),
(5879, 4),
(5879, 5);
```

#### seip_connection

```sql
CREATE TABLE IF NOT EXISTS `seip_connection` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `seip_connection` (`id`, `name`) VALUES
(1, 'master'),
(2, 'pequiven'),
(3, 'polinter'),
(4, 'pralca');
```

> Se ha agregado un campo a la tabla **seip\_c\_user** llamado **_default\_conn_**, el cual contiene el *Id* de la conexión por defecto del usuario.


Cambios Significativos
--------------------------------

Extendida la tabla de relaciones entre rol y usuario agregando el ID de la empresa en **fos_user_user_rol**

| user_id | rol_id | company_id |
| :---: | :---: | :---: |
| *seip_user* | *seip_c_rol* | *seip_cei_Company* |

Creado listener para los filtros antes de la ejecución de cada controlador

```php
DefaultController extends Controller implements ControllerFilters 
```

Sobreescritos los metodos de doctrine en **SEIPBundle/Doctrine** para gestionar las entidades, ejemplo:

```php
public function getManager($name = null)
{
    if (null === $name) {
        $name = $this->container->get('session')->get('connectionParameter', 'default'); //selectedManager;
    }

    $managers = $this->getManagerNames();

    if (!isset($managers[$name])) {
        throw new \InvalidArgumentException(sprintf('Doctrine %s Manager named "%s" does not exist.', $this->getName(), $name));
    }

    return $this->getService($managers[$name]);
}
```

Existe un servicio en *MasterBundle* [**MasterConnection**] que se encarga de las conexiones a entidades

```php
public function testMultiEmpresaAction($param){
    $conn = $this->get('app.connection_service');

    switch($param){
        case 'query':
            $query = $conn->createQuery('SELECT c.id, c.description FROM PequivenMasterBundle:Complejo c');
            $data = $query->getResult();
            break;

        case 'repo':
            $array = array();

            $data = $conn->getRepository('PequivenMasterBundle:Complejo')
                         ->findAll();

            foreach ($data as $item)
                array_push($array, $item->getId().": ".$item->getDescription());

            $data = $array;

            break;

        case 'insert':
            $complejo = new Complejo;

            $complejo->setCreatedAt(new \DateTime("now"));
            $complejo->setUpdatedAt(new \DateTime("now"));
            $complejo->setDescription("COMPLEJO SIN NOMBRE");
            $complejo->setEnabled(1);
            $complejo->setRef("CPAMC");

            $em = $conn->getManager();

            $em->persist($complejo);
            $em->flush();

            $data = "Registro Insertado";
            break;

        default:
            $data = $this->get('session')->get('connectionParameter');
            break;
    }

    var_dump($data);
}
```
> Existe un alias que apunta a todas las entidades modificadas dentro de los métodos de doctrine, por ejemplo, llamar a *$this->getDoctrine()->getManager()* es un alias a *$this->get('app.connection_service')->getManager()*

Implementada interfaz en las clases de SONATA para múltiples EMs (Multi-Empresa)

```php
use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseAdmin;
use Pequiven\MasterBundle\Model\MasterAdminInterface;

class UserAdmin extends BaseAdmin implements MasterAdminInterface
{
    protected $modelManager;

    public function setModelManager(\Sonata\AdminBundle\Model\ModelManagerInterface $modelManager) {
        parent::setModelManager($modelManager);
        $this->modelManager = $modelManager;
    }

    public function setCustomEntityManager(\Pequiven\MasterBundle\Service\MasterConnection $connection) {
        $this->modelManager->setEntityManagerName($connection->getManagerName());
        ...
        $this->modelManager->setEntityManagerName('nombre_de_la_entidad_en_parameters.yml');
    }
    ...
}
```

Definido el metodo que obtiene el ID de la empresa actual del usuario (seleccionada, no a la que pertenece)

```php
public function getCurrentCompanyId()
{
    return is_null($this->getCurrentCompany()) ?
        $this->getCompany()->getId() : $this->getCurrentCompany()->getId();
}
```

Remplazo en el metodo para obtener roles en la entidad del usuario

```php
public function getRoles()
{
    $roles = $this->roles;        
    if(is_null($this->getCompany()))
        return $roles;

    foreach ($this->getGroups() as $group) {
        if($this->getCurrentCompanyId() === $group->getCompany()->getId())
            $roles = array_merge($roles, $group->getRol()->getRoles());
    }
    
    $roles[] = static::ROLE_DEFAULT;
    return array_unique($roles);
}
```

Forma de reconocer si el rol pertenece a la empresa o no en la entidad del usuario

```php
$this->getCurrentCompanyId() === $group->getCompany()->getId()
```

Posibles Errores
----------------------------------
> Entities passed to the choice field must be managed. Maybe persist them in the entity manager?

La entidad de la conexión (EntityManager) no puede ser detectada automaticamente para la entidad que se esta manejando, para solventarlo hay que definir el EntityManager en el campo que hace referencia (relación) llamando al método *getEntityManagerName*, por ejemplo:

```php
->add('formulaLevel')

// por esto....

->add('formulaLevel', [null | 'entity'], array(
    'em' => $this->modelManager->getEntityManagerName()
))
```

> Allowed memory size of XXXXXXXXX bytes exhausted

El servidor se ha quedado sin memoria para procesar mas registros, una solución temporal es modificar el valor memory_limit desde la configuración de PHP


Modulos Sonata
----------------------------------

** Indicador **

- [x] Fórmula
- [x] Variable de Fórmulas
- [ ] Detalle de Fórmula del indicador <-- Error 500
- [ ] Valor de Indicador <-- Error 500
- [x] Etiqueta del indicador
- [x] Detalles de los Gráficos del indicador
- [x] Tipo de Punto de Atención
- [x] Frecuencia de Notificación
- [x] Indicador
- [x] Detalle de Indicador
- [ ] Plantilla de Reportes <-- Attempted to call an undefined method named "getReportTemplateTypesLabel" of class "Pequiven\SEIPBundle\Model\DataLoad\ReportTemplate"

** Objetivo **

- [x] Objetivo
- [x] Resultado

** Core **

- [ ] Periodo <-- [Semantical Error] line 0, col 99 near 'p_c IS NULL OR': Error: Cannot add having condition on a non result variable
- [ ] Configuración
- [x] Grupos de configuración

** Programa de gestión **

- [x] Programa de Gestión
- [x] Metas
- [ ] Detalles de Metas <-- Error 500
- [ ] Línea de Tiempo <-- Error 500

** Rango de gestión **

[x] Rango de gestión

** Línea Estratégica **

[x] Línea estratégica

** Tipos de Gráficos **

- [x] Gráficos

** Usuario **

- [x] Complejo
- [x] Gerencia de 1ra Línea
- [x] Gerencia de 2da Línea
- [x] Grupo de Gerencias
- [x] Superintendencia/Coordinación
- [x] Estructura de Cargos
- [x] Entidades
- [x] Permisos/Roles
- [x] Usuarios
- [x] Grupos

** Control Estadístico e Informático **

- [x] Causa de Parada
- [x] Empresa
- [x] Localidad
- [x] Entidad
- [x] Planta
- [x] Producto
- [x] Servicio
- [x] País
- [x] Moneda
- [x] Línea de Producto
- [x] Tipo de Registro
- [x] Sector
- [ ] Sub-Sector <-- Sin registros
- [x] Tipo de Incidencia
- [x] Tipo de Sede
- [x] Fallas de PNR
- [x] Unidad de Medida
- [x] Región
- [x] Hora de Parada
- [ ] Centro de Acopio <-- Sin registros
- [ ] Almacén <-- Sin registros
- [ ] Punto de Despacho <-- Sin registros

** SIG **

- [x] Sistema de Gestión
- [x] Políticas de Sistemas de Gestión
- [x] Procesos de los Sistemas de Gestión
- [x] Tipos de Acciones del Plan de Acción
- [x] Tipos de Verificaciones Plan de Acción

** Círculo de Estudio y Trabajo **

- [x] Etiquetas de Archivos

** Sip **

- [x] Material CUTL

** Localicación **

- [x] País
- [x] Región
- [x] Estado
- [x] Municipio
- [x] Parroquia
- [x] Ciudad


Cambios de 2.4 a 2.8
----------------------------------

**Pequiven/SEIPBundle/Resources/config/routing/dataLoad/dashboard.yml**
```yaml
pequiven_data_load_dashboard_production:
  path: /production/{typeView}
  defaults: .....
```

**Pequiven/SEIPBundle/DependencyInjection/PequivenSEIPExtension.php**
```php
$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
$loader->load('services.yml');
```

**app/config/config.yml**
```yaml
knp_menu:
  twig:
      template: knp_menu.html.twig
  templating: false
  default_renderer: twig
```

**Pequiven/SEIPBundle/Resources/config/services.yml**
```yaml
parameters:
  knp_menu.menu_builder.class: Pequiven\SEIPBundle\Menu\Template\Developer\BackendMenuBuilder
  knp_menu.voter.request.class: Pequiven\SEIPBundle\Menu\Template\Developer\RequestVoter

services:
  app.menu_builder:
      class: "%knp_menu.menu_builder.class%"
      arguments: ["@knp_menu.factory", "@security.context", "@translator", "@service_container"]
      calls:
          - [ setContainer, [ @service_container ] ]

  app.menu.main:
      class: Knp\Menu\MenuItem # the service definition requires setting the class
      # factory_service: app.menu_builder
      # factory_method: createSidebarMenu
      factory: ["@app.menu_builder", createSidebarMenu]
      arguments: ["@request", "@request_stack"]
      scope: request # needed as we have the request as a dependency here
      tags:
          - { name: knp_menu.menu, alias: main }

  app.menu.voter.request:
      class: "%knp_menu.voter.request.class%"
      arguments: ["@service_container"]
      tags:
          - { name: knp_menu.voter }

  app.twig_string_loader:
      class:        "Twig_Loader_String"

  app.twig_string:
      class:        "%twig.class%"
      arguments:    [@app.twig_string_loader, %knp_menu.renderer.twig.options%]
```

**Pequiven/SEIPBundle/Menu/Template/Developer/RequestVoter.php**
```php
//Comentar...
else if($item->getUri() !== $this->container->get('request')->getBaseUrl().'/' && (substr($this->container->get('request')->getRequestUri(), 0,  strlen($item->getUri() === $item->getUri())))){
     return true;
}
```

**Pequiven/SEIPBundle/Resources/views/Template/Developer/base.html.twig**
```html
<html class="no-js linen ng-app" lang="{{ app.request.locale }}">
```

**Pequiven/SEIPBundle/Resources/views/Template/Developer/Layout/_sidebar-drop-down-menu.html.twig**
```twig
{% set menu = knp_menu_get('main') %}
{{ knp_menu_render(menu, {'template': 'PequivenSEIPBundle:Template/Developer:menu.html.twig', 'currentClass': 'current navigable-current'}) }}
```

**Pequiven/SEIPBundle/Resources/config/routing/main.yml**
```
Cambiar el segundo pequiven_data_load --> pequiven_data_load_main
```

**Pequiven/MasterBundle/Admin/PeriodAdmin.php**
```
Cambiar $xor = $qb->expr()->orX($qb->expr()->isNull('p_c')); -> isNull('p_c.id'));
```

**Configurar la zona horaria**
```
/etc/php5/apache2/php.ini -> date.timezone = "America/Caracas"

/etc/apache2/apache2.conf -> SetEnv TZ America/Caracas

/etc/mysql/conf.d/mariadb.cnf -> default-time-zone = "-04:30"
```

**vendor/sonata-project/admin-bundle/Form/ChoiceList/ModelChoiceList.php**
```php
Agregar el siguiente código en la función load:
protected function load($choices)
{
    if (is_array($choices) && count($choices) > 0) {
        $entities = $choices;
    } elseif ($this->query) {
        $entities = $this->modelManager->executeQuery($this->query);
    } else {
        $entities = $this->modelManager->findBy($this->class);
    }
    if (null === $entities) {
        return array();
    }
    $choices = array();
    $this->entities = array();
    ...
```

**vendor/sonata-project/admin-bundle/Form/Type/ModelType.php**
```php
En la línea 90, cambiar el 
    'choices'           => null,
por
    'choices'           => array(),
```

<<<<<<< HEAD
**OJO PeriodService**
```php
$this->getDoctrine()->getEntityManager()->persist($user->getPeriod());
$this->getDoctrine()->getEntityManager()->persist($user->getParent());
=======
**Logos Empresas**
```
Los logos de las empresas (color blanco) que se mostrarán en el header de la aplicación se deben guardar en el dir:
Pequiven/SEIPBundle/Resources/public/logotipos-pqv/logotipos-pdf/nombre_de_la_empresa.png

En la BD se almacenaran los logos con los colores originales de cada empresa.
>>>>>>> 34732334b27a5b001e112eaf98d3b588c1550eca
```