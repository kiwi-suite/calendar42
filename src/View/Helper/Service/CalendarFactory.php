<?php
namespace Calendar42\View\Helper\Service;

use Calendar42\Selector\EventCalendarSelector;
use Calendar42\TableGateway\CalendarTableGateway;
use Calendar42\View\Helper\Calendar;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class CalendarFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $selector = $container->get('Selector')->get(EventCalendarSelector::class);
        $tableGateway = $container->get('TableGateway')->get(CalendarTableGateway::class);

        return new Calendar($selector, $tableGateway);
    }
}
