<?php

declare(strict_types=1);

namespace Jarvis\Skill\Helper;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
abstract class AbstractSettingsLoader
{
    /**
     * Returns an array that contains current project global settings.
     *
     * @return array
     */
    public static function load(): array
    {
        $vars = static::vars();
        $raw = str_replace(
            array_keys($vars),
            array_values($vars),
            file_get_contents(static::settingsPath())
        );

        return json_decode($raw, true);
    }

    /**
     * Returns project root directory.
     *
     * @return string
     */
    abstract protected static function rootdir(): string;

    /**
     * Returns project global settings file path.
     *
     * @return string
     */
    protected static function settingsPath(): string
    {
        return realpath(static::rootdir() . '/res/app_settings.json');
    }

    /**
     * Returns list of variables to replace in global settings raw string.
     *
     * @return array
     */
    protected static function vars(): array
    {
        return [
            '%root_dir%' => static::rootdir(),
        ];
    }
}
