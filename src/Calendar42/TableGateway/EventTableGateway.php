<?php
namespace Calendar42\TableGateway;

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
    protected $databaseTypeMap = array();

    /**
     * @var string
     */
    protected $modelPrototype = 'Calendar42\\Model\\Event';


}
