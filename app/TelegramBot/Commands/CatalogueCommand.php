<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Category;
use App\TelegramBot\Traits\CallbackData;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class CatalogueCommand extends UserCommand
{
    use CallbackData;

    /**
     * @var string
     */
    protected $name = 'catalogue';

    /**
     * @var string
     */
    protected $description = 'Просмотр категорий';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    private $data;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = trim($message->getText(true));

        $sendMessage = 'Каталог';
        if ($text == '') {

            // get standard keyboard
            $keyboard = $this->keyboardCategories();

            return Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => $sendMessage,
                'reply_markup' => $keyboard,
            ]);
        } else {
            // parse data
            $this->data = $this->parseCallbackData($text);

            // get keyboard
            if (isset($this->data['category_show'])) {
                // show subcategories
                $category_id = (int)$this->data['category_show'];
                if ($category_id == 0) {
                    $keyboard = $this->keyboardCategories();
                } else {
                    $category = Category::find($category_id);
                    $sendMessage .= ': ' . $category->full_name;
                    $keyboard = $this->keyboardCategories($category);
                }
            } elseif (isset($this->data['category_products'])) {
                // show products
                $category_id = (int)$this->data['category_products'];
                $category = Category::findOrFail($category_id);
                $sendMessage .= ': ' . $category->full_name;
                $keyboard = $this->keyboardProducts($category);

            } else {
                // error
                return Request::emptyResponse();
            }

            return Request::editMessageText([
                'chat_id' => $chat_id,
                'message_id' => $message->getMessageId(),
                'text' => $sendMessage,
                'reply_markup' => $keyboard,
            ]);
        }
    }

    private function keyboardCategories(Category $category = null)
    {
        if ($category === null) {
            $subcategories = Category::active()->whereNull('parent_id')->get();
        } else {
            $subcategories = Category::active()->where('parent_id', $category->id)->get();
        }

        $buttons = collect();

        // no children categories - show products keyboard
        if ($category !== null && $subcategories->isEmpty()) {
            return $this->keyboardProducts($category);
        }

        // show subcategories keyboard
        foreach ($subcategories as $subcategory) {
            $buttons->push(['text' => $subcategory->name, 'callback_data' => 'category_show:' . $subcategory->id]);
        }
        $buttons = $buttons->chunk(3);
        if ($category !== null) {
            $buttons->push([
                [
                    'text' => 'Просмотр товаров',
                    'callback_data' => 'category_products:' . ($category->id ?? 0) . '|page:1',
                ],
            ]);
            $buttons->push([
                [
                    'text' => 'Назад',
                    'callback_data' => 'category_show:' . ($category->parent_id ?? 0),
                ],
            ]);
        }

        $inline_keyboard = new InlineKeyboard(...$buttons->toArray());

        return $inline_keyboard;
    }

    private function keyboardProducts(Category $category)
    {
        $buttons = collect();

        $page = isset($this->data['page']) ? (int)$this->data['page'] : 1;
        $prevPage = $page - 1;
        $nextPage = $page + 1;

        $limit = 10;
        $offset = ($page - 1) * $limit;
        $products = $category->allProducts()->active()->inStock()->latest()->skip($offset)->take($limit)->get();

        if (!$products->isEmpty()) {
            foreach ($products as $product) {
                $buttons->push(['text' => $product->telegram_name, 'callback_data' => 'product_show:' . $product->id . '|return_category:' . $category->id . '|return_page:' . $page]);
            }
            $buttons = $buttons->chunk(2);

            // nav buttons
            $navButtons = [];
            if ($page > 1) {
                $prevPage = $page - 1;
                $navButtons[] = ['text' => '<', 'callback_data' => 'category_products:' . $category->id . '|page:' . $prevPage,]; // Пред. стр.
            }
            $navButtons[] = ['text' => '>', 'callback_data' => 'category_products:' . $category->id . '|page:' . $nextPage,]; // След. стр.
            $buttons->push($navButtons);
            $buttons->push([['text' => 'Назад', 'callback_data' => 'category_show:' . ($category->parent_id ?? 0),],]); // Назад
        } else {
            if ($page == 1) {
                $buttons->push([['text' => 'В этой категории товаров нет. Вернуться назад', 'callback_data' => 'category_show:' . (int)$category->parent_id]]);
            } else {
                $buttons->push([['text' => 'Больше товаров нет. Вернуться на пред. стр.', 'callback_data' => 'category_products:' . (int)$category->id . '|page:' . $prevPage]]);
            }
        }



        $inline_keyboard = new InlineKeyboard(...$buttons->toArray());

        return $inline_keyboard;
    }
}
