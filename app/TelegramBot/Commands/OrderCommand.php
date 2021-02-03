<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Helpers\Helper;
use App\Product;
use App\TelegramBot\Traits\CallbackData;
use App\TelegramBot\Traits\ManageData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\SystemCommands\StartCommand;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\PhotoSize;
use Longman\TelegramBot\Request;

class OrderCommand extends UserCommand
{
    use CallbackData;
    use ManageData;

    /**
     * @var string
     */
    protected $name = 'order';

    /**
     * @var string
     */
    protected $description = 'Оформление заказа';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Conversation Object
     *
     * @var \Longman\TelegramBot\Conversation
     */
    protected $conversation;

    private $data;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        // prepare response
        $sendData = [
            'chat_id' => $chat_id,
        ];

        //Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        if ($text == __('telegram.buttons.back') && isset($notes['state'])) {
            $notes['state']--;
            $text = '';
        }

        //cache data from the tracking session if any
        $state = 0;
        if (isset($notes['state'])) {
            $state = $notes['state'];
        }

        // no products selected - '/order' sent directly
        if ($text == '' && !isset($notes['state'])) {
            // get standard keyboard
            $notes = [];
            $this->conversation->update();
            $this->conversation->stop();
            return $this->sendErrorMessage($chat_id, 'Чтобы заказать товар перейдите в каталог /catalogue');
        }

        // prepare response result
        $result = Request::emptyResponse();

        switch ($state) {
            case -1:
                $notes = [];
                $this->conversation->update();
                $this->conversation->stop();
                return $this->sendErrorMessage($chat_id, 'Оформление заказа отменено. Перейти в каталог /catalogue');
                break;
            case 0:
                // get product from callback query
                $callbackQuery = $this->getCallbackQuery();
                if ($callbackQuery) {
                    $this->data = $this->parseCallbackData($text);
                    if (isset($this->data['order_product'])) {
                        $notes['product_id'] = (int)$this->data['order_product'];
                        $text = '';
                    } else {
                        return $this->sendErrorMessage($chat_id, 'Чтобы заказать товар перейдите в каталог /catalogue');
                    }
                }
                if ($text === '') {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $sendData['text'] = 'Ваше имя:';
                    $sendData['reply_markup'] = $this->getKeyboard([
                        $user->getFirstName(),
                        __('telegram.buttons.back'),
                    ]);

                    $result = Request::sendMessage($sendData);
                    break;
                }

                $notes['name'] = $text;
                $text = '';

            // no break
            case 1:
                $contact = $message->getContact();
                if (/*$text === '' &&*/ $contact === null) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $sendData['text'] = 'Ваш номер телефона';
                    $keyboardData = [
                        __('telegram.buttons.back'),
                        (new KeyboardButton('Отправить мой номер'))->setRequestContact(true),
                    ];
                    // $currentUser = $this->getUser($user_id);
                    // if (isset($currentUser->phone)) {
                    //     $keyboardData[] = $currentUser->phone;
                    // }
                    $sendData['reply_markup'] = $this->getKeyboard($keyboardData);

                    $result = Request::sendMessage($sendData);
                    break;
                }

                $notes['phone'] = $contact ? $contact->getPhoneNumber() : $text;
                if ($contact) {
                    $this->saveUser($user_id, ['phone' => $contact->getPhoneNumber()]);
                }

                $text = '';

            // no break
            case 2:
                // send message to admin
                $adminMessage = '<strong>' . setting('site.title') . ' - Новый заказ (Telegram Bot)</strong>' . PHP_EOL;
                $adminMessage .= '<strong>Имя:</strong> ' . $notes['name'] . PHP_EOL;
                $adminMessage .= '<strong>Телефон:</strong> ' . $notes['phone'] . PHP_EOL;

                if (isset($notes['product_id'])) {
                    $product = Product::findOrFail($notes['product_id']);
                    $adminMessage .= '<strong>Товар:</strong> ' . '<a href="' . $product->url . '">' . $product->telegram_name . '</a>' . PHP_EOL;
                }

                $telegramUser = $this->getTelegramUser($user);
                if (count($telegramUser)) {
                    $adminMessage .= '<strong>Telegram пользователь:</strong> ' . implode(' ', $telegramUser) . PHP_EOL;
                }

                Helper::toTelegram($adminMessage);

                // send message
                $sendData['text'] = 'Заказ оформлен, мы свяжемся с вами в ближайшее время';
                $sendData['reply_markup'] = StartCommand::keyboard();
                $notes = [];
                $this->conversation->update();
                $this->conversation->stop();

                $result = Request::sendMessage($sendData);
                break;
        }

        return $result;
    }
}
