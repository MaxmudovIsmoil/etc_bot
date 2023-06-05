<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Nutgram;

trait CacheTrait
{

    public function cache_push(Nutgram $bot, $array = [])
    {
        $user = Cache::get($bot->userId());
        $user = array_merge($user, $array);
        Cache::put($bot->userId(), $user);
    }


    public function cache_clear(Nutgram $bot)
    {
        Cache::forget($bot->userId());
    }


}
