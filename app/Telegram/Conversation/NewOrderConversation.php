<?php

namespace App\Telegram\Conversation;

use App\Helpers\Helper;
use App\Jobs\SendMailJob;
use App\Models\Order;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;


class NewOrderConversation extends Conversation {

    public ?string $full_name = null;
    public ?string $address = null;
    public ?string $phone = null;

    protected function getSerializableAttributes(): array
    {
        return [
            'full_name' => $this->full_name,
            'address' => $this->address,
            'phone' => $this->phone,
        ];
    }

    public function start(Nutgram $bot)
    {
        $text = Helper::getText()->order_fio;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);

        $this->next('fullNameStep');
    }

    public function fullNameStep(Nutgram $bot)
    {
        $this->full_name = $bot->message()->text;

        $bot->sendMessage(Helper::getText()->order_address, [
            'parse_mode' => ParseMode::HTML
        ]);

        $this->next('addressStep');
    }

    public function addressStep(Nutgram $bot)
    {
        $this->address = $bot->message()->text;

        $bot->sendMessage(Helper::getText()->order_phone, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('Phone', request_contact: true),
                    KeyboardButton::make('⬅️ Назад'),
                ),
            'parse_mode' => ParseMode::HTML
        ]);

        $this->next('phoneStep');
    }


    public function phoneStep(Nutgram $bot)
    {
        if ($bot->message()->contact)
        {
            $this->phone = $bot->message()->contact->phone_number;

            $this->show_order_list($bot);

            $this->end();
        }
        else
        {
            if (Helper::checkPhoneNumber($bot->message()->text)) {
                // ok
                $this->phone = $bot->message()->text;

                $this->show_order_list($bot);

                $this->end();
            }
            else {
                // no
                $bot->sendMessage(Helper::getText()->order_phone_warning);
            }
        }

    }


    public function show_order_list($bot)
    {
        $order_id = Order::insertGetId([
            'full_name' => $this->full_name,
            'address' => $this->address,
            'phone' => $this->phone,
            'chat_id' => $bot->chatId(),
            'status' => 0
        ]);

        $bot->sendMessage($this->text($this->full_name, $this->address, $this->phone), [
            'reply_markup' => InlineKeyboardMarkup::make()
                ->addRow(
                    InlineKeyboardButton::make('✅ Сохранить', callback_data: "$order_id:1"),
                    InlineKeyboardButton::make('❌ Отменить', callback_data: "$order_id:-1")
                ),
            'parse_mode' => ParseMode::HTML,
        ]);
    }

    public function text($full_name, $address, $phone, $icon = null): string
    {
        return "<b>". Helper::getText()->order_list_show_title."</b>  " . $icon . "\n"
            . Helper::getText()->order_fio_key . $full_name . "\n"
            . Helper::getText()->order_address_key . $address . "\n"
            . Helper::getText()->order_phone_key . $phone;
    }


    public function order_save_or_cancel(Nutgram $bot)
    {
        // inline btn click, clock icon hide
        $bot->answerCallbackQuery();

        $order_id = explode(':', $bot->callbackQuery()->data)[0];
        $status   = explode(':', $bot->callbackQuery()->data)[1];

        $order = $this->order($order_id, $status);

        if ($status == 1) {
            $icon = "✅";
            $message = Helper::getText()->new_order_saved;
            // send mail
            SendMailJob::dispatch($order)->onQueue('new_order');
        }
        else {
            Order::findOrFail($order_id)->delete();
            $icon = "❌";
            $message = Helper::getText()->order_no_btn_text;
        }

        $text = $this->text($order->full_name, $order->address, $order->phone, $icon);
        $bot->editMessageText($text,[
            'parse_mode' => ParseMode::HTML,
        ]);

        $bot->sendMessage($message, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('🧾 Тариф'),
                    KeyboardButton::make('📝 Подключить услугу'),
                ),
            'parse_mode' => ParseMode::HTML,
        ]);
    }


    public function order($order_id, $status): object
    {
        $order = Order::findOrFail($order_id);
        $order->fill(['status' => $status]);
        $order->save();

        return $order;
    }

}


