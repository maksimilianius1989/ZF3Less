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
    'ldapServers' => [
        'mainDC' => [
            'host' => '192.168.0.102',
            'useStartTls' => true,
            'accountDomainName' => 'ad.alex-tech-adventures.com',
            'accountDomainNameShort' => 'ad',
            'baseDN' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
            'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_BACKSLASH, // alex-tech\sasha
        ],

        'apacheDS' => [
            'host' => '127.0.0.1',
            'port' => 10389,
            'accountDomainShort' => 'alex-tech',
            'accountDomainName' => 'ds.alex-tech-adventures.com',
            'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_DN, // alex-tech\sasha
            'baseDN' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
        ],
    ],
];
