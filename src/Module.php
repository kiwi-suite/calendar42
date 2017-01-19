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
use Core42\ModuleManager\GetConfigTrait;
use Core42\Mvc\Environment\Environment;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface,
    DependencyIndicatorInterface,
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
     * @return mixed
     */
    public function getCliConfig()
    {
        //return include_once __DIR__ . '/../config/cli/cli.config.php';
    }
}
