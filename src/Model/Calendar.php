<?php
namespace Calendar42\Model;

use Core42\Model\AbstractModel;
use Core42\Stdlib\DateTime;

/**
 * @method Calendar setId(int $id)
 * @method int getId()
 * @method Calendar setTitle(string $title)
 * @method string getTitle()
 * @method Calendar setSettings(string $settings)
 * @method string getSettings()
 * @method Calendar setUpdated(DateTime $updated)
 * @method DateTime getUpdated()
 * @method Calendar setCreated(DateTime $created)
 * @method DateTime getCreated()
 */
class Calendar extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
        'id',
        'title',
        'settings',
        'updated',
        'created',
    ];
}
