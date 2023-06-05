<?php

namespace App\Traits;


use SergiX44\Nutgram\Nutgram;

trait StepTrait
{


    public function set_user_step(Nutgram $bot, $btn)
    {
        return $bot->setUserData('user_step', $btn, $bot->userId());
    }

    public function get_user_step(Nutgram $bot)
    {
        return $bot->getUserData('user_step', $bot->userId());
    }

    public function delete_user_step(Nutgram $bot)
    {
        return $bot->deleteUserData('user_step');
    }


}
