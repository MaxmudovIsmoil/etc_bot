<?php

namespace App\Http\Controllers\Controller;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Jobs\SendMailJob;
use App\Models\Order;
use App\Traits\CacheTrait;
use App\Traits\StepTrait;
use Illuminate\Support\Facades\Cache;
use PHPUnit\TextUI\Help;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class NewOrderController_back extends Controller
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
                Cache::put($bot->userId(), ['order_fio' => $bot->message()->text]);

                $text = Helper::getText()->order_address;
                $this->sendStepMessage($bot, $text);

                $this->set_user_step($bot, 'order_address');
                break;
            }
            case 'order_address':
            {
                $this->cache_push($bot, ['order_address' => $bot->message()->text]);

                $text = Helper::getText()->order_phone;
                $this->sendStepMessage($bot, $text);

                $this->set_user_step($bot, 'order_phone');
                break;
            }
            case 'order_phone':
            {
                if ($bot->message()->contact)
                {
                    $this->cache_push($bot, ['order_phone' => $bot->message()->contact->phone_number]);

                    $text = Helper::getText()->order_location;
                    $this->sendStepMessage($bot, $text);

                    $this->set_user_step($bot, 'order_location');
                }
                else
                {
                    if (Helper::checkPhoneNumber($bot->message()->text)) {
                        // ok
                        $this->cache_push($bot, ['order_phone' => $bot->message()->text]);

                        $text = Helper::getText()->order_location;
                        $this->sendStepMessage($bot, $text);

                        $this->set_user_step($bot, 'order_location');
                    }
                    else {
                        // no
                        $bot->sendMessage(Helper::getText()->order_phone_warning);
                    }
                }

                break;
            }
            case 'order_location':
            {
                if ($bot->message()->location)
                {
                    $this->cache_push($bot, [
                        'longitude' => $bot->message()->location->longitude,
                        'latitude'  => $bot->message()->location->latitude,
                    ]);

                    $bot->sendMessage(json_encode($bot->message()->location));

                    $this->set_user_step($bot, 'order_done');

                    $this->show_order_list($bot);
                }
                else
                {
                    // no location
                    $bot->sendMessage(Helper::getText()->order_location_warning, [
                        'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                            ->addRow(
                                KeyboardButton::make('⬅️ Назад')
                            ),
                        'parse_mode' => ParseMode::HTML
                    ]);
                }

                break;
            }
            default:
            {
                $this->sendStepMessage($bot, 'any text');
                break;
            }
        }

    }

    public function sendStepMessage(Nutgram $bot, $text)
    {
        if ($this->get_user_step($bot) == 'order_address')
        {
            $bot->sendMessage($text, [
                'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                    ->addRow(
                        KeyboardButton::make('Phone', request_contact: true),
                        KeyboardButton::make('⬅️ Назад'),
                    ),
                'parse_mode' => ParseMode::HTML
            ]);
        }
        else
        {
            $bot->sendMessage($text, [
                'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                    ->addRow(
                        KeyboardButton::make('⬅️ Назад')
                    ),
                'parse_mode' => ParseMode::HTML
            ]);
        }

    }


    public function show_order_list($bot)
    {
        $text = $this->text_list($bot);

        $bot->sendMessage($text, [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('✅ Сохранять', callback_data: 'order_list_save'),
                    InlineKeyboardButton::make('❌ Отмена', callback_data: 'order_list_cancel')
                ),
            'parse_mode' => ParseMode::HTML
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
            'location' => null,
            'longitude' => (Cache::get($bot->userId())['longitude']) ?: null,
            'latitude' => (Cache::get($bot->userId())['latitude']) ?: null,
        ];

//         SendMailJob::dispatch($data)->onQueue('new_order');

        $text = $this->text_list($bot, "✅");
        $bot->editMessageText($text, [
            'parse_mode' => ParseMode::HTML
        ]);

        $bot->sendMessage('successfully congratulations');

        // save db
        Order::create($data);


        // clear cache
        $this->cache_clear($bot);
    }


    public function order_list_cancel(Nutgram $bot)
    {
        $text = $this->text_list($bot, "❌");

        $bot->editMessageText($text, [
            'parse_mode' => ParseMode::HTML
        ]);

        $this->cache_clear($bot);

        $bot->sendMessage(Helper::getText()->order_no_btn_text);

        $this->newOrder($bot);
    }



    public function text_list(Nutgram $bot, $icon = null)
    {
        return "<b>". Helper::getText()->order_list_show_title."</b>  ".$icon."  \n"
            . Helper::getText()->order_fio_key . Cache::get($bot->userId())['order_fio'] . "\n"
            . Helper::getText()->order_address_key . Cache::get($bot->userId())['order_address'] . "\n"
            . Helper::getText()->order_phone_key . Cache::get($bot->userId())['order_phone'] . "\n"
            . Helper::getText()->order_location_key . "⤴️\n";
    }

}
