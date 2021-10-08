<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf2ed5c1356ccf16016f5be4104e100d4
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf2ed5c1356ccf16016f5be4104e100d4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf2ed5c1356ccf16016f5be4104e100d4::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
