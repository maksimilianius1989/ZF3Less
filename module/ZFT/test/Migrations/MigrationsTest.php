<?php

namespace ZFTTest\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use ZFT\Migrations\Migrations;
use PHPUnit\Framework\TestCase;
use ZFT\Migrations\MigrationsEvent;

final class MigrationsStub extends Migrations
{
    const MINIMUM_SCHEMA_VERSION = 2;

    public $testVersion = 0;

    protected function update_001 ()
    {

    }

    protected function update_002 ()
    {

    }

    protected function setVersion ($version)
    {
        
    }
    
    public function getVersion()
    {
        return $this->testVersion;
    }
    
    protected function getTargetVersion ()
    {
        return 2;
    }
}

class MigrationsTest extends TestCase
{
    public function testListenersCalled()
    {
        $platformInterface = $this->createMock(PlatformInterface::class);
        $platformInterface->method('getName')
            ->willReturn('SQLite');

        $adapterMock = $this->createMock(Adapter::class);
        $adapterMock->method('getPlatform')
            ->willReturn($platformInterface);

        $migration = new MigrationsStub($adapterMock);

        $handleMigrationsStartRun = false;
        $migration->attach(MigrationsEvent::MIGRATIONS_START, function (MigrationsEvent $event) use(&$handleMigrationsStartRun) {
            $handleMigrationsStartRun = true;

            $params = $event->getParams();
            $this->assertEquals(0, $params['from']);
            $this->assertEquals(2, $params['to']);
        });
        $migration->run();

        $this->assertEquals(true, $handleMigrationsStartRun);
    }
}
