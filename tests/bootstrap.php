<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Sauvegarde APP_ENV si déjà défini (ex: par phpunit.xml.dist) pour qu'il ne soit pas écrasé par .env.local
$appEnvOverride = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null;

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Restaure APP_ENV après bootEnv pour que phpunit.xml.dist ait la priorité sur .env.local
if ($appEnvOverride !== null) {
    $_SERVER['APP_ENV'] = $appEnvOverride;
    $_ENV['APP_ENV'] = $appEnvOverride;
    putenv('APP_ENV='.$appEnvOverride);
}
