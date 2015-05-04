<?php

namespace Feedback\Form;

use Zend\Form\Form;

class Feedback extends Form 
{
    public function __construct($name = null)
    {
        parent::__construct('feedbackForm');
        
        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Тема сообщения',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'id' => 'feedbackSubject',
                'class' => 'form-control',
                'placeholder' => 'Тема сообщения',
                'maxlength' => 250,
            ),
        ));
        
        $this->add(array(
            'name' => 'text',
            'options' => array(
                'label' => 'Текст сообщения',
                'label_attributes' => array(
                    'class' => 'sr-only',
                ),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'feedbackText',
                'class' => 'form-control',
                'placeholder' => 'Текст сообщения',
                'rows' => 2,
                'maxlength' => 5000,
            ),
        ));
        
        $this->add(array(
            'name' => 'file',
            'attributes' => array(
                'type' => 'file',
                'id' => 'feedbackFile',
                'class' => 'form-control',
                'title' => 'Размер прикрепленного файла не должен превышать 20Мb',
                'data-toggle' => 'tooltip',
            ),
        ));
    }
}
