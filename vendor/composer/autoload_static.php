<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit98627ed2f0d6a091192a0c89972205a1
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TheGSC\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TheGSC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'TheGSC\\BaseSetup\\ACF' => __DIR__ . '/../..' . '/src/BaseSetup/ACF.php',
        'TheGSC\\BaseSetup\\Admin' => __DIR__ . '/../..' . '/src/BaseSetup/Admin.php',
        'TheGSC\\BaseSetup\\Comments' => __DIR__ . '/../..' . '/src/BaseSetup/Comments.php',
        'TheGSC\\BaseSetup\\Images' => __DIR__ . '/../..' . '/src/BaseSetup/Images.php',
        'TheGSC\\BaseSetup\\Menus' => __DIR__ . '/../..' . '/src/BaseSetup/Menus.php',
        'TheGSC\\BaseSetup\\PostThumbnails' => __DIR__ . '/../..' . '/src/BaseSetup/PostThumbnails.php',
        'TheGSC\\BaseSetup\\Seo' => __DIR__ . '/../..' . '/src/BaseSetup/Seo.php',
        'TheGSC\\BaseSetup\\Sidebars' => __DIR__ . '/../..' . '/src/BaseSetup/Sidebars.php',
        'TheGSC\\Blocks\\BlockOverrides\\BlockOverrides' => __DIR__ . '/../..' . '/src/Blocks/BlockOverrides/BlockOverrides.php',
        'TheGSC\\GraphQL\\GuideConnections' => __DIR__ . '/../..' . '/src/GraphQL/GuideConnections.php',
        'TheGSC\\PostTypes\\Events' => __DIR__ . '/../..' . '/src/PostTypes/Events.php',
        'TheGSC\\PostTypes\\Guides' => __DIR__ . '/../..' . '/src/PostTypes/Guides.php',
        'TheGSC\\PostTypes\\Woocommerce' => __DIR__ . '/../..' . '/src/PostTypes/Woocommerce.php',
        'TheGSC\\TheGSC' => __DIR__ . '/../..' . '/src/TheGSC.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit98627ed2f0d6a091192a0c89972205a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit98627ed2f0d6a091192a0c89972205a1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit98627ed2f0d6a091192a0c89972205a1::$classMap;

        }, null, ClassLoader::class);
    }
}