<?php
/* Function to show error and exit */
function error($msg) {
    header("HTTP/1.0 404 Not Found");
    echo $msg;
    exit;
}

/* Hard-coded target host */
$flask_url = "http://10.205.203.179:5000";

/* Get the full request path */
$request_path = $_SERVER['REQUEST_URI'];

/* Remove the proxy script path from the request */
$proxy_base = "/proxy-simple.php";
if (strpos($request_path, $proxy_base) === 0) {
    $request_path = substr($request_path, strlen($proxy_base));
}

/* Remove duplicate slashes */
$request_path = ltrim($request_path, '/');

/* Build the final Flask request URL */
$url = rtrim($flask_url, '/') . "/" . $request_path;

/* Debugging: Log the request URL */
error_log("Proxy forwarding request to: $url");

/* Open connection to Flask backend */
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // Don't store in memory
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, getallheaders());
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
    echo $data;
    return strlen($data);
});

/* Set headers to avoid buffering issues */
header("Content-Type: application/octet-stream");
header("Transfer-Encoding: chunked");
header("Connection: keep-alive");

/* Execute request and stream response */
$success = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
?>
