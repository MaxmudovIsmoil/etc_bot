<?php
/** @var SergiX44\Nutgram\Nutgram $bot */


use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\Controller\BackController;
use App\Http\Controllers\Controller\NewOrderController;
use App\Http\Controllers\Controller\TariffController;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

/*** ################################# Tariff ################################# **/

$bot->onText("🧾 Тариф", [TariffController::class, 'tariff']);

$bot->onMessage([TariffController::class, 'tariff_list']);

$bot->onCallbackQueryData('show_info', [TariffController::class, 'show_info']);

/*** ################################# ./Tariff ############################### **/




/*** ################################# New Order ################################# **/

$bot->onText("📝 Новая заявка", [NewOrderController::class, 'newOrder']);

$bot->onMessage([NewOrderController::class, 'order_list']);

$bot->onCallbackQueryData('order_list_save', [NewOrderController::class, 'order_list_save']);

$bot->onCallbackQueryData('order_list_cancel', [NewOrderController::class, 'order_list_cancel']);

/*** ################################ ./New Order ################################# **/


$bot->onText("⬅️ Назад", [BackController::class, 'back']);


$bot->onText("cache", function(Nutgram $bot) {
    $bot->sendMessage(json_encode(Cache::get($bot->userId())));
});


$bot->onException(function (Nutgram $bot, \Throwable $exception) {
    \Illuminate\Support\Facades\Log::info($exception->getMessage());

    $chat_id = env('ADMIN_CHAT_ID');
    $bot->sendMessage('Error: ' . $exception->getMessage(), ['chat_id' => $chat_id]);
});


//$bot->fallback(function (Nutgram $bot) {
//    $bot->sendMessage('Sorry, I don\'t understand.');
//});
