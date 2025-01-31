<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

// We'll assume file_id is provided via GET parameter: download_file.php?file_id=xxxx
if (!isset($_GET['file_id'])) {
    echo "No file_id provided";
    exit();
}

$file_id = $_GET['file_id'];
$flask_url = "http://10.205.203.179:5000/api/download/" . $file_id;

// We'll get the file as binary from Flask
$ch = curl_init($flask_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// So we can grab headers
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit();
}

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);

curl_close($ch);

if ($http_code != 200) {
    // If the Flask route returned something like 404 or other error, just show it
    header('Content-Type: application/json');
    echo $body;
    exit();
}

// We'll attempt to figure out a filename from the Content-Disposition if it's present
$filename = "downloaded_file";
if (preg_match('/filename=(.+)/', $headers, $matches)) {
    $filename = trim($matches[1]);
    // Remove any quotes
    $filename = str_replace('"', '', $filename);
}

// Set headers to force download
header('Content-Type: ' . $content_type);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . strlen($body));

// Output the file body
echo $body;
exit();
?>
