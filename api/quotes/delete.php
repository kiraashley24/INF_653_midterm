<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

// Set ID to delete
$quote->id = isset($data->id) ? $data->id : null;
if (!empty($quote->id)) {
    // Delete author
    $result = $quote->delete();
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(
            array('message' => 'Uh Oh')
        );
    }
} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
