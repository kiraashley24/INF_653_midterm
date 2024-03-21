<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include necessary files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are provided
if (!empty($data->id) && !empty($data->category)) {
    // Set category properties
    $category->id = $data->id;
    $category->category = $data->category;

    // Update category
    $result = $category->update();

    // Check if category was updated
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array('message' => 'Category Not Updated'));
    }
} else {
    echo json_encode(array('message' => 'Missing Required Parameters'));
}

?>
