<?php
 
namespace Users\Mapper;

interface DbManagerInterface 
{
    /**
     * Set hash to verify that only one person may be authorized.
     * 
     * @param random string $hash
     * @param string $email
     * @return DbManager
     */
    public function updateSessionHashByEmail($hash, $email);
    
    /**
     * Compare $sessionHash Session vs Db.
     * 
     * @param type $userId
     * @param type $sessionHash
     * @return boolean
     */
    public function verifyLogged($userId, $sessionHash);
    
    /**
     * Anyone of an email address, except whose state is unconfirmed - will consider as existing.
     *
     * @param type $email
     * @return boolean
     */
    public function existsEmailAddress($email);
    
    /**
     * Add new user.
     * 
     * If user already exists (unconfirmed state) - 
     * just do update columns username, password and confirm_code.
     * 
     * @param type $userData
     * @return int $userId
     */
    public function createNewUser($userData);
    
    /**
     * Get user id.
     * 
     * @param type $email
     * @return type
     */
    public function getUserIdByEmail($email);
    
    /**
     * Get user entity.
     * 
     * @param type $id
     * @return User
     */
    public function getUserById($id);
    
    /**
     * Get user by email.
     * 
     * @param string $email
     * @return UserInterface $user
     * @throws \Exception
     */
    public function getUserByEmail($email);
    
    /**
     * Get password hash.
     * 
     * @param string $email
     * @param array $extra
     * @return string
     */
    public function getPasswordByEmail($email, Array $extra = array());
    
    /**
     * Register confirmation.
     * 
     * @param array $confirmData
     * @return boolean
     */
    public function registerConfirm($confirmData);
    
}
