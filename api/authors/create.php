<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
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

// Check if author is provided
if (!empty($data->author)) {
    // Set category property
    $author->author = $data->author;
    $result = $author->create();

    // Create author
    if (isset($result['id']) && isset($result['author'])) {
        echo json_encode($result);
    } else {
        echo json_encode(
            array('message' => 'Author Not Created')
        );
    }
} else {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
}
?>
