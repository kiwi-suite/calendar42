<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @package   calendar42
 * @link      https://github.com/raum42/calendar42
 * @copyright Copyright (c) 2010-2017 raum42 OG (http://www.raum42.at)
 * @license   MIT License
 * @author    raum42 <kiwi@raum42.at>
 */

namespace Calendar42;

use Admin42\ModuleManager\Feature\AdminAwareModuleInterface;
use Admin42\ModuleManager\GetAdminConfigTrait;
use Core42\ModuleManager\Feature\CliConfigProviderInterface;
use Core42\ModuleManager\GetConfigTrait;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface,
    DependencyIndicatorInterface,
    CliConfigProviderInterface,
    AdminAwareModuleInterface
{
    use GetConfigTrait;
    use GetAdminConfigTrait;

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        if (!$serviceManager->get(Environment::class)->is(\Admin42\Module::ENVIRONMENT_ADMIN)) {
            return;
        }

        //$e->getApplication()->getEventManager()->getSharedManager()->attach(
        //    'Zend\Mvc\Controller\AbstractController',
        //    MvcEvent::EVENT_DISPATCH,
        //    function ($e) {
        //        $sm = $e->getApplication()->getServiceManager();
        //
        //        $viewHelperManager = $sm->get('viewHelperManager');
        //
        //        $headScript = $viewHelperManager->get('headScript');
        //        $headLink = $viewHelperManager->get('headLink');
        //        $basePath = $viewHelperManager->get('basePath');
        //
        //        $headScript->appendFile($basePath('/assets/admin/calendar/js/vendor.min.js'));
        //        $headScript->appendFile($basePath('/assets/admin/calendar/js/calendar42.min.js'));
        //        $headLink->appendStylesheet($basePath('/assets/admin/calendar/css/calendar42.min.css'));
        //
        //        //$formElement = $viewHelperManager->get('formElement');
        //        //$formElement->addClass('Frontend42\FormElements\PageSelector', 'formpageselector');
        //    },
        //    100
        //);
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'Core42',
            'Admin42',
        ];
    }

    /**
     * @return array
     */
    public function getCliConfig()
    {
        $config = [];
        $configPath = dirname((new \ReflectionClass($this))->getFileName()).'/../config/cli/*.config.php';

        $entries = Glob::glob($configPath);
        foreach ($entries as $file) {
            $config = ArrayUtils::merge($config, include_once $file);
        }

        return $config;
    }
}
