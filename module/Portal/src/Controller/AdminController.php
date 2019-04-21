<?php

namespace Portal\Controller;

use Zend\View\Model\ViewModel;
use ZFT\User;

class AdminController
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
