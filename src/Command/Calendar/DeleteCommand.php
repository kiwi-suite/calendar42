<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Command\Calendar;

use Calendar42\Model\Calendar;
use Core42\Command\AbstractCommand;

class DeleteCommand extends AbstractCommand
{
    /**
     * @var Calendar
     */
    private $calendarModel;

    /**
     * @var int
     */
    private $calendarId;

    /**
     * @param int $calendarId
     * @return $this
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->calendarId)) {
            $this->calendarModel =
                $this->getTableGateway('Calendar42\Calendar')->selectByPrimary((int)$this->calendarId);
        }

        if (!($this->calendarModel instanceof Calendar)) {
            $this->addError("event", "invalid calendar");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->getTableGateway('Calendar42\Calendar')->delete($this->calendarModel);

        return $this->calendarModel;
    }
}
