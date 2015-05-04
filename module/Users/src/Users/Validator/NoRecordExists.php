<?php

namespace Users\Validator;

use Zend\Validator\AbstractValidator;
use Users\Service\UserServiceInterface;

class NoRecordExists extends AbstractValidator
{
    const ERROR_RECORD_FOUND = 'recordFound';
    
    protected $messageTemplates = array(
        self::ERROR_RECORD_FOUND => 'This email already exists in the system.',
    );
    
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        
        parent::__construct(null);
    }
    
    public function isValid($value)
    {
        $valid = true;
        
        $result = $this->userService->existsEmailAddress($value);
        
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
