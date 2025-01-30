<?php

/*
 * PHP Proxy for forwarding requests from a public server to an internal Flask server.
 * 
 * Steps to use:
 * 1. Place this file (proxy.php) on your Windows server in a web-accessible location.
 * 2. Make sure PHP is enabled.
 * 3. Access it in your browser like: http://YOUR_PUBLIC_SERVER/proxy.php/some/path?foo=bar
 *    The script will forward to http://10.205.203.179:5000/some/path?foo=bar
 */

// --- Configuration: change to your LAN server's address/port ---
$lanServer = "10.205.203.179";
$lanPort   = 5000;

// --- Figure out the path the user wants (after /proxy.php) ---
$requestedPath = "";
if (isset($_SERVER['REQUEST_URI'])) {
    // full URI, e.g. "/proxy.php/some/path?foo=bar"
    // parse the path portion
    $fullPath = $_SERVER['REQUEST_URI'];

    // This is the path to the current script, e.g. "/proxy.php"
    $scriptPath = $_SERVER['SCRIPT_NAME'];

    // remove the script path from the start of the full path
    // basically everything after "proxy.php"
    if (strpos($fullPath, $scriptPath) === 0) {
        $requestedPath = substr($fullPath, strlen($scriptPath));
    }
}

// --- Build the target URL (including query string if present) ---
$queryString = $_SERVER['QUERY_STRING'] ?? '';
$protocol = "http";  // If your Flask server uses HTTPS internally, change to "https"
$targetUrl = $protocol . "://{$lanServer}:{$lanPort}{$requestedPath}";
if (!empty($queryString)) {
    // If there's already a question mark in $requestedPath, skip adding another
    $targetUrl .= (strpos($requestedPath, '?') === false ? '?' : '&') . $queryString;
}

// --- Initialize cURL to forward the request ---
$ch = curl_init($targetUrl);

// Set request method (GET, POST, PUT, etc.)
$method = $_SERVER['REQUEST_METHOD'];
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

// If there's a request body (e.g. POST data), forward it
$requestBody = file_get_contents('php://input');
if (!empty($requestBody)) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
}

// Copy all request headers
$headers = [];
foreach (getallheaders() as $key => $value) {
    // Optional: filter out or manipulate certain headers if needed
    $headers[] = "$key: $value";
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// We want the response headers and body back
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

// Actually send the request
$response = curl_exec($ch);

// Separate headers and body
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$responseHeader = substr($response, 0, $headerSize);
$responseBody   = substr($response, $headerSize);

// Get the HTTP status code of the response
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cURL
curl_close($ch);

// --- Forward the response headers back to the client ---
$headerLines = explode("\r\n", $responseHeader);
foreach ($headerLines as $headerLine) {
    if (stripos($headerLine, 'Transfer-Encoding: chunked') === 0) {
        // Avoid sending this back, it can cause issues
        continue;
    }
    if (stripos($headerLine, 'Content-Length:') === 0) {
        // We'll let PHP handle content length automatically
        continue;
    }
    if (!empty($headerLine)) {
        header($headerLine, false);
    }
}

// Set the HTTP response code
http_response_code($httpCode);

// Finally, output the response body
echo $responseBody;

?>
