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

class DeleteCommand extends AbstractCommand
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var int
     */
    private $eventId;

    /**
     * @param Event $event
     * @return $this
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @param int $eventId
     * @return $this
     */
    public function setEventId($eventId)
    {
        $this->$eventID = $eventId;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->eventId)) {
            $this->event = $this->getTableGateway('Event42\Event')->selectByPrimary((int)$this->eventId);
        }

        if (!($this->event instanceof Event)) {
            $this->addError("event", "invalid event");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->getTableGateway('Event42\Event')->delete($this->event);
    }
}
