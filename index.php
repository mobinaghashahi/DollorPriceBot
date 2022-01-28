<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


$botToken = "yourToken Telegram";
$webSite = "https://api.telegram.org/bot" . $botToken;

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];



$result = file_get_contents("https://www.tgju.org/%D9%82%DB%8C%D9%85%D8%AA-%D8%AF%D9%84%D8%A7%D8%B1");

$row = explode("\n", $result);

$near=false;
foreach ($row as $value) {
    if($near==true&&preg_match('/<span class="info-value"><span class="info-price">/',$value))
    {
        $data=preg_replace('/<span class="info-value"><span class="info-price">/',null,$value);
        $data=preg_replace('/<\/span> <span class="info-change">/'," ",$data);
        $data=preg_replace('/<\/span><\/span>/'," ",$data);

        $data="قیمت دلار ".$data." تومان";

        sendMessage($chatId,$data);
    }
    else{
        $near=false;
    }

    if (preg_match('/<strong>دلار<\/strong>/',$value)) {
        $near=true;
    }

}


$result = file_get_contents("https://www.tgju.org/profile/price_try");

$row = explode("\n", $result);

foreach ($row as $value) {

    if (preg_match('/<span data-col="info.last_trade.PDrCotVal">/',$value)) {
        $data=preg_replace('/<span data-col="info.last_trade.PDrCotVal">/',null,$value);
        $data=preg_replace('/<\/span>/',null,$data);
        $data="قیمت لیر ".$data." تومان";
        sendMessage($chatId,$data);

        break;
    }

}
if(!empty($update["message"]["chat"]["first_name"])||!empty($update["message"]["chat"]["last_name"]))
    sendMessage(159354346,"نام: ".$update["message"]["chat"]["first_name"]." ".$update["message"]["chat"]["last_name"]);
sendMessage(159354346,"پیام: ".$update["message"]["text"]);
if(!empty($update["message"]["chat"]["title"]))
    sendMessage(159354346,"نام گروه: ".$update["message"]["chat"]["title"]);
sendMessage(159354346,$update['result']);
function sendMessage($chatId, $message)
{

    $url = $GLOBALS['webSite'] . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
    file_get_contents($url);


}