<?php

/// DotEnv Bootstrap
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required([
    'DATABASE_HOST',
    'DATABASE_PORT',
    'DATABASE_USER',
    'DATABASE_PASS',
    'DATABASE_SCHEMA',
    'SMTP_HOST',
    'SMTP_PORT',
    'SMTP_USERNAME',
    'SMTP_PASSWORD',
    'WEBSERVER_NAME',
    'WEBSERVER_PORT',
    'APP_SECRET'
]);

/// Doctrine Bootstrap

$config = new \Doctrine\ORM\Configuration();
$cache = new \Doctrine\Common\Cache\ArrayCache();

$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(__DIR__.'/src/Entity', false);
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir(__DIR__.'/tmp');
$config->setProxyNamespace('MisterIcy\\RnR\\Proxies');


$config->setAutoGenerateProxyClasses(true);

$parameters = [
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DATABASE_HOST'],
    'port' => $_ENV['DATABASE_PORT'],
    'user' => $_ENV['DATABASE_USER'],
    'password' => $_ENV['DATABASE_PASS'],
    'dbname' => $_ENV['DATABASE_SCHEMA'],
];

$entityManager = \Doctrine\ORM\EntityManager::create($parameters, $config);

$transport = (new Swift_SmtpTransport(
    $_ENV['SMTP_HOST'], $_ENV['SMTP_PORT']
))
    ->setUsername($_ENV['SMTP_USERNAME'])
    ->setPassword($_ENV['SMTP_PASSWORD']);
