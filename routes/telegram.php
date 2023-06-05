<?php
/** @var SergiX44\Nutgram\Nutgram $bot */


use App\Helpers\Helper;
use App\Models\Order;
use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\Controller\BtnController;
use App\Http\Controllers\Controller\NewOrderController;
use App\Http\Controllers\Controller\TariffController;
use Illuminate\Support\Facades\Cache;
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


$bot->onText("🧾 Тариф", [TariffController::class, 'tariff']);


/*** ################################# New Order ################################# **/

$bot->onText("📝 Новая заявка", [NewOrderController::class, 'newOrder']);

$bot->onMessage([NewOrderController::class, 'order_list']);

$bot->onCallbackQueryData('order_list_save', [NewOrderController::class, 'order_list_save']);

$bot->onCallbackQueryData('order_list_cancel', [NewOrderController::class, 'order_list_cancel']);

/*** ################################ ./New Order ################################# **/


$bot->onText("⬅️ Назад", [BtnController::class, 'back']);


$bot->onText("cache", function(Nutgram $bot) {
    $bot->sendMessage(json_encode(Cache::get($bot->userId())));
});

//$bot->fallback(function (Nutgram $bot) {
//    $bot->sendMessage('Sorry, I don\'t understand.');
//});

//
//$bot->onText("test", function ($bot) {
//
//    $user_step = $bot->getUserData('user_step', $bot->userId());
//
//    $bot->sendMessage("user_step: " . $user_step, [
//        'parse_mode' => "HTML"
//    ]);
//});




$bot->onException(function (Nutgram $bot, \Throwable $exception) {
    \Illuminate\Support\Facades\Log::info($exception->getMessage());
//    error_log($exception);

    $chat_id = env('ADMIN_CHAT_ID');
    $bot->sendMessage('Error: ' . $exception->getMessage(), ['chat_id' => $chat_id]);
});


