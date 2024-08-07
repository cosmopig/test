<?php
// 报告所有 PHP 错误
error_reporting(E_ALL);

// 加载 .env 文件中的配置
require 'vendor/autoload.php';

// 指定 .env 文件的路径（使用相对路径）
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 从 .env 文件中获取配置
$merchantId = getenv('MERCHANT_ID');
$hashKey = getenv('HASH_KEY');
$hashIv = getenv('HASH_IV');
$apiUrl = getenv('API_URL');
$version = getenv('VERSION');

function encryptData($data, $key, $iv) {
    return bin2hex(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv));
}

function addPadding($string, $blocksize = 32) {
    $len = strlen($string);
    $pad = $blocksize - ($len % $blocksize);
    return $string . str_repeat(chr($pad), $pad);
}

function createTradeInfo($postData, $key, $iv) {
    $data = http_build_query($postData);
    $data = addPadding($data);
    return encryptData($data, $key, $iv);
}

function createTradeSha($tradeInfo, $key, $iv) {
    $shaString = "HashKey=$key&$tradeInfo&HashIV=$iv";
    return strtoupper(hash("sha256", $shaString));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = [
        'MerchantID' => $merchantID,
        'RespondType' => 'JSON',
        'TimeStamp' => time(),
        'Version' => $version,
        'MerchantOrderNo' => uniqid(),
        'Amt' => $_POST['amount'],
        'ItemDesc' => $_POST['itemName'],
        'Email' => $_POST['email'],
        'LoginType' => 0,
        'CREDIT' => 1,
        'CREDITAGREEMENT' => 1
    ];

    $tradeInfo = createTradeInfo($postData, $hashKey, $hashIV);
    $tradeSha = createTradeSha($tradeInfo, $hashKey, $hashIV);

    $payload = [
        'MerchantID_' => $merchantID,
        'TradeInfo' => $tradeInfo,
        'TradeSha' => $tradeSha,
        'Version' => $version,
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
?>
