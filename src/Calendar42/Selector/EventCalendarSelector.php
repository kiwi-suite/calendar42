<?php
namespace Calendar42\Selector;

use Calendar42\Model\Event;
use Cocur\Slugify\Slugify;
use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\DatabaseHydrator;
use Core42\Selector\AbstractDatabaseSelector;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Json\Json;
use Zend\View\Helper\ServerUrl;
use Zend\View\Helper\Url;

class EventCalendarSelector extends AbstractDatabaseSelector
{
    /**
     * @var array
     */
    protected $calendarIds;

    /**
     * @var array
     */
    protected $eventIds;

    /**
     * @var bool
     */
    protected $crudUrls;

    /**
     * @var bool
     */
    protected $ical;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var bool
     */
    protected $includePast = true;

    /**
     * @var string
     */
    protected $order = 'start ASC';

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @param $calendarIds
     * @return $this
     */
    public function setCalendarIds($calendarIds)
    {
        if(empty($calendarIds)) {
            return $this;
        }

        if (!is_array($calendarIds)) {
            $calendarIds = [$calendarIds];
        }
        $this->calendarIds = $calendarIds;

        return $this;
    }

    /**
     * @param $eventIds
     * @return $this
     */
    public function setEventIds($eventIds)
    {
        if(empty($eventIds)) {
            return $this;
        }

        if (!is_array($eventIds)) {
            $eventIds = [$eventIds];
        }
        $this->eventIds = $eventIds;

        return $this;
    }

    /**
     * @param bool $crudUrls
     * @return $this
     */
    public function setCrudUrls($crudUrls)
    {
        $this->crudUrls = $crudUrls;

        return $this;
    }

    /**
     * @param boolean $ical
     * @return $this
     */
    public function setIcal($ical)
    {
        $this->ical = $ical;

        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param $includePast
     * @return $this
     */
    public function setIncludePast($includePast)
    {
        $this->includePast = $includePast;

        return $this;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        /** @var DatabaseHydrator $hydrator */
        $hydrator = $this->getTableGateway('Calendar42\Event')->getHydrator();

        /** @var Url $urlHelper */
        $urlHelper = $this->getServiceManager()->get('viewHelperManager')->get('url');

        $linkTableGateway = $this->getTableGateway('Admin42\Link');
        $linkProvider = $this->getServiceManager()->get('Admin42\LinkProvider');


        /*
         * calendars
         */

        $calendars = [];
        $slugify = new Slugify();
        $result = $this->getTableGateway('Calendar42\Calendar')->select();
        foreach ($result as $calendar) {
            $calendarSettings = json_decode($calendar->getSettings());

            $calendars[$calendar->getId()] = [
                'name'  => $calendar->getTitle(),
                'class' => $slugify->slugify($calendarSettings->handle),
                'color' => $calendarSettings->color,
            ];
        }

        /*
         * events
         */

        $events = [];
        $sql = new Sql($this->getServiceManager()->get('Db\Master'));
        $statement = $sql->prepareStatementForSqlObject($this->getSelect());
        $result = $statement->execute();

        foreach ($result as $eventData) {

            $event = new Event;
            $event->exchangeArray($hydrator->hydrateArray($eventData));

            // TODO: correct end before start date - fullcalendar will display, ical not
            // TODO: correct end before start date - fullcalendar will display, ical not

            $start = $event->getStart();
            $end = $event->getEnd();

            // all-day events should have added one day each
            //if($event->getAllDay()) {
            //    if($end) {
            //        $end->modify('+1 day');
            //    }
            //}

            if (!empty($this->timezone)) {
                $start->setTimezone(new \DateTimeZone('Europe/Vienna'));
            }
            $event->setStart($start->format('Y-m-d H:i:sP'));

            if ($end) {
                if (!empty($this->timezone)) {
                    $end->setTimezone(new \DateTimeZone('Europe/Vienna'));
                }
                $event->setEnd($end->format('Y-m-d H:i:sP'));
            }

            $eventExt = [
                'className' => [],
            ];

            if ($this->crudUrls) {
                $eventExt['updateUrl'] = $urlHelper('admin/event/edit', ['id' => $event->getId()]);
                $eventExt['deleteUrl'] = $urlHelper('admin/event/delete', ['id' => $event->getId()]);
            }

            $calendar =
                (array_key_exists($event->getCalendarId(), $calendars)) ? $calendars[$event->getCalendarId()] : null;

            if ($calendar) {
                if (!empty($calendar['class'])) {
                    $eventExt['className'][] = 'event-type-'.$calendar['class'];
                }
                if (!empty($calendar['color'])) {
                    $eventExt['color'] = $calendar['color'];
                }
            }

            $eventExt['link'] = null;
            if ((int)$event->getLinkId() > 0) {
                $link = $linkTableGateway->selectByPrimary($event->getLinkId());
                if (!empty($link)) {
                    $eventExt['link'] =
                        $linkProvider->assemble($link->getType(), Json::decode($link->getValue(), Json::TYPE_ARRAY));
                }
            }

            $event = $event->toArray();

            unset($event['updated']);
            unset($event['created']);
            unset($event['linkId']);

            $events[] = array_merge($event, $eventExt);
        }

        $result = [
            'events'    => $events,
            'calendars' => $calendars,
        ];

        if($this->ical) {

            /** @var ServerUrl $serverUrlHelper */
            $serverUrlHelper = $this->getServiceManager()->get('viewHelperManager')->get('server_url');

            $vCalendar = new \Eluceo\iCal\Component\Calendar($serverUrlHelper->getHost());
            foreach($events as $event) {
                $vEvent = new \Eluceo\iCal\Component\Event();
                $start = new \DateTime($event['start']);
                // always have an end date - otherwise event could be truncated if end is on same day
                $end = $event['end'] ? new \DateTime($event['end']) : $start;
                $vEvent
                    ->setDtStart($start)
                    ->setDtEnd($end)
                    //->setNoTime($event['allDay'])
                    ->setSummary($event['title'])
                ;
                $vCalendar->addComponent($vEvent);
            }
            $result['ical'] = $vCalendar->render();
        }

        return $result;
    }

    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        $sql = new Sql($this->getServiceManager()->get('Db\Master'));
        $select = $sql->select();

        $select->from($this->getTableGateway('Calendar42\Event')->getTable());

        if (!empty($this->calendarIds)) {
            $select->where(['calendarId' => $this->calendarIds]);
        }

        if (!empty($this->eventIds)) {
            $select->where(['id' => $this->eventIds]);
        }

        if ($this->includePast === false) {
            $select->where(function(Where $where) {
                $now = new \DateTime();
                $where->greaterThanOrEqualTo('start', $now->format('Y-m-d H:i:s'));
            });
        }

        if ($this->limit !== null) {
            $select->limit($this->limit);
        }

        $select->order($this->order);

        return $select;
    }
}
