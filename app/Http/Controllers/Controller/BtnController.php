<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\StepTrait;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class BtnController extends Controller
{

    use StepTrait;


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

    }



    public function location(Nutgram $bot)
    {
        $location = $bot->message()->location;
        $latitude = $location->latitude;
        $longitude = $location->longitude;

        $bot->sendLocation($latitude, $longitude, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);

        $bot->sendMessage("<code>" . json_encode( $bot->message()->location) . "</code>", [
            'parse_mode' => "HTML"
        ]);
    }


}
