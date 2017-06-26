<?php

declare(strict_types=1);

namespace Jarvis\Skill\Helper;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
abstract class AbstractSettingsLoader
{
    /**
     * Returns an array that contains current project global settings. If an env
     * is given, it will try to load the file and override values.
     *
     * @return array
     */
    public static function load(string $env = ''): array
    {
        $filepath = static::settingsPath();
        if (false === $filepath) {
            throw new \RuntimeException(
                'Failed to read app_settings.json, the file does not exist or is not readable.'
            );
        }

        $settings = static::read($filepath);
        if (false != $env && false !== $envFilepath = static::settingsPath($env)) {
            $settings = array_merge_assoc_recursive(
                $settings,
                static::read($envFilepath)
            );
        }

        return $settings;
    }

    /**
     * Returns project root directory.
     *
     * @return string
     */
    abstract protected static function rootdir(): string;

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

    /**
     * Returns project global settings file path according to provided environment.
     *
     * @param string $env The environment to use
     *
     * @return string
     */
    private static function settingsPath(string $env = ''): string
    {
        return realpath(sprintf(
            '%s/res/app_settings%s.json',
            static::rootdir(),
            $env ? '.' . $env : ''
        ));
    }

    /**
     * Reads the provided JSON file path, computes it and returns it as array.
     *
     * @param  string $path The JSON file path to read
     *
     * @return array the array that contains data of provided JSON file
     */
    private static function read(string $path): array
    {
        $vars = static::vars();
        $raw = str_replace(
            array_keys($vars),
            array_values($vars),
            file_get_contents($path)
        );

        $result = json_decode($raw, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf(
                'Failed to read JSON file %s, an error occured: %s (%d)',
                $path,
                json_last_error_msg(),
                json_last_error()
            ));
        }

        return $result;
    }
}
