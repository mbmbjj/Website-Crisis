<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

header('Content-Type: application/json');

// Check if cURL is installed
if (!function_exists('curl_init')) {
    echo json_encode(["error" => "cURL is not installed on the server"]);
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'upload') {
    echo json_encode(["debug" => "Upload action triggered"]);

    // 🔍 Print full $_FILES array to debug
    echo "\nDEBUG: FILES Data:\n";
    var_dump($_FILES);

    // ✅ Check if a file is uploaded
    if (!isset($_FILES['file'])) {
        echo json_encode(["error" => "No file uploaded"]);
        exit();
    }

    // 🔍 Print error code if file upload fails
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["error" => "File upload error", "code" => $_FILES['file']['error']]);
        exit();
    }

    $file_path = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];

    // 🔍 Ensure the file path exists
    if (!file_exists($file_path)) {
        echo json_encode(["error" => "Temporary file not found"]);
        exit();
    }

    $uid = uniqid('', true);
    $flask_url = "http://10.205.203.179:5000/upload/" . $uid;

    // ✅ Send file to Flask server
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $flask_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($file_path, $_FILES['file']['type'], $file_name)
    ]);

    $response = curl_exec($ch);
    $curl_error = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    // 🔍 Print Flask response
    echo "\nDEBUG: Flask Response:\n";
    var_dump($response);

    if ($curl_error) {
        echo json_encode(["error" => "cURL Error", "message" => $curl_error]);
        exit();
    }

    echo json_encode(["uuid" => $uid, "flask_response" => $response]);
    exit();
}
?>
