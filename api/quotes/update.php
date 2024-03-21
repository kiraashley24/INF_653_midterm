<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit;
}

// Set ID to update
$quote->id = $data->id;

// Check if author_id exists
if (!isset($data->author_id)) {
    echo json_encode(array('message' => 'Missing author_id'));
    exit;
}

// Check if category_id exists
if (!isset($data->category_id)) {
    echo json_encode(array('message' => 'Missing category_id'));
    exit;
}

if (!empty($data->quote)) {
    // Set quote data
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    // Update quote
    $result = $quote->update();
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}


?>
