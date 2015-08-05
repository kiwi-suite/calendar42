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
use Calendar42\Command\Calendar\CreateCommand;
use Calendar42\Command\Calendar\DeleteCommand;
use Calendar42\Command\Calendar\EditCommand;
use Calendar42\Form\Calendar\CreateForm;
use Calendar42\Form\Calendar\EditForm;
use Calendar42\Selector\EventCalendarSelector;
use Core42\View\Model\JsonModel;
use Zend\Http\Headers;
use Zend\Http\Response;

class CalendarController extends AbstractAdminController
{
    /**
     *
     */
    public function calendarAction()
    {
        $calendarId = $this->params()->fromRoute('id');
        $calendar = null;

        if ($calendarId) {
            $result = $this->getTableGateway('Calendar42\Calendar')->selectByPrimary($calendarId);
            $calendar = $result;
        }

        $events = $this->eventsAction();

        return [
            'events'   => $events,
            'calendar' => $calendar,
        ];
    }

    /**
     *
     */
    public function listAction()
    {
        $calendars = [];

        $result = $this->getTableGateway('Calendar42\Calendar')->select();

        foreach ($result as $calendar) {

            $calendarExt = [
                'updateUrl'      => $this->url()->fromRoute('admin/calendar/edit', ['id' => $calendar->getId()]),
                'deleteUrl'      => $this->url()->fromRoute('admin/calendar/delete', ['id' => $calendar->getId()]),
                'eventSourceUrl' => $this->url()->fromRoute('admin/calendar/events', ['id' => $calendar->getId()]),
                'createEventUrl' => $this->url()->fromRoute('admin/event/add').'?calendarId='.$calendar->getId(),
            ];

            $calendars[] = (object)array_merge($calendar->toArray(), $calendarExt);
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new JsonModel($calendars);
        }

        return ['calendars' => $calendars];
    }

    /**
     *
     */
    public function eventsAction()
    {
        /** @var EventCalendarSelector $selector */
        $selector = $this->getSelector('Calendar42\EventCalendar');
        $events = $selector->setCalendarIds($this->params()->fromRoute('id'))
            ->setCrudUrls(true)
            ->getResult();

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new JsonModel($events);
        }

        return $events;
    }

    /**
     *
     */
    public function icalAction()
    {
        /** @var EventCalendarSelector $selector */
        $selector = $this->getSelector('Calendar42\EventCalendar');
        $events = $selector->setCalendarIds($this->params()->fromRoute('id'))
            ->setIcal(true)
            ->getResult();

        //$events = json_encode($events);
        var_dump($events);
        die();

        $headers = new Headers;
        //$headers->addHeaders(
        //    [
        //        //'Content-Disposition' => 'attachment; filename="' . $cmd->getFileName() . '"',
        //        //'Content-Type'        => 'application/octet-stream',
        //        //'Content-Length'      => strlen($cmd->getOutput()),
        //        'Expires'             => '@0', // @0, because zf2 parses date as string to \DateTime() object
        //        'Cache-Control'       => 'must-revalidate',
        //        'Pragma'              => 'public'
        //    ]
        //);

        $response = new Response;
        $response->setContent($events);
        $response->setHeaders($headers);

        return $response;
    }

    /**
     *
     */
    public function detailAction()
    {
        if ($this->params()->fromRoute("isEditMode")) {
            return $this->editAction();
        }

        return $this->createAction();
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    public function createAction()
    {
        /** @var CreateForm $form */
        $form = $this->getForm('Calendar42\Calendar\Create');

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        if ($prg !== false) {
            /** @var CreateCommand $cmd */
            $cmd = $this->getCommand('Calendar42\Calendar\Create');

            $formCommand = $this->getFormCommand();
            $calendar = $formCommand->setForm($form)
                ->setCommand($cmd)
                ->setData($prg)
                ->run();

            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage(
                    [
                        'title'   => 'toaster.calendar.create.title.success',
                        'message' => 'toaster.calendar.create.message.success',
                    ]
                );
                return $this->redirect()->toRoute('admin/calendar/calendar', ['id' => $calendar->getId()]);
            } else {
                $this->flashMessenger()->addErrorMessage(
                    [
                        'title'   => 'toaster.calendar.create.title.error',
                        'message' => 'toaster.calendar.create.message.error',
                    ]
                );
            }
        }

        return [
            'form' => $form,
        ];
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    public function editAction()
    {
        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        $calendar = $this->getTableGateway('Calendar42\Calendar')->selectByPrimary(
            (int)$this->params()->fromRoute('id')
        );

        if (empty($calendar)) {
            return $this->redirect()->toRoute('admin/calendar');
        }

        /** @var EditForm $form */
        $form = $this->getForm('Calendar42\Calendar\Edit');
        $form->setData($calendar->toArray())->setFieldValues(json_decode($calendar->getSettings()));

        if ($prg !== false) {
            /** @var EditCommand $cmd */
            $cmd = $this->getCommand('Calendar42\Calendar\Edit');
            $cmd->setCalendarId((int)$this->params()->fromRoute('id'));

            $formCommand = $this->getFormCommand();
            $formCommand->setForm($form)
                ->setCommand($cmd)
                ->setData($prg)
                ->run();

            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage(
                    [
                        'title'   => 'toaster.calendar.edit.title.success',
                        'message' => 'toaster.calendar.edit.message.success',
                    ]
                );
                return $this->redirect()->toRoute('admin/calendar/list');
            } else {
                $this->flashMessenger()->addErrorMessage(
                    [
                        'title'   => 'toaster.calendar.edit.title.error',
                        'message' => 'toaster.calendar.edit.message.error',
                    ]
                );
            }
        }

        return [
            'form'     => $form,
            'calendar' => $calendar,
        ];
    }

    /**
     *
     */
    public function deleteAction()
    {
        /** @var DeleteCommand $deleteCmd */
        $deleteCmd = $this->getCommand('Calendar42\Calendar\Delete');

        if ($this->getRequest()->isDelete()) {

            $deleteParams = [];
            parse_str($this->getRequest()->getContent(), $deleteParams);

            $deleteCmd->setCalendarId((int)$deleteParams['id'])
                ->run();

            return new JsonModel(
                [
                    'success' => true,
                ]
            );
        } elseif ($this->getRequest()->isPost()) {

            $deleteCmd->setCalendarId((int)$this->params()->fromPost('id'))
                ->run();

            $this->flashMessenger()->addSuccessMessage(
                [
                    'title'   => 'toaster.event.delete.title.success',
                    'message' => 'toaster.event.delete.message.success',
                ]
            );
        }

        if ($deleteCmd->getErrors()) {
            return new JsonModel(
                [
                    'success' => false,
                    'errors'  => $deleteCmd->getErrors(),
                ]
            );
        } else {
            return new JsonModel(
                [
                    'redirect' => $this->url()->fromRoute('admin/calendar/list')
                ]
            );
        }
    }
}
