<?php
/**
 * This is the configuration override file for the Production environment.
 * Refer to app.php for verbose comments and options.
 */

use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Parse Heroku DSNs into arrays for convenient
 * use during configuration.
 **/
$postgresDSN = parse_url(env('DATABASE_URL'));



# Configure all logs for Heroku.
foreach (array_keys(Configure::read('Log')) as $log) {
    Configure::write("Log.$log.className", 'Console');
}


return [
    'debug' => false,

    'Env' => [
        'HintText' => 'You\'re in Production!',
        'HintColor' => 'firebrick',
    ],

    'Security.salt' => env('SECURITY_SALT'),

    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Postgres',
            'persistent' => false,
            'host' => $postgresDSN['host'],
            'port' => $postgresDSN['port'],
            'username' => $postgresDSN['user'],
            'password' => $postgresDSN['pass'],
            'database' => ltrim($postgresDSN['path'], '/'),
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        ],
    ],

    'Analytics' => [
        'google' => 'UA-XXXXXXXX',
    ],
];
