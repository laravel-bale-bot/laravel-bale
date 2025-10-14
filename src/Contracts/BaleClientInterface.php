<?php

namespace Khody2012\LaravelBale\Contracts;

interface BaleClientInterface
{
    // --- Basic API ---
    public function getMe(): array;
    public function sendMessage(int|string $chatId, string $text, array $options = []): array;

    // --- Media API ---
    public function sendPhoto(int|string $chatId, string $photo, string $caption = '', array $options = []): array;
    public function sendDocument(int|string $chatId, string $document, string $caption = '', array $options = []): array;
    public function sendVoice(int|string $chatId, string $voice, string $caption = '', array $options = []): array;

    // --- Webhook Management ---
    public function setWebhook(string $url, array $options = []): array;
    public function deleteWebhook(): array;
    public function getUpdates(array $params = []): array;

    // --- Raw API Access ---
    public function api(string $method, array $params = []): array;
}
