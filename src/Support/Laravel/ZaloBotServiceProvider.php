<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Support\Laravel;

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\EndpointResolver;
use Hoangkhacphuc\ZaloBot\Contracts\HttpClient as HttpClientContract;
use Hoangkhacphuc\ZaloBot\Contracts\MessageDispatcher as MessageDispatcherContract;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\Contracts\Validator;
use Hoangkhacphuc\ZaloBot\Service\MessageDispatcher;
use Hoangkhacphuc\ZaloBot\Service\WebhookService;
use Hoangkhacphuc\ZaloBot\Support\DefaultEndpointResolver;
use Hoangkhacphuc\ZaloBot\Support\HttpClient;
use Illuminate\Support\ServiceProvider;

class ZaloBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/zalobot.php', 'zalobot');

        $this->app->singleton(HttpClientContract::class, HttpClient::class);
        $this->app->singleton(EndpointResolver::class, DefaultEndpointResolver::class);
        $this->app->singleton(MessageDispatcherContract::class, MessageDispatcher::class);

        $this->app->singleton(BotConfig::class, function ($app) {
            $cfg = $app['config']['zalobot'];

            return new BotConfig(
                $cfg['access_token'] ?? '',
                $cfg['webhook_url'] ?? '',
                $cfg['webhook_secret'] ?? ''
            );
        });

        $this->app->bind(WebhookService::class, function ($app) {
            return new WebhookService(
                $app->make(BotConfig::class),
                $app->make(Repository::class),
                $app->make(Validator::class),
                $app->make(MessageDispatcherContract::class)
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../../config/zalobot.php' => config_path('zalobot.php'),
        ], 'zalobot-config');
    }
}
