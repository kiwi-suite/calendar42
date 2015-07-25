<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Controller;

use Admin42\Mvc\Controller\AbstractAdminController;
use Zend\Http\Response;

class CalendarController extends AbstractAdminController
{
    /**
     *
     */
    public function calendarAction()
    {
        // TODO: return sources urls of calendar by id if set - otherwise all

        return [
            //'createEditForm' => $createEditForm,
        ];
    }

    /**
     *
     */
    public function listAction()
    {
        // TODO: list of calendars
    }

    /**
     *
     */
    public function detailAction()
    {
        // TODO: edit form for calendar
    }

    /**
     *
     */
    public function deleteAction()
    {
        // TODO: delete action for calendar
    }
}
