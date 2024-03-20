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

    // Get ID
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get single author
    $author->read_single();

    // Check if author exists
    if ($author->id) {
        // Create array
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author
        );

        // Make JSON
        echo json_encode($author_arr);
    } else {
        // No author found
        http_response_code(404);
        echo json_encode(array('message' => 'author_id Not Found'));
    }
