<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Command\Calendar;

use Core42\Command\AbstractCommand;
use Calendar42\Model\Calendar;

class DeleteCommand extends AbstractCommand
{
    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * @var int
     */
    private $calendarId;

    /**
     * @param Calendar $calendar
     * @return $this
     */
    public function setCalendar(Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * @param int $calendarId
     * @return $this
     */
    public function setCalendarId($calendarId)
    {
        $this->$calendarID = $calendarId;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->calendarId)) {
            $this->calendar = $this->getTableGateway('Calendar42\Calendar')->selectByPrimary((int)$this->calendarId);
        }

        if (!($this->calendar instanceof Calendar)) {
            $this->addError("calendar", "invalid calendar");
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->getTableGateway('Calendar42\Calendar')->delete($this->calendar);
    }
}
