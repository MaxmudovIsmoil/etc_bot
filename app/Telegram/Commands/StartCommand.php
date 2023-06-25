<?php

namespace App\Telegram\Commands;

use App\Helpers\Helper;
use App\Models\Client;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

class StartCommand extends Command
{

    protected string $command = 'start';

    protected ?string $description = 'A lovely description.';


    public function handle(Nutgram $bot): void
    {
        $text = Helper::getText($bot)->start;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('🧾 Тариф'),
                    KeyboardButton::make('📝 Подключить услугу'),
                ),
            'parse_mode' => ParseMode::HTML,
        ]);


        Client::insertOrIgnore([
            [
                'chat_id' => $bot->chatId(),
                'full_name' => $bot->user()->first_name . " " . $bot->user()->last_name,
                'username' => $bot->message()->chat->username,
            ]
        ]);
    }


}
