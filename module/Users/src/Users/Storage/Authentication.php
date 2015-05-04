<?php

namespace Users\Storage;

use Users\Mapper\DbManagerInterface;
use Zend\Session\Container;
//use Zend\Session\Config\StandardConfig;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

class Authentication implements AuthenticationInterface
{
    protected $dbManager;
    
    protected $session;

    public function __construct(DbManagerInterface $dbManager)
    {
        $this->dbManager = $dbManager;
        
        $this->session = new Container('user');
    }
    
    /**
     * Is user authorized.
     * 
     * @return boolean
     */
    public function hasLogged()
    {
        try {
            $userId      = $this->session->offsetGet('userId');
            $sessionHash = $this->session->offsetGet('sessionHash');

            if (!$userId || !$sessionHash) {
                return false;
            }

            $verify = $this->dbManager->verifyLogged($userId, $sessionHash);
            
            return (bool)$verify;
            
        } catch (\Exception $ex) {
            return false;
        }
    }
    
    /**
     * Set session variables.
     * 
     * @param type $userId
     * @param type $sessionHash
     */
    public function setStorage($userId, $sessionHash)
    {
        $this->session->offsetSet('userId', $userId);
        $this->session->offsetSet('sessionHash', $sessionHash);
    }
    
    /**
     * Get session variables.
     * 
     * @return array
     */
    public function getStorage()
    {
        $sessionHash = $this->session->offsetGet('sessionHash');
        $userId      = $this->session->offsetGet('userId');
        
        return array(
            'sessionHash' => $sessionHash,
            'userId'      => $userId,
        );
    }
    
    /**
     * Clear session.
     */
    public function clearStorage()
    {
        $this->session->getManager()->getStorage()->clear('user');
    }

}
