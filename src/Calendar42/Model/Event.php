<?php
namespace Calendar42\Model;

use Core42\Model\AbstractModel;

/**
 * @method Event setId() setId(int $id)
 * @method int getId() getId()
 * @method Event setCalendarId() setCalendarId(int $calendarId)
 * @method int getCalendarId() getCalendarId()
 * @method Event setTitle() setTitle(string $title)
 * @method string getTitle() getTitle()
 * @method Event setStart() setStart(\DateTime $start)
 * @method \DateTime getStart() getStart()
 * @method Event setEnd() setEnd(\DateTime $end)
 * @method \DateTime getEnd() getEnd()
 * @method Event setAllDay() setAllDay(boolean $allDay)
 * @method boolean getAllDay() getAllDay()
 * @method Event setLocation() setLocation(string $location)
 * @method string getLocation() getLocation()
 * @method Event setInfo() setInfo(string $info)
 * @method string getInfo() getInfo()
 * @method Event setLinkId() setLinkId(string $linkId)
 * @method string getLinkId() getLinkId()
 * @method Event setUpdated() setUpdated(\DateTime $updated)
 * @method \DateTime getUpdated() getUpdated()
 * @method Event setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
 */
class Event extends AbstractModel
{

    /**
     * @var array
     */
    protected $properties = array(
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
    );


}
