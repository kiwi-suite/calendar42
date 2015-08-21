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
use Zend\Form\Element\MultiCheckbox;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MultiCalendarFactory implements FactoryInterface
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
        foreach($result as $calendar) {
            $calendars [$calendar->getId()]= $calendar->getTitle();
        }
        $element = new MultiCheckbox();
        $element->setValueOptions($calendars);
        return $element;
    }
}
