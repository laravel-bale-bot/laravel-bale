# Laravel Bale Bot Package 🤖

A robust and developer-friendly Laravel package for building and managing bots on **[Bale Messenger](https://bale.ai)**.  
This package provides a **modern**, **extendable**, and **dynamic** integration layer with the Bale Bot API, making it easy to send messages, handle updates, manage callbacks, and automate complex bot workflows — all within your Laravel application.

---

## 📑 Table of Contents
- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
  - [Sending Messages](#-sending-messages)
  - [Sending Files and Media](#-sending-photos-files-and-other-media)
  - [Handling Incoming Messages](#-replying-to-incoming-messages)
  - [Handling Commands](#-handling-commands)
  - [Inline Keyboards](#-inline-keyboard-and-buttons)
  - [Polling Mode](#-polling-alternative-to-webhook)
  - [Working with Queues](#-working-with-queues)
  - [Advanced Example](#-advanced-example-auto-responder-bot)
  - [Raw API Access](#-accessing-raw-api-methods)
  - [Custom Webhook Controller](#-custom-webhook-controller-optional)
- [Summary](#-summary)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

- 🧠 Clean and expressive API for Bale Bot  
- ⚙️ Works seamlessly with **Laravel 10+**  
- 🌐 Supports both **Webhook** and **Long Polling**  
- ⚡ Asynchronous message handling via Laravel queues  
- 🔔 Built-in event system for updates, callbacks, and media  
- 🧩 Extensible architecture for advanced bot customization  
- 📦 Follows PSR-12 and SOLID design principles  
- 🔒 Secure and production-ready  

---

## 🧰 Requirements

- PHP 8.1 or higher  
- Laravel 10 or newer  
- cURL or Guzzle HTTP client  
- Bale Bot Token (from [Bale BotFather](https://docs.bale.ai/#/))

---

## ⚙️ Installation

Install the package via Composer:

```bash
composer require laravel-bale-bot/laravel-bale
```

Then publish the configuration file:

```bash
php artisan vendor:publish --tag=bale-config
```

This will create a `config/bale.php` file in your project.

---

## 🧩 Configuration

Edit the configuration file `config/bale.php`:

```php
return [
    'token' => env('BALE_BOT_TOKEN', ''),
    'webhook_url' => env('BALE_WEBHOOK_URL', ''),
    'use_queue' => env('BALE_USE_QUEUE', true),
    'poll_interval' => env('BALE_POLL_INTERVAL', 2),
];
```

Add the following lines to your `.env` file:

```env
BALE_BOT_TOKEN=your-bale-bot-token-here
BALE_WEBHOOK_URL=https://yourdomain.com/bale/webhook
BALE_USE_QUEUE=true
```

Register your webhook with:

```bash
php artisan bale:set-webhook
```

To remove the webhook and use polling mode instead:

```bash
php artisan bale:delete-webhook
```

---

## 💡 Usage

The Laravel Bale Bot package aims to provide a **fluent and flexible** API for building bots.  
You can use it via **dependency injection**, the **Bale facade**, or directly with the **service container**.

---

### 📨 Sending Messages

You can send text messages via the injected client or facade.

**Using dependency injection:**

```php
use LaravelBaleBot\LaravelBale\LaravelBale\Contracts\BaleClientInterface;

class NotificationService
{
    public function __construct(protected BaleClientInterface $bale) {}

    public function notify($chatId, $text)
    {
        $this->bale->sendMessage($chatId, $text);
    }
}
```

**Using the facade:**

```php
use LaravelBaleBot\LaravelBale\LaravelBale\Facades\Bale;

Bale::sendMessage(12345678, 'Hello Bale!');
```

**With optional parameters:**

```php
Bale::sendMessage(12345678, 'Click below 👇', [
    'reply_markup' => [
        'keyboard' => [['Yes', 'No']],
        'resize_keyboard' => true,
        'one_time_keyboard' => true,
    ]
]);
```

---

### 📎 Sending Photos, Files, and Other Media

Send photos, documents, or voice messages easily:

```php
Bale::sendPhoto($chatId, storage_path('app/public/welcome.jpg'), 'Welcome!');
Bale::sendDocument($chatId, storage_path('app/docs/guide.pdf'), 'User Guide');
Bale::sendVoice($chatId, storage_path('app/audio/hello.ogg'), 'Voice message');
```

The package handles file uploads and Bale API formatting automatically.

---

### 🔁 Replying to Incoming Messages

Incoming messages trigger a `MessageReceived` event.

Example listener:

```php
use LaravelBaleBot\LaravelBale\LaravelBale\Events\MessageReceived;
use LaravelBaleBot\LaravelBale\LaravelBale\Facades\Bale;

class RespondToUser
{
    public function handle(MessageReceived $event)
    {
        $chatId = $event->message['chat']['id'];
        $text = strtolower($event->message['text'] ?? '');

        match ($text) {
            'hello' => Bale::sendMessage($chatId, 'Hi there 👋'),
            'help' => Bale::sendMessage($chatId, 'How can I help you today?'),
            default => Bale::sendMessage($chatId, 'Sorry, I did not understand that.')
        };
    }
}
```

---

### ⚙️ Handling Commands

For handling commands like `/start`, `/about`, etc.:

```php
if (str_starts_with($text, '/start')) {
    Bale::sendMessage($chatId, "Welcome to the Bale Bot 🎉");
} elseif (str_starts_with($text, '/about')) {
    Bale::sendMessage($chatId, "This bot is powered by Laravel Bale.");
}
```

You can later register these commands in a `CommandRouter` for a cleaner structure.

---

### 🧩 Inline Keyboard and Buttons

Create interactive inline keyboards:

```php
$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => 'Visit Website 🌐', 'url' => 'https://example.com'],
            ['text' => 'Support 💬', 'callback_data' => 'support']
        ]
    ]
];

Bale::sendMessage($chatId, 'Choose an option:', ['reply_markup' => $keyboard]);
```

**Handle callbacks:**

```php
use LaravelBaleBot\LaravelBale\LaravelBale\Events\CallbackQueryReceived;

class HandleCallback
{
    public function handle(CallbackQueryReceived $event)
    {
        $data = $event->callbackQuery['data'];
        $chatId = $event->callbackQuery['message']['chat']['id'];

        if ($data === 'support') {
            Bale::sendMessage($chatId, 'Please describe your issue. Our support team will contact you soon.');
        }
    }
}
```

---

### 🔄 Polling (Alternative to Webhook)

If you don’t want to use webhooks, use polling:

```bash
php artisan bale:poll-updates
```

You can change the polling interval in `config/bale.php`.

---

### 🧵 Working with Queues

Each update is automatically dispatched to a queued job (`HandleUpdateJob`).  
Run the queue worker:

```bash
php artisan queue:work
```

You can modify the queue settings in your `.env` or `config/queue.php`.

---

### 🧠 Advanced Example: Auto Responder Bot

A simple auto-responder bot example:

```php
namespace App\Listeners;

use LaravelBaleBot\LaravelBale\LaravelBale\Events\MessageReceived;
use LaravelBaleBot\LaravelBale\LaravelBale\Facades\Bale;

class AutoResponder
{
    public function handle(MessageReceived $event)
    {
        $chatId = $event->message['chat']['id'];
        $text = trim(strtolower($event->message['text'] ?? ''));

        $responses = [
            'hi' => 'Hello! 👋',
            'how are you' => 'I’m doing great, thanks for asking!',
            'bye' => 'Goodbye! See you soon 👋',
        ];

        $reply = $responses[$text] ?? "I didn’t quite catch that. Type 'help' for available commands.";

        Bale::sendMessage($chatId, $reply);
    }
}
```

---

### 🧰 Accessing Raw API Methods

For full API control, use the `api()` method:

```php
$response = Bale::api('getChat', ['chat_id' => 12345678]);
```

Or through the injected HTTP client:

```php
$bale->call('getChat', ['chat_id' => 12345678]);
```

---

### 🪄 Custom Webhook Controller (Optional)

Create your own webhook controller if you prefer direct handling:

```php
use LaravelBaleBot\LaravelBale\LaravelBale\Http\Controllers\BaleWebhookController;

class CustomWebhookController extends BaleWebhookController
{
    public function handleUpdate(array $update)
    {
        Log::info('Received update:', $update);
        return response()->json(['ok' => true]);
    }
}
```

And register it in `routes/web.php`:

```php
Route::post('/bale/custom-webhook', [CustomWebhookController::class, 'handle']);
```

---

## 📋 Summary

| Action | Example |
|---------|----------|
| Send text | `Bale::sendMessage($chatId, 'Hello')` |
| Send photo | `Bale::sendPhoto($chatId, $path, 'Caption')` |
| Send document | `Bale::sendDocument($chatId, $filePath)` |
| Handle messages | `MessageReceived` event |
| Handle callbacks | `CallbackQueryReceived` event |
| Set webhook | `php artisan bale:set-webhook` |
| Poll updates | `php artisan bale:poll-updates` |
| Use queue | `php artisan queue:work` |

---

## 🤝 Contributing

We welcome all contributions!  
To contribute, please:

1. Fork the repository  
2. Create a new branch for your feature or bugfix  
3. Write clean, tested, and well-documented code  
4. Submit a Pull Request  

Make sure your code follows **PSR-12**, uses **type hints**, and includes **unit tests** when possible.

---

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

**Developed with ❤️ for the Laravel Community — making bot development faster, cleaner, and more enjoyable.**
