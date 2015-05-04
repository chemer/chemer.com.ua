<?php

namespace Feedback\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Feedback\Form\Feedback as FeedbackForm;
use Feedback\Form\FeedbackFilter;
use Users\Service\UserServiceInterface;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;
use Zend\Mail\Transport\Sendmail;

class FeedbackController extends AbstractActionController
{
    protected $userService;
    
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Feedback letter.
     * 
     * If method POST - return view as Callback function, with params such as
     * error_code: 0 - ok,
     *             1 - logged required,
     *             2 - validation error,
     *             3 - other;
     * modal_view: html part;
     * message: string or array (if validation error)
     * 
     * @return ViewModel
     */
    public function sendLetterAction()
    {
        $viewModel    = new ViewModel();
        $feedbackForm = new FeedbackForm();
        $request      = $this->getRequest();
        
        if ($request->isPost()) {
            $viewModel->setTerminal(true)
                      ->setTemplate('feedback/feedback/feedback_callback');
            
            try {
                if (!$this->userService->hasLogged()) {
                    $modalView = new ViewModel();
                    $modalView->setTerminal(true)->setTemplate('feedback/feedback/logged_required');
                    
                    $params = array(
                        'error_code' => 1,
                        'modal_view'  => $this->getServiceLocator()->get('viewrenderer')->render($modalView),
                    );
                    
                    $viewModel->setVariable('params', $params);

                    return $viewModel;
                }
                
                if ($this->exceedLimit()) {
                    $params = array(
                        'error_code' => 3,
                        'message'    => 'Size of POST data  exceeds the allowed limit.',
                    );
                    
                    $viewModel->setVariable('params', $params);

                    return $viewModel;
                }
                
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );

                $feedbackFilter = new FeedbackFilter();

                $feedbackForm->setInputFilter($feedbackFilter->getInputFilter())
                             ->setData($post);
                
                if ($feedbackForm->isValid()) {
                    $this->sendEmail($feedbackForm->getData());
                    
                    $modalView = new ViewModel();
                    $modalView->setTerminal(true)->setTemplate('feedback/feedback/send_letter_success');
                    
                    $params = array(
                        'error_code' => 0,
                        'modal_view'  => $this->getServiceLocator()->get('viewrenderer')->render($modalView),
                    );
                    
                    $viewModel->setVariable('params', $params);

                    return $viewModel;
                } else {
                    $params = array(
                        'error_code' => 2,
                        'message'    => $feedbackForm->getMessages(),
                    );
                    
                    $viewModel->setVariable('params', $params);

                    return $viewModel;
                }
                
            } catch (\Exception $ex) {
                $params = array(
                    'error_code' => 3,
                    'message'    => $ex->getMessage(),
                );

                $viewModel->setVariable('params', $params);

                return $viewModel;
            }
        }
        
        $viewModel->setVariable('feedbackForm', $feedbackForm);
        
        return $viewModel;
    }

    protected function exceedLimit()
    {
        $contentlength = (int)filter_input(INPUT_SERVER, 'CONTENT_LENGTH');
        
        if ($contentlength < 1) {
            return true;
        }
        
        $postMaxSize = ini_get('post_max_size');
        
        switch(substr($postMaxSize,-1)) {
            case 'G':
                $postMaxSize = $postMaxSize * 1024 *1024 * 1024;
                break;
            case 'M':
                $postMaxSize = $postMaxSize * 1024 *1024;
                break;
            case 'K':
                $postMaxSize = $postMaxSize * 1024;
                break;
        }
        
        return $contentlength > $postMaxSize ? true : false;
    }
    
    protected function sendEmail($formData)
    {
        $letterTemplate = new ViewModel();
        $letterTemplate->setTemplate('feedback/feedback/letter_template')
                       ->setVariables(array(
                           'sender'     => $this->userService->getCurrentUser(),
                           'textLetter' => $formData['text'],
                       ));
        
        $html = new MimePart($this->getServiceLocator()->get('viewrenderer')->render($letterTemplate));
        $html->type    = Mime::TYPE_HTML;
        $html->charset = 'utf-8';
        
        $message     = new Message();
        $messageBody = new MimeMessage();
        $messageBody->addPart($html);
        
        if ($formData['file']['error'] === 0) {
            $attachment = new MimePart(fopen($formData['file']['tmp_name'], 'r'));
            $attachment->type        = $formData['file']['type'];
            $attachment->filename    = $formData['file']['name'];
            $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
            $attachment->encoding    = Mime::ENCODING_BASE64;
            
            
            $messageBody->addPart($attachment);
            $messageType = 'multipart/mixed';
        } else {
            $messageType = 'text/html';
        }
        
        $message->setBody($messageBody)
                ->addTo('evgeny.chemersky@gmail.com')
                ->setSubject($formData['subject'])
                ->setEncoding('utf-8');
        
        $message->getHeaders()->get('content-type')->setType($messageType);
        
        $transport = new Sendmail();
        $transport->send($message);
    }
}
