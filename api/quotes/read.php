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

    // Get row count
    $num = $result->rowCount();

    // Check if any quotes
    if ($num > 0) {
        // Quote array
        $quote_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author_id' => $author_id,
                'author' => $author_name,
                'category_id' => $category_id,
                'category' => $category_name
            );

            // Push to "data"
            array_push($quote_arr, $quote_item);
        }

        // Turn to JSON & output
        echo json_encode($quote_arr);
    } else {
        // No Quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }
