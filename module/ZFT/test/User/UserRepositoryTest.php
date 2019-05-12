<?php

namespace ZFTTest\User;

use PHPUnit\Framework\TestCase;
use ZFT\User\DataMapperInterface;
use ZFT\User\IdentityMapInterface;
use ZFT\User\Repository as UserRepository;

class UserRepositoryTest extends TestCase
{
    public function testCanCreateUserRepositoryObject()
    {
        $identityMapStup = new class() implements IdentityMapInterface {

        };

        $dataMapperStup = new class() implements DataMapperInterface {

        };

        $repository = new UserRepository($identityMapStup, $dataMapperStup);

        $this->assertInstanceOf(UserRepository::class, $repository);
    }
}
