<?php

namespace TescoRedemptionPortal\Form;

class VerifyForm extends CsrfForm
{
    public function __construct()
    {
        // We want to ignore the name passed
        parent::__construct('verify');

        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'token',
            'attributes' => [
                'id' => 'token',
                'type' => 'text',
                'placeholder' => 'Token',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => ''
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Submit',
                'class' => 'button arrow'
            ]
        ]);
    }
}