<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\TelegramBot\Traits\CallbackData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    use CallbackData;

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data = $callback_query->getData();

        $command = $this->getCallbackCommand($callback_data);
        if ($command) {
            $update = $this->getUpdate()->getRawData();
            $update['message'] = $update['callback_query']['message'];
            $update['message']['from'] = $update['callback_query']['from'];
            $update['message']['text'] = '/' . $command['command'] . ' ' . $callback_data;
            $commandClass = $command['class'];
            return (new $commandClass($this->telegram, new Update($update)))->preExecute();
        }

        return Request::emptyResponse();

//        $data = [
//            'callback_query_id' => $callback_query_id,
//            'text'              => 'Idea Store',
//            'show_alert'        => $callback_data === 'thumb up',
//            'cache_time'        => 5,
//        ];
//
//        return Request::answerCallbackQuery($data);
    }
}
