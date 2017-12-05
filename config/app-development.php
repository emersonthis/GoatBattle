<?php
/**
 * This is the configuration override file for the Development environment.
 * Refer to app.php for verbose comments and options.
 */

use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Parse Heroku DSNs into arrays for convenient
 * use during configuration.
 **/
// $postgresDSN = parse_url(env('DATABASE_URL'));

# Configure all logs for Heroku.
foreach (array_keys(Configure::read('Log')) as $log) {
    Configure::write("Log.$log.className", 'Console');
}

return [
    'debug' => true,

    'Env' => [
        'HintText' => 'You\'re on the development server.',
        'HintColor' => 'dodgerblue',
    ],

    'Security.salt' => '0bY4MOk7NJfwUJbCpi6hZy9tLgg6R2rqCAjEnMWuV7oQOdGfVtL44Eo4EFq3xrPJ',

    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => 'root',
            'database' => 'goats',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        ],
    ],
];
