<?php

$host = 'localhost';
$user = 'ci32018_tr';
$pass = '1RTTin2R';
$db = 'ci32018_tr';

$dsn = "mysql:host={$host};dbname={$db};charset=utf8";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $opt);

function getChatId($chat_id){
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM chat WHERE chat_id = ?');
    $stmt->execute([$chat_id]);
    return $stmt->fetch();
}

function addChatId($chat_id, $first_name, $lang){
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO (chat_id, first_name, lang) VALUES (?, ?, ?)');
    return $stmt->execute([$chat_id, $first_name, $lang]);
}

function updateChat($chat_id, $lang){
    global $pdo;
    $stmt = $pdo->prepare('UPDATE chat SET lang = ? WHERE chat_id = ?');
    return $stmt->execute([$lang, $chat_id]);
}