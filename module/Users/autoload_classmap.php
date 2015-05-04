<?php

return array(
    'Users\Module'                         => __DIR__ . '/Module.php',
    'Users\Controller\UserController'      => __DIR__ . '/src/Users/Controller/UserController.php',
    'Users\Entity\User'                    => __DIR__ . '/src/Users/Entity/User.php',
    'Users\Entity\UserInterface'           => __DIR__ . '/src/Users/Entity/UserInterface.php',
    'Users\Form\Login'                     => __DIR__ . '/src/Users/Form/Login.php',
    'Users\Form\LoginFilter'               => __DIR__ . '/src/Users/Form/LoginFilter.php',
    'Users\Form\Register'                  => __DIR__ . '/src/Users/Form/Register.php',
    'Users\Form\RegisterFilter'            => __DIR__ . '/src/Users/Form/RegisterFilter.php', 
    'Users\Mapper\DbManager'               => __DIR__ . '/src/Users/Mapper/DbManager.php',
    'Users\Mapper\DbManagerInterface'      => __DIR__ . '/src/Users/Mapper/DbManagerInterface.php',
    'Users\Service\UserService'            => __DIR__ . '/src/Users/Service/UserService.php',
    'Users\Service\UserServiceInterface'   => __DIR__ . '/src/Users/Service/UserServiceInterface.php',
    'Users\Validator\NoRecordExists'       => __DIR__ . '/src/Users/Validator/NoRecordExists.php',
);