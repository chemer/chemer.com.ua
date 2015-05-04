<?php

namespace Users\Entity;

interface UserInterface
{
    /**
     * Get id.
     *
     * @return int
     */
    public function getUserId();

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setUserId($id);

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username);

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email);

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName);

    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword();

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password);

    /**
     * Get state.
     *
     * @return string
     */
    public function getState();

    /**
     * Set state.
     *
     * @param string $state
     * @return UserInterface
     */
    public function setState($state);
    
    /**
     * Get registerDate.
     *
     * @return date
     */
    public function getRegisterDate();
    
    /**
     * Set registerDate.
     *
     * @param dare $date
     * @return UserInterface
     */
    public function setRegisterDate($date);

    /**
     * Get confirmCode.
     *
     * @return string
     */
    public function getConfirmCode();
    
    /**
     * Set confirmCode.
     *
     * @param string $code
     * @return UserInterface
     */
    public function setConfirmCode($code);
    
    /**
     * Get sessionHash.
     *
     * @return string
     */
    public function getSessionHash();
    
    /**
     * Set sessionHash.
     *
     * @param string $hash
     * @return UserInterface
     */
    public function setSessionHash($hash);
}
