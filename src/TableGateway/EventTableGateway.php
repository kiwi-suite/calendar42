<?php
namespace Calendar42\TableGateway;

use Calendar42\Model\Event;
use Core42\Db\TableGateway\AbstractTableGateway;

class EventTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = 'calendar42_event';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id'         => 'integer',
        'calendarId' => 'integer',
        'title'      => 'string',
        'start'      => 'dateTime',
        'end'        => 'dateTime',
        'allDay'     => 'boolean',
        'location'   => 'string',
        'info'       => 'string',
        'linkId'     => 'integer',
        'updated'    => 'dateTime',
        'created'    => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = Event::class;
}
