<?php

namespace Users\Form;

use Zend\Form\Form;
use Zend\Form\Element\Button;

class Register extends Form 
{
    public function __construct($name = null)
    {
        parent::__construct('registerForm');
        
        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Ф.И.О.',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'id' => 'username',
                'class' => 'form-control',
                'placeholder' => 'Ф.И.О.',
            ),
        ));
        
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
        
        $this->add(array(
            'name' => 'confirm_password',
            'options' => array(
                'label' => 'Повторите пароль',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'password',
                'id' => 'confirm_password',
                'class' => 'form-control',
                'placeholder' => 'Повторите пароль',
            ),
        ));
        
        $submitElement = new Button('submit');
        $submitElement->setLabel('Зарегистрироваться')
                      ->setAttributes(array(
                          'type'  => 'submit',
                          'class' => 'btn btn-default',
                      ));
        
        $this->add($submitElement, array(
            'priority' => -100,
        ));
    }
}
