<?php

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ProfileController extends AbstractActionController
{
    public function viewAction()
    {

    }

    public function editAction()
    {

    }

    public function submitAction()
    {
        $success = true;

        if ($success) {
            $destinationRoute = 'profile/view_profile';
        } else {
            $destinationRoute = 'profile/edit_profile/form_profile';
        }

        return $this->redirect()->toRoute($destinationRoute);
    }
}
