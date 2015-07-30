<?php
/**
 * calendar42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2015 raum42 OG (http://www.raum42.at)
 *
 */

namespace Calendar42\Form\Calendar;

use Zend\Form\Element\Color;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class CreateForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $this->add(new Csrf('csrf'));

        $title = new Text('title');
        $title->setLabel('label.title');
        $this->add($title);

        $handle = new Text('handle');
        $handle->setLabel('label.handle');
        $this->add($handle);

        $color = new Color('color');
        $color->setLabel('label.color');
        $this->add($color);
    }

    public function setFieldValues($fieldValues) {

        foreach($fieldValues as $field => $value){
            $element = $this->get($field);
            if($element) {
                $element->setValue($value);
            }
        }
    }
}
