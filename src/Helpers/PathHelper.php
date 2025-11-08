<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 08:29
 * Project: maatify:psr-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Helpers;

/**
 * Class PathHelper
 *
 * Utility class responsible for generating dynamic, date-structured log file paths.
 * Follows the pattern:
 *     /Y/m/d/H/<context>.log
 *
 * Features:
 *  - Automatically creates missing directories.
 *  - Uses base path from environment variable `LOG_PATH` (falls back to `/storage/logs`).
 *  - Sanitizes context names to prevent invalid or unsafe characters.
 *
 * Example output:
 * ```
 * /var/www/project/storage/logs/2025/11/05/08/repositories/customers.log
 * ```
 *
 * @package Maatify\PsrLogger\Helpers
 */
final class PathHelper
{
    /**
     * Build a fully qualified log file path organized by date/hour.
     *
     * The structure is: `/Y/m/d/H/<context>.log`
     * Example: `/storage/logs/2025/11/05/08/app.log`
     *
     * @param string $context  Log context name (e.g., "app", "auth/login", "repositories/customer").
     *                         Non-alphanumeric characters are automatically sanitized.
     *
     * @return string  Absolute path to the generated log file.
     */
    public static function buildPath(string $context = 'app'): string
    {
        // Base logs directory (from .env or default path)
        $basePath = getenv('LOG_PATH') ? : ($_ENV['LOG_PATH'] ?? __DIR__ . '/../../../storage/logs');

        // Time-based subdirectory (Y/m/d/H)
        $datePath = date('Y/m/d/H');

        // Sanitize context (allow only letters, numbers, underscores, dashes, slashes)
        $context = strtolower(trim(preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $context)));

        // Construct full path
        $fullPath = rtrim($basePath, '/') . '/' . $datePath . '/' . $context . '.log';

        // Ensure directory exists
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        return $fullPath;
    }
}
