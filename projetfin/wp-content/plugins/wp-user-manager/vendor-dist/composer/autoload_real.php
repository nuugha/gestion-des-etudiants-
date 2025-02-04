<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit30f4ec827c741f4a5689c14e03eefa0c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('WPUM\Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \WPUM\Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit30f4ec827c741f4a5689c14e03eefa0c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \WPUM\Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit30f4ec827c741f4a5689c14e03eefa0c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\WPUM\Composer\Autoload\ComposerStaticInit30f4ec827c741f4a5689c14e03eefa0c::getInitializer($loader));

        $loader->register(true);

        $filesToLoad = \WPUM\Composer\Autoload\ComposerStaticInit30f4ec827c741f4a5689c14e03eefa0c::$files;
        $requireFile = \Closure::bind(static function ($fileIdentifier, $file) {
            if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
                $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

                require $file;
            }
        }, null, null);
        foreach ($filesToLoad as $fileIdentifier => $file) {
            $requireFile($fileIdentifier, $file);
        }

        return $loader;
    }
}
