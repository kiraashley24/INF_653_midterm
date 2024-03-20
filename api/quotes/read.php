<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Quote read query
$result = $quote->read();

// Check if any quotes
if (!empty($result)) {
    // Turn to JSON & output
    echo json_encode($result);
} else {
    // No Quotes
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
?>
