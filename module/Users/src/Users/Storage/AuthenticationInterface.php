<?php

namespace Users\Storage;

interface AuthenticationInterface 
{
    /**
     * Is user authorized.
     * 
     * @return boolean
     */
    public function hasLogged();
    
    /**
     * Set session variables.
     * 
     * @param type $userId
     * @param type $sessionHash
     */
    public function setStorage($userId, $sessionHash);
    
    /**
     * Get session variables.
     * 
     * @return array
     */
    public function getStorage();
    
    /**
     * Clear session.
     */
    public function clearStorage();
    
}
