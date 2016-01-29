<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Command\Calendar;

use Calendar42\Model\Calendar;
use Core42\Command\AbstractCommand;

class EditCommand extends AbstractCommand
{
    /**
     * @var int
     */
    private $calendarId;

    /**
     * @var Calendar
     */
    private $calendarModel;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $settings
     */
    private $settings;

    /**
     * @param int $calendarId
     * @return $this
     */
    public function setCalendarId($calendarId)
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param $settings
     * @return $this
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param array $values
     */
    public function hydrate(array $values)
    {
        $this->setTitle(array_key_exists('title', $values) ? $values['title'] : null);
        $this->setSettings(json_encode([
            'color'  => array_key_exists('color', $values) ? $values['color'] : null,
            'handle' => array_key_exists('handle', $values) ? $values['handle'] : null,
        ]));
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (!empty($this->calendarId)) {
            $this->calendarModel =
                $this->getTableGateway('Calendar42\Calendar')->selectByPrimary((int)$this->calendarId);
        }

        if (!($this->calendarModel instanceof Calendar)) {
            $this->addError("calendar", "invalid calendar");
        }

        if (empty($this->title)) {
            $this->addError("title", "title can't be empty");
        }
    }

    /**
     * @return Calendar
     */
    protected function execute()
    {
        $dateTime = new \DateTime();

        $this->calendarModel
            ->setTitle($this->title)
            ->setSettings($this->settings)
            ->setUpdated($dateTime);

        $this->getTableGateway('Calendar42\Calendar')->update($this->calendarModel);

        return $this->calendarModel;
    }
}
