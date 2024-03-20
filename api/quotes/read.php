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
        // Quote array
        $quote_arr = array();

        foreach ($result as $row) {
            $quote_item = array(
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
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
?>
