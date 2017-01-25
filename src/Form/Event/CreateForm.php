<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Form\Event;

use Admin42\FormElements\Form;
use Admin42\FormElements\Link;

class CreateForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add(
            [
                'name' => 'csrf',
                'type' => 'csrf',
            ]
        );

        $calendar = $this->getFormFactory()->getFormElementManager()->get('calendar');
        $calendar->setName("calendarId");
        $calendar->setLabel("Calendar");
        $calendar->setAttribute("required", "required");
        $this->add($calendar);

        $this->add(
            [
                'name'     => 'title',
                'type'     => 'text',
                'label'    => 'label.title',
                'required' => true,
            ]
        );

        $this->add(
            [
                'name'     => 'start',
                'type'     => 'dateTime',
                'label'    => 'Start',
                'required' => true,
            ]
        );

        $this->add(
            [
                'name'     => 'end',
                'type'     => 'dateTime',
                'label'    => 'End',
            ]
        );

        $this->add(
            [
                'name'     => 'allDay',
                'type'     => 'checkbox',
                'label'    => 'label.all-day',
            ]
        );

        $this->add(
            [
                'name'     => 'location',
                'type'     => 'text',
                'label'    => 'label.location',
            ]
        );

        $this->add(
            [
                'name'     => 'info',
                'type'     => 'textarea',
                'label'    => 'label.info',
            ]
        );

        /** @var Link $link */
        $link = $this->getFormFactory()->getFormElementManager()->get(Link::class);
        $link->setName("linkId");
        $link->setLabel("Link");
        $this->add($link);
    }

    public function setCalendarId($calendarId)
    {
        $this->get('calendarId')->setValue($calendarId);
    }
}
