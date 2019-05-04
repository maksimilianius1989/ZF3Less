<?php

namespace Portal\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZFT\Migrations\Migrations;

class AdminController extends AbstractActionController
{
    /** @var Migrations */
    private $migrations;
    
    public function __construct(Migrations $migrations)
    {
        $this->migrations = $migrations;
    }
    
    public function indexAction()
    {
        return [
            'needsMigration' => $this->migrations->needsUpdate(),
        ];
    }
    
    public function runmigrationsAction()
    {
        $this->migrations->run();
    }
}
