<?php

namespace TescoRedemptionPortal\Form;

use Zend\InputFilter\InputFilter;

class VerifyFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'token',
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => [
                        'message' => 'Please enter your Token'
                    ]
                ]
            ]
        ]);
    }
}