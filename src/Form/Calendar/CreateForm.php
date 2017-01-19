<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Form\Calendar;

use Admin42\FormElements\Form;
use Zend\Form\Exception\InvalidElementException;

class CreateForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add(
            [
                'name' => 'csrf',
                'type' => 'csrf',
            ]
        );

        $this->add(
            [
                'name'  => 'title',
                'type'  => 'text',
                'label' => 'label.title',
            ]
        );

        $this->add(
            [
                'name'  => 'handle',
                'type'  => 'text',
                'label' => 'label.handle',
            ]
        );

        $this->add(
            [
                'name'  => 'color',
                'type'  => 'text',
                'label' => 'label.color',
            ]
        );
    }

    /**
     * @param $fieldValues
     */
    public function setFieldValues($fieldValues)
    {
        foreach ($fieldValues as $field => $value) {
            try {
                $element = $this->get($field);

                if ($element) {
                    $element->setValue($value);
                }
            } catch(InvalidElementException $e) {
                //
            }
        }
    }
}
