<?php

if (!defined('APP_ENV')) {
    $defaultTier = 'live';
    $env = getenv('devTier') ?: getenv('APP_ENV') ?: $defaultTier;
    $devMode = $env == 'local' || $env == 'dev_trunk';

    define('APP_ENV', $env);
    define('DEV_MODE', $devMode);
}

$modules = [
    'TescoRedemptionPortal'
];

return array(
    'modules' => $modules,

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './module',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{{,*.}global,{,*.}' . $env . '}.php',
        ),

        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        'config_cache_enabled' => false,//!DEV_MODE,

        // The key used to create the configuration cache file name.
        'config_cache_key' => 'app_config',

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        'module_map_cache_enabled' => !DEV_MODE,

        // The key used to create the class map cache file name.
        'module_map_cache_key' => 'module_map_cache',

        // The path in which to cache merged configuration.
        'cache_dir' => 'data/config/',

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        'check_dependencies' => DEV_MODE,
    ),

    // Initial configuration with which to seed the ServiceManager.
    // Should be compatible with Zend\ServiceManager\Config.
    'service_manager' => array(
        'factories' => array(
            'Zend\Log' => function ($sm) {
                // Create the logger
                $log = new Zend\Log\Logger();

                // Add a processor to include env information in the logs
                $processor = new \TescoRedemptionPortal\Processor\EnvLogProcessor();
                $log->addProcessor($processor);

                // Add the file writer
                $writer = new Zend\Log\Writer\Stream('data/logs/error.log');
                $log->addWriter($writer);

                return $log;
            },
            'Zend\Cache' => function () {
                return Zend\Cache\StorageFactory::factory(
                    array(
                        'adapter' => array(
                            'name' => 'filesystem',
                            'options' => array(
                                'cacheDir' => 'data/cache',
                                'dirPermission' => 0755,
                                'filePermission' => 0666,
                                'ttl' => 86400
                            )
                        ),
                        'plugins' => array('serializer')
                    )
                );
            }
        )
    ),
);
