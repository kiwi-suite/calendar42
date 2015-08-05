<?php
namespace Calendar42\Selector;

use Calendar42\Model\Event;
use Cocur\Slugify\Slugify;
use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\DatabaseHydrator;
use Core42\Selector\AbstractDatabaseSelector;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Json\Json;
use Zend\View\Helper\Url;

class EventCalendarSelector extends AbstractDatabaseSelector
{
    /**
     * @var int
     */
    protected $calendarIds;

    /**
     * @var bool
     */
    protected $crudUrls;

    /**
     * @var bool
     */
    protected $ical;

    /**
     * @param $calendarIds
     * @return $this
     */
    public function setCalendarIds($calendarIds)
    {
        if (!is_array($calendarIds)) {
            $calendarIds = [$calendarIds];
        }
        $this->calendarIds = $calendarIds;

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

            $event->setStart($event->getStart()->format('Y-m-d H:i:sP'));

            if ($event->getEnd()) {
                $event->setEnd($event->getEnd()->format('Y-m-d H:i:sP'));
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

        return [
            'events'    => $events,
            'calendars' => $calendars,
        ];
    }

    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        $sql = new Sql($this->getServiceManager()->get('Db\Master'));
        $select = $sql->select();

        $select->from($this->getTableGateway('Calendar42\Event')->getTable());

        if ($this->calendarIds) {
            $select->where(['calendarId' => $this->calendarIds]);
        }

        $select->order('start ASC');

        return $select;
    }
}
