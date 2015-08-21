<?php
namespace Calendar42\View\Helper;

use Calendar42\Selector\EventCalendarSelector;
use Zend\View\Helper\AbstractHelper;

class Calendar extends AbstractHelper
{
    /**
     * @var EventCalendarSelector
     */
    protected $eventCalendarSelector;

    /**
     * @param EventCalendarSelector $eventCalendarSelector
     */
    public function __construct(EventCalendarSelector $eventCalendarSelector)
    {
        $this->eventCalendarSelector = $eventCalendarSelector;
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
        $order = 'start ASC'
    ) {
        return $this
            ->eventCalendarSelector
            ->setCalendarIds($calendarIds)
            ->setLimit($limit)
            ->setOrder($order)
            ->setIncludePast($includePast)
            ->getResult();
    }

}
