<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d31af00be5ded3e9d1114ecf3f49067
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AIOSEO\\Plugin\\Addon\\LinkAssistant\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Admin\\Admin' => __DIR__ . '/../..' . '/app/Admin/Admin.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Admin\\Usage' => __DIR__ . '/../..' . '/app/Admin/Usage.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\Api' => __DIR__ . '/../..' . '/app/Api/Api.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\Common' => __DIR__ . '/../..' . '/app/Api/Common.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\Data' => __DIR__ . '/../..' . '/app/Api/Data.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\DomainsReport' => __DIR__ . '/../..' . '/app/Api/DomainsReport.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\DomainsReportInner' => __DIR__ . '/../..' . '/app/Api/DomainsReportInner.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\LinksReport' => __DIR__ . '/../..' . '/app/Api/LinksReport.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\LinksReportInner' => __DIR__ . '/../..' . '/app/Api/LinksReportInner.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\PostReport' => __DIR__ . '/../..' . '/app/Api/PostReport.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\PostSettings' => __DIR__ . '/../..' . '/app/Api/PostSettings.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Api\\Settings' => __DIR__ . '/../..' . '/app/Api/Settings.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\LinkAssistant' => __DIR__ . '/../..' . '/app/LinkAssistant.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Links\\Data' => __DIR__ . '/../..' . '/app/Links/Data.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Links\\Links' => __DIR__ . '/../..' . '/app/Links/Links.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Main\\Main' => __DIR__ . '/../..' . '/app/Main/Main.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Main\\Paragraph' => __DIR__ . '/../..' . '/app/Main/Paragraph.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Main\\Updates' => __DIR__ . '/../..' . '/app/Main/Updates.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Models\\Link' => __DIR__ . '/../..' . '/app/Models/Link.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Models\\Suggestion' => __DIR__ . '/../..' . '/app/Models/Suggestion.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Options\\InternalOptions' => __DIR__ . '/../..' . '/app/Options/InternalOptions.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Options\\Options' => __DIR__ . '/../..' . '/app/Options/Options.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Suggestions\\Data' => __DIR__ . '/../..' . '/app/Suggestions/Data.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Suggestions\\Suggestions' => __DIR__ . '/../..' . '/app/Suggestions/Suggestions.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Traits\\Debug' => __DIR__ . '/../..' . '/app/Traits/Debug.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Traits\\DomainsReport' => __DIR__ . '/../..' . '/app/Traits/DomainsReport.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Traits\\LinksReport' => __DIR__ . '/../..' . '/app/Traits/LinksReport.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Traits\\Overview' => __DIR__ . '/../..' . '/app/Traits/Overview.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Traits\\PostSettings' => __DIR__ . '/../..' . '/app/Traits/PostSettings.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Utils\\Cache' => __DIR__ . '/../..' . '/app/Utils/Cache.php',
        'AIOSEO\\Plugin\\Addon\\LinkAssistant\\Utils\\Helpers' => __DIR__ . '/../..' . '/app/Utils/Helpers.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d31af00be5ded3e9d1114ecf3f49067::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d31af00be5ded3e9d1114ecf3f49067::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7d31af00be5ded3e9d1114ecf3f49067::$classMap;

        }, null, ClassLoader::class);
    }
}
