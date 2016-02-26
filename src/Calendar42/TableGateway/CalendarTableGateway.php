<?php
namespace Calendar42\TableGateway;

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
    protected $databaseTypeMap = [];

    /**
     * @var string
     */
    protected $modelPrototype = 'Calendar42\\Model\\Calendar';
}
