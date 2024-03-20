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

    // Get author_id and category_id if provided
    $author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

    // Quote read query
    $result = $quote->read($author_id, $category_id);

    // Check if any quotes
    if (count($result) > 0) {
        // Turn to JSON & output
        echo json_encode($result);
    } else {
        // No Quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
?>
