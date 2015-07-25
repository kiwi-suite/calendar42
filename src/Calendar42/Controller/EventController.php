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
use Core42\View\Model\JsonModel;
use Zend\Http\Response;

class EventController extends AbstractAdminController
{
    /**
     *
     */
    public function indexAction()
    {
        // TODO: list of events

        return new JsonModel([
            [
                'title' => 'Test',
            ],
        ]);
    }

    /**
     *
     */
    public function detailAction()
    {
        // TODO: edit form for event

        return new JsonModel([
            'title' => 'Test',
        ]);
    }

    /**
     *
     */
    public function deleteAction()
    {
        // TODO: delete action for event

        return new JsonModel([
            'success' => true,
        ]);
    }
}
