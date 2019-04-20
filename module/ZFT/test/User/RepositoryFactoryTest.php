<?php

namespace ZFTTest\User;

use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use ZFT\User\DataMapperInterface;
use ZFT\User\IdentityMapInterface;
use ZFT\User\MemoryIdentityMap;
use ZFT\User\MysqlDataMapper;
use ZFT\User\Repository;
use ZFT\User\RepositoryFactory;

class RepositoryFactoryTest extends TestCase
{
    public function testCanCreateUserRepository()
    {
        $sm = new ServiceManager();

        $sm->setFactory(MemoryIdentityMap::class, function () {
            return new class() implements IdentityMapInterface {

            };
        });

        $sm->setFactory(MysqlDataMapper::class, function () {
            return new class() implements DataMapperInterface {

            };
        });

        $factory = new RepositoryFactory();
        $repository = $factory($sm, RepositoryFactory::class);

        $this->assertInstanceOf(Repository::class, $repository);
    }
}
