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

## 📦 Installation

Install via Composer:

```bash
composer require maatify/psr-logger
```

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

## 🧾 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

You’re free to use, modify, and distribute this library with attribution.


---
