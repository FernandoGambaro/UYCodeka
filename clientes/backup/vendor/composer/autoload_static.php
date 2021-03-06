<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5f2036a8e867903c12b9a58572acfa2b
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SSilence\\ImapClient\\' => 20,
        ),
        'P' => 
        array (
            'PhpImap\\' => 8,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'D' => 
        array (
            'Ddeboer\\Imap\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SSilence\\ImapClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/ssilence/php-imap-client/ImapClient',
        ),
        'PhpImap\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-imap/php-imap/src/PhpImap',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Ddeboer\\Imap\\' => 
        array (
            0 => __DIR__ . '/..' . '/ddeboer/imap/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5f2036a8e867903c12b9a58572acfa2b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5f2036a8e867903c12b9a58572acfa2b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
