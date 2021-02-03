<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\CatalogueCommand;
use Longman\TelegramBot\Commands\UserCommands\ContactsCommand;
use Longman\TelegramBot\Commands\UserCommands\MessageCommand;
use Longman\TelegramBot\Commands\UserCommands\OurchannelCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function executeNoDb()
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $message_id = $message->getMessageId();
        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));
        $from       = $message->getFrom();
        $user_id    = $from->getId();

        $update = json_decode($this->update->toJson(), true);

        if($text === __('telegram.buttons.catalogue')){
            $update['message']['text'] = '/catalogue';
            return (new CatalogueCommand($this->telegram, new Update($update)))->preExecute();
        }
        elseif($text === __('telegram.buttons.write_us')){
            $update['message']['text'] = '/message';
            return (new MessageCommand($this->telegram, new Update($update)))->preExecute();
        }
        elseif($text === __('telegram.buttons.contacts')){
            $update['message']['text'] = '/contacts';
            return (new ContactsCommand($this->telegram, new Update($update)))->preExecute();
        }
        elseif($text === __('telegram.buttons.our_channel')){
            $update['message']['text'] = '/ourchannel';
            return (new OurchannelCommand($this->telegram, new Update($update)))->preExecute();
        }

        //If a conversation is busy, execute the conversation command after handling the message
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        //Fetch conversation command if it exists and execute it
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
        }



        return Request::emptyResponse();
    }
}
