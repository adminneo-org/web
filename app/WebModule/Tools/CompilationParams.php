<?php

declare(strict_types=1);

namespace WebModule\Tools;

use Nette\Utils\Strings;

class CompilationParams
{
    private static array $themeVariants = [];

    public const Projects = [
        "admin" => "AdminNeo",
        "editor" => "EditorNeo",
    ];

    public const Versions = [
        "5.0.0-rc" => "5.0.0-rc",
    ];

    public const Drivers = [
        "mysql" => "MySQL",
        "pgsql" => "PostgreSQL",
        "mssql" => "MS SQL",
        "sqlite" => "SQLite",
        "oracle" => "Oracle",
        "mongo" => "MongoDB",
        "simpledb" => "SimpleDB",
        "elastic" => "Elasticsearch (beta)",
        "clickhouse" => "ClickHouse (alpha)",
    ];

    public const Languages = [
        'ar' => 'Arabic',
        'bg' => 'Bulgarian',
        'bn' => 'Bengali',
        'bs' => 'Bosnian',
        'ca' => 'Catalan',
        'cs' => 'Czech',
        'da' => 'Danish',
        'de' => 'German',
        'el' => 'Greek',
        'en' => 'English',
        'es' => 'Spanish',
        'et' => 'Estonian',
        'fa' => 'Persian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'gl' => 'Galician',
        'he' => 'Hebrew',
        'hi' => 'Hindi',
        'hu' => 'Hungarian',
        'id' => 'Indonesian',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'ka' => 'Georgian',
        'ko' => 'Korean',
        'lt' => 'Lithuanian',
        'lv' => 'Latvian',
        'ms' => 'Malay',
        'nl' => 'Dutch',
        'no' => 'Norwegian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'pt-BR' => 'Portuguese (Brazil)',
        'ro' => 'Romanian',
        'ru' => 'Russian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'sr' => 'Serbian',
        'sv' => 'Swedish',
        'ta' => 'Tamil',
        'th' => 'Thai',
        'tr' => 'Turkish',
        'uk' => 'Ukrainian',
        'vi' => 'Vietnamese',
        'zh' => 'Chinese',
        'zh-TW' => 'Chinese (Taiwan)',
    ];

    public const Themes = [
        "default" => "Default",
    ];

    public const ColorVariants = [
        "blue",
        "green",
        "red",
    ];

    public static function getThemeVariants(): array
    {
        if (!self::$themeVariants) {
            foreach (CompilationParams::Themes as $theme => $name) {
                self::$themeVariants[$theme] = "$name (all colors)";

                foreach (CompilationParams::ColorVariants as $color) {
                    self::$themeVariants["$theme-$color"] = "$name • $color";
                }
            }
        }

        return self::$themeVariants;
    }

    public static function normalizeThemes(array $themes): array
    {
        $themeMap = [];
        $colorPattern = "(" . implode("|", self::ColorVariants) . ")";

        // Collect themes names without a color variant.
        foreach ($themes as $theme) {
            if (!Strings::match($theme, "~-$colorPattern\$~")) {
                $themeMap[$theme] = true;
            }
        }

        // Add themes with a color variant.
        foreach ($themes as $theme) {
            if ($matches = Strings::match($theme, "~^(.+)-$colorPattern\$~")) {
                if (!isset($themeMap[$matches[1]])) {
                    $themeMap[$theme] = true;
                }
            }
        }

        return array_keys($themeMap);
    }
}
