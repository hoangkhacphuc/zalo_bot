# Hoangkhacphuc/ZaloBot

A PHP library for building Zalo chatbots.

This library works in plain PHP, Laravel, or any PHP framework. It provides a set of Data Transfer Objects (DTOs),
exceptions, and helper methods to interact with the Zalo API and handle messages safely.

## Features

- Handle Zalo messages and events.
- Convert Zalo API responses into DTOs (Message, Webhook, etc.).
- Strict typing support with PHP 8+ (declare(strict_types=1)).
- Compatible with PHP frameworks such as Laravel.
- Provides custom exceptions for invalid requests, missing configuration, or incorrect data.

# Why Use This Library?
- Framework-independent – works in any PHP environment.
- Type-safe – DTOs ensure that all data conforms to expected types.
- Easy to test – you can inject request data instead of relying on $_SERVER or php://input.
- Extensible – you can add new DTOs, events, or exceptions as your project grows.
- Ready for production – designed to handle real Zalo API events safely and reliably.

## Requirements

- PHP 8.1 or higher
- Composer
- Optional: Laravel 9+ for Laravel integration

## Installation

Install via Composer:

```bash
composer require hoangkhacphuc/zalo_bot
```

## Usage in Plain PHP

```php
<?php
require_once 'vendor/autoload.php';

use Hoangkhacphuc\ZaloBot\ZaloBot;
use Hoangkhacphuc\ZaloBot\WebhookHandler;

$bot = new ZaloBot('<zalo-bot-access-token>');
$handler = new WebhookHandler('<webhook-url>', '<webhook-secret>');
$message = $handler->getMessage();
echo "Received message: " . $message->content;
```

## License
MIT License. See the [LICENSE](LICENSE) file for details.