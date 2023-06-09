<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Models\Order;
use App\Traits\CacheTrait;
use App\Traits\StepTrait;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;


class NewOrderController extends Controller
{

    use StepTrait, CacheTrait;


    public function newOrder(Nutgram $bot)
    {
        $this->set_user_step($bot, 'order_fio');
        $text = Helper::getText()->order_fio;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);
    }


    public function order_list(Nutgram $bot)
    {
        $user_step = $this->get_user_step($bot);

        switch ($user_step) {
            case 'order_fio':
            {
                $this->order_full_name($bot);
                break;
            }
            case 'order_address':
            {
                $this->order_address($bot);
                break;
            }
            case 'order_phone':
            {
                $this->order_phone($bot);
                break;
            }
        }

    }

    public function order_full_name(Nutgram $bot)
    {
        Cache::put($bot->userId(), ['order_fio' => $bot->message()->text]);

        $bot->sendMessage(Helper::getText()->order_address, [
            'parse_mode' => ParseMode::HTML
        ]);

        $this->set_user_step($bot, 'order_address');
    }


    public function order_address(Nutgram $bot)
    {
        $this->cache_push($bot, ['order_address' => $bot->message()->text]);

        $bot->sendMessage(Helper::getText()->order_phone, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('Phone', request_contact: true),
                    KeyboardButton::make('⬅️ Назад'),
                ),
            'parse_mode' => ParseMode::HTML
        ]);

        $this->set_user_step($bot, 'order_phone');
    }


    public function order_phone($bot)
    {
        if ($bot->message()->contact)
        {
            $this->cache_push($bot, ['order_phone' => $bot->message()->contact->phone_number]);

            $this->set_user_step($bot, 'order_done');

            $this->show_order_list($bot);
        }
        else
        {
            if (Helper::checkPhoneNumber($bot->message()->text)) {
                // ok
                $this->cache_push($bot, ['order_phone' => $bot->message()->text]);

                $this->set_user_step($bot, 'order_done');

                $this->show_order_list($bot);
            }
            else {
                // no
                $bot->sendMessage(Helper::getText()->order_phone_warning);
            }
        }
    }



    public function show_order_list($bot)
    {
        $text = $this->text_list($bot);

        $bot->sendMessage($text, [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('✅ Сохранить', callback_data: 'order_list_save'),
                    InlineKeyboardButton::make('❌ Отменить', callback_data: 'order_list_cancel')
                ),
            'parse_mode' => ParseMode::HTML,
        ]);
    }


    public function order_list_save(Nutgram $bot)
    {
        // send mail
        $data = [
            'full_name' => Cache::get($bot->userId())['order_fio'],
            'phone' => Cache::get($bot->userId())['order_phone'],
            'address' => Cache::get($bot->userId())['order_address'],
            'chat_id' => $bot->userId(),
        ];

         SendMailJob::dispatch($data)->onQueue('new_order');

        $text = $this->text_list($bot, "✅");
        $bot->editMessageText($text, [
            'parse_mode' => ParseMode::HTML
        ]);


        $bot->sendMessage(Helper::getText()->new_order_saved, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Подключить услугу'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);

        $this->set_user_step($bot, 'back');

        // save db
        Order::create($data);

        // inline btn click, clock icon hide
        $bot->answerCallbackQuery();

        // clear cache
        $this->cache_clear($bot);
    }


    public function order_list_cancel(Nutgram $bot)
    {
        // inline btn click, clock icon hide
        $bot->answerCallbackQuery();

        $text = $this->text_list($bot, "❌");

        $bot->editMessageText($text, [
            'parse_mode' => ParseMode::HTML
        ]);

        $this->cache_clear($bot);

        $bot->sendMessage(Helper::getText()->order_no_btn_text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Новая заявка'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);

        $this->set_user_step($bot, 'back');
    }



    public function text_list(Nutgram $bot, $icon = null)
    {
        return "<b>". Helper::getText()->order_list_show_title."</b>  ".$icon."  \n"
            . Helper::getText()->order_fio_key . Cache::get($bot->userId())['order_fio'] . "\n"
            . Helper::getText()->order_address_key . Cache::get($bot->userId())['order_address'] . "\n"
            . Helper::getText()->order_phone_key . Cache::get($bot->userId())['order_phone'];
    }

}
