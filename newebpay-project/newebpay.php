<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Add CORS headers
header("Access-Control-Allow-Origin: https://backtest.zeabur.app/#/Newebpay/Newebpay"); // 允许的源
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // 允许的方法
header("Access-Control-Allow-Headers: Content-Type"); // 允许的请求头
header("Access-Control-Allow-Credentials: true"); // 允许凭证

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw POST data
    $postData = json_decode(file_get_contents('php://input'), true);

    // Extract the merchant information
    $mid = $_ENV['NEWEBPAY_MERCHANT_ID'];
    $key = $_ENV['NEWEBPAY_KEY'];
    $iv = $_ENV['NEWEBPAY_IV'];
    $api_url = $_ENV['NEWEBPAY_API_URL'];

    // Add required fields to the POST data
    $postData['MerchantID'] = $mid;
    $postData['RespondType'] = $_ENV['NEWEBPAY_RETURN_TYPE'];
    $postData['Version'] = $_ENV['NEWEBPAY_VERSION'];
    $postData['TimeStamp'] = $_ENV['NEWEBPAY_TIMESTAMP'];
    $postData['P3D'] = $_ENV['NEWEBPAY_3D'];
    $postData['MerchantOrderNo'] = $_ENV['NEWEBPAY_ORDER_NO'];

    // Remove the merchant information from the POST data
    unset($postData['url']);

    // Build the request string
    $data1 = http_build_query($postData);

    // Encrypt the request string
    $edata1 = bin2hex(openssl_encrypt($data1, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv));

    // Generate the hash
    $hashs = "HashKey=" . $key . "&" . $edata1 . "&HashIV=" . $iv;
    $hash = strtoupper(hash("sha256", $hashs));

    // Build the POST data
    $post_str = [
        'MerchantID_' => $mid,
        'PostData_' => $edata1,
    ];

    // Initialize curl
    $ch = curl_init();

    // Set curl options
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_str));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // Execute curl
    $result = curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_errno($ch);

    // Close curl
    curl_close($ch);

    // Return the result
    echo json_encode([
        'url' => $api_url,
        'send_parameter' => $post_str,
        'http_status' => $retcode,
        'curl_error_no' => $curl_error,
        'web_info' => json_decode($result, true),
    ]);
}
?>
