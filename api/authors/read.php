<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Include necessary files
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Get authors
    $result = $author->read();
    $num = $result->rowCount();

    // Check if any authors
    if($num > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $author_item = array(
                'id' => $id,
                'author' => $author
            );

            // Echo each author item
            echo json_encode($author_item);
        }
    } else {
        // No Authors
        echo json_encode(
            array('message' => 'No Authors Found')
        );
    }
