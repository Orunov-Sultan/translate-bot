<?php
require_once 'Telegram.php';
require_once 'TelegramErrorLogger.php';
require_once 'config.php';
require_once 'db.php';

//https://api.telegram.org/bot(BOT_TOKEN)/setWebhook?url=https://yoursite.com/your_update.php

$telegram = new Telegram(TOKEN);

$chat_id = $telegram->ChatID() ?? '';
$text = $telegram->Text() ?? '';
$first_name = $telegram->FirstName();


if ($text == '/start' || $text == 'start'){

    $data = getChatId($chat_id);
    if (empty($data)){
        addChatId($chat_id, $first_name, 'en');
        $check = 'en';
    }else{
        $check = $data['lang'];
    }

    $option = [
        [
            $telegram->buildInlineKeyBoardButton("en", '', 'en'),
            $telegram->buildInlineKeyBoardButton("ru", '', 'ru')
        ]
    ];
    $keyb = $telegram->buildInlineKeyBoard($option);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => "Оставьте отмеченный язык для перевода с него или выберите другой"
    ];
    $telegram->sendMessage($content);
}


file_put_contents(__DIR__.'/log.txt', print_r($telegram, 1));

