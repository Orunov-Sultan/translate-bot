<?php
require_once 'Telegram.php';
require_once 'TelegramErrorLogger.php';
require_once 'config.php';
require_once 'db.php';

//https://api.telegram.org/bot(BOT_TOKEN)/setWebhook?url=https://yoursite.com/your_update.php

$telegram = new Telegram(TOKEN);

$chat_id = $telegram->ChatID() ?? '';
$text = $telegram->Text() ?? '';

file_put_contents(__DIR__.'/log.txt', print_r($telegram, 1));

