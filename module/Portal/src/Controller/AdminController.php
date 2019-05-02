<?php

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZFT\User;

class AdminController extends AbstractActionController
{
    /** @var User\Repository */
    private $userRepository;
    
    public function __construct(User\Repository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function indexAction()
    {
        $user = $this->userRepository->getUserById(5);

        return new ViewModel();
    }
}
