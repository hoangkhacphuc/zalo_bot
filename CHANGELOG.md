# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2025-01-08

### Added
- Core ZaloBot SDK with SOLID architecture
- Type-safe DTOs: `Message`, `Webhook`, `InfoMe`, `MessageResponse`
- Interfaces: `HttpClient`, `MessageDispatcher`, `EndpointResolver`, `ZaloBotInterface`
- Default implementations: `Support/HttpClient`, `Service/MessageDispatcher`, `Support/DefaultEndpointResolver`
- Factories: `Support/ZaloBotFactory`, `Service/WebhookServiceFactory` for easy wiring
- Message handlers: `TextMessage`, `ImageMessage`, `StickerMessage` base classes
- Webhook validation and processing via `WebhookService`
- Rich exception hierarchy for robust error handling
- Laravel integration: Service Provider, config publishing, auto-discovery
- Comprehensive test suite with Pest
- CI/CD: GitHub Actions (tests, PHPStan, Pint)
- Documentation: README, examples, migration guide

### Features
- Send messages: text, photos, stickers, chat actions
- Webhook handling with message dispatcher pattern
- Framework-agnostic design (works in plain PHP or Laravel)
- Dependency injection for testability
- Strict typing with PHP 8.1+

### Dependencies
- PHP 8.1+
- Guzzle HTTP client
- tekvn/php-enum

[1.0.0]: https://github.com/hoangkhacphuc/zalo_bot/releases/tag/v1.0.0
