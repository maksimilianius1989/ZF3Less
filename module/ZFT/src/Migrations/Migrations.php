<?php

namespace ZFT\Migrations;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Source\Factory as MetadataFactory;
use Zend\Db\Sql\Ddl\Column\Varchar;
use Zend\Db\Sql\Ddl\CreateTable;
use Zend\Db\Sql\Ddl\SqlInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class Migrations
{
    const MINIMUM_SCHEMA_VERSION = 1;
    const INI_TABLE = 'ini-dev';
    const SCHEMA_OPTION_NAME = 'ZftSchemaVersion';

    /** @var Adapter */
    private $adapter;

    /** @var PlatformInterface */
    private $platform;

    /** @var MetadataInterface */
    private $metadata;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->platform = $adapter->getPlatform();
        $this->metadata = MetadataFactory::createSourceFromAdapter($adapter);
    }

    public function needsUpdate()
    {
        return ($this->getVersion() < self::MINIMUM_SCHEMA_VERSION);
    }

    public function execute(SqlInterface $ddl)
    {
        $sql = new Sql($this->adapter);
        $sqlString = $sql->buildSqlString($ddl);

        $result =  $this->adapter->query($sqlString, Adapter::QUERY_MODE_PREPARE);
        $result->execute();
    }
    
    private function getVersion()
    {
        $tables = $this->metadata->getTables();

        $iniTable = array_filter($tables, function (TableObject $table) {
            return strcmp($table->getName(), self::INI_TABLE) === 0;
        });

        if (count($iniTable) === 0) {
            return 0;
        }

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(self::INI_TABLE);
        $select->where(['option' => self::SCHEMA_OPTION_NAME]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $result = $result->current();
        $version = $result['value'];

        return $version;
    }
    
    public function run()
    {
        $migrationClass = new \ReflectionClass(Migrations::class);
        $methods = $migrationClass->getMethods(\ReflectionMethod::IS_PROTECTED);

        $updates = [];
        array_walk($methods, function (\ReflectionMethod $method) use (&$updates) {
            $version = (int) substr($method->getName(), strpos($method->getName(), '_') + 1);
            $updates[$version] = $method->getName();
        });

        ksort($updates);

        $currentVersion = (int) $this->getVersion();
        for ($v = $currentVersion + 1; $v <= self::MINIMUM_SCHEMA_VERSION; $v++) {
            $update = $updates[$v];
            $this->{$update}();

            $this->setVersion($v);
        }

        return;
    }
    
    private function setVersion($version)
    {
        $sql = new Sql($this->adapter);
        $schemaVersionUpdate = $sql->update();
        $schemaVersionUpdate->table(self::INI_TABLE);
        $schemaVersionUpdate->set(['value' => $version]);

        $schemaVersionRow = new Where();
        $schemaVersionRow->equalTo('optoin', self::SCHEMA_OPTION_NAME);

        $schemaVersionStatement = $sql->prepareStatementForSqlObject($schemaVersionUpdate);
        $schemaVersionStatement->execute();
    }
    
    protected function update_001 ()
    {
        $iniTable = new CreateTable(self::INI_TABLE);

        $option = new Varchar('option');
        $value = new Varchar('value');

        $iniTable->addColumn($option);
        $iniTable->addColumn($value);

        $this->execute($iniTable);

        $sql = new Sql($this->adapter);
        $insertInitialVersion = $sql->insert();
        $insertInitialVersion->into(self::INI_TABLE);
        $value = [
            'option' => self::SCHEMA_OPTION_NAME,
            'value' => 1,
        ];
        $insertInitialVersion->columns(array_keys($value));
        $insertInitialVersion->values(array_values($value));

        $insertStatement = $sql->prepareStatementForSqlObject($insertInitialVersion);
        $insertStatement->execute();
    }
}
