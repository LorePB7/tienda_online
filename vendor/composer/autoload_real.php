<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInita07af5f851a46c61e888c28cbe2e7e0d
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

        spl_autoload_register(array('ComposerAutoloaderInita07af5f851a46c61e888c28cbe2e7e0d', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInita07af5f851a46c61e888c28cbe2e7e0d', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInita07af5f851a46c61e888c28cbe2e7e0d::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
