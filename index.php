<?php
require_once 'Telegram.php';
require_once 'TelegramErrorLogger.php';
require_once 'config.php';

//https://api.telegram.org/bot(BOT_TOKEN)/setWebhook?url=https://yoursite.com/your_update.php

$telegram = new Telegram('5537922099:AAFFTD3OJAtreMx2i6QfbRnDYBCkHF1zWIU');

$chat_id = $telegram->ChatID();
$content = array('chat_id' => $chat_id, 'text' => 'Test');
$telegram->sendMessage($content);

file_put_contents(__DIR__.'/log.txt', print_r($telegram, 1));

