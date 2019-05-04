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

class Migrations
{
    const MINIMUM_SCHEMA_VERSION = 1;
    const INI_TABLE = 'ini-dev';

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

        $this->adapter->query($sqlString, Adapter::QUERY_MODE_PREPARE);
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

//        $sql = 'SELECT value FROM ' . $this->platform->quoteIdentifier(self::INI_TABLE) . ' WHERE option = :option';
//        $result = $this->adapter->query($sql, ['option' => 'ZftSchemaVersion']);
//        $result = $result->toArray();
//        $version = $result[0]['value'];

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(self::INI_TABLE);
        $select->where(['option' => 'ZftSchemaVersion']);

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $result = $result->current();
        $version = $result['value'];

        return $version;
    }
    
    protected function update1_001 ()
    {
        $iniTable = new CreateTable('ini');

        $option = new Varchar('option');
        $value = new Varchar('value');

        $iniTable->addColumn($option);
        $iniTable->addColumn($value);

        $this->execute($iniTable);

        $sql = new Sql($this->adapter);
        $insertInitialVersion = $sql->insert();
        $insertInitialVersion->into(self::INI_TABLE);
        $value = [
            'option' => 'ZftSchemaVersion',
            'value' => 1,
        ];
        $insertInitialVersion->columns(array_column($value));
        $insertInitialVersion->values(array_values($value));

        $insertStatement = $sql->prepareStatementForSqlObject($insertInitialVersion);
        $insertStatement->execute();
    }
}
