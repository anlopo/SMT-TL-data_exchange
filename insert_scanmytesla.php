<?php
$logFile = 'insert_scanmytesla_log.txt';
$dataFile = 'scanmytesla_data.json';
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
if ($method === 'POST' && isset($_POST['m']) && !empty($_POST['m']) && isset($_POST['t']) && !empty($_POST['t']) && isset($_POST['v']) && !empty($_POST['v'])) {
    $decodedData = urldecode($_POST['m']);
    $jsonData = json_decode($decodedData, true);
    
    if ($jsonData !== null) {
        $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT) . ",\n";
        file_put_contents($dataFile, $jsonString, FILE_APPEND);
        echo "OK";
    } else {
       echo "ERROR: unknown request";
    }
}
?>