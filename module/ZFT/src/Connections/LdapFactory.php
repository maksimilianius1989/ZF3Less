<?php

namespace ZFT\Connections;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Ldap\Ldap;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\FactoryInterface;

class LdapFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $sm
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     * @throws \Zend\Ldap\Exception\LdapException
     */
    public function __invoke(ContainerInterface $sm, $requestedName, array $options = null)
    {
        $config = $sm->get('Configuration');

        if (!array_key_exists('ldapServers', $config) || !array_key_exists('ldap', $config['ldapServers'])) {
            throw new ServiceNotCreatedException('Configuration is missing "ldap" key');
        }

        $config = $config['ldapServers']['ldap'];

        $ldap = new Ldap();
        $ldap->setOptions($config);

        return $ldap;
    }
}
