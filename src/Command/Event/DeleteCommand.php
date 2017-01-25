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
use Calendar42\TableGateway\EventTableGateway;
use Core42\Command\AbstractCommand;

class DeleteCommand extends AbstractCommand
{
    /**
     * @var Event
     */
    private $eventModel;

    /**
     * @var int
     */
    private $eventId;

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
     *
     */
    protected function preExecute()
    {
        if (!empty($this->eventId)) {
            $this->eventModel = $this->getTableGateway(EventTableGateway::class)->selectByPrimary((int)$this->eventId);
        }

        if (!($this->eventModel instanceof Event)) {
            $this->addError("event", "invalid event");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->getTableGateway(EventTableGateway::class)->delete($this->eventModel);

        return $this->eventModel;
    }
}
