<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $feedback = $this->forward()->dispatch('feedback_controller', array('action' => 'send_letter'));
        $viewModel->addChild($feedback, 'feedback');
        
        return $viewModel;
    }
}
