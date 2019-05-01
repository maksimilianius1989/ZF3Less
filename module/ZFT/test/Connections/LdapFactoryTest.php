<?php

namespace ZFTTest\Connections;

use PHPUnit\Framework\TestCase;
use Zend\Ldap\Ldap;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceManager;
use ZFT\Connections\LdapFactory;

class LdapFactoryTest extends TestCase
{
    public function testThrowsExceptionWhenNoConfigurationFound()
    {
        $sm = new ServiceManager();
        $sm->setService('Configuration', []);

        $this->expectException(ServiceNotCreatedException::class);

        $ldapFactory = new LdapFactory();
        $ldap = $ldapFactory($sm, 'ldap');
    }

    public function testOptionsPassedToLdapConnection()
    {
        $configArray = ['host' => 'testHost'];

        $sm = new ServiceManager();
        $sm->setService('Configuration', ['ldapServers' => ['ldap' => $configArray]]);

        $ldapFactory = new LdapFactory();
        /** @var Ldap $ldap */
        $ldap = $ldapFactory($sm, 'ldap');

        $this->assertArraySubset($configArray, $ldap->getOptions(), 'Ldap connection should receive configuration
            from Service manager');
    }
}
