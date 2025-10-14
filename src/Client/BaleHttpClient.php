<?php
namespace LaravelBaleBot\LaravelBale\LaravelBale\Client;

use LaravelBaleBot\LaravelBale\LaravelBale\Contracts\BaleClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class BaleHttpClient implements BaleClientInterface
{
    protected string $token;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct(array $config = [])
    {
        $this->token = $config['token'] ?? config('bale.token');
        $this->baseUrl = rtrim($config['base_url'] ?? config('bale.base_url', 'https://tapi.bale.ai'), '/');
        $this->timeout = $config['timeout'] ?? config('bale.timeout', 10);
    }

    protected function url(string $method): string
    {
        return "{$this->baseUrl}/bot{$this->token}/{$method}";
    }

    protected function request(string $method, array $payload = [], bool $isMultipart = false): array
    {
        $http = Http::timeout($this->timeout)->acceptJson();

        $response = $isMultipart
            ? $http->asMultipart()->post($this->url($method), $payload)
            : $http->post($this->url($method), $payload);

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array
    {
        if (! $response->successful()) {
            Log::warning('Bale API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'ok' => false,
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }

    // ---- Basic API ----

    public function getMe(): array
    {
        return $this->request('getMe');
    }

    public function sendMessage(int|string $chatId, string $text, array $options = []): array
    {
        $payload = array_merge(['chat_id' => $chatId, 'text' => $text], $options);
        return $this->request('sendMessage', $payload);
    }

    // ---- Media API ----

    public function sendPhoto(int|string $chatId, string $photo, string $caption = '', array $options = []): array
    {
        $payload = array_merge(['chat_id' => $chatId, 'caption' => $caption], $options);
        $payload['photo'] = fopen($photo, 'r');
        return $this->request('sendPhoto', $payload, true);
    }

    public function sendDocument(int|string $chatId, string $document, string $caption = '', array $options = []): array
    {
        $payload = array_merge(['chat_id' => $chatId, 'caption' => $caption], $options);
        $payload['document'] = fopen($document, 'r');
        return $this->request('sendDocument', $payload, true);
    }

    public function sendVoice(int|string $chatId, string $voice, string $caption = '', array $options = []): array
    {
        $payload = array_merge(['chat_id' => $chatId, 'caption' => $caption], $options);
        $payload['voice'] = fopen($voice, 'r');
        return $this->request('sendVoice', $payload, true);
    }

    // ---- Webhook Management ----

    public function setWebhook(string $url, array $options = []): array
    {
        $payload = array_merge(['url' => $url], $options);
        return $this->request('setWebhook', $payload);
    }

    public function deleteWebhook(): array
    {
        return $this->request('deleteWebhook');
    }

    public function getUpdates(array $params = []): array
    {
        return $this->request('getUpdates', $params);
    }

    // ---- Raw API Access ----

    public function api(string $method, array $params = []): array
    {
        return $this->request($method, $params);
    }

    public function call(string $method, array $params = []): array
    {
        return $this->api($method, $params);
    }
}
