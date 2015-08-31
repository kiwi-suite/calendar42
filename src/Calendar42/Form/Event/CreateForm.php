<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Form\Event;

use Admin42\FormElements\DateTime;
use Admin42\FormElements\Link;
use Calendar42\FormElements\Calendar;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class CreateForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add(new Csrf('csrf'));

        /** @var Calendar $calendar */
        $calendar = $this->getFormFactory()->getFormElementManager()->get('calendar');
        $calendar->setName("calendarId");
        $calendar->setLabel("Calendar");
        $calendar->setAttribute("required", "required");
        $this->add($calendar);

        $title = new Text('title');
        $title->setLabel('label.title');
        $title->setAttribute("required", "required");
        $this->add($title);

        /** @var DateTime $start */
        $start = $this->getFormFactory()->getFormElementManager()->get('datetime');
        $start->setName("start");
        $start->setLabel("Start");
        $start->setAttribute("required", "required");
        $this->add($start);

        /** @var DateTime $end */
        $end = $this->getFormFactory()->getFormElementManager()->get('datetime');
        $end->setName("end");
        $end->setLabel("End");
        $this->add($end);

        $info = new Checkbox('allDay');
        $info->setLabel('label.all-day');
        $this->add($info);

        $location = new Text('location');
        $location->setLabel('label.location');
        $this->add($location);

        $info = new TextArea('info');
        $info->setLabel('label.info');
        $this->add($info);

        /** @var Link $link */
        $link = $this->getFormFactory()->getFormElementManager()->get('link');
        $link->setName("linkId");
        $link->setLabel("Link");
        $this->add($link);
    }

    public function setCalendarId($calendarId) {

        $this->get('calendarId')->setValue($calendarId);
    }
}
