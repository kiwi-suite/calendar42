<?php
namespace Calendar42\TableGateway;

use Calendar42\Model\Calendar;
use Core42\Db\TableGateway\AbstractTableGateway;

class CalendarTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = 'calendar42_calendar';

    /**
     * @var array
     */
    protected $primaryKey = ['id'];

    /**
     * @var array
     */
    protected $databaseTypeMap = [
        'id'       => 'integer',
        'title'    => 'string',
        'settings' => 'string',
        'updated'  => 'dateTime',
        'created'  => 'dateTime',
    ];

    /**
     * @var string
     */
    protected $modelPrototype = Calendar::class;
}
