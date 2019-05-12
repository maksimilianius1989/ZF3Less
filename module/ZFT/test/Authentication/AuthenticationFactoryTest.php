<?php

namespace ZFTTest\Authentication;

use PHPUnit\Framework\TestCase;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceManager;
use ZFT\Authentication\AuthenticationServiceFactory;
use Zend\Session;
use Zend\Authentication;
use ZFT\Connections\LdapFactory;

class AuthenticationFactoryTest extends TestCase
{
    /** @var ServiceManager */
    private $sm;
    
    public function setUp()
    {
        parent::setUp();

        $this->sm = new ServiceManager();
        $this->sm->setService('Configuration', require __DIR__ . '/../../../../config/autoload/global.php');
        $this->sm->setService('authentication', new AuthenticationServiceFactory());
        $this->sm->setFactory('ldap', LdapFactory::class);
    }

    public function testCanCreateAuthenticationService()
    {
        $authServiceFactory = new AuthenticationServiceFactory();

        /** @var AuthenticationService $authService */
        $authService = $authServiceFactory($this->sm, 'authentication');

        $this->assertInstanceOf(AuthenticationService::class, $authService);
        $this->assertInstanceOf(AdapterInterface::class, $authService->getAdapter());

        $rs = $authService->authenticate();

        return;
    }
    
    public function testIdentityCreated()
    {
        /** @var AuthenticationServiceFactory $authServiceFactory */
        $authServiceFactory = $this->sm->get('authentication');

        /** @var AuthenticationService $auth */
        $auth = $authServiceFactory($this->sm, 'authentication');
        $auth->clearIdentity();

        /** @var Authentication\Adapter\Ldap $adapter */
        $adapter = $auth->getAdapter();
        $adapter->setIdentity('zftutorial');
        $adapter->setPassword('Qwerty123456');

        $auth->authenticate();

        $container = new Session\Container(\Zend\Authentication\Storage\Session::NAMESPACE_DEFAULT);
        $identity = $container->{Authentication\Storage\Session::MEMBER_DEFAULT};

        $this->assertEquals('ad\\zftutorial', $identity);

        return;
    }
}
