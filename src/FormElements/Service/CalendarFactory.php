<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\FormElements\Service;

use Calendar42\TableGateway\CalendarTableGateway;
use Zend\Form\Element\Select;
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
        /** @var CalendarTableGateway $tableGateway */
        $tableGateway = $serviceLocator->getServiceLocator()->get('tablegateway')->get('Calendar42\Calendar');
        $result = $tableGateway->select();
        $calendars = [];
        foreach ($result as $calendar) {
            $calendars [$calendar->getId()] = $calendar->getTitle();
        }
        $element = new Select();
        $element->setValueOptions($calendars);
        return $element;
    }
}
