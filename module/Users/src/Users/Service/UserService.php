<?php

namespace Users\Service;

use Zend\Crypt\Password\Bcrypt;
use Users\Mapper\DbManagerInterface;
use Users\Entity\UserInterface;
use Users\Storage\AuthenticationInterface;

class UserService implements UserServiceInterface
{
    protected $dbManager;
    
    protected $authStorage;

    public function __construct(DbManagerInterface $dbManager, AuthenticationInterface $authStorage)
    {
        $this->dbManager = $dbManager;
        $this->authStorage = $authStorage;
    }
    
    /**
     * Get current user.
     * 
     * @return UserInterface
     */
    public function getCurrentUser()
    {
        $storage = $this->authStorage->getStorage();
        $userId  = $storage['userId'];
        $user    = $this->getUserById($userId);
        
        return $user;
    }

    /**
     * Do authentication.
     * 
     * @param type $loginForm
     * @return \Users\Service\UserService
     * @throws \Exception
     */
    public function authenticate($email, $password)
    {
        $extra       = array('state' => 'active');
        $sessionHash = $this->generateHashCode(5);
        
        if (!$this->verifyPassword($email, $password, $extra)) {
            throw new \Exception('Authentication failed. Please try again.');
        }
        
        $this->dbManager->updateSessionHashByEmail($sessionHash, $email);
        
        $user = $this->getUserByEmail($email);
        
        $this->authStorage->setStorage($user->getUserId(), $user->getSessionHash());
        
        return $this;
    }
    
    /**
     * Get user entity by email.
     * 
     * @param type $email
     * @return UserInterface
     * @throws \Exception
     */
    public function getUserByEmail($email)
    {
        $user = $this->dbManager->getUserByEmail($email);
        
        if (!$user instanceof UserInterface) {
            throw new \Exception('Setup instance of UserInterface is failed.');
        }
        
        return $user;
    }
    
    /**
     * Get user entity by id.
     * 
     * @param type $id
     * @return UserInterface
     * @throws \Exception
     */
    public function getUserById($id)
    {
        $user = $this->dbManager->getUserById($id);
        
        if (!$user instanceof UserInterface) {
            throw new \Exception('Setup instance of UserInterface is failed.');
        }
        
        return $user;
    }
    
    /**
     * Is user authorized.
     * 
     * @return boolean
     */
    public function hasLogged()
    {
        return $this->authStorage->hasLogged();
    }

    /**
     * Anyone of an email address, except whose state is unconfirmed - will consider as existing.
     *
     * @param type $email
     * @return boolean
     */
    public function existsEmailAddress($email)
    {
        return $this->dbManager->existsEmailAddress($email);
    }
    
    /**
     * Add new user in the Db.
     * 
     * @param type $formData
     * @return int $userId
     * @throws \Exception
     */
    public function createNewUser($formData)
    {
        $queryData = array(
            'username' => $formData['username'],
            'email' => $formData['email'],
            'password' => $this->encryptPassword($formData['password']),
            'confirm_code' => $this->generateHashCode(),
            'state' => 'unconfirmed',
        );
        
        $userId = $this->dbManager->createNewUser($queryData);
        
        if (!$userId) {
            throw new \Exception('Occurred a fail of setting UserId.');
        }
        
        return $userId;
    }
    
    /**
     * 
     * @param type $email
     * @param type $password
     * @param type $extra
     * @return type
     */
    public function verifyPassword($email, $password, $extra)
    {
        $passwordSecure = $this->dbManager->getPasswordByEmail($email, $extra);
        
        $bcrypt = new Bcrypt();
        $verify = $bcrypt->verify($password, $passwordSecure);
        
        return (bool) $verify;
    }
    
    /**
     * Register confirmation and then auto login.
     * 
     * @param int $id
     * @param string $code
     * @return \Users\Service\UserService
     * @throws \Exception
     */
    public function registerConfirm($id, $code)
    {
        if (!$code) {
            throw new \Exception('Confirm code can not be empty.');
        }
        
        $queryData = array(
            'user_id' => $id,
            'confirm_code' => $code,
            'session_hash' => $this->generateHashCode(),
            'state.post' => 'active',
            'state.pre' => 'unconfirmed',
         );
        
        if (!$this->dbManager->registerConfirm($queryData)) {
            throw new \Exception('Confirm code is incorrect or has been activated.');
        }
        
        $this->authStorage->setStorage($queryData['user_id'], $queryData['session_hash']);
        
        return $this;
    }

    protected function generateHashCode($cost = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $cost; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return md5($randomString);
    }
    
    protected function encryptPassword($value)
    {
        $bcrypt = new Bcrypt();
        
        $password = (string) $value;
        $bcrypt->setCost(8);
        $securePassword = $bcrypt->create($password);
        
        return $securePassword;
    }

}
