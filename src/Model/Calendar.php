<?php
namespace Calendar42\Model;

use Core42\Model\AbstractModel;

/**
 * @method Calendar setId() setId(int $id)
 * @method int getId() getId()
 * @method Calendar setTitle() setTitle(string $title)
 * @method string getTitle() getTitle()
 * @method Calendar setSettings() setSettings(string $settings)
 * @method string getSettings() getSettings()
 * @method Calendar setUpdated() setUpdated(\DateTime $updated)
 * @method \DateTime getUpdated() getUpdated()
 * @method Calendar setCreated() setCreated(\DateTime $created)
 * @method \DateTime getCreated() getCreated()
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
