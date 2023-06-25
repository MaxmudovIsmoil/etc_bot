<?php

namespace App\Telegram\Conversation;

use App\Helpers\Helper;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class TariffConversation extends Conversation {

    public ?string $username = null;
    public ?string $password = null;
    public ?string $subject_id = null;

    protected \App\Service\ApiService $api;

    public function __construct(\App\Service\ApiService $apiService) { // called when the conversation is deserialized
        $this->api = $apiService;
    }

    protected function getSerializableAttributes(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'subject_id' => $this->subject_id,
        ];
    }

    public function start(Nutgram $bot)
    {
        $text = Helper::getText()->tariff_login;

        $bot->sendMessage($text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)
                ->addRow(
                    KeyboardButton::make('⬅️ Назад')
                ),
            'parse_mode' => ParseMode::HTML
        ]);

        $this->next('usernameStep');
    }

    public function usernameStep(Nutgram $bot)
    {
        $this->username = $bot->message()->text;

        $check_username = $this->api->check_username($this->username);

        if ($check_username['status'])
        {
            $bot->sendMessage(Helper::getText()->tariff_password, [
                'parse_mode' => ParseMode::HTML
            ]);

            $this->next('passwordStep');
        }
        else {
            $bot->sendMessage($check_username['error_text'], [
                'parse_mode' => ParseMode::HTML
            ]);
        }
    }

    public function passwordStep(Nutgram $bot)
    {
        $this->password = $bot->message()->text;

        $check_password = $this->api->check_password($this->username, $this->password);

        if ($check_password['status'])
        {
            $info = $this->api->info($this->username, $this->password, $check_password['subject_id']);

            $this->show_info($bot, $info);

            $this->end();
        }
        else
        {
            $bot->sendMessage($check_password['error_text'], [
                'parse_mode' => ParseMode::HTML
            ]);
        }
    }


    public function show_info($bot, $info)
    {
        $text = "<b>🗓 ". Helper::getText()->tariff_info_title."</b>\n "
            . Helper::getText()->tariff_full_name . $info['full_name'] . "\n"
            . Helper::getText()->tariff_address . $info['address'] . "\n"
            . Helper::getText()->tariff_code . $info['tariff_code']."\n"
            . Helper::getText()->show_balance . $info['balance']." сум\n"
            . Helper::getText()->tariff_status . $info['tariff_status'];

        $bot->sendMessage($text, [
            'parse_mode' => ParseMode::HTML
        ]);

        $bot->sendMessage(Helper::getText()->tariff_done_text, [
            'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
                KeyboardButton::make('🧾 Тариф'),
                KeyboardButton::make('📝 Подключить услугу'),
            ),
            'parse_mode' => ParseMode::HTML,
        ]);
    }


}


