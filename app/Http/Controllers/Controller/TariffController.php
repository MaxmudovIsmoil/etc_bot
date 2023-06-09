<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\CacheTrait;
use App\Traits\StepTrait;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class TariffController extends Controller
{

    use StepTrait, CacheTrait;

    protected $api;

    public function __construct()
    {
        $this->api = new ApiController();
    }

    public function tariff(Nutgram $bot)
    {
        $this->set_user_step($bot, 'tariff_login');

        $text = Helper::getText()->tariff_login;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);
    }


    public function tariff_list(Nutgram $bot)
    {
        $user_step = $this->get_user_step($bot);

        switch ($user_step) {
            case 'tariff_login':
            {
                $login = $this->api->check_login($bot->message()->text);

                if (!$login['status'])
                {
                    $bot->sendMessage($login['error_text']);
                    break;
                }

                Cache::put($bot->userId(), ['login' => $bot->message()->text]);

                $text = Helper::getText()->tariff_password;
                $bot->sendMessage($text, [
                    'parse_mode' => ParseMode::HTML
                ]);

                $this->set_user_step($bot, 'tariff_password');

                break;
            }
            case 'tariff_password':
            {
                $login = Cache::get($bot->userId())['login'];
                $password = $bot->message()->text;

                $login = $this->api->login($login, $password);

                if ($login['status'])
                {
                    $this->show_balance($bot, $login['rate']);

                    $this->cache_push($bot, ['password' => $password]);
                    $this->cache_push($bot, ['subject_id' => $login['subject_id']]);
                    $this->cache_push($bot, ['rate' => $login['rate']]);

                    $this->set_user_step($bot, 'tariff_done');
                    break;
                }

                $this->set_user_step($bot, 'tariff_login');


                $bot->sendMessage($login['error_text'], [
                    'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                        ->addRow(
                            KeyboardButton::make('⬅️ Назад')
                        ),
                    'parse_mode' => ParseMode::HTML
                ]);
                break;
            }

        }
    }


    public function show_balance(Nutgram $bot, $balance)
    {
        $balance = number_format($balance, 2,'.', ' ');
        $text = Helper::getText()->show_balance . " " . $balance . " сум";

        $bot->sendMessage($text, [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('ℹ️ Показать информацию', callback_data: 'show_info')
                ),
            'parse_mode' => ParseMode::HTML,
        ]);
    }


    public function show_info(Nutgram $bot)
    {
        $login = Cache::get($bot->userId())['login'];
        $password = Cache::get($bot->userId())['password'];
        $subject_id = Cache::get($bot->userId())['subject_id'];

        $info = $this->api->info($login, $password, $subject_id);

        $text = "<b>🗓 ". Helper::getText()->tariff_info_title."</b>\n"
            . Helper::getText()->tariff_full_name . $info['full_name'] . "\n"
            . Helper::getText()->tariff_address . $info['address'] . "\n"
            . Helper::getText()->tariff_code . $info['tariff_code']."\n"
            . Helper::getText()->show_balance . Cache::get($bot->userId())['rate']." сум\n"
            . Helper::getText()->tariff_status . $info['tariff_status'];

        $bot->editMessageText($text, [
            'parse_mode' => ParseMode::HTML,
        ]);

        $bot->sendMessage(Helper::getText()->tariff_done_text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Подключить услугу'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);

        $this->set_user_step($bot, 'back');


        $this->cache_clear($bot);

        // inline btn click, clock icon hide
        $bot->answerCallbackQuery();

    }


}
