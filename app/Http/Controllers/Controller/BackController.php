<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\CacheTrait;
use App\Traits\StepTrait;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class BackController extends Controller
{

    use StepTrait, CacheTrait;

    public function back(Nutgram $bot)
    {
        $this->set_user_step($bot, 'back');

        $text = Helper::getText()->back;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Новая заявка'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);

        $this->cache_clear($bot);

    }



}
