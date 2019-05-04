<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'hostname' => 'db',
        'driver' => 'Pdo_Pgsql',
        'database' => 'dbname',
        'username' => 'dbuser',
        'password' => 'dbpwd',
    ],
    'ldap' => [
        'host' => '192.168.0.101',
        'accountDomainName' => 'ad.alex-tech-adventures.com',
        'accountDomainNameShort' => 'ad',
        'baseDn' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
        'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_BACKSLASH, // alex-tech\sasha
    ],
//        'apacheDS' => [
//            'host' => '127.0.0.1',
//            'port' => 10389,
//            'accountDomainNameShort' => 'alex-tech',
//            'accountDomainName' => 'ds.alex-tech-adventures.com',
//            'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_DN, // alex-tech\sasha
//            'baseDn' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
//        ],
];
