![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)
---
[![Current Version](https://img.shields.io/packagist/v/maatify/psr-logger)](https://packagist.org/packages/maatify/psr-logger)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/psr-logger)](https://packagist.org/packages/maatify/psr-logger)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/psr-logger)](https://packagist.org/packages/maatify/psr-logger/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/psr-logger)](https://packagist.org/packages/maatify/psr-logger/stats)
[![License](https://img.shields.io/packagist/l/maatify/psr-logger)](https://github.com/MaatifyDev/maatify-psr-logger/blob/main/LICENSE)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/MaatifyDev/maatify-psr-logger/tests.yml?label=tests)](https://github.com/MaatifyDev/maatify-psr-logger/actions)
[![Code Quality](https://img.shields.io/codefactor/grade/github/MaatifyDev/maatify-psr-logger/main)](https://www.codefactor.io/repository/github/MaatifyDev/maatify-psr-logger)

# 🧾 maatify/psr-logger

A **PSR-3 compatible logger** powered by **Monolog**,
supporting **dynamic file naming**, **hourly log rotation**, and **project-aware path detection**.
Built for professional PHP projects that need organized, flexible, and standards-compliant logging.

---

### 🧱 Built on Monolog

This package is powered by [**Monolog v3**](https://github.com/Seldaek/monolog) —
the industry-standard logging library for PHP.
It extends Monolog with **automatic path detection**, **hourly rotation**, and **project-aware file structure**,
making it ideal for scalable and professional PHP applications.

---

## 📦 Installation

Install via Composer:

```bash
composer require maatify/psr-logger
```

---


### 📦 Dependencies

This library relies on:

| Dependency           | Purpose                                                    | Link                                                               |
|----------------------|------------------------------------------------------------|--------------------------------------------------------------------|
| **monolog/monolog**  | Core logging framework for PHP (v3)                        | [github.com/Seldaek/monolog](https://github.com/Seldaek/monolog)   |
| **psr/log**          | PSR-3 compliant interface for logging interoperability     | [php-fig.org/psr/psr-3](https://www.php-fig.org/psr/psr-3/)        |
| **vlucas/phpdotenv** | Environment variable management for dynamic log paths      | [github.com/vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) |
| **phpunit/phpunit**  | Unit testing framework (development only)                  | [phpunit.de](https://phpunit.de)                                   |

> 🧱 `maatify/psr-logger` builds upon these open-source libraries to provide a **unified**,
> **context-aware**, and **developer-friendly** logging experience across all Maatify packages.


---

## ⚙️ Features

- ✅ PSR-3 compatible (`Psr\Log\LoggerInterface`)
- ✅ Built on **Monolog v3**
- ✅ Smart file rotation by date/hour (`Y/m/d/H`)
- ✅ Dynamic file naming by class or custom context
- ✅ Works both standalone or inside any Composer project
- ✅ Optional log cleanup (manual or via cron)
- ✅ Zero configuration — auto-detects project root

---

## 🧩 Usage

### Basic Example

```php
use Maatify\PsrLogger\LoggerFactory;

$logger = LoggerFactory::create('services/payment');
$logger->info('Payment started', ['order_id' => 501]);
```

Resulting file:

```
storage/logs/2025/11/05/10/services/payment.log
```

---

### Auto Context Detection

Automatically uses the calling class name as the log context:

```php
use Maatify\PsrLogger\Traits\LoggerContextTrait;

class UserService
{
    use LoggerContextTrait;

    public function __construct()
    {
        $this->initLogger(); // auto: logs/UserService.log
        $this->logger->info('User service initialized');
    }
}
```

---

## 🧱 Folder Structure

```
maatify-psr-logger/
├── src/
│   ├── Config/
│   │   └── LoggerConfig.php
│   ├── Helpers/
│   │   └── PathHelper.php
│   ├── Rotation/
│   │   └── LogCleaner.php
│   ├── Traits/
│   │   └── LoggerContextTrait.php
│   └── LoggerFactory.php
├── scripts/
│   └── clean_logs.php
├── .env.example
├── composer.json
└── README.md
```

---

## 🧹 Log Cleanup (manual or cron)

You can run the provided script to delete old log files manually or via cron.

### 1. Manual Cleanup

```bash
php vendor/maatify/psr-logger/scripts/clean_logs.php
```

### 2. Composer Shortcut

Add to your project’s `composer.json`:

```composer
"scripts": {
  "logs:clean": "php vendor/maatify/psr-logger/scripts/clean_logs.php"
}
```

Then run:

```bash
composer logs:clean
```

### 3. Cron Job Example

```bash
0 3 * * * php /var/www/project/vendor/maatify/psr-logger/scripts/clean_logs.php >> /var/log/log_cleanup.log 2>&1
```

---

## ⚙️ Environment Variables

| Variable             | Default        | Description                                   |
|----------------------|----------------|-----------------------------------------------|
| `LOG_PATH`           | `storage/logs` | Base directory for log files                  |
| `LOG_RETENTION_DAYS` | `14`           | Number of days to keep logs (used by cleanup) |

Example `.env` file:

```bash
LOG_PATH=storage/logs
LOG_RETENTION_DAYS=14
```
### 🌿 Optional: Environment Initialization

This package ships with [**vlucas/phpdotenv**](https://github.com/vlucas/phpdotenv)  
to simplify environment configuration management.

> The logger itself **does not automatically load** your `.env` file.
> You should initialize it at the project level before using the logger.

Example:

```php
use Dotenv\Dotenv;
use Maatify\PsrLogger\LoggerFactory;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$logger = LoggerFactory::create('bootstrap/test');
$logger->info('Logger initialized successfully!');
```
---

## 🧭 Project Root Detection

If the library is installed inside:

```
vendor/maatify/psr-logger
```

it automatically goes **3 levels up** to the project root.
If running standalone (during development), it uses its own root directory.

---

## 🔌 Integration Examples

### 🪶 Slim Framework

```php
use Maatify\PsrLogger\LoggerFactory;

$container->set('logger', function() {
    return LoggerFactory::create('slim/app');
});

// Example usage inside a route:
$app->get('/ping', function ($request, $response) {
    $this->get('logger')->info('Ping request received');
    return $response->write('pong');
});
```

---

### 🧱 Laravel

In `app/Providers/AppServiceProvider.php`:

```php
use Maatify\PsrLogger\LoggerFactory;

public function register()
{
    $this->app->singleton('maatify.logger', function () {
        return LoggerFactory::create('laravel/app');
    });
}
```

Usage anywhere:

```php
app('maatify.logger')->info('Laravel integration working!');
```

---

### ⚙️ Native PHP Project

```php
require __DIR__ . '/vendor/autoload.php';

use Maatify\PsrLogger\LoggerFactory;

$logger = LoggerFactory::create('custom/project');
$logger->error('Something went wrong', ['code' => 500]);
```

---

## 📚 Built Upon

`maatify/psr-logger` proudly builds upon several mature and industry-standard open-source foundations:

| Library                                                     | Description                                | Usage in Project                                                                              |
| ----------------------------------------------------------- | ------------------------------------------ | --------------------------------------------------------------------------------------------- |
| **[monolog/monolog](https://github.com/Seldaek/monolog)**   | Industry-standard PHP logging library (v3) | Core logging engine used for structured logging, channel management, and handler pipeline.    |
| **[psr/log](https://www.php-fig.org/psr/psr-3/)**           | PSR-3 logging interface                    | Defines the standardized logger interface used by all Maatify components.                     |
| **[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)** | Environment configuration manager          | Loads and manages environment variables for dynamic log paths and environment detection.      |
| **[maatify/common](https://github.com/Maatify/common)**     | Shared helper utilities and path resolvers | Provides helper classes for file path normalization, directory creation, and text formatting. |
| **[phpunit/phpunit](https://phpunit.de)**                   | PHP unit testing framework                 | Powers the automated test suite and CI/CD validation through GitHub Actions.                  |

> Huge thanks to the open-source community for their continued contributions,
> enabling Maatify to build reliable, scalable, and developer-friendly PHP infrastructure. ❤️

---

## 🧾 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

You’re free to use, modify, and distribute this library with attribution.


---
