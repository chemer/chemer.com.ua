<?php

namespace Users\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RegisterFilter implements InputFilterAwareInterface
{
    protected $inputFilter;
    protected $noRecordExists;

    public function __construct($noRecordExists)
    {
        $this->noRecordExists = $noRecordExists;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {   
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'username',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[ЇїЁёІіЄєa-zа-я-\.\' ]+$/iu',
                            'messages' => array(
                                'regexNotMatch' => "Invalid characters."
                            )
                        ) 
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 100,
                        ),
                    ),
                ),
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'EmailAddress',
                    ),
                    $this->noRecordExists,
                ),
                'filters'  => array(
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'password',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 4,
                            'max'      => 60,
                        ),
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'confirm_password',
                'required' => true,
                'validators' => array(
                    array(
                        'name'    => 'Identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                'notSame' => 'Confirm password incorrect.'
                            ),
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
