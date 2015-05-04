<?php

namespace Feedback\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\FileInput;

class FeedbackFilter implements InputFilterAwareInterface
{
    protected $inputFilter;
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {   
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'subject',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 250,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'text',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 5000,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'file',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'filesize',
                        'options' => array(
                            'max' => '20MB',
                        ),
                    ),
                ),
            ));
            
            /*$fileInput = new FileInput('file');
            $fileInput->setRequired(false);
            $fileInput->getValidatorChain()
                      ->attachByName('filesize', array('max' => '20MB'));
            
            $inputFilter->add($fileInput);*/
                      
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
