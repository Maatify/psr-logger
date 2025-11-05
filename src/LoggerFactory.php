<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 08:31
 * Project: maatify:psr-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\PsrLogger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Maatify\PsrLogger\Helpers\PathHelper;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerFactory
 *
 * Responsible for creating PSR-3 compliant Monolog loggers
 * with automatic context-based naming and hourly file rotation.
 *
 * Features:
 *  - Creates a logger instance using Monolog.
 *  - Builds dynamic log paths via {@see PathHelper::buildPath()}.
 *  - Automatically derives the context name from the caller class if not provided.
 *  - Ensures logs are organized in `/Y/m/d/H/context.log` directories.
 *
 * Example output:
 * ```
 * /storage/logs/2025/11/05/08/App/Repository/OffersRepository.log
 * ```
 *
 * @package Maatify\PsrLogger
 */
final class LoggerFactory
{
    /**
     * Create a Monolog-based PSR Logger instance.
     *
     * If `$context` is not explicitly provided, the factory automatically
     * detects the calling class name via `debug_backtrace()` and uses it
     * as the logging context (converted to path-friendly format).
     *
     * @param string|null $context  Optional context name or namespace.
     *                              If null, caller class is detected automatically.
     *
     * @return LoggerInterface  PSR-3 compliant logger instance ready for use.
     *
     * @example
     * ```php
     * $logger = LoggerFactory::create('repositories/customer');
     * $logger->info('Customer login successful');
     * ```
     *
     * @example
     * ```php
     * // Automatic context detection:
     * $logger = LoggerFactory::create();
     * $logger->error('Unexpected database failure');
     * ```
     */
    public static function create(?string $context = null): LoggerInterface
    {
        // Auto-detect class name if not provided
        if ($context === null) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = $trace[1]['class'] ?? 'app';
            $context = str_replace('\\', '/', $caller);
        }

        // Build full path for hourly log rotation
        $path = PathHelper::buildPath($context);

        // Initialize Monolog logger with StreamHandler
        $logger = new Logger($context);
        $logger->pushHandler(new StreamHandler($path, Logger::DEBUG));

        return $logger;
    }
}
