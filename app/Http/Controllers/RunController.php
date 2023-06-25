<?php

namespace App\Http\Controllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;

class RunController extends Controller
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(Nutgram $bot)
    {
        // $bot->registerCommand(StartCommand::class)->description('Начать!');

        $bot->run();
    }

}
