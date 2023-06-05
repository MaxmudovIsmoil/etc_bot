<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\StepTrait;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class TariffController extends Controller
{

    use StepTrait;

    public function tariff(Nutgram $bot)
    {
        $this->set_user_step($bot, 'tariff');

        $text = Helper::getText()->tariff;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);

    }





}
