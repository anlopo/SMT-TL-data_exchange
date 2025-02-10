<?php
$logFile = 'check_token_log.txt';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
$url = $_SERVER['REQUEST_URI'] ?? 'UNKNOWN';

$headers = getallheaders();
$headersFormatted = "";
foreach ($headers as $key => $value) {
    $headersFormatted .= "$key: $value\n";
}

$body = file_get_contents('php://input');

$logMessage = "------------------------------\n";
$logMessage .= "Time: " . date('Y-m-d H:i:s') . "\n";
$logMessage .= "IP: $ip\n";
$logMessage .= "Method: $method\n";
$logMessage .= "URL: $url\n";
$logMessage .= "Headers:\n$headersFormatted\n";
$logMessage .= "Body:\n$body\n";
$logMessage .= "------------------------------\n\n";

file_put_contents($logFile, $logMessage, FILE_APPEND);

header('Content-Type: text/plain');
if ($method === 'POST' && isset($_POST['t']) && !empty($_POST['t'])) {
    echo "OK";
} else {
    echo "ERROR: unknown request";
}
?>