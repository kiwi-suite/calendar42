<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42;

use Admin42\Mvc\Controller\AbstractAdminController;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/../../config/assets.config.php',
            include __DIR__ . '/../../config/module.config.php',
            include __DIR__ . '/../../config/navigation.config.php',
            include __DIR__ . '/../../config/routing.config.php',
            include __DIR__ . '/../../config/translation.config.php'
        );
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractController',
            MvcEvent::EVENT_DISPATCH,
            function ($e) {
                $controller = $e->getTarget();

                if (!($controller instanceof AbstractAdminController)) {
                    return;
                }

                $sm = $e->getApplication()->getServiceManager();

                $viewHelperManager = $sm->get('viewHelperManager');

                $headScript = $viewHelperManager->get('headScript');
                $headLink = $viewHelperManager->get('headLink');
                $basePath = $viewHelperManager->get('basePath');

                $headScript->appendFile($basePath('/assets/admin/calendar/js/vendor.min.js'));
                $headScript->appendFile($basePath('/assets/admin/calendar/js/calendar42.min.js'));
                $headLink->appendStylesheet($basePath('/assets/admin/calendar/css/calendar42.min.css'));

                //$formElement = $viewHelperManager->get('formElement');
                //$formElement->addClass('Frontend42\FormElements\PageSelector', 'formpageselector');
            },
            100
        );
    }
}
