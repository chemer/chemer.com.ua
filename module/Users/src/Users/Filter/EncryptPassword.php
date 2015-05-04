<?php

namespace Users\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Crypt\Password\Bcrypt;

class EncryptPassword extends AbstractFilter
{
    public function filter($value)
    {
        $bcrypt = new Bcrypt();
        
        $password = (string) $value;
        $bcrypt->setCost(8);
        $securePassword = $bcrypt->create($password);
        
        return $securePassword;
    }
}
