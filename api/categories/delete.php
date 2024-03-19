<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Include database and category files
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Check if category ID is provided
if (!isset($data->id)) {
    echo json_encode(array('message' => 'Missing category ID'));
    exit;
}

// Set ID to DELETE
$category->id = $data->id;

// Delete category
if ($category->delete()) {
    echo json_encode(
        array('message' => 'Category deleted')
    );
} else {
    echo json_encode(
        array('message' => 'Category not deleted')
    );
}
