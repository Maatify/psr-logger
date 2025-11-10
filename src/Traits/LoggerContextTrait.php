<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 08:32
 * Project: maatify:psr-logger
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Traits;

use Maatify\PsrLogger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Trait LoggerContextTrait
 *
 * Provides an easy and consistent way to initialize a PSR-3 compliant logger
 * within any class that uses this trait.
 *
 * Features:
 *  - Automatically creates a logger instance via {@see LoggerFactory::create()}.
 *  - Supports custom or auto-detected logging contexts.
 *  - Exposes `$this->logger` as a ready-to-use property implementing `LoggerInterface`.
 *
 * Typical use case:
 * ```php
 * class ExampleService
 * {
 *     use LoggerContextTrait;
 *
 *     public function __construct()
 *     {
 *         $this->initLogger(); // Auto context detection
 *     }
 *
 *     public function process(): void
 *     {
 *         $this->logger->info('Processing started.');
 *     }
 * }
 * ```
 *
 * @package Maatify\PsrLogger\Traits
 */
trait LoggerContextTrait
{
    /**
     * PSR-3 compliant logger instance for the current class context.
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Initialize logger with optional custom context name.
     *
     * If `$context` is not provided, the logger context is automatically
     * determined based on the calling class name (via {@see LoggerFactory::create()}).
     *
     * @param string|null $context Optional custom context for log file path or naming.
     *
     * @return void
     *
     * @example
     * ```php
     * $this->initLogger('services/payment');
     * $this->logger->error('Payment gateway timeout.');
     * ```
     */
    protected function initLogger(?string $context = null): void
    {
        $this->logger = LoggerFactory::create($context);
    }
}
