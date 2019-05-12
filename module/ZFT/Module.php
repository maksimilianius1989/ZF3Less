<?php
/**
 * Created by PhpStorm.
 * User: vimax
 * Date: 20.04.19
 * Time: 16:35
 */

namespace ZFT;

use Zend\Db\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZFT\Authentication\AuthenticationServiceFactory;
use ZFT\Connections\LdapFactory;
use ZFT\Migrations\Migrations;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\MysqlDataMapper;
use ZFT\User\Repository as UserRepository;
use ZFT\User\RepositoryFactory;

class Module implements ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $sm = $application->getServiceManager();

        $em = $application->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $event) use ($sm, $em) {
            $router = $event->getRouteMatch();
            if (!($router->getParam('needsDatabase') === false)) {
                $adapter = $sm->get('dbcon');

                $migration = new Migrations($adapter);
                if ($migration->needsUpdate()) {
                    $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
                    $event->setError('Database Needs Update');
                    $event->setParam('needsDatabaseUpdate', true);

                    $event->stopPropagation(true);
                    $em->triggerEvent($event);

                    return $event->getRequest();
                }
            }
        }, 100);
    }
    
    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                MysqlDataMapper::class => InvokableFactory::class,
                MemoryIdentityMap::class => InvokableFactory::class,

                UserRepository::class => RepositoryFactory::class,
                'authentication' => AuthenticationServiceFactory::class,

                'ldap' => LdapFactory::class,
            ],
            'aliases' => [
                'dbcon' => AdapterInterface::class,
            ],
        ];
    }
}
