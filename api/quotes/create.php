<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
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

    // Check if required fields are provided
    if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
        // Set quote property
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        $result = $quote->create();

        // Create quote
        if (isset($result['id']) && isset($result['quote']) && isset($result['author_id']) && isset($result['category_id'])) {
            echo json_encode($result);
        } else {
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
        }
    } else {
        // Check if author_id is empty  
        if (empty($data->author_id)) {
            echo json_encode(
                array('message' => 'Missing Required Parameters')
            );
        }

        // Check if category_id is empty
        if (empty($data->category_id)) {
            echo json_encode(
                array('message' => 'Missing Required Parameters')
            );
        }

        // Check if any other required fields are missing
        if (empty($data->quote)) {
            echo json_encode(
                array('message' => 'Missing Required Parameters')
            );
        }
    }


?>