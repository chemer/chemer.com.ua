<?php

namespace Users\Entity;

class User implements UserInterface
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $username = 'Guest';

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $state;
    
    /**
     * @var date
     */
    protected $registerDate;
    
    /**
     * @var string
     */
    protected $confirmCode;
    
    /**
     * @var string
     */
    protected $sessionHash;

    /**
     * Get id.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setUserId($id)
    {
        $this->userId = (int) $id;
        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param string $state
     * @return UserInterface
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    
    /**
     * Get registerDate.
     *
     * @return date
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * Set registerDate.
     *
     * @param date $date
     * @return UserInterface
     */
    public function setRegisterDate($date)
    {
        $this->registerDate = $date;
        return $this;
    }
    
    /**
     * Get confirmCode.
     *
     * @return string
     */
    public function getConfirmCode()
    {
        return $this->confirmCode;
    }
    
    /**
     * Set confirmCode.
     *
     * @param string $code
     * @return UserInterface
     */
    public function setConfirmCode($code)
    {
        $this->confirmCode = $code;
        return $this;
    }
    
    /**
     * Get sessionHash.
     *
     * @return string
     */
    public function getSessionHash()
    {
        return $this->sessionHash;
    }
    
    /**
     * Set sessionHash.
     *
     * @param string $hash
     * @return UserInterface
     */
    public function setSessionHash($hash)
    {
        $this->sessionHash = $hash;
        return $this;
    }
}
