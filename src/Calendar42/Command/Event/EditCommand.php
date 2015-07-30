<?php
/**
 * event42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Command\Event;

use Calendar42\Model\Event;
use Core42\Command\AbstractCommand;
use DateTime;

class EditCommand extends AbstractCommand
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var Event
     */
    private $eventModel;

    private $calendarId; // 'All Day Green'

    private $title; // 'All Day Green'

    private $start; // new Date(y, m, d-4, 9)

    private $end; // new Date(y, m, d-4, 21) // exclusive

    private $allDay; // true

    private $location; // 'Vienna'

    private $info; // 'All day event test: time truncated'

    private $linkId;

    /**
     * @param int $eventId
     * @return $this
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * @param mixed $calendarId
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @param mixed $allDay
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @param mixed $linkId
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setCalendarId(array_key_exists('calendarId', $values) ? $values['calendarId'] : null);
        $this->setTitle(array_key_exists('title', $values) ? $values['title'] : null);
        $this->setStart(array_key_exists('start', $values) ? $values['start'] : null);
        $this->setEnd(array_key_exists('end', $values) ? $values['end'] : null);
        $this->setAllDay(array_key_exists('allDay', $values) ? $values['allDay'] : null);
        $this->setLocation(array_key_exists('location', $values) ? $values['location'] : null);
        $this->setInfo(array_key_exists('info', $values) ? $values['info'] : null);
        $this->setLinkId(array_key_exists('linkId', $values) ? $values['linkId'] : null);
    }

    /**
     * validate values
     */
    protected function preExecute()
    {
        if (!empty($this->eventId)) {
            $this->eventModel =
                $this->getTableGateway('Calendar42\Event')->selectByPrimary((int)$this->eventId);
        }

        if (!($this->eventModel instanceof Event)) {
            $this->addError("event", "invalid event");
        }

        if (empty($this->calendarId)) {
            $this->addError("calendarId", "Calendar can't be empty");
        }

        if (empty($this->title)) {
            $this->addError("title", "Title can't be empty");
        }

        if(!empty($this->start)) {
            // truncate timezone information to prevent mysql error
            $start = new DateTime($this->start);
            $this->start = $start->format('Y-m-d H:i:s');
        } else {
            $this->addError("start", "Start can't be empty");
        }

        if(!empty($this->end)) {
            // truncate timezone information to prevent mysql error
            $end = new DateTime($this->end);
            $this->end = $end->format('Y-m-d H:i:s');
        }

        $this->end = (empty($this->end)) ? null : $this->end;
        $this->allDay = !empty($this->allDay);
    }

    /**
     * @return Event
     */
    protected function execute()
    {
        $dateTime = new \DateTime();
        $this->eventModel
            ->setCalendarId($this->calendarId)
            ->setTitle($this->title)
            ->setStart($this->start)
            ->setEnd($this->end)
            ->setAllDay($this->allDay)
            ->setLocation($this->location)
            ->setInfo($this->info)
            ->setLinkId($this->linkId)
            ->setCreated($dateTime)
            ->setUpdated($dateTime);

        $this->getTableGateway('Calendar42\Event')->update($this->eventModel);

        return $this->eventModel;
    }
}