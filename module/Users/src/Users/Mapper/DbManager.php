<?php

namespace Users\Mapper;

use Users\Entity\User;
use Users\Entity\UserInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator;

class DbManager implements DbManagerInterface
{   
    protected $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->sql = new Sql\Sql($dbAdapter);
    }
    
    /**
     * Set hash to verify that only one person may be authorized.
     * 
     * @param random string $hash
     * @param string $email
     * @return DbManager
     */
    public function updateSessionHashByEmail($hash, $email)
    {
        $update = $this->sql->update();
        $update->table('user');
        $update->set(array(
            'session_hash' => $hash,
        ));
        $where = new Sql\Where();
        $where->equalTo('email', $email);
        $update->where($where);

        $statment = $this->sql->prepareStatementForSqlObject($update);
        $resultSet = new ResultSet();
        $resultSet->initialize($statment->execute());
        
        return $this;
    }
    
    /**
     * Compare $sessionHash Session vs Db.
     * 
     * @param type $userId
     * @param type $sessionHash
     * @return boolean
     */
    public function verifyLogged($userId, $sessionHash)
    {
        $select = $this->sql->select();
        $select->from('user');
        $where = new Sql\Where();
        $where->equalTo('user_id', $userId)
              ->and
              ->equalTo('session_hash', $sessionHash);
        $select->where($where);
        $select->columns(array(
            'count' => new Sql\Expression('COUNT(*)'))
        );
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->current();
            return (bool)reset($resultArray);
        } else {
            return false;
        }
    }

    /**
     * Anyone of an email address, except whose state is unconfirmed - will consider as existing.
     *
     * @param type $email
     * @return boolean
     */
    public function existsEmailAddress($email)
    {   
        $select = $this->sql->select();
        $select->from('user');
        $where = new Sql\Where();
        $where->equalTo('email', $email)
              ->and
              ->notEqualTo('state', 'unconfirmed');
        $select->where($where);
        $select->columns(array(
            'count' => new Sql\Expression('COUNT(*)'))
        );
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->current();
            return (bool)reset($resultArray);
        } else {
            return true;
        }
    }
    
    /**
     * Add new user.
     * 
     * If user already exists (unconfirmed state) - 
     * just do update columns username, password and confirm_code.
     * 
     * @param type $userData
     * @return int $userId
     */
    public function createNewUser($userData)
    {
        $userId = $this->getUserIdByEmail($userData['email']);
        
        if (!is_null($userId)) {
            // todo: update columns username, password and confirm_code
            $update = $this->sql->update();
            $update->table('user');
            $update->set(array(
                'username' => $userData['username'],
                'password' => $userData['password'],
                'confirm_code' => $userData['confirm_code'],
            ));
            $where = new Sql\Where();
            $where->equalTo('user_id', $userId)
                  ->and
                  ->equalTo('state', $userData['state']);
            $update->where($where);

            $statment = $this->sql->prepareStatementForSqlObject($update);
            $resultSet = new ResultSet();
            $resultSet->initialize($statment->execute());
        } else {
            // todo: add new record
            $insert = $this->sql->insert();
            $insert->into('user');
            $insert->values($userData);

            $statment = $this->sql->prepareStatementForSqlObject($insert);
            $resultSet = new ResultSet();
            $resultSet->initialize($statment->execute());
            $userId = $resultSet->getDataSource()->getGeneratedValue();
        }
        
        return $userId;
    }
    
    /**
     * Get user id.
     * 
     * @param type $email
     * @return type
     */
    public function getUserIdByEmail($email)
    {
        $select = $this->sql->select();
        $select->from('user');
        $where = new Sql\Where();
        $where->equalTo('email', $email);
        $select->where($where);
        $select->columns(array(
            'user_id' => new Sql\Expression('user_id')
        ));
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->toArray();
            $resultArray = reset($resultArray);
        }
        
        $userId = isset($resultArray['user_id']) ? $resultArray['user_id'] : null;
       
        return $userId;
    }
    
    /**
     * Get user entity.
     * 
     * @param type $id
     * @return User
     */
    public function getUserById($id)
    {
        $user = new User();
        
        if ((int)$id < 1) {
            return $user;
        }
        
        $select = $this->sql->select();
        $select->from('user');
        $select->where(array('user_id' => $id));
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->toArray();
            
            $hydrator = new Hydrator\ClassMethods();
            $user = $hydrator->hydrate(reset($resultArray), $user);
        }
        
        return $user;
    }
    
    /**
     * Get user entity.
     * 
     * @param type $email
     * @return UserInterface|User
     * @throws \Exception
     */
    public function getUserByEmail($email)
    {
        $user = new User();
        
        if (!$email) {
            return $user;
        }
        
        $select = $this->sql->select();
        $select->from('user');
        $select->where(array('email' => $email));
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->toArray();
            
            $hydrator = new Hydrator\ClassMethods();
            $user = $hydrator->hydrate(reset($resultArray), $user);
        }
        
        if (!$user instanceof UserInterface) {
            throw new \Exception('Setup instance of UserInterface is failed.');
        }
        
        return $user;
    }
    
    /**
     * Get password hash.
     * 
     * @param string $email
     * @param array $extra
     * @return string
     */
    public function getPasswordByEmail($email, Array $extra = array())
    {
        $select = $this->sql->select();
        $select->from('user');
        $where = new Sql\Where();
        $where->equalTo('email', $email);
        
        while (list($key, $value) = each($extra)) {
            $where->and->equalTo($key, $value);
        }
        
        $select->where($where);
        $select->columns(array(
            'password' => new Sql\Expression('password')
        ));
        
        $statment = $this->sql->prepareStatementForSqlObject($select);
        //var_dump($this->sql->getSqlStringForSqlObject($select));die;
        
        $result = $statment->execute();
        
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultArray = $resultSet->initialize($result)->toArray();
            $resultArray = reset($resultArray);
        }
        
        $password = isset($resultArray['password']) ? $resultArray['password'] : null;
       
        return $password;
        
    }
    
    /**
     * Register confirmation.
     * 
     * @param array $confirmData
     * @return boolean
     */
    public function registerConfirm($confirmData)
    {
        $update = $this->sql->update();
        $update->table('user');
        $update->set(array(
            'state' => $confirmData['state.post'],
            'session_hash' => $confirmData['session_hash'],
            'confirm_code' => null,
        ));
        $where = new Sql\Where();
        $where->equalTo('user_id', $confirmData['user_id'])
              ->and
              ->equalTo('confirm_code', $confirmData['confirm_code'])
              ->and
              ->equalTo('state', $confirmData['state.pre']);
        $update->where($where);
        
        $statment = $this->sql->prepareStatementForSqlObject($update);
        $resultSet = new ResultSet();
        $confirm = $resultSet->initialize($statment->execute())
                             ->getDataSource()
                             ->count();
        
        return (bool)(int)$confirm;
    }
    
}