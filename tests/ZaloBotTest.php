<?php

declare(strict_types=1);

use Hoangkhacphuc\ZaloBot\Contracts\EndpointResolver;
use Hoangkhacphuc\ZaloBot\Contracts\HttpClient;
use Hoangkhacphuc\ZaloBot\DTO\InfoMe;
use Hoangkhacphuc\ZaloBot\Enums\ZaloBotMethod;
use Hoangkhacphuc\ZaloBot\ZaloBot;

class FakeHttpClient implements HttpClient
{
    public array $requests = [];

    public function __construct(private array $responses) {}

    public function post(string $url, array $data = [], array $headers = []): array
    {
        $this->requests[] = compact('url', 'data', 'headers');

        return array_shift($this->responses) ?? ['ok' => true, 'result' => []];
    }
}

class FakeResolver implements EndpointResolver
{
    public function resolve(string $accessToken, ZaloBotMethod $method): string
    {
        return "https://example.test/bot{$accessToken}/{$method->value}";
    }
}

it('gets bot info successfully', function () {
    $client = new FakeHttpClient([
        ['ok' => true, 'result' => ['id' => '123', 'name' => 'Bot']],
    ]);
    $bot = new ZaloBot('TOKEN', $client, new FakeResolver);

    $info = $bot->getMe();

    expect($info)->toBeInstanceOf(InfoMe::class);
});
