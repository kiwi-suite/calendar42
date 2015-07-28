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

class CreateCommand extends AbstractCommand
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $settings
     */
    private $settings;

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
        $this->setSettings(array_key_exists('settings', $values) ? $values['settings'] : null);
    }


    /**
     * validate values
     */
    protected function preExecute()
    {
        if (empty($this->title)) {
            $this->addError("title", "title can't be empty");
        }

        $this->settings = (empty($this->settings)) ? null : $this->settings;
    }

    /**
     * @return Calendar
     */
    protected function execute()
    {
        $dateTime = new \DateTime();
        $calendar = new Calendar();
        $calendar->setTitle($this->title)
            ->setSettings($this->settings)
            ->setCreated($dateTime)
            ->setUpdated($dateTime);

        $this->getTableGateway('Calendar42\Calendar')->insert($calendar);

        return $calendar;
    }

}
