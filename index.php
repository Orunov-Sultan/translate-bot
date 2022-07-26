<?php
require_once 'Telegram.php';
require_once 'TelegramErrorLogger.php';
require_once 'config.php';
require_once 'db.php';
require_once ('vendor/autoload.php');


use \Dejurin\GoogleTranslateForFree;

//https://api.telegram.org/bot(BOT_TOKEN)/setWebhook?url=https://yoursite.com/your_update.php

$telegram = new Telegram(TOKEN);

$chat_id = $telegram->ChatID() ?? '';
$text = $telegram->Text() ?? '';
$first_name = $telegram->FirstName();
$callback_message = $telegram->Callback_Message() ?? '';
$callback_data = $telegram->Callback_Data() ?? '';

if ($text == '/start' || $text == 'start'){

    $data = getChatId($chat_id);
    if (empty($data)){
        addChatId($chat_id, $first_name, 'en');
        $check = 'en';
    }else{
        $check = $data['lang'];
    }

    $keyb = $telegram->buildInlineKeyBoard(getKeyboard($check));

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => "Оставьте отмеченный язык для перевода с него или выберите другой"
    ];
    $telegram->sendMessage($content);
}elseif (!empty($callback_message)){

    foreach ($callback_message['reply_markup']['inline_keyboard'][0] as $item){
        if ($item['text'] == $callback_data){
            updateChat($callback_message['chat']['id'], $callback_data);

            $response = $telegram->answerCallbackQuery([
                'callback_query_id' => $telegram->Callback_ID(),
                //        'text' => "Язык перевода: {$telegram->Callback_Data()}"
            ]);

            $keyb = $telegram->buildInlineKeyBoard(getKeyboard($callback_data));

            $content = [
                'chat_id' => $callback_message['chat']['id'],
                'reply_markup' => $keyb,
                'text' => "Можете вводить текст для перевода с выбранного языка"
            ];
            $telegram->sendMessage($content);
            break;
        }
    }
    $response = $telegram->answerCallbackQuery([
        'callback_query_id' => $telegram->Callback_ID(),
        'text' => "Это уже активный язык"
    ]);
}elseif (!empty($text)){

    $data = getChatId($chat_id);

    $source = ($data['lang'] == 'en') ? 'en' : 'ru';
    $target = ($data['lang'] == 'ru') ? 'en' : 'ru';
    $attempts = 5;

    $tr = new GoogleTranslateForFree();
    $result = $tr->translate($source, $target, $text, $attempts);

    if ($result){

        $content = [
            'chat_id' => $chat_id,
            'text' => $result
        ];
        $telegram->sendMessage($content);

    }else{
        $content = [
            'chat_id' => $chat_id,
            'text' => 'Упс... Я не смог перевести'
        ];
        $telegram->sendMessage($content);
    }
}else{
    $content = [
        'chat_id' => $chat_id,
        'text' => 'Это бот-переводчик, поэтому он ожидает от вас текст для перевода...'
    ];
    $telegram->sendMessage($content);
}


function getKeyboard($lang){
    global $telegram;

    return [
        [
            $telegram->buildInlineKeyBoardButton($lang == "en" ? "en 🗸" : "en", '', 'en'),
            $telegram->buildInlineKeyBoardButton($lang == "ru" ? "ru 🗸" : "ru", '', 'ru')
        ]
    ];
}

