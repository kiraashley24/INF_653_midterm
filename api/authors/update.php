<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Include necessary files
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate author object
    $author = new Author($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set ID to update
    $author->id = $data->id;

    if (!empty($data->author)) {
        $author->author = $data->author;

        // Update author
        $result = $author->update();
        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(
                array('message' => 'author Not Updated')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }