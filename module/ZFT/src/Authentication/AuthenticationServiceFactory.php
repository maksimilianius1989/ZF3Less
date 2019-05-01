<?php

namespace ZFT\Authentication;

use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\Adapter\Ldap as LdapAdapter;

class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $sm
     * @param  string $requestedName
     * @param  null|array $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null)
    {
        $config = $sm->get('Configuration');

        $adapter = new LdapAdapter();
        $adapter->setOptions($config['ldapServers']);

        $auth = new AuthenticationService();
        $auth->setAdapter($adapter);

        return $auth;
    }
}
