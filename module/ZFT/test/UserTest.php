<?php

namespace ZFTTest;

use \PHPUnit\Framework\TestCase;
use ZFT\User;

class UserTest extends TestCase
{
    public function testCanCreateUserObject()
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
    }
}
