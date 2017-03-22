<?php

namespace TescoRedemptionPortal\Form;

use Zend\Form\Form;

class CsrfForm extends Form
{
    public function __construct($name)
    {
        parent::__construct($name);

        $this->add([
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600
                ]
            ]
        ]);
    }
}