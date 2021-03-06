<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit411f9236d29447c7065d4e585c0ea8bf
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Process\\' => 26,
            'Spatie\\PdfToText\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Spatie\\PdfToText\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/pdf-to-text/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit411f9236d29447c7065d4e585c0ea8bf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit411f9236d29447c7065d4e585c0ea8bf::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
