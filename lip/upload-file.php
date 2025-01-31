<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

header('Content-Type: application/json');

// Make sure we have a file
if(!isset($_FILES['file'])){
    echo json_encode(["error" => "No file uploaded"]);
    exit();
}

// Generate unique ID
$uid = uniqid('', true);
$flask_url = "http://10.205.203.179:5000/api/upload/" . $uid;

// Initialize cURL
$ch = curl_init($flask_url);

$cfile = new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']);

// Prepare POST fields
$post_fields = array(
    "file" => $cfile
);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
    exit();
}

curl_close($ch);

if (empty($response)) {
    echo json_encode(["error" => "Empty response from Flask server"]);
    exit();
}

// Return the Flask server response
echo $response;
?>
