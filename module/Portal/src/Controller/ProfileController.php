<?php

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * @method FlashMessenger flashMessenger()
 */

class ProfileController extends AbstractActionController
{
    public function viewAction()
    {
        return;
    }

    public function editAction()
    {
        return;
    }

    public function submitAction()
    {
        $flashMessenger = $this->flashMessenger();

        $success = true;
        if ($success) {
            $destinationRoute = 'profile/view_profile';
            $flashMessenger->addMessage('Profile successfully saved', FlashMessenger::NAMESPACE_SUCCESS, 100);
            $flashMessenger->addMessage('Profile successfully saved2', FlashMessenger::NAMESPACE_WARNING, 100);
        } else {
            $destinationRoute = 'profile/edit_profile/form_profile';
            $flashMessenger->addErrorMessage('Invalid email');
            $flashMessenger->addErrorMessage('Invalid username');
        }

        return $this->redirect()->toRoute($destinationRoute);
    }
}
