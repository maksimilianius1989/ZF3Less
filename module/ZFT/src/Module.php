<?php
/**
 * Created by PhpStorm.
 * User: vimax
 * Date: 20.04.19
 * Time: 16:35
 */

namespace ZFT;


use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface
{

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [];
    }
}