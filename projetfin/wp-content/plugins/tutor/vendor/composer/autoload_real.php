<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit631a460b6010ba6d03c73cfd415f87ca
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

        spl_autoload_register(array('ComposerAutoloaderInit631a460b6010ba6d03c73cfd415f87ca', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit631a460b6010ba6d03c73cfd415f87ca', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit631a460b6010ba6d03c73cfd415f87ca::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}