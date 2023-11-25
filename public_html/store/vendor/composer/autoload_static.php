<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbfefd448b45c923b414cce09e76bfe34
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Masterminds\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/filp/whoops/src/Whoops',
        ),
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Masterminds\\' => 
        array (
            0 => __DIR__ . '/..' . '/masterminds/html5/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Symfony\\Component\\DomCrawler\\' => 
            array (
                0 => __DIR__ . '/..' . '/symfony/dom-crawler',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbfefd448b45c923b414cce09e76bfe34::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbfefd448b45c923b414cce09e76bfe34::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbfefd448b45c923b414cce09e76bfe34::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitbfefd448b45c923b414cce09e76bfe34::$classMap;

        }, null, ClassLoader::class);
    }
}
