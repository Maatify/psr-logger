# 🧾 CHANGELOG — Maatify PSR-Logger

All notable changes to this project will be documented in this file.
This project follows **[Semantic Versioning (SemVer)](https://semver.org/)**.

---

## 🏁 v1.0.1 — 2025-11-10

### ⚙️ Enhancement: Return LoggerInterface from initLogger() for Direct Use

#### ✨ Added

* `LoggerContextTrait::initLogger()` now returns the created `LoggerInterface` instance.
* Allows direct inline usage:

  ```php
  $logger = $this->initLogger('services/payment');
  $logger->debug('Inline logger usage');
  ```

#### 🔧 Improved

* Enhanced `LoggerContextTrait` documentation and examples.
* Fully backward compatible with all previous usage patterns.
* Verified compatibility with PHP 8.4 and Maatify Common v1.0.1.

---

## 🚀 v1.0.0 — 2025-11-09

### 🧩 Initial Stable Release

#### ✅ Core Features

* PSR-3 compliant logging foundation built on **Monolog**.
* `LoggerFactory` for unified contextual logger creation.
* `LoggerContextTrait` for auto-injected class-based logging.
* Hierarchical file structure:

  ```
  storage/logs/YYYY/MM/DD/HH/context.log
  ```
* Supports context-based names (e.g., `api/auth`, `services/payment`).
* Fully tested on PHP 8.4.

#### 🧪 QA & Testing

* 100% PHPUnit coverage across all logger factory and trait components.
* Verified cross-library integration with `maatify/common` and `maatify/bootstrap`.

---

**MIT License** © [Maatify.dev](https://www.maatify.dev)
Maintained by **Mohamed Abdulalim (@megyptm)**

---