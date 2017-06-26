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
     *
     * @throws \RuntimeException if the app_settings.json file does not exist or is not readable
     * @throws \InvalidArgumentException if the env settings file does not exist or is not readable
     */
    public static function load(string $env = ''): array
    {
        $filename = static::settingsPath();
        if (false === $filename || !is_readable($filename)) {
            throw new \RuntimeException(
                'Failed to read app_settings.json, the file does not exist or is not readable.'
            );
        }

        $settings = static::read($filename);
        if (false == $env) {
            return $settings;
        }

        $envFilename = static::settingsPath($env);
        if (false === $envFilename || !is_readable($envFilename)) {
            throw new \InvalidArgumentException(sprintf(
                'Failed to load app_settings.%s.json, the file does not exist or is not readable.',
                $env
            ));
        }

        return array_merge_assoc_recursive(
            $settings,
            static::read($envFilename)
        );
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
     *
     * @throws \InvalidArgumentException if it failed to decode json
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
