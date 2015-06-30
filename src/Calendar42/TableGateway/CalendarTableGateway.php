<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\TableGateway;

use Core42\Db\TableGateway\AbstractTableGateway;

class CalendarTableGateway extends AbstractTableGateway
{
    /**
     * @var string
     */
    protected $table = 'calendar42_calendars';

    /**
     * @var array
     */
    protected $databaseTypeMap = [];

    /**
     * @var string
     */
    protected $modelPrototype = 'Calendar42\\Model\\Calendar';
}
