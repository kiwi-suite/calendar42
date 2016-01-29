<?php
namespace Calendar42\View\Helper\Service;

use Calendar42\View\Helper\Calendar;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CalendarFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();
        $selector = $serviceManager
            ->get('Selector')
            ->get('Calendar42\EventCalendar');

        $tableGateway = $serviceManager
            ->get('TableGateway')
            ->get('Calendar42\Calendar');

        return new Calendar($selector, $tableGateway);
    }
}
