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
use Calendar42\Command\Event\CreateCommand;
use Calendar42\Command\Event\DeleteCommand;
use Calendar42\Command\Event\EditCommand;
use Calendar42\Form\Event\CreateForm;
use Calendar42\Form\Event\EditForm;
use Calendar42\TableGateway\EventTableGateway;
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

        return new JsonModel(
            [
                [
                    'title' => 'Test',
                ],
            ]
        );
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
        $form = $this->getForm(CreateForm::class);

        $form->setCalendarId($this->params()->fromQuery('calendarId'));

        $prg = $this->prg();
        if ($prg instanceof Response) {
            return $prg;
        }

        if ($prg !== false) {
            /** @var CreateCommand $cmd */
            $cmd = $this->getCommand(CreateCommand::class);

            $formCommand = $this->getFormCommand();
            $event = $formCommand->setForm($form)
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
                return $this->redirect()->toRoute('admin/calendar/calendar', ['id' => $event->getCalendarId()]);
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

        $event = $this->getTableGateway(EventTableGateway::class)->selectByPrimary(
            (int)$this->params()->fromRoute('id')
        );

        if (empty($event)) {
            return $this->redirect()->toRoute('admin/calendar');
        }

        /** @var EditForm $form */
        $form = $this->getForm(EditForm::class);
        $form->setData($event->toArray());

        if ($prg !== false) {
            /** @var EditCommand $cmd */
            $cmd = $this->getCommand(EditCommand::class);
            $cmd->setEventId((int)$this->params()->fromRoute('id'));

            $formCommand = $this->getFormCommand();
            $updatedEvent = $formCommand->setForm($form)
                ->setCommand($cmd)
                ->setData($prg)
                ->run();

            if (!$formCommand->hasErrors()) {
                $this->flashMessenger()->addSuccessMessage(
                    [
                        'title'   => 'toaster.event.edit.title.success',
                        'message' => 'toaster.event.edit.message.success',
                    ]
                );

                return $this->redirect()->toRoute('admin/calendar/calendar', ['id' => $updatedEvent->getCalendarId()]);
            } else {
                $this->flashMessenger()->addErrorMessage(
                    [
                        'title'   => 'toaster.event.edit.title.error',
                        'message' => 'toaster.event.edit.message.error',
                    ]
                );
            }
        }

        return [
            'form'  => $form,
            'event' => $event,
        ];
    }

    /**
     *
     */
    public function deleteAction()
    {
        /** @var DeleteCommand $deleteCmd */
        $deleteCmd = $this->getCommand(DeleteCommand::class);

        if ($this->getRequest()->isDelete()) {

            $deleteParams = [];
            parse_str($this->getRequest()->getContent(), $deleteParams);

            $deleteCmd->setEventId((int)$deleteParams['id'])
                ->run();

            return new JsonModel(
                [
                    'success' => true,
                ]
            );
        } elseif ($this->getRequest()->isPost()) {

            $deleteCmd->setEventId((int)$this->params()->fromPost('id'))
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
                    'redirect' => $this->url()->fromRoute('admin/calendar')
                ]
            );
        }
    }
}
