<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbac11081a94cd13a468e4f5ec133cb5b
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

        spl_autoload_register(array('ComposerAutoloaderInitbac11081a94cd13a468e4f5ec133cb5b', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbac11081a94cd13a468e4f5ec133cb5b', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbac11081a94cd13a468e4f5ec133cb5b::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
