<?php
/** @var SergiX44\Nutgram\Nutgram $bot */


use App\Helpers\Helper;
use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\Controller\NewOrderController;
use App\Telegram\Commands\StartCommand;
use App\Telegram\Conversation\TariffConversation;
use App\Telegram\Conversation\NewOrderConversation;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->onCommand('start', StartCommand::class);

$bot->onText("🧾 Тариф", TariffConversation::class);

/*** ################################# New Order ################################# **/

$bot->onText("📝 Подключить услугу", NewOrderConversation::class);

$bot->onCallbackQuery([NewOrderConversation::class, 'order_save_or_cancel']);

/*** ################################ ./New Order ################################# **/


$bot->onText("⬅️ Назад", function (Nutgram $bot) {
    $text = Helper::getText()->back;
    $bot->sendMessage($text, [
        'reply_markup' => ReplyKeyboardMarkup::make(resize_keyboard: true)->addRow(
            KeyboardButton::make('🧾 Тариф'),
            KeyboardButton::make('📝 Подключить услугу'),
        ),
        'parse_mode' => ParseMode::HTML,
    ]);
});


$bot->onException(function (Nutgram $bot, \Throwable $exception) {
    \Illuminate\Support\Facades\Log::info($exception->getMessage());

    $chat_id = env('ADMIN_CHAT_ID');
    $bot->sendMessage('Error: ' . $exception->getMessage(), ['chat_id' => $chat_id]);
});

//$bot->onText("cache", function(Nutgram $bot) {
//    $bot->sendMessage(json_encode(Cache::get($bot->userId())));
//});

//$bot->fallback(function (Nutgram $bot) {
//    $bot->sendMessage('Sorry, I don\'t understand.');
//});
