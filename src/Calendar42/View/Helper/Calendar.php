<?php
namespace Calendar42\View\Helper;

use Calendar42\Selector\EventCalendarSelector;
use Calendar42\TableGateway\CalendarTableGateway;
use Zend\Db\Sql\Select;
use Zend\View\Helper\AbstractHelper;

class Calendar extends AbstractHelper
{
    /**
     * @var EventCalendarSelector
     */
    protected $eventCalendarSelector;

    /**
     * @var CalendarTableGateway
     */
    protected $calendarTableGateway;

    /**
     * @param EventCalendarSelector $eventCalendarSelector
     */
    public function __construct(
        EventCalendarSelector $eventCalendarSelector,
        CalendarTableGateway $calendarTableGateway
    ) {
        $this->eventCalendarSelector = $eventCalendarSelector;

        $this->calendarTableGateway = $calendarTableGateway;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param array $calendarIds
     * @param null $limit
     * @return mixed
     */
    public function getEvents(
        $calendarIds = [],
        $limit = null,
        $includePast = true,
        $order = 'start ASC',
        $timezone = null
    ) {
        $selector = $this
            ->eventCalendarSelector
            ->setCalendarIds($calendarIds)
            ->setLimit($limit)
            ->setOrder($order)
            ->setIncludePast($includePast);

        if ($timezone !== null) {
            $selector->setTimezone($timezone);
        }

        return $selector->getResult();
    }

    /**
     * @param string $orderBy
     * @return array
     */
    public function getAllCalendars($orderBy = 'title ASC')
    {
        $result = $this->calendarTableGateway->select(function (Select $select) use ($orderBy){
            if (!empty($orderBy)) {
                $select->order($orderBy);
            }
        });

        $calendars = [];
        foreach ($result as $_res) {
            $calendars[] = $_res;
        }

        return $calendars;
    }

}
