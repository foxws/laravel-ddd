<?php

declare(strict_types=1);

namespace Foxws\Ddd\Support;

use Illuminate\Support\Facades\Config;

class DddSubstitutions
{
    /**
     * Get the type-to-subfolder substitutions used when generating DDD objects.
     *
     * @return array<string, string>
     */
    public static function get(): array
    {
        return [
            'action' => 'Actions',
            'cast' => 'Casts',
            'channel' => 'Channels',
            'class' => '',
            'collection' => 'Collections',
            'command' => 'Commands',
            'contract' => 'Contracts',
            'controller' => 'Controllers',
            'data' => 'DataObjects',
            'dto' => 'DataObjects',
            'enum' => 'Enums',
            'event' => 'Events',
            'exception' => 'Exceptions',
            'factory' => 'Database\Factories',
            'filter' => 'Filters',
            'job' => 'Jobs',
            'listener' => 'Listeners',
            'mail' => 'Mail',
            'middleware' => 'Middleware',
            'migration' => 'Database\Migrations',
            'model' => 'Models',
            'notification' => 'Notifications',
            'observer' => 'Observers',
            'pipe' => 'Pipes',
            'policy' => 'Policies',
            'provider' => 'Providers',
            'query_builder' => 'QueryBuilders',
            'request' => 'Requests',
            'resource' => 'Resources',
            'rule' => 'Rules',
            'scope' => 'Scopes',
            'seeder' => 'Database\Seeders',
            'service' => 'Services',
            'setting' => 'Settings',
            'state' => 'States',
            'trait' => 'Concerns',
            'value_object' => 'ValueObjects',
            'view_model' => 'ViewModels',
            ...static::customSubstitutions(),
        ];
    }

    /**
     * Get the custom substitutions configured for the application.
     *
     * @return array<string, string>
     */
    public static function customSubstitutions(): array
    {
        return Config::array('ddd.substitutions', []);
    }
}
