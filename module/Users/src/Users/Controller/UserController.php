<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Users\Form\Login as LoginForm;
use Users\Form\LoginFilter;
use Users\Form\Register as RegisterForm;
use Users\Service\UserServiceInterface;

class UserController extends AbstractActionController
{   
    protected $userService;
    
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function loginAction()
    {
        $viewModel = new ViewModel();
        $loginForm = new LoginForm(); 
        $request   = $this->getRequest();
        
        if (!$request->isXmlHttpRequest()) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        
        $viewModel->setTerminal(true)->setTemplate('users/user/login');
        
        if ($request->isPost() && !$this->userService->hasLogged()) {
            $jsonModel   = new JsonModel();
            $loginFilter = new LoginFilter();
            
            $loginForm->setInputFilter($loginFilter->getInputFilter())
                      ->setData($request->getPost());
            
            try {
                if (!$loginForm->isValid()) {
                    throw new \Exception('Authentication failed. Please try again.');
                }
                
                $formData = $loginForm->getData();
                $this->userService->authenticate($formData['email'], $formData['password']);
                
                $jsonModel->setVariable('logged', true);
                
            } catch (\Exception $ex) {
                $viewModel->setVariables(array(
                    'generalError' => $ex->getMessage(),
                    'loginForm' => $loginForm,
                ));
                
                $jsonModel->setVariables(array(
                    'logged' => false,
                    'view' => $this->getServiceLocator()->get('viewrenderer')->render($viewModel),
                ));
            }
            
            return $jsonModel;         
        }
        
        $viewModel->setVariable('loginForm', $loginForm);

        return $viewModel;
    }
    
    public function registerAction()
    {   
        $viewModel    = new ViewModel();
        $registerForm = new RegisterForm();
        $request      = $this->getRequest();
        
        if (!$request->isXmlHttpRequest()) {
            $viewModel->setTemplate('error/404');
            return $viewModel;
        }
        
        $viewModel->setTerminal(true)->setTemplate('users/user/register');
        
        if ($request->isPost() && !$this->userService->hasLogged()) {
            $registerFilter = $this->getServiceLocator()
                                   ->get('register_filter')
                                   ->getInputFilter();
            
            $registerForm->setInputFilter($registerFilter)
                         ->setData($request->getPost());
            
            try {
                if ($registerForm->isValid()) {
                    $userId = $this->userService->createNewUser($registerForm->getData());
                    $user   = $this->userService->getUserById($userId);
                    
                    $this->sendRegisterLetter($user);

                    $this->redirect()->toRoute('user/register_confirm', array(
                        'id' => $userId
                    ));
                }
            } catch (\Exception $ex) {
                $viewModel->setVariable('generalError', $ex->getMessage());
            }
        }
        
        $viewModel->setVariable('registerForm', $registerForm);

        return $viewModel;
    }
    
    public function registerConfirmAction()
    {
        $id      = $this->params()->fromRoute('id', 0);
        $code    = $this->params()->fromRoute('code', 0);
        $request = $this->getRequest();
        
        if ($request->isGet() && !$code) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true)
                      ->setVariable('id', $id)
                      ->setTemplate('users/user/register_confirm');
            
            return $viewModel;
        }
        
        try {
            $this->userService->registerConfirm($id, $code);
            
            $logged = true;
            $error  = '';
        } catch (\Exception $ex) {
            $logged = false;
            $error  = $ex->getMessage();
        }
        
        // If we came here by reference from email
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect()->toRoute('home');
        }

        $jsonModel = new JsonModel();
        
        $jsonModel->setVariables(array(
            'logged' => $logged,
            'error' => $error,
        ));

        return $jsonModel;
    }
    
    public function settingAction()
    {
        return array();
    }
    
    public function logoutAction()
    {
        $this->getServiceLocator()->get('auth_storage')->clearStorage();
        
        $this->redirect()->toRoute('home');
    }
    
    protected function sendRegisterLetter($user)
    {
        $view = new ViewModel();
        $view->setTemplate('users/user/register_letter')->setVariable('user', $user);
        
        $htmlView = $this->getServiceLocator()->get('viewrenderer')->render($view);
        
        $html = new \Zend\Mime\Part($htmlView);
        $html->type = 'text/html';
        $html->charset = 'utf-8';

        $body = new \Zend\Mime\Message();
        $body->setParts(array($html));

        $message = new \Zend\Mail\Message();
        $message->setBody($body)
                ->addTo($user->getEmail())
                ->setSubject('Регистрация на chemer.com.ua')
                ->setEncoding('utf-8');
        
        $transport = new \Zend\Mail\Transport\Sendmail();
        $transport->send($message);
    }
}
