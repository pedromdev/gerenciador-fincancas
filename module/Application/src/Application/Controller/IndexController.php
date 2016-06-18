<?php

namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel([
            'loginForm' => $this->getServiceLocator()->get('Application\Form\Login'),
        ]);
    }
    
    public function signupAction()
    {
        return new ViewModel([
            'signupForm' => $this->getServiceLocator()->get('Application\Form\Signup'),
        ]);
    }
}
