<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Service;

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\MessageDispatcher as MessageDispatcherContract;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\Contracts\Validator;
use Hoangkhacphuc\ZaloBot\Validators\WebhookValidator;

class WebhookServiceFactory
{
    public static function create(BotConfig $botConfig, Repository $repository, ?Validator $validator = null, ?MessageDispatcherContract $dispatcher = null): WebhookService
    {
        $validatorInstance = $validator ?? new WebhookValidator;
        $dispatcherInstance = $dispatcher ?? new MessageDispatcher;

        return new WebhookService($botConfig, $repository, $validatorInstance, $dispatcherInstance);
    }
}
