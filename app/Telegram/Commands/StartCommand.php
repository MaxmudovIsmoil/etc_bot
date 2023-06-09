<?php

namespace App\Telegram\Commands;

use App\Helpers\Helper;
use App\Traits\StepTrait;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;

class StartCommand extends Command
{

    protected string $command = 'start';

    protected ?string $description = 'A lovely description.';

    use StepTrait;

    public function handle(Nutgram $bot): void
    {
        $this->set_user_step($bot, 'start');

        $text = Helper::getText($bot)->start;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Подключить услугу'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);

    }


}
