<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestingController extends Controller
{
    public function index()
    {
        $data = [
            ['phone' => '998908081239', 'text' => utf8_encode(iconv('windows-1251', 'utf-8', 'Тестовое сообщение от xplore.uz'))],
            // Если сообщения приходят в неправильной кодировке, используйте iconv:
            //['phone' => 'NUMBER', 'text' => utf8_encode(iconv('windows-1251', 'utf-8', 'TEXT'))],
        ];

        $client = new Client();
        $query = [
            "login" => "xplore",
            "password" => "iwPhBSgI1XBb",
            "data" => json_encode($data)
        ];
        try {
            $client->request('POST', 'http://83.69.139.182:8083/', [
                'form_params' => $query
            ]);
        } catch (RequestException $e) {
            Log::info($e->getMessage());
        }
    }
}
