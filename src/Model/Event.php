<?php
namespace Calendar42\Model;

use Core42\Model\AbstractModel;
use Core42\Stdlib\DateTime;

/**
 * @method Event setId(int $id)
 * @method int getId()
 * @method Event setCalendarId(int $calendarId)
 * @method int getCalendarId()
 * @method Event setTitle(string $title)
 * @method string getTitle()
 * @method Event setStart(DateTime $start)
 * @method DateTime getStart()
 * @method Event setEnd(DateTime $end)
 * @method DateTime getEnd()
 * @method Event setAllDay(boolean $allDay)
 * @method boolean getAllDay()
 * @method Event setLocation(string $location)
 * @method string getLocation()
 * @method Event setInfo(string $info)
 * @method string getInfo()
 * @method Event setLinkId(int $linkId)
 * @method int getLinkId()
 * @method Event setUpdated(DateTime $updated)
 * @method DateTime getUpdated()
 * @method Event setCreated(DateTime $created)
 * @method DateTime getCreated()
 */
class Event extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
        'id',
        'calendarId',
        'title',
        'start',
        'end',
        'allDay',
        'location',
        'info',
        'linkId',
        'updated',
        'created',
    ];
}
