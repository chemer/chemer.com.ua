<?php

namespace Users\Form;

use Zend\Form\Form;
use Zend\Form\Element\Button;

class Login extends Form 
{
    public function __construct($name = null)
    {
        parent::__construct('loginForm');
        
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'id' => 'email',
                'class' => 'form-control',
                'placeholder' => 'E-mail',
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Пароль',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'password',
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Пароль',
            ),
        ));
        
        $submitElement = new Button('submit');
        $submitElement->setLabel('Войти')
                      ->setAttributes(array(
                          'type'  => 'submit',
                          'class' => 'btn btn-default',
                      ));
        
        $this->add($submitElement, array(
            'priority' => -100,
        ));
    }
}
