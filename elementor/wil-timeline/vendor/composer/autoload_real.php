<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitWiloked9d17646f5604d2c8becfa7bbb027518
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitWiloked9d17646f5604d2c8becfa7bbb027518', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitWiloked9d17646f5604d2c8becfa7bbb027518', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitWiloked9d17646f5604d2c8becfa7bbb027518::getInitializer($loader));

        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInitWiloked9d17646f5604d2c8becfa7bbb027518::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequireWiloked9d17646f5604d2c8becfa7bbb027518($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequireWiloked9d17646f5604d2c8becfa7bbb027518($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}