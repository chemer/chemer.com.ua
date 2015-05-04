<?php

namespace Users\Service;

interface UserServiceInterface
{
    /**
     * Get current user.
     * 
     * @return UserInterface
     */
    public function getCurrentUser();
    
    /**
     * Do authentication.
     * 
     * Verify password, update session hash (because  only one person may be authorized).
     * And set up authentication storage.
     * 
     * @param Array $loginForm
     * @return \Users\Service\UserService
     * @throws \Exception
     */
    public function authenticate($email, $password);
    
    /**
     * Get user entity by email.
     * 
     * @param type $email
     * @return \Users\Entity\UserInterface
     * @throws \Exception
     */
    public function getUserByEmail($email);
    
    /**
     * Get user entity by id.
     * 
     * @param type $id
     * @return \Users\Entity\UserInterface
     * @throws \Exception
     */
    public function getUserById($id);
    
    /**
     * Is user authorized.
     * 
     * @return boolean
     */
    public function hasLogged();
    
    /**
     * Anyone of an email address, except whose state is unconfirmed - will consider as existing.
     *
     * @param type $email
     * @return boolean
     */
    public function existsEmailAddress($email);
    
    /**
     * Add new user in the Db.
     * 
     * @param type $formData
     * @return int $userId
     * @throws \Exception
     */
    public function createNewUser($formData);
    
    /**
     * Register confirmation.
     * 
     * @param type $id
     * @param type $code
     * @return type
     * @throws \Exception
     */
    public function registerConfirm($id, $code);
}
